<!doctype html>
<html lang="az">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>DARKIOS • Admin Console</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#08080a;
  --rail:#0b0b0e;
  --panel:#101014;
  --panel2:#16161c;
  --line:#23232b;
  --text:#ffffff;
  --muted:#8e8e9a;
  --accent:#e63946;
  --accent-hover:#ff4757;
  --accent-soft:rgba(230,57,70,.12);
  --accent-border:rgba(230,57,70,.35);
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
  color:var(--accent);
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
.rail button:hover{
  background:var(--panel2);
  color:var(--text);
}
.rail button.active{
  background:var(--accent-soft);
  color:var(--accent);
  border-color:var(--accent-border);
}

.main{margin-left:54px;min-width:0;width:calc(100% - 54px)}

/* Topbar */
.topbar{
  height:52px;
  background:rgba(11,11,14,.9);
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
.top-left .dot{width:8px;height:8px;border-radius:50%;background:var(--accent);box-shadow:0 0 10px var(--accent)}
.top-right{display:flex;align-items:center;gap:8px}
.top-btn{
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
.top-btn:hover{border-color:#3a3a46;background:var(--panel2)}

.content{padding:22px 18px 34px;max-width:1500px;margin:auto}
.heading{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:0 0 18px;flex-wrap:wrap}
.heading h1{font-size:21px;margin:0 0 4px;font-weight:800;letter-spacing:-.2px}
.heading p{margin:0;color:var(--muted);font-size:12px;font-weight:600}

.create{
  background:var(--accent);
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
.create:hover{background:var(--accent-hover)}

/* Cards */
.cards{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:18px}
.metric{
  border:1px solid var(--line);
  border-radius:10px;
  background:var(--panel);
  padding:14px 16px;
}
.metric.accent{border-color:var(--accent-border)}
.metric .label{font-size:10px;letter-spacing:.6px;color:var(--muted);text-transform:uppercase;font-weight:800}
.metric strong{display:block;font-size:22px;margin-top:6px;font-weight:800;color:var(--text)}
.metric.accent strong{color:var(--accent)}

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
  color:var(--accent);
  background:var(--accent-soft);
  border:1px solid var(--accent-border);
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
.table{width:100%;border-collapse:collapse;min-width:780px}
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
.table tbody tr:hover{background:#141419}
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
.status.active{
  border-color:#2f2f38;
  color:#fff;
  background:#1c1c22;
}
.status.revoked,.status.expired{
  border-color:var(--accent-border);
  color:var(--accent);
  background:var(--accent-soft);
}
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
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.82);z-index:20;display:none;align-items:center;justify-content:center;padding:16px}
.modal-bg.open{display:flex}
.modal{width:min(500px,100%);background:var(--panel);border:1px solid var(--line);border-radius:12px;padding:20px}
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
.modal-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:16px;flex-wrap:wrap}
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
}
.secondary.danger{border-color:var(--accent-border);color:var(--accent);background:var(--accent-soft)}
.footnote{color:var(--muted);font-size:11px;margin-top:10px;font-weight:600}
.toast{
  position:fixed;right:18px;bottom:18px;
  background:#fff;color:#08080a;
  padding:10px 15px;border-radius:8px;
  font-weight:800;font-size:12px;
  z-index:40;display:none;
}
.toast.show{display:block}
.settings-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.setting-card{background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:16px}
.setting-card h3{margin:0 0 6px;font-size:14px;font-weight:800}
.setting-card p{color:var(--muted);font-size:11px;margin:0 0 12px;font-weight:600}

/* Login */
.login-screen{min-height:100vh;display:grid;place-items:center;padding:20px;background:var(--bg)}
.login-card{width:min(380px,100%);padding:30px;background:var(--panel);border:1px solid var(--line);border-radius:14px}
.login-logo{
  width:46px;height:46px;border-radius:11px;
  background:var(--accent-soft);border:1px solid var(--accent-border);
  color:var(--accent);display:grid;place-items:center;margin-bottom:16px;
}
.login-logo svg{width:24px;height:24px;fill:none;stroke:currentColor;stroke-width:2.2}
.login-card h1{font-size:22px;letter-spacing:1px;margin:0 0 4px;font-weight:800}
.login-card h1 span{color:var(--accent)}
.login-card p{color:var(--muted);font-size:12px;margin:0 0 20px;font-weight:600}
.login-card .field{margin:12px 0}
.login-card .field input{height:42px}
.login-submit{width:100%;height:42px;margin-top:10px}
.login-error{color:var(--accent);font-size:11px;min-height:15px;margin-top:6px;font-weight:700}

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
    <p id="loginSubtitle">Administrator girişi</p>
    <form onsubmit="loginAdmin(event)">
      <div class="field"><label id="loginUserLabel">Username</label><input id="loginUser" autocomplete="username" required placeholder="Username"></div>
      <div class="field"><label id="loginPassLabel">Password</label><input id="loginPass" type="password" autocomplete="current-password" required placeholder="Password"></div>
      <div id="loginError" class="login-error"></div>
      <button class="create login-submit" id="loginSubmit">Sign in</button>
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
        <button class="top-btn" onclick="renderAll()">Refresh</button>
        <button class="top-btn" onclick="logoutAdmin()">Sign out</button>
      </div>
    </header>

    <main class="content">
      <!-- 1. API KEYS -->
      <section class="view active" id="view-api">
        <div class="heading">
          <div>
            <h1>API Keys</h1>
            <p>Açarların idarə edilməsi</p>
          </div>
          <button class="create" onclick="openCreate()">＋ Create API Key</button>
        </div>

        <div class="cards">
          <div class="metric"><div class="label">Total Keys</div><strong id="mTotal">0</strong></div>
          <div class="metric"><div class="label">Active</div><strong id="mActive">0</strong></div>
          <div class="metric"><div class="label">Tester Keys</div><strong id="mService">0</strong></div>
          <div class="metric accent"><div class="label">Banned Keys</div><strong id="mBanned">0</strong></div>
        </div>

        <div class="table-panel">
          <div class="table-head">
            <div class="table-title">API Key List <span class="count" id="rowCount">0 keys</span></div>
            <div class="filters">
              <input id="search" placeholder="Açar və ya ad axtar..." oninput="renderRows()">
              <select id="statusFilter" onchange="renderRows()">
                <option value="">Status</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>Expired</option>
              </select>
              <select id="typeFilter" onchange="renderRows()">
                <option value="">Type</option>
                <option>NORMAL</option>
                <option>TESTER</option>
              </select>
              <button class="reset" onclick="resetFilters()">Reset</button>
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
                  <th>DEVICE LIMIT</th>
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
            <h1>Key Generator</h1>
            <p>Tək və ya toplu açar yaradın</p>
          </div>
        </div>
        <div class="table-panel" style="padding:18px">
          <div class="formgrid">
            <div class="field"><label>Key name</label><input id="genName" placeholder="Məs: VIP Key"></div>
            <div class="field"><label>Key type</label><select id="genType"><option value="NORMAL">NORMAL</option><option value="TESTER">TESTER</option></select></div>
            <div class="field"><label>Max IP / Devices</label><select id="genDevices"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="5">5</option><option value="0">Unlimited</option></select></div>
            <div class="field"><label>Validity unit</label><select id="genDurationUnit"><option value="h">Hours</option><option value="D" selected>Days</option><option value="W">Weeks</option><option value="M">Months</option><option value="LIFETIME">Lifetime</option></select></div>
            <div class="field"><label>Duration amount</label><input id="genDurationValue" type="number" min="1" value="1"></div>
            <div class="field"><label>Key prefix</label><input id="genPrefix" value="DARKIOS"></div>
            <div class="field"><label>Custom duration label</label><input id="genDurationLabel" placeholder="1D / VIP"></div>
            <div class="field"><label>How many keys</label><input id="genCount" type="number" min="1" max="100" value="1"></div>
          </div>
          <button class="create" style="margin-top:16px" onclick="generateKeys()">Generate API Key(s)</button>
          <div id="generated" style="margin-top:14px"></div>
        </div>
      </section>

      <!-- 3. BANNED KEYS -->
      <section class="view" id="view-banned">
        <div class="heading">
          <div>
            <h1>Banned Keys</h1>
            <p>Bloklanmış açarlar siyahısı</p>
          </div>
          <button class="create" onclick="openBan()">＋ Ban key</button>
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
            <h1>Settings</h1>
            <p>Backup və sistem idarəsi</p>
          </div>
        </div>
        <div class="settings-grid">
          <div class="setting-card">
            <h3>Backup & Export</h3>
            <p>Bütün açarları TXT və ya JSON formatında yükləyin.</p>
            <button class="secondary" onclick="exportTxt()">Download keys.txt</button>
            <button class="secondary" onclick="backupJson()">Download JSON</button>
          </div>
          <div class="setting-card">
            <h3>Import Keys</h3>
            <p>TXT faylından açarları əlavə edin və ya JSON bərpa edin.</p>
            <label class="secondary" style="display:inline-block">Import TXT<input type="file" accept=".txt,text/plain" hidden onchange="importTxt(this.files[0]);this.value=''"></label>
            <label class="secondary" style="display:inline-block">Restore JSON<input type="file" accept=".json" hidden onchange="restoreJson(this.files[0])"></label>
          </div>
          <div class="setting-card">
            <h3>Reset All Keys</h3>
            <p>Bütün açarları və IP sessiyalarını təmizləyin.</p>
            <button class="secondary danger" onclick="clearAllKeys()">Clear all keys</button>
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

// Start clean with no demo keys
let records=JSON.parse(localStorage.getItem('darkios_keys_v2')||'[]');
let banned=JSON.parse(localStorage.getItem('darkios_banned_v2')||'[]');
let deviceSessions=JSON.parse(localStorage.getItem('darkios_devices_v2')||'[]');

function persist(){
  localStorage.setItem('darkios_keys_v2',JSON.stringify(records));
  localStorage.setItem('darkios_banned_v2',JSON.stringify(banned));
  localStorage.setItem('darkios_devices_v2',JSON.stringify(deviceSessions));
}
function notify(s){let t=$('#toast');t.textContent=s;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),2200)}
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}

