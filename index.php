<?php
session_start();
define('AA', 'AA');

$dbFile = __DIR__ . '/keys_database.sqlite';
$db = new PDO('sqlite:' . $dbFile);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS license_keys (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    license_key TEXT UNIQUE NOT NULL,
    hwid TEXT DEFAULT NULL,
    expires_at DATETIME NOT NULL,
    is_banned INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

if (isset($_GET['api']) && $_GET['api'] === 'verify') {
    header('Content-Type: application/json');
    $key = trim($_POST['key'] ?? $_GET['key'] ?? '');
    $hwid = trim($_POST['hwid'] ?? $_GET['hwid'] ?? '');

    if (empty($key) || empty($hwid)) {
        echo json_encode(['status' => 'error', 'message' => 'Açar və ya HWID boş ola bilməz!']);
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM license_keys WHERE license_key = :key LIMIT 1");
    $stmt->execute([':key' => $key]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['status' => 'error', 'message' => 'Açar tapılmadı və ya yanlışdır!']);
        exit;
    }

    if ($row['is_banned'] == 1) {
        echo json_encode(['status' => 'error', 'message' => 'Bu açar bloklanıb!']);
        exit;
    }

    if (strtotime($row['expires_at']) < time()) {
        echo json_encode(['status' => 'error', 'message' => 'Açarın istifadə müddəti bitib!']);
        exit;
    }

    if (empty($row['hwid'])) {
        $update = $db->prepare("UPDATE license_keys SET hwid = :hwid WHERE id = :id");
        $update->execute([':hwid' => $hwid, ':id' => $row['id']]);
    } elseif ($row['hwid'] !== $hwid) {
        echo json_encode(['status' => 'error', 'message' => 'HWID uyğun gəlmir! Bu açar başqa cihaza bağlıdır.']);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Giriş uğurludur!',
        'expires_at' => $row['expires_at']
    ]);
    exit;
}

if (isset($_POST['admin_login'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['is_admin'] = true;
    } else {
        $loginError = "Yanlış şifrə!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$isAdmin = $_SESSION['is_admin'] ?? false;

if ($isAdmin) {
    if (isset($_POST['create_key'])) {
        $days = (int)($_POST['days'] ?? 30);
        $customKey = trim($_POST['custom_key'] ?? '');
        $newKey = !empty($customKey) ? $customKey : 'KEY-' . strtoupper(bin2hex(random_bytes(6)));
        $expiresAt = date('Y-m-d H:i:s', strtotime("+$days days"));

        try {
            $stmt = $db->prepare("INSERT INTO license_keys (license_key, expires_at) VALUES (:key, :exp)");
            $stmt->execute([':key' => $newKey, ':exp' => $expiresAt]);
        } catch (Exception $e) {
            $panelMsg = "Xəta: Bu açar artıq mövcuddur.";
        }
    }

    if (isset($_GET['reset_hwid'])) {
        $stmt = $db->prepare("UPDATE license_keys SET hwid = NULL WHERE id = :id");
        $stmt->execute([':id' => (int)$_GET['reset_hwid']]);
        header("Location: index.php");
        exit;
    }

    if (isset($_GET['toggle_ban'])) {
        $stmt = $db->prepare("UPDATE license_keys SET is_banned = 1 - is_banned WHERE id = :id");
        $stmt->execute([':id' => (int)$_GET['toggle_ban']]);
        header("Location: index.php");
        exit;
    }

    if (isset($_GET['delete'])) {
        $stmt = $db->prepare("DELETE FROM license_keys WHERE id = :id");
        $stmt->execute([':id' => (int)$_GET['delete']]);
        header("Location: index.php");
        exit;
    }

    $keys = $db->query("SELECT * FROM license_keys ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HWID Key Management Panel</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .card { background: #1e293b; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); margin-bottom: 20px; }
        h2 { margin-top: 0; color: #38bdf8; }
        input, select, button { padding: 10px 14px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 14px; }
        button { background: #0284c7; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #0369a1; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #334155; font-size: 14px; }
        th { color: #94a3b8; }
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: bold; }
        .badge-green { background: #065f46; color: #34d399; }
        .badge-red { background: #7f1d1d; color: #fca5a5; }
        .action-link { color: #38bdf8; text-decoration: none; margin-right: 10px; font-size: 13px; }
        .action-link.danger { color: #f87171; }
    </style>
</head>
<body>
<div class="container">
    <?php if (!$isAdmin): ?>
        <div class="card" style="max-width: 400px; margin: 80px auto;">
            <h2>Admin Girişi</h2>
            <?php if (!empty($loginError)) echo "<p style='color:#f87171;'>$loginError</p>"; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Admin şifrəsi" required style="width: 100%; box-sizing: border-box; margin-bottom: 12px;">
                <button type="submit" name="admin_login" style="width: 100%;">Daxil Ol</button>
            </form>
        </div>
    <?php else: ?>
        <div class="card" style="display:flex; justify-content:space-between; align-items:center;">
            <h2>HWID Lisenziya İdarəetmə Paneli</h2>
            <a href="?logout=1" class="action-link danger">Çıxış</a>
        </div>

        <div class="card">
            <h3>Yeni Açar Yarat</h3>
            <?php if (!empty($panelMsg)) echo "<p style='color:#f87171;'>$panelMsg</p>"; ?>
            <form method="POST" style="display:flex; gap:10px; flex-wrap:wrap;">
                <input type="text" name="custom_key" placeholder="Xüsusi açar (boş qalsa avtomatik)">
                <select name="days">
                    <option value="1">1 Gün</option>
                    <option value="7">7 Gün</option>
                    <option value="30" selected>30 Gün</option>
                    <option value="365">1 İl</option>
                </select>
                <button type="submit" name="create_key">+ Açar Yarat</button>
            </form>
        </div>

        <div class="card" style="overflow-x:auto;">
            <h3>Mövcud Açarlar (<?= count($keys) ?>)</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Açar (Key)</th>
                        <th>Bağlı HWID</th>
                        <th>Bitmə Tarixi</th>
                        <th>Status</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($keys as $k): ?>
                    <tr>
                        <td><?= $k['id'] ?></td>
                        <td><strong><?= htmlspecialchars($k['license_key']) ?></strong></td>
                        <td><?= $k['hwid'] ? htmlspecialchars($k['hwid']) : '<span style="color:#64748b;">Bağlanmayıb</span>' ?></td>
                        <td><?= $k['expires_at'] ?></td>
                        <td>
                            <?php if ($k['is_banned']): ?>
                                <span class="badge badge-red">Bloklanıb</span>
                            <?php elseif (strtotime($k['expires_at']) < time()): ?>
                                <span class="badge badge-red">Vaxtı Bitib</span>
                            <?php else: ?>
                                <span class="badge badge-green">Aktiv</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?reset_hwid=<?= $k['id'] ?>" class="action-link">HWID Sıfırla</a>
                            <a href="?toggle_ban=<?= $k['id'] ?>" class="action-link"><?= $k['is_banned'] ? 'Bloku Aç' : 'Blokla' ?></a>
                            <a href="?delete=<?= $k['id'] ?>" class="action-link danger" onclick="return confirm('Silinsin?')">Sil</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
