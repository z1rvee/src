<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>DARKIOS • Admin Console</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#070709;
  --rail:#0b0b0e;
  --panel:#101015;
  --panel2:#17171e;
  --line:#24242f;
  --text:#ffffff;
  --muted:#8e8e9c;
  --red:#e63946;
  --red-hover:#ff4757;
  --red-soft:rgba(230,57,70,.14);
  --red-border:rgba(230,57,70,.42);
}
*{box-sizing:border-box}
body{
  margin:0;
  background:var(--bg);
  color:var(--text);
  font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;
  font-weight:700;
  font-size:13px;
  min-height:100vh;
  -webkit-font-smoothing:antialiased;
}
.shell{display:flex;min-height:100vh}

/* Minimal 4-item Rail */
.rail{
  width:54px;
  flex:0 0 54px;
  background:var(--rail);
  border-right:1px solid var(--line);
  display:flex;
  flex-direction:column;
  align-items:center;
  padding:14px 6px;
  gap:10px;
  position:fixed;
  inset:0 auto 0 0;
  z-index:8;
}
.rail .logo{
  height:38px;
  width:38px;
  border-radius:10px;
  background:var(--panel2);
  border:1px solid var(--line);
  display:grid;
  place-items:center;
  color:var(--red);
  margin-bottom:10px;
}
.rail svg{
  width:19px;
  height:19px;
  fill:none;
  stroke:currentColor;
  stroke-width:2.1;
  stroke-linecap:round;
  stroke-linejoin:round;
}
.rail button{
  width:38px;
  height:38px;
  border:1px solid transparent;
  border-radius:10px;
  background:transparent;
  color:var(--muted);
  display:grid;
  place-items:center;
  cursor:pointer;
  transition:all .18s ease;
}
.rail button:hover{background:var(--panel2);color:var(--text)}
.rail button.active{
  background:var(--red-soft);
  color:var(--red);
  border-color:var(--red-border);
}

.main{margin-left:54px;min-width:0;width:calc(100% - 54px)}