function go(v){
  document.querySelectorAll('.view').forEach(x=>x.classList.remove('active'));
  $('#view-'+v).classList.add('active');
  document.querySelectorAll('.rail button').forEach(x=>x.classList.toggle('active',x.dataset.view===v));
  const titles={api:'API Keys',generator:'Key Generator',banned:'Banned Keys',settings:'Settings'};
  $('#topTitle').textContent=titles[v]||'API Keys';
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
    notify('Daxil olundu');
  }else{
    $('#loginError').textContent='İstifadəçi adı və ya parol yanlışdır';
  }
}
function logoutAdmin(){
  sessionStorage.removeItem('darkios_auth');
  $('#adminApp').style.display='none';
  $('#loginScreen').style.display='grid';
  $('#loginPass').value='';
}

function randomToken(n=14){
  const chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
  let out='';const bytes=new Uint8Array(n);crypto.getRandomValues(bytes);
  for(let b of bytes)out+=chars[b%chars.length];
  return out;
}
function makeKey(type='NORMAL',prefix='DARKIOS',label='1D'){
  return `${prefix}-${type==='TESTER'?'TESTER-':''}${label}-${randomToken(14)}`;
}
function expiryFrom(unit,value){
  if(unit==='LIFETIME')return null;
  let n=Math.max(1,+value||1),ms=unit==='h'?3600000:unit==='D'?86400000:unit==='W'?604800000:2592000000;
  return Date.now()+n*ms;
}
function remaining(r){
  if(!r.expiresAt)return 'LIFETIME';
  let ms=r.expiresAt-Date.now();
  if(ms<=0)return 'Expired';
  let s=Math.floor(ms/1000),d=Math.floor(s/86400);s%=86400;
  let h=Math.floor(s/3600);s%=3600;let m=Math.floor(s/60);s%=60;
  return `${d}d ${h}h ${m}m ${s}s`;
}
function updateExpired(){
  let changed=false;
  records.forEach(r=>{if(r.expiresAt&&r.expiresAt<=Date.now()&&r.status==='Active'){r.status='Expired';changed=true}});
  if(changed)persist();
}

function renderRows(){
  updateExpired();
  let q=$('#search').value.toLowerCase(),st=$('#statusFilter').value,ty=$('#typeFilter').value;
  let arr=records.filter(r=>(!q||r.name.toLowerCase().includes(q)||r.key.toLowerCase().includes(q))&&(!st||r.status===st)&&(!ty||r.type===ty));
  $('#rowCount').textContent=arr.length+' keys';
  $('#rows').innerHTML=arr.map(r=>{
    let active=deviceSessions.filter(d=>d.keyId===r.id&&d.status==='Active').length;
    let limit=r.deviceLimit===0?'∞':(r.deviceLimit||1);
    return `<tr>
      <td><div class="name">${esc(r.name)}</div><div class="desc">${esc(r.description||'API key')}</div></td>
      <td><span class="keycode">${esc(r.key)}</span></td>
      <td><span class="type">${esc(r.type||'NORMAL')}</span></td>
      <td><span class="status ${r.status==='Active'?'active':r.status==='Expired'?'expired':''}">${esc(r.status)}</span></td>
      <td>${active} / ${limit}</td>
      <td>${remaining(r)}</td>
      <td>${esc(r.created||'')}</td>
      <td><button class="dots" onclick="rowMenu('${r.id}')">⋮</button></td>
    </tr>`;
  }).join('')||`<tr><td colspan="8" class="blank">Hələ açar yoxdur. "+ Create API Key" düyməsi ilə yeni açar yaradın.</td></tr>`;

  $('#mTotal').textContent=records.length;
  $('#mActive').textContent=records.filter(r=>r.status==='Active').length;
  $('#mService').textContent=records.filter(r=>r.type==='TESTER').length;
  $('#mBanned').textContent=banned.length;
}