/* Topbar */
.topbar{
  height:52px;
  background:rgba(11,11,14,.92);
  backdrop-filter:blur(10px);
  border-bottom:1px solid var(--line);
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 18px;
  position:sticky;
  top:0;
  z-index:5;
}
.top-left{display:flex;align-items:center;gap:10px;font-size:15px;font-weight:800;letter-spacing:.3px}
.top-left .dot{width:8px;height:8px;border-radius:50%;background:var(--red);box-shadow:0 0 10px var(--red)}
.top-right{display:flex;align-items:center;gap:8px}
.top-btn,.top-select{
  border:1px solid var(--line);
  background:var(--panel);
  color:var(--text);
  padding:6px 11px;
  border-radius:7px;
  font-family:inherit;
  font-size:11px;
  font-weight:700;
  cursor:pointer;
  transition:.18s;
}
.top-btn:hover,.top-select:hover{border-color:#3a3a48;background:var(--panel2)}

.content{padding:22px 18px 36px;max-width:1520px;margin:auto}
.heading{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:0 0 18px;flex-wrap:wrap}
.heading h1{font-size:21px;margin:0 0 4px;font-weight:800;letter-spacing:-.2px}
.heading p{margin:0;color:var(--muted);font-size:12px;font-weight:600}

.create{
  background:var(--red);
  color:#fff;
  border:0;
  border-radius:8px;
  padding:9px 15px;
  font-family:inherit;
  font-size:12px;
  font-weight:800;
  cursor:pointer;
  transition:.18s;
}
.create:hover{background:var(--red-hover)}

/* Cards */
.cards{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:18px}
.metric{
  border:1px solid var(--line);
  border-radius:10px;
  background:var(--panel);
  padding:14px 16px;
}
.metric.accent{border-color:var(--red-border)}
.metric .label{font-size:10px;letter-spacing:.6px;color:var(--muted);text-transform:uppercase;font-weight:800}
.metric strong{display:block;font-size:22px;margin-top:6px;font-weight:800;color:var(--text)}
.metric.accent strong{color:var(--red)}

/* Table */
.table-panel{border:1px solid var(--line);background:var(--panel);border-radius:10px;overflow:hidden}
.table-head{
  padding:12px 14px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  border-bottom:1px solid var(--line);
  flex-wrap:wrap;
}
.table-title{font-size:13px;font-weight:800;display:flex;align-items:center;gap:8px}
.count{
  font-size:10px;
  color:var(--red);
  background:var(--red-soft);
  border:1px solid var(--red-border);
  padding:3px 7px;
  border-radius:5px;
  font-weight:800;
}
.filters{display:flex;align-items:center;gap:7px;flex-wrap:wrap}
.filters input,.filters select{
  height:30px;
  border:1px solid var(--line);
  border-radius:7px;
  background:var(--bg);
  color:var(--text);
  font-family:inherit;
  font-size:11px;
  font-weight:700;
  padding:0 10px;
}
.filters input{width:170px}
.reset{
  height:30px;
  border:1px solid var(--line);
  border-radius:7px;
  background:var(--panel2);
  color:var(--text);
  font-family:inherit;
  font-size:11px;
  font-weight:700;
  padding:0 11px;
  cursor:pointer;
}
.table-wrap{overflow-x:auto}
.table{width:100%;border-collapse:collapse;min-width:860px}
.table th{
  height:36px;
  background:var(--panel2);
  color:var(--muted);
  text-align:left;
  font-size:10px;
  font-weight:800;
  letter-spacing:.5px;
  padding:0 12px;
  border-bottom:1px solid var(--line);
}
.table td{
  height:48px;
  border-bottom:1px solid var(--line);
  padding:8px 12px;
  font-size:12px;
  font-weight:700;
  color:var(--text);
  white-space:nowrap;
}
.table tr:last-child td{border-bottom:0}
.table tbody tr:hover{background:#14141a}
.name{font-weight:800;color:#fff;font-size:12px}
.desc{font-size:10px;color:var(--muted);margin-top:2px;font-weight:600}
.keycode{
  font-family:'JetBrains Mono',monospace;
  font-size:11px;
  font-weight:700;
  background:var(--bg);
  border:1px solid var(--line);
  color:#fff;
  border-radius:6px;
  padding:4px 8px;
}
.type{
  font-size:10px;
  font-weight:800;
  border:1px solid var(--line);
  background:var(--panel2);
  color:#fff;
  border-radius:5px;
  padding:3px 7px;
}
.status{
  font-size:10px;
  font-weight:800;
  border:1px solid var(--line);
  color:var(--muted);
  border-radius:5px;
  padding:3px 7px;
}
.status.active{border-color:#343440;color:#fff;background:#1c1c24}
.status.paused,.status.expired{border-color:var(--red-border);color:var(--red);background:var(--red-soft)}
.dots{
  border:1px solid var(--line);
  background:var(--panel2);
  color:#fff;
  border-radius:6px;
  width:28px;
  height:28px;
  font-size:15px;
  cursor:pointer;
}
.blank{padding:42px 16px;text-align:center;color:var(--muted);font-size:12px;font-weight:700}
.view{display:none}.view.active{display:block}

/* Modals & Forms */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.84);z-index:20;display:none;align-items:center;justify-content:center;padding:16px}
.modal-bg.open{display:flex}
.modal{width:min(540px,100%);background:var(--panel);border:1px solid var(--line);border-radius:12px;padding:20px;max-height:90vh;overflow-y:auto}
.modal-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.modal-top h2{font-size:16px;margin:0;font-weight:800}
.x{border:0;background:none;color:var(--muted);font-size:22px;cursor:pointer}
.formgrid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.field{display:flex;flex-direction:column;gap:6px}
.field.full{grid-column:1/-1}
.field label{font-size:11px;color:var(--muted);font-weight:700}
.field input,.field select,.field textarea{
  background:var(--bg);
  border:1px solid var(--line);
  color:#fff;
  border-radius:7px;
  padding:10px;
  font-family:inherit;
  font-size:12px;
  font-weight:700;
}
.modal-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:14px;flex-wrap:wrap}
.secondary{
  background:var(--panel2);
  color:#fff;
  border:1px solid var(--line);
  border-radius:7px;
  padding:8px 12px;
  font-family:inherit;
  font-size:11px;
  font-weight:700;
  cursor:pointer;
  transition:.15s;
}
.secondary:hover{border-color:#3b3b49;background:#20202a}
.secondary.danger{border-color:var(--red-border);color:var(--red);background:var(--red-soft)}
.secondary.danger:hover{background:rgba(230,57,70,.22)}
.section-label{font-size:10px;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin:14px 0 6px;font-weight:800}
.footnote{color:var(--muted);font-size:11px;margin-top:8px;font-weight:600}
.toast{
  position:fixed;right:18px;bottom:18px;
  background:#fff;color:#070709;
  padding:10px 15px;border-radius:8px;
  font-weight:800;font-size:12px;
  z-index:40;display:none;
}
.toast.show{display:block}
.settings-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.setting-card{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:16px}
.setting-card h3{margin:0 0 6px;font-size:14px;font-weight:800}
.setting-card p{color:var(--muted);font-size:11px;margin:0 0 12px;font-weight:600;line-height:1.5}

/* Switch toggle for UPDATE!!! */
.switch-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:8px}
.switch{position:relative;display:inline-block;width:48px;height:26px}
.switch input{opacity:0;width:0;height:0}
.slider{position:absolute;cursor:pointer;inset:0;background:var(--panel2);border:1px solid var(--line);transition:.2s;border-radius:26px}
.slider:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background:#fff;transition:.2s;border-radius:50%}
input:checked + .slider{background:var(--red);border-color:var(--red)}
input:checked + .slider:before{transform:translateX(22px)}
.update-badge{
  display:inline-block;
  margin-top:8px;
  font-family:'JetBrains Mono',monospace;
  font-size:10px;
  font-weight:800;
  padding:4px 8px;
  border-radius:5px;
  background:var(--red-soft);
  border:1px solid var(--red-border);
  color:var(--red);
}

/* Login */
.login-screen{min-height:100vh;display:grid;place-items:center;padding:20px;background:var(--bg)}
.login-card{width:min(380px,100%);padding:30px;background:var(--panel);border:1px solid var(--line);border-radius:14px}
.login-logo{
  width:46px;height:46px;border-radius:11px;
  background:var(--red-soft);border:1px solid var(--red-border);
  color:var(--red);display:grid;place-items:center;margin-bottom:16px;
}
.login-logo svg{width:24px;height:24px;fill:none;stroke:currentColor;stroke-width:2.2}
.login-card h1{font-size:22px;letter-spacing:1px;margin:0 0 4px;font-weight:800}
.login-card h1 span{color:var(--red)}
.login-card p{color:var(--muted);font-size:12px;margin:0 0 20px;font-weight:600}
.login-card .field{margin:12px 0}
.login-card .field input{height:42px}
.login-submit{width:100%;height:42px;margin-top:10px}
.login-error{color:var(--red);font-size:11px;min-height:15px;margin-top:6px;font-weight:700}

@media(max-width:720px){
  .cards{grid-template-columns:repeat(2,minmax(0,1fr))}
  .settings-grid{grid-template-columns:1fr}
  .formgrid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<div id="loginScreen" class="login-screen">
  <div class="login-card">
    <div class="login-logo">
      <svg viewBox="0 0 24 24"><path d="M12 2.8 20.5 7.4v9.2L12 21.2l-8.5-4.6V7.4L12 2.8Z"/><path d="m8 12 2.5 2.5L16.5 8.5"/></svg>
    </div>
    <h1>DARKIOS <span>ADMIN</span></h1>
    <p id="loginSub">Administrator Sign In</p>
    <form onsubmit="loginAdmin(event)">
      <div class="field"><label id="lblUser">Username</label><input id="loginUser" autocomplete="username" required placeholder="Username"></div>
      <div class="field"><label id="lblPass">Password</label><input id="loginPass" type="password" autocomplete="current-password" required placeholder="Password"></div>
      <div id="loginError" class="login-error"></div>
      <button class="create login-submit" id="btnSignIn">Sign in</button>
    </form>
  </div>
</div>

<div class="shell" id="adminApp" style="display:none">
  <aside class="rail" id="rail">
    <div class="logo">
      <svg viewBox="0 0 24 24"><path d="M12 2.8 20.5 7.4v9.2L12 21.2l-8.5-4.6V7.4L12 2.8Z"/><path d="m8 12 2.5 2.5L16.5 8.5"/></svg>
    </div>
    <button class="active" title="API Keys" data-view="api">
      <svg viewBox="0 0 24 24"><circle cx="8" cy="15.5" r="3.5"/><path d="m10.5 13 8-8 2 2-2 2 1.5 1.5-2 2-1.5-1.5-3 3"/></svg>
    </button>
    <button title="Key Generator" data-view="generator">
      <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    </button>
    <button title="Banned Keys" data-view="banned">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m5.6 5.6 12.8 12.8"/></svg>
    </button>
    <button title="Settings" data-view="settings">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.26.6.77 1.02 1.4 1.14H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/></svg>
    </button>
  </aside>

  <div class="main">
    <header class="topbar">
      <div class="top-left"><span class="dot"></span><span id="topTitle">API Keys</span></div>
      <div class="top-right">
        <select class="top-select" id="langSelect" onchange="setLang(this.value)">
          <option value="en">English</option>
          <option value="tr">Türkçe</option>
        </select>
        <button class="top-btn" onclick="renderAll()" id="btnRefresh">Refresh</button>
        <button class="top-btn" onclick="logoutAdmin()" id="btnLogout">Sign out</button>
      </div>
    </header>

    <main class="content">
      <!-- 1. API KEYS -->
      <section class="view active" id="view-api">
        <div class="heading">
          <div>
            <h1 id="h1Api">API Keys</h1>
            <p id="subApi">Manage and monitor generated keys</p>
          </div>
          <button class="create" onclick="openCreate()" id="btnCreateTop">＋ Create API Key</button>
        </div>

        <div class="cards">
          <div class="metric"><div class="label" id="mLblTotal">Total Keys</div><strong id="mTotal">0</strong></div>
          <div class="metric"><div class="label" id="mLblActive">Active</div><strong id="mActive">0</strong></div>
          <div class="metric"><div class="label" id="mLblTester">Tester Keys</div><strong id="mService">0</strong></div>
          <div class="metric accent"><div class="label" id="mLblBanned">Banned Keys</div><strong id="mBanned">0</strong></div>
        </div>

        <div class="table-panel">
          <div class="table-head">
            <div class="table-title"><span id="tblTitle">API Key List</span> <span class="count" id="rowCount">0 keys</span></div>
            <div class="filters">
              <input id="search" placeholder="Search name or key..." oninput="renderRows()">
              <select id="statusFilter" onchange="renderRows()">
                <option value="">Status</option>
                <option value="Active">Active</option>
                <option value="Paused">Paused</option>
                <option value="Expired">Expired</option>
              </select>
              <select id="typeFilter" onchange="renderRows()">
                <option value="">Type</option>
                <option value="NORMAL">NORMAL</option>
                <option value="TESTER">TESTER</option>
              </select>
              <button class="reset" onclick="resetFilters()" id="btnReset">Reset</button>
            </div>
          </div>
          <div class="table-wrap">
            <table class="table">
              <thead>
                <tr>
                  <th>NAME</th>
                  <th>KEY</th>
                  <th>TYPE</th>
                  <th>STATUS</th>
                  <th>DEVICES</th>
                  <th>TIME LEFT</th>
                  <th>CREATED</th>
                  <th>ACTIONS</th>
                </tr>
              </thead>
              <tbody id="rows"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- 2. KEY GENERATOR -->
      <section class="view" id="view-generator">
        <div class="heading">
          <div>
            <h1 id="h1Gen">Key Generator</h1>
            <p id="subGen">Generate custom or bulk keys</p>
          </div>
        </div>
        <div class="table-panel" style="padding:18px">
          <div class="formgrid">
            <div class="field"><label id="gLblName">Name (optional)</label><input id="genName" placeholder="Optional"></div>
            <div class="field"><label id="gLblType">Key type</label><select id="genType"><option value="NORMAL">NORMAL</option><option value="TESTER">TESTER</option></select></div>
            <div class="field"><label id="gLblDevices">Max IP / Devices</label><select id="genDevices"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="5">5</option><option value="0">Unlimited</option></select></div>
            <div class="field"><label id="gLblUnit">Validity unit</label><select id="genDurationUnit" onchange="toggleGenDuration()"><option value="m">Minutes</option><option value="h">Hours</option><option value="D" selected>Days</option><option value="W">Weeks</option><option value="M">Months</option><option value="LIFETIME">Lifetime</option></select></div>
            <div class="field"><label id="gLblAmount">Duration amount</label><input id="genDurationValue" type="number" min="1" value="1"></div>
            <div class="field"><label id="gLblPrefix">Key prefix</label><input id="genPrefix" value="DARKIOS"></div>
            <div class="field"><label id="gLblCustomKey">Custom key (exact value, e.g. AMK123)</label><input id="genCustomKey" placeholder="AMK123"></div>
            <div class="field"><label id="gLblCount">How many keys</label><input id="genCount" type="number" min="1" max="100" value="1"></div>
            <div class="field full"><label id="gLblDesc">Description (optional)</label><input id="genDesc" placeholder="Optional"></div>
          </div>
          <button class="create" style="margin-top:16px" onclick="generateKeys()" id="btnGenSubmit">Generate API Key(s)</button>
          <div id="generated" style="margin-top:14px"></div>
        </div>
      </section>

      <!-- 3. BANNED KEYS -->
      <section class="view" id="view-banned">
        <div class="heading">
          <div>
            <h1 id="h1Ban">Banned Keys</h1>
            <p id="subBan">Blocked keys list</p>
          </div>
          <button class="create" onclick="openBan()" id="btnBanModal">＋ Ban key</button>
        </div>
        <div class="table-panel">
          <div class="table-wrap">
            <table class="table">
              <thead><tr><th>KEY</th><th>REASON</th><th>DATE</th><th>ACTION</th></tr></thead>
              <tbody id="bannedRows"></tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- 4. SETTINGS -->
      <section class="view" id="view-settings">
        <div class="heading">
          <div>
            <h1 id="h1Set">Settings</h1>
            <p id="subSet">System controls, UPDATE switch and backup</p>
          </div>
        </div>
        <div class="settings-grid">
          <div class="setting-card" style="border-color:var(--red-border)">
            <h3>UPDATE!!!</h3>
            <p id="updateDesc">When ON, pauses all active keys. When OFF, restores only keys paused by UPDATE!!! (manually paused keys stay paused).</p>
            <div class="switch-row">
              <span id="updateStatusText" style="font-size:12px;font-weight:800">OFF</span>
              <label class="switch">
                <input type="checkbox" id="updateToggle" onchange="toggleUpdateMode(this.checked)">
                <span class="slider"></span>
              </label>
            </div>
            <div id="updateBadgeWrap"></div>
          </div>

          <div class="setting-card">
            <h3 id="setBackupTitle">Backup & Export</h3>
            <p id="setBackupDesc">Export keys to TXT or JSON backup.</p>
            <button class="secondary" onclick="exportTxt()">Download keys.txt</button>
            <button class="secondary" onclick="backupJson()">Download JSON</button>
          </div>

          <div class="setting-card">
            <h3 id="setImportTitle">Import Keys</h3>
            <p id="setImportDesc">Import keys from TXT or restore JSON.</p>
            <label class="secondary" style="display:inline-block">Import TXT<input type="file" accept=".txt,text/plain" hidden onchange="importTxt(this.files[0]);this.value=''"></label>
            <label class="secondary" style="display:inline-block">Restore JSON<input type="file" accept=".json" hidden onchange="restoreJson(this.files[0])"></label>
          </div>

          <div class="setting-card">
            <h3 id="setClearTitle">Reset All Keys</h3>
            <p id="setClearDesc">Delete all keys and active device sessions.</p>
            <button class="secondary danger" onclick="clearAllKeys()" id="btnClearAll">Clear all keys</button>
          </div>
        </div>
      </section>
    </main>
  </div>
</div>

<div class="modal-bg" id="modalBg">
  <div class="modal">
    <div class="modal-top"><h2 id="modalTitle">Action</h2><button class="x" onclick="closeModal()">×</button></div>
    <div id="modalBody"></div>
  </div>
</div>
<div class="toast" id="toast"></div>

<script>
const $=s=>document.querySelector(s),uid=()=>Math.random().toString(36).slice(2,10)+Date.now().toString(36).slice(-4);

let records=JSON.parse(localStorage.getItem('darkios_keys_v3')||'[]');
let banned=JSON.parse(localStorage.getItem('darkios_banned_v3')||'[]');
let deviceSessions=JSON.parse(localStorage.getItem('darkios_devices_v3')||'[]');
let updateModeOn=JSON.parse(localStorage.getItem('darkios_update_mode_v3')||'false');
let currentLang=localStorage.getItem('darkios_lang_v3')||'en';

const i18n={
  en:{
    api:'API Keys',generator:'Key Generator',banned:'Banned Keys',settings:'Settings',
    subApi:'Manage and monitor generated keys',subGen:'Generate custom or bulk keys',
    subBan:'Blocked keys list',subSet:'System controls, UPDATE switch and backup',
    create:'＋ Create API Key',refresh:'Refresh',logout:'Sign out',reset:'Reset',
    noKeys:'No keys yet. Click "+ Create API Key" to create one.',
    waitingDevice:'Waiting Device',expired:'Expired',
    pausedManual:'Paused (Manual)',pausedTemp:'Paused (Temp)',pausedUpdate:'Paused (UPDATE!!!)',
    loginSub:'Administrator Sign In',user:'Username',pass:'Password',signIn:'Sign in',
    badLogin:'Invalid username or password',
    updateOnDesc:'UPDATE!!! is ON: active keys are paused with reason UPDATE!!!',
    updateOffDesc:'When ON, pauses all active keys. When OFF, restores only keys paused by UPDATE!!! (manually paused keys stay paused).'
  },
  tr:{
    api:'API Anahtarları',generator:'Anahtar Oluşturucu',banned:'Yasaklı Anahtarlar',settings:'Ayarlar',
    subApi:'Oluşturulan anahtarları yönetin ve izleyin',subGen:'Özel veya toplu anahtar oluşturun',
    subBan:'Engellenen anahtarlar listesi',subSet:'Sistem kontrolleri, UPDATE anahtarı ve yedekleme',
    create:'＋ API Anahtarı Oluştur',refresh:'Yenile',logout:'Çıkış yap',reset:'Sıfırla',
    noKeys:'Henüz anahtar yok. Oluşturmak için "+ API Anahtarı Oluştur" butonuna basın.',
    waitingDevice:'Cihaz Bekleniyor',expired:'Süresi Doldu',
    pausedManual:'Durduruldu (Manuel)',pausedTemp:'Durduruldu (Geçici)',pausedUpdate:'Durduruldu (UPDATE!!!)',
    loginSub:'Yönetici Girişi',user:'Kullanıcı Adı',pass:'Şifre',signIn:'Giriş Yap',
    badLogin:'Kullanıcı adı veya şifre hatalı',
    updateOnDesc:'UPDATE!!! AÇIK: aktif anahtarlar UPDATE!!! sebebiyle durduruldu',
    updateOffDesc:'Açıldığında tüm aktif anahtarları durdurur. Kapatıldığında yalnızca UPDATE!!! ile durdurulanları geri açar (manuel durdurulanlar durur).'
  }
};
const t=k=>(i18n[currentLang]||i18n.en)[k]||i18n.en[k]||k;

function setLang(lang){
  currentLang=i18n[lang]?lang:'en';
  localStorage.setItem('darkios_lang_v3',currentLang);
  applyLang();
  renderAll();
}
function applyLang(){
  document.documentElement.lang=currentLang;
  $('#langSelect').value=currentLang;
  $('#loginSub').textContent=t('loginSub');
  $('#lblUser').textContent=t('user');
  $('#lblPass').textContent=t('pass');
  $('#btnSignIn').textContent=t('signIn');
  $('#btnRefresh').textContent=t('refresh');
  $('#btnLogout').textContent=t('logout');
  $('#h1Api').textContent=t('api');
  $('#subApi').textContent=t('subApi');
  $('#btnCreateTop').textContent=t('create');
  $('#tblTitle').textContent=t('api');
  $('#btnReset').textContent=t('reset');
  $('#h1Gen').textContent=t('generator');
  $('#subGen').textContent=t('subGen');
  $('#h1Ban').textContent=t('banned');
  $('#subBan').textContent=t('subBan');
  $('#h1Set').textContent=t('settings');
  $('#subSet').textContent=t('subSet');
  const activeView=document.querySelector('.view.active')?.id.replace('view-','')||'api';
  $('#topTitle').textContent=t(activeView);
}

function persist(){
  localStorage.setItem('darkios_keys_v3',JSON.stringify(records));
  localStorage.setItem('darkios_banned_v3',JSON.stringify(banned));
  localStorage.setItem('darkios_devices_v3',JSON.stringify(deviceSessions));
  localStorage.setItem('darkios_update_mode_v3',JSON.stringify(updateModeOn));
}
function notify(msg){
  let el=$('#toast');el.textContent=msg;el.classList.add('show');
  setTimeout(()=>el.classList.remove('show'),2200);
}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}

function go(v){
  document.querySelectorAll('.view').forEach(x=>x.classList.remove('active'));
  $('#view-'+v).classList.add('active');
  document.querySelectorAll('.rail button').forEach(x=>x.classList.toggle('active',x.dataset.view===v));
  $('#topTitle').textContent=t(v);
  renderAll();
}
$('#rail').addEventListener('click',e=>{let b=e.target.closest('button[data-view]');if(b)go(b.dataset.view)});

function loginAdmin(e){
  e.preventDefault();
  if($('#loginUser').value==='dark'&&$('#loginPass').value==='wear5555'){
    sessionStorage.setItem('darkios_auth','1');
    $('#loginScreen').style.display='none';
    $('#adminApp').style.display='flex';
    $('#loginError').textContent='';
    notify('OK');
  }else{
    $('#loginError').textContent=t('badLogin');
  }
}
function logoutAdmin(){
  sessionStorage.removeItem('darkios_auth');
  $('#adminApp').style.display='none';
  $('#loginScreen').style.display='grid';
  $('#loginPass').value='';
}

function durationToMs(unit,val){
  if(unit==='LIFETIME')return null;
  let n=Math.max(1,+val||1);
  if(unit==='m')return n*60000;
  if(unit==='h')return n*3600000;
  if(unit==='D')return n*86400000;
  if(unit==='W')return n*604800000;
  if(unit==='M')return n*2592000000;
  return n*86400000;
}

function buildKeyValue({type,prefix,customKey}){
  const ck=(customKey||'').trim();
  if(ck)return ck; // Exact value as entered, e.g. AMK123
  const p=(prefix||'DARKIOS').trim()||'DARKIOS';
  const chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
  let rand='';
  const bytes=new Uint8Array(12);crypto.getRandomValues(bytes);
  for(let b of bytes)rand+=chars[b%chars.length];
  return type==='TESTER'?`${p}-TESTER-${rand}`:`${p}-${rand}`;
}

// Countdown starts ONLY after first device connects (startedAt != null)
function checkStates(){
  let changed=false;
  const now=Date.now();
  records.forEach(r=>{
    // Temporary pause expiration
    if(r.pauseType==='temp' && r.pauseUntil && r.pauseUntil<=now){
      r.pauseType=null;
      r.pauseUntil=null;
      r.status='Active';
      changed=true;
    }
    // Duration countdown expiration (only if started)
    if(r.startedAt && r.durationMs && r.status==='Active'){
      const endAt=r.startedAt+r.durationMs;
      if(endAt<=now){
        r.status='Expired';
        changed=true;
      }
    }
  });
  if(changed)persist();
}

function timeLeftText(r){
  if(!r.durationMs)return 'LIFETIME';
  if(!r.startedAt)return t('waitingDevice');
  const rem=(r.startedAt+r.durationMs)-Date.now();
  if(rem<=0)return t('expired');
  let s=Math.floor(rem/1000);
  let d=Math.floor(s/86400);s%=86400;
  let h=Math.floor(s/3600);s%=3600;
  let m=Math.floor(s/60);s%=60;
  return `${d}d ${h}h ${m}m ${s}s`;
}

function statusBadgeText(r){
  if(r.status==='Paused'){
    if(r.pauseType==='update')return t('pausedUpdate');
    if(r.pauseType==='temp')return t('pausedTemp');
    return t('pausedManual');
  }
  if(r.status==='Expired')return t('expired');
  return 'Active';
}

function renderRows(){
  checkStates();
  let q=$('#search').value.toLowerCase(),st=$('#statusFilter').value,ty=$('#typeFilter').value;
  let arr=records.filter(r=>(!q||(r.name||'').toLowerCase().includes(q)||r.key.toLowerCase().includes(q))&&(!st||r.status===st)&&(!ty||r.type===ty));
  $('#rowCount').textContent=arr.length+' keys';
  $('#rows').innerHTML=arr.map(r=>{
    let active=deviceSessions.filter(d=>d.keyId===r.id&&d.status==='Active').length;
    let limit=r.deviceLimit===0?'∞':(r.deviceLimit||1);
    let cls=r.status==='Active'?'active':(r.status==='Paused'||r.status==='Expired'?'paused':'');
    return `<tr>
      <td><div class="name">${esc(r.name||'Key')}</div><div class="desc">${esc(r.description||'—')}</div></td>
      <td><span class="keycode">${esc(r.key)}</span></td>
      <td><span class="type">${esc(r.type||'NORMAL')}</span></td>
      <td><span class="status ${cls}">${esc(statusBadgeText(r))}</span></td>
      <td>${active} / ${limit}</td>
      <td>${esc(timeLeftText(r))}</td>
      <td>${esc(r.created||'')}</td>
      <td><button class="dots" onclick="rowMenu('${r.id}')">⋮</button></td>
    </tr>`;
  }).join('')||`<tr><td colspan="8" class="blank">${esc(t('noKeys'))}</td></tr>`;

  $('#mTotal').textContent=records.length;
  $('#mActive').textContent=records.filter(r=>r.status==='Active').length;
  $('#mService').textContent=records.filter(r=>r.type==='TESTER').length;
  $('#mBanned').textContent=banned.length;
}

function renderUpdateUI(){
  $('#updateToggle').checked=!!updateModeOn;
  $('#updateStatusText').textContent=updateModeOn?'ON':'OFF';
  $('#updateStatusText').style.color=updateModeOn?'var(--red)':'#fff';
  $('#updateDesc').textContent=updateModeOn?t('updateOnDesc'):t('updateOffDesc');
  const countUpdatePaused=records.filter(r=>r.status==='Paused'&&r.pauseType==='update').length;
  $('#updateBadgeWrap').innerHTML=updateModeOn
    ?`<div class="update-badge">UPDATE!!! ACTIVE · ${countUpdatePaused} key(s) paused</div>`
    :'';
}

function toggleUpdateMode(checked){
  if(checked){
    updateModeOn=true;
    let pausedCount=0;
    records.forEach(r=>{
      if(r.status==='Active'){
        r.status='Paused';
        r.pauseType='update';
        r.pauseUntil=null;
        pausedCount++;
      }
    });
    persist();renderAll();
    notify(`UPDATE!!! ON (${pausedCount})`);
  }else{
    updateModeOn=false;
    let restoredCount=0;
    records.forEach(r=>{
      // Restore ONLY keys paused by UPDATE!!!; manual/temp pauses remain untouched
      if(r.status==='Paused'&&r.pauseType==='update'){
        r.pauseType=null;
        r.pauseUntil=null;
        r.status=(r.startedAt&&r.durationMs&&(r.startedAt+r.durationMs<=Date.now()))?'Expired':'Active';
        restoredCount++;
      }
    });
    persist();renderAll();
    notify(`UPDATE!!! OFF (${restoredCount})`);
  }
}

function resetFilters(){$('#search').value='';$('#statusFilter').value='';$('#typeFilter').value='';renderRows()}
function modal(title,body){$('#modalTitle').textContent=title;$('#modalBody').innerHTML=body;$('#modalBg').classList.add('open')}
function closeModal(){$('#modalBg').classList.remove('open')}

function openCreate(){
  modal('Create API Key',`<form onsubmit="createRecord(event)">
    <div class="formgrid">
      <div class="field"><label>Name (optional)</label><input id="newName" placeholder="Optional"></div>
      <div class="field"><label>Key type</label><select id="newType"><option value="NORMAL">NORMAL</option><option value="TESTER">TESTER</option></select></div>
      <div class="field"><label>Max IP / Devices</label><select id="newDeviceLimit"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="5">5</option><option value="0">Unlimited</option></select></div>
      <div class="field"><label>Validity unit</label><select id="newDurationUnit" onchange="toggleModalDuration()"><option value="m">Minutes</option><option value="h">Hours</option><option value="D" selected>Days</option><option value="W">Weeks</option><option value="M">Months</option><option value="LIFETIME">Lifetime</option></select></div>
      <div class="field"><label>Duration amount</label><input id="newDurationValue" type="number" min="1" value="1"></div>
      <div class="field"><label>Key prefix</label><input id="newPrefix" value="DARKIOS"></div>
      <div class="field full"><label>Custom key (exact value, e.g. AMK123)</label><input id="newCustomKey" placeholder="AMK123"></div>
      <div class="field full"><label>Description (optional)</label><input id="newDesc" placeholder="Optional"></div>
    </div>
    <p class="footnote">Countdown starts only when the first device connects.</p>
    <div class="modal-actions">
      <button type="button" class="secondary" onclick="closeModal()">Cancel</button>
      <button class="create">Create API Key</button>
    </div>
  </form>`);
}
function toggleModalDuration(){
  const life=$('#newDurationUnit').value==='LIFETIME';
  $('#newDurationValue').disabled=life;$('#newDurationValue').style.opacity=life?'.4':'1';
}
function toggleGenDuration(){
  const life=$('#genDurationUnit').value==='LIFETIME';
  $('#genDurationValue').disabled=life;$('#genDurationValue').style.opacity=life?'.4':'1';
}

function createRecord(e){
  e.preventDefault();
  const type=$('#newType').value;
  const unit=$('#newDurationUnit').value;
  const val=$('#newDurationValue').value;
  const prefix=$('#newPrefix').value;
  const customKey=$('#newCustomKey').value;
  const name=($('#newName').value||'').trim()||'Key';
  const desc=($('#newDesc').value||'').trim()||'—';
  const durationMs=durationToMs(unit,val);

  const r={
    id:uid(),
    name,
    description:desc,
    type,
    status:updateModeOn?'Paused':'Active',
    pauseType:updateModeOn?'update':null,
    pauseUntil:null,
    deviceLimit:+($('#newDeviceLimit').value||1),
    created:new Date().toISOString().slice(0,16).replace('T',' '),
    key:buildKeyValue({type,prefix,customKey}),
    durationMs,
    startedAt:null // Starts only when a device enters
  };
  records.unshift(r);persist();closeModal();renderAll();notify('OK');
  modal('Key Created',`<input style="width:100%;background:#070709;color:#fff;border:1px solid #24242f;padding:10px;border-radius:7px;font-family:'JetBrains Mono',monospace" id="createdKey" value="${esc(r.key)}" readonly>
    <div class="modal-actions">
      <button class="secondary" onclick="navigator.clipboard?.writeText($('#createdKey').value);notify('Copied')">Copy</button>
      <button class="create" onclick="closeModal()">Done</button>
    </div>`);
}

function rowMenu(id){
  const r=records.find(x=>x.id===id);if(!r)return;
  modal('Key Actions',`
    <p class="footnote"><b>${esc(r.key)}</b> · ${esc(statusBadgeText(r))} · ${esc(timeLeftText(r))}</p>

    <div class="section-label">Key & Device</div>
    <div class="modal-actions" style="justify-content:flex-start">
      <button class="secondary" onclick="copyKey('${id}')">Copy</button>
      <button class="secondary" onclick="simulateConnect('${id}')">Test Device Connect</button>
      <button class="secondary" onclick="changeDeviceLimit('${id}',1)">+1 Device Slot</button>
      <button class="secondary" onclick="changeDeviceLimit('${id}',-1)">-1 Device Slot</button>
    </div>

    <div class="section-label">Time (+/-)</div>
    <div class="modal-actions" style="justify-content:flex-start">
      <button class="secondary" onclick="adjustTimeModal('${id}','add')">+ Add Time</button>
      <button class="secondary" onclick="adjustTimeModal('${id}','sub')">- Remove Time</button>
    </div>

    <div class="section-label">Pause / Ban / Delete</div>
    <div class="modal-actions" style="justify-content:flex-start">
      ${r.status==='Paused'
        ? `<button class="secondary" onclick="resumeKey('${id}')">Resume Key</button>`
        : `<button class="secondary" onclick="pauseIndefinite('${id}')">Pause (Until I Open)</button>
           <button class="secondary" onclick="pauseTimedModal('${id}')">Pause (Set Time)</button>`
      }
      <button class="secondary danger" onclick="banRecord('${id}')">Ban Key</button>
      <button class="secondary danger" onclick="deleteRecord('${id}')">Delete</button>
    </div>
  `);
}

function copyKey(id){const r=records.find(x=>x.id===id);if(r){navigator.clipboard?.writeText(r.key);notify('Copied')}}

function simulateConnect(id){
  const r=records.find(x=>x.id===id);if(!r)return;
  if(!r.startedAt){
    r.startedAt=Date.now();
    notify('Countdown started');
  }
  const ip='192.168.1.'+(Math.floor(Math.random()*200)+10);
  deviceSessions.unshift({id:uid(),keyId:id,ip,status:'Active',at:new Date().toLocaleString()});
  persist();renderAll();rowMenu(id);
}

function changeDeviceLimit(id,delta){
  const r=records.find(x=>x.id===id);if(!r)return;
  const cur=r.deviceLimit===0?1:(r.deviceLimit||1);
  r.deviceLimit=Math.max(1,cur+delta);
  persist();renderAll();rowMenu(id);notify(`Limit: ${r.deviceLimit}`);
}

function adjustTimeModal(id,mode){
  const r=records.find(x=>x.id===id);if(!r)return;
  modal(mode==='add'?'+ Add Time':'- Remove Time',`
    <div class="formgrid">
      <div class="field"><label>Amount</label><input id="adjAmt" type="number" min="1" value="1"></div>
      <div class="field"><label>Unit</label><select id="adjUnit"><option value="m">Minutes</option><option value="h">Hours</option><option value="D" selected>Days</option></select></div>
    </div>
    <div class="modal-actions">
      <button class="secondary" onclick="rowMenu('${id}')">Back</button>
      <button class="create" onclick="applyTimeAdjust('${id}','${mode}')">Apply</button>
    </div>
  `);
}

function applyTimeAdjust(id,mode){
  const r=records.find(x=>x.id===id);if(!r)return;
  const amt=Math.max(1,+$('#adjAmt').value||1);
  const unit=$('#adjUnit').value;
  const delta=amt*(unit==='m'?60000:unit==='h'?3600000:86400000);
  if(!r.durationMs){
    // If lifetime and adding time, set base from now
    if(mode==='add'){
      r.startedAt=r.startedAt||Date.now();
      r.durationMs=delta;
    }
  }else{
    if(mode==='add'){
      r.durationMs+=delta;
      if(r.status==='Expired'&&(r.startedAt||Date.now())+r.durationMs>Date.now()){
        r.status='Active';
      }
    }else{
      r.durationMs=Math.max(0,r.durationMs-delta);
      if(r.startedAt&&(r.startedAt+r.durationMs)<=Date.now()){
        r.status='Expired';
      }
    }
  }
  persist();renderAll();rowMenu(id);notify('Updated');
}

function pauseIndefinite(id){
  const r=records.find(x=>x.id===id);if(!r)return;
  r.status='Paused';
  r.pauseType='manual';
  r.pauseUntil=null;
  persist();renderAll();closeModal();notify('Paused');
}

function pauseTimedModal(id){
  modal('Pause (Set Time)',`
    <div class="formgrid">
      <div class="field"><label>Amount</label><input id="pAmt" type="number" min="1" value="1"></div>
      <div class="field"><label>Unit</label><select id="pUnit"><option value="m">Minutes</option><option value="h" selected>Hours</option><option value="D">Days</option></select></div>
    </div>
    <div class="modal-actions">
      <button class="secondary" onclick="rowMenu('${id}')">Back</button>
      <button class="create" onclick="applyTimedPause('${id}')">Pause</button>
    </div>
  `);
}

function applyTimedPause(id){
  const r=records.find(x=>x.id===id);if(!r)return;
  const amt=Math.max(1,+$('#pAmt').value||1);
  const unit=$('#pUnit').value;
  const ms=amt*(unit==='m'?60000:unit==='h'?3600000:86400000);
  r.status='Paused';
  r.pauseType='temp';
  r.pauseUntil=Date.now()+ms;
  persist();renderAll();closeModal();notify('Temp paused');
}

function resumeKey(id){
  const r=records.find(x=>x.id===id);if(!r)return;
  r.pauseType=null;
  r.pauseUntil=null;
  r.status=(r.startedAt&&r.durationMs&&(r.startedAt+r.durationMs<=Date.now()))?'Expired':'Active';
  persist();renderAll();closeModal();notify('Resumed');
}

function banRecord(id){
  const r=records.find(x=>x.id===id);if(!r)return;
  banned.unshift({id:uid(),key:r.key,reason:'Banned by admin',date:new Date().toLocaleString()});
  records=records.filter(x=>x.id!==id);
  persist();renderAll();closeModal();notify('Banned');
}

function deleteRecord(id){
  records=records.filter(x=>x.id!==id);
  persist();renderAll();closeModal();notify('Deleted');
}

function generateKeys(){
  const n=Math.max(1,Math.min(100,+$('#genCount').value||1));
  const name=($('#genName').value||'').trim()||'Key';
  const desc=($('#genDesc').value||'').trim()||'—';
  const type=$('#genType').value;
  const unit=$('#genDurationUnit').value;
  const val=$('#genDurationValue').value;
  const prefix=$('#genPrefix').value;
  const customKey=$('#genCustomKey').value;
  const durationMs=durationToMs(unit,val);
  const out=[];

  for(let i=0;i<n;i++){
    const keyVal=n===1&&customKey.trim()?customKey.trim():buildKeyValue({type,prefix,customKey:n===1?customKey:''});
    const r={
      id:uid(),
      name,
      description:desc,
      type,
      status:updateModeOn?'Paused':'Active',
      pauseType:updateModeOn?'update':null,
      pauseUntil:null,
      deviceLimit:+$('#genDevices').value,
      created:new Date().toISOString().slice(0,16).replace('T',' '),
      key:keyVal,
      durationMs,
      startedAt:null
    };
    records.unshift(r);out.push(r.key);
  }
  persist();
  $('#generated').innerHTML=`<textarea style="width:100%;height:105px;background:#070709;color:#fff;border:1px solid #24242f;padding:10px;border-radius:7px;font-family:'JetBrains Mono',monospace" readonly>${esc(out.join('\n'))}</textarea>
    <button class="secondary" style="margin-top:8px" onclick="navigator.clipboard?.writeText(${JSON.stringify(out.join('\n'))});notify('Copied')">Copy all</button>`;
  renderAll();
}

function openBan(){
  modal('Ban API Key',`<form onsubmit="banManual(event)">
    <div class="field"><label>API Key</label><input id="banValue" required></div>
    <div class="field" style="margin-top:10px"><label>Reason</label><input id="banReason" placeholder="Reason"></div>
    <div class="modal-actions"><button class="create">Ban key</button></div>
  </form>`);
}
function banManual(e){
  e.preventDefault();
  banned.unshift({id:uid(),key:$('#banValue').value.trim(),reason:($('#banReason').value||'').trim()||'Banned',date:new Date().toLocaleString()});
  records=records.filter(r=>r.key!==$('#banValue').value.trim());
  persist();renderAll();closeModal();notify('Banned');
}
function renderBanned(){
  $('#bannedRows').innerHTML=banned.map(b=>`<tr>
    <td><span class="keycode">${esc(b.key)}</span></td>
    <td>${esc(b.reason)}</td>
    <td>${esc(b.date)}</td>
    <td><button class="secondary" onclick="unban('${b.id}')">Unban</button></td>
  </tr>`).join('')||`<tr><td colspan="4" class="blank">—</td></tr>`;
}
function unban(id){banned=banned.filter(x=>x.id!==id);persist();renderAll()}
function clearAllKeys(){records=[];deviceSessions=[];updateModeOn=false;persist();renderAll();notify('Cleared')}

function exportTxt(){const lines=records.map(r=>r.key);download('api-keys.txt',lines.join('\n')+(lines.length?'\n':''),'text/plain')}
function importTxt(file){
  if(!file)return;const reader=new FileReader();
  reader.onload=()=>{
    const lines=String(reader.result).split(/\r?\n/).map(x=>x.trim()).filter(Boolean);
    let added=0;
    for(const key of lines){
      if(records.some(r=>r.key===key))continue;
      records.unshift({id:uid(),name:'Key',description:'—',type:'NORMAL',status:updateModeOn?'Paused':'Active',pauseType:updateModeOn?'update':null,pauseUntil:null,deviceLimit:1,created:new Date().toISOString().slice(0,16).replace('T',' '),key,durationMs:null,startedAt:null});
      added++;
    }
    persist();renderAll();notify(`+${added}`);
  };reader.readAsText(file);
}
function backupJson(){download('darkios-backup.json',JSON.stringify({records,banned,updateModeOn},null,2),'application/json')}
function restoreJson(file){
  if(!file)return;const r=new FileReader();
  r.onload=()=>{try{const d=JSON.parse(r.result);records=d.records||[];banned=d.banned||[];updateModeOn=!!d.updateModeOn;persist();renderAll();notify('Restored')}catch{notify('Invalid JSON')}};
  r.readAsText(file);
}
function download(name,data,type){const a=document.createElement('a'),u=URL.createObjectURL(new Blob([data],{type}));a.href=u;a.download=name;a.click();URL.revokeObjectURL(u)}

function renderAll(){renderRows();renderBanned();renderUpdateUI()}
applyLang();
renderAll();
setInterval(()=>{if($('#adminApp').style.display!=='none'){renderRows();renderUpdateUI()}},1000);
if(sessionStorage.getItem('darkios_auth')==='1'){$('#loginScreen').style.display='none';$('#adminApp').style.display='flex'}
</script>
</body>
</html>