function resetFilters(){$('#search').value='';$('#statusFilter').value='';$('#typeFilter').value='';renderRows()}
function modal(title,body){$('#modalTitle').textContent=title;$('#modalBody').innerHTML=body;$('#modalBg').classList.add('open')}
function closeModal(){$('#modalBg').classList.remove('open')}

function openCreate(){
  modal('Create API Key',`<form onsubmit="createRecord(event)">
    <div class="formgrid">
      <div class="field full"><label>Name</label><input id="newName" required placeholder="Məs: User Key"></div>
      <div class="field"><label>Key type</label><select id="newType"><option value="NORMAL">NORMAL</option><option value="TESTER">TESTER</option></select></div>
      <div class="field"><label>Max IP / Devices</label><select id="newDeviceLimit"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="5">5</option><option value="0">Unlimited</option></select></div>
      <div class="field"><label>Validity unit</label><select id="newDurationUnit" onchange="toggleCustomExpiry()"><option value="h">Hours</option><option value="D" selected>Days</option><option value="W">Weeks</option><option value="M">Months</option><option value="LIFETIME">Lifetime</option></select></div>
      <div class="field"><label>Duration amount</label><input id="newDurationValue" type="number" min="1" value="1"></div>
      <div class="field"><label>Key prefix</label><input id="newPrefix" value="DARKIOS" maxlength="24"></div>
      <div class="field"><label>Custom duration label</label><input id="newDurationLabel" placeholder="1D / VIP" maxlength="16"></div>
      <div class="field full"><label>Description</label><input id="newDesc" placeholder="İstəyə bağlı qeyd"></div>
    </div>
    <div class="modal-actions">
      <button type="button" class="secondary" onclick="closeModal()">Cancel</button>
      <button class="create">Create API Key</button>
    </div>
  </form>`);
}
function toggleCustomExpiry(){
  let life=$('#newDurationUnit').value==='LIFETIME';
  $('#newDurationValue').disabled=life;
  $('#newDurationValue').style.opacity=life?'.4':'1';
}

function createRecord(e){
  e.preventDefault();
  const type=$('#newType').value,unit=$('#newDurationUnit').value,value=$('#newDurationValue').value;
  const prefix=($('#newPrefix').value.trim()||'DARKIOS').replace(/[^a-zA-Z0-9_-]/g,'').toUpperCase();
  const custom=$('#newDurationLabel').value.trim();
  const durationLabel=custom||(unit==='LIFETIME'?'LIFETIME':`${value}${unit}`);
  const expiresAt=expiryFrom(unit,value);
  const r={
    id:uid(),
    name:$('#newName').value,
    type,
    status:'Active',
    deviceLimit:+($('#newDeviceLimit')?.value||1),
    created:new Date().toISOString().slice(0,16).replace('T',' '),
    key:makeKey(type,prefix,durationLabel),
    description:$('#newDesc').value,
    expiresAt
  };
  records.unshift(r);persist();closeModal();renderAll();notify('Açar yaradıldı');
  modal('Key Created',`<p class="footnote">Açarı indi kopyalayın:</p>
    <input style="width:100%;background:#08080a;color:#fff;border:1px solid #23232b;padding:10px;border-radius:7px;font-family:'JetBrains Mono',monospace" id="createdKey" value="${esc(r.key)}" readonly>
    <div class="modal-actions">
      <button class="secondary" onclick="navigator.clipboard?.writeText($('#createdKey').value);notify('Kopyalandı')">Copy key</button>
      <button class="create" onclick="closeModal()">Done</button>
    </div>`);
}

function rowMenu(id){
  let r=records.find(x=>x.id===id);if(!r)return;
  const act=r.status==='Active';
  modal('Key Actions',`<p class="footnote">${esc(r.name)} · ${esc(r.status)} · ${remaining(r)}</p>
    <div class="modal-actions" style="justify-content:flex-start">
      <button class="secondary" onclick="copyKey('${id}')">Copy</button>
      <button class="secondary" onclick="toggleRecord('${id}')">${act?'Pause':'Activate'}</button>
      <button class="secondary" onclick="editExpiry('${id}')">Extend</button>
      <button class="secondary danger" onclick="banRecord('${id}')">Ban</button>
      <button class="secondary danger" onclick="deleteRecord('${id}')">Delete</button>
    </div>`);
}
function copyKey(id){let r=records.find(x=>x.id===id);if(r){navigator.clipboard?.writeText(r.key);notify('Kopyalandı')}}
function toggleRecord(id){let r=records.find(x=>x.id===id);if(r){r.status=r.status==='Active'?'Inactive':'Active';persist();closeModal();renderAll()}}
function editExpiry(id){
  let r=records.find(x=>x.id===id);if(!r)return;
  modal('Extend Expiration',`<div class="field"><label>Əlavə ediləcək gün</label><input id="extendDays" type="number" min="1" value="7"></div>
    <div class="modal-actions">
      <button class="secondary" onclick="closeModal()">Cancel</button>
      <button class="create" onclick="extendKey('${id}')">Save</button>
    </div>`);
}
function extendKey(id){
  let r=records.find(x=>x.id===id);if(!r)return;
  let days=Math.max(1,+$('#extendDays').value||1);
  r.expiresAt=(r.expiresAt&&r.expiresAt>Date.now()?r.expiresAt:Date.now())+days*86400000;
  r.status='Active';persist();closeModal();renderAll();notify('Müddət uzadıldı');
}
function banRecord(id){
  let r=records.find(x=>x.id===id);if(!r)return;
  banned.unshift({id:uid(),key:r.key,reason:'Admin tərəfindən bloklandı',date:new Date().toLocaleString()});
  records=records.filter(x=>x.id!==id);persist();closeModal();renderAll();notify('Açar bloklandı');
}
function deleteRecord(id){records=records.filter(x=>x.id!==id);persist();closeModal();renderAll();notify('Silindi')}

function generateKeys(){
  let n=Math.max(1,Math.min(100,+$('#genCount').value||1)),name=$('#genName').value||'Generated Key',out=[];
  let type=$('#genType').value,unit=$('#genDurationUnit').value,value=$('#genDurationValue').value;
  let prefix=($('#genPrefix').value.trim()||'DARKIOS').replace(/[^a-zA-Z0-9_-]/g,'').toUpperCase();
  let label=$('#genDurationLabel').value.trim()||(unit==='LIFETIME'?'LIFETIME':`${value}${unit}`);
  let expiresAt=expiryFrom(unit,value);
  for(let i=0;i<n;i++){
    let r={id:uid(),name,type,status:'Active',deviceLimit:+$('#genDevices').value,created:new Date().toISOString().slice(0,16).replace('T',' '),key:makeKey(type,prefix,label),description:'',expiresAt};
    records.unshift(r);out.push(r.key);
  }
  persist();
  $('#generated').innerHTML=`<div style="color:#fff;font-size:12px;margin-bottom:8px">${n} açar yaradıldı</div>
    <textarea style="width:100%;height:110px;background:#08080a;color:#fff;border:1px solid #23232b;padding:10px;border-radius:7px;font-family:'JetBrains Mono',monospace" readonly>${out.join('\n')}</textarea>
    <button class="secondary" style="margin-top:8px" onclick="navigator.clipboard?.writeText(${JSON.stringify(out.join('\n'))});notify('Kopyalandı')">Copy all</button>`;
  renderAll();
}

function openBan(){
  modal('Ban API Key',`<form onsubmit="banManual(event)">
    <div class="field"><label>API Key</label><input id="banValue" required></div>
    <div class="field" style="margin-top:10px"><label>Reason</label><input id="banReason" required></div>
    <div class="modal-actions"><button class="create">Ban key</button></div>
  </form>`);
}
function banManual(e){
  e.preventDefault();
  banned.unshift({id:uid(),key:$('#banValue').value,reason:$('#banReason').value,date:new Date().toLocaleString()});
  records=records.filter(r=>r.key!==$('#banValue').value);
  persist();closeModal();renderAll();
}
function renderBanned(){
  $('#bannedRows').innerHTML=banned.map(b=>`<tr>
    <td><span class="keycode">${esc(b.key)}</span></td>
    <td>${esc(b.reason)}</td>
    <td>${esc(b.date)}</td>
    <td><button class="secondary" onclick="unban('${b.id}')">Unban</button></td>
  </tr>`).join('')||`<tr><td colspan="4" class="blank">Bloklanmış açar yoxdur</td></tr>`;
}
function unban(id){banned=banned.filter(x=>x.id!==id);persist();renderAll()}
function clearAllKeys(){records=[];deviceSessions=[];persist();renderAll();notify('Bütün açarlar silindi')}

function exportTxt(){let lines=records.map(r=>r.key);download('api-keys.txt',lines.join('\n')+(lines.length?'\n':''),'text/plain')}
function importTxt(file){
  if(!file)return;let reader=new FileReader();
  reader.onload=()=>{
    let lines=String(reader.result).split(/\r?\n/).map(x=>x.trim()).filter(Boolean),added=0;
    for(let key of lines){
      if(records.some(r=>r.key===key))continue;
      records.unshift({id:uid(),name:'Imported Key',type:'NORMAL',status:'Active',deviceLimit:1,created:new Date().toISOString().slice(0,16).replace('T',' '),key,description:'',expiresAt:null});
      added++;
    }
    persist();renderAll();notify(added+' açar əlavə edildi');
  };reader.readAsText(file);
}
function backupJson(){download('darkios-backup.json',JSON.stringify({records,banned},null,2),'application/json')}
function restoreJson(file){
  if(!file)return;let r=new FileReader();
  r.onload=()=>{try{let d=JSON.parse(r.result);records=d.records||[];banned=d.banned||[];persist();renderAll();notify('Bərpa edildi')}catch{notify('Yanlış JSON faylı')}};
  r.readAsText(file);
}
function download(name,data,type){let a=document.createElement('a'),u=URL.createObjectURL(new Blob([data],{type}));a.href=u;a.download=name;a.click();URL.revokeObjectURL(u)}

function renderAll(){renderRows();renderBanned()}
renderAll();
setInterval(()=>{if($('#adminApp').style.display!=='none')renderRows()},1000);
if(sessionStorage.getItem('darkios_auth')==='1'){$('#loginScreen').style.display='none';$('#adminApp').style.display='flex'}
</script>
</body>
</html>
