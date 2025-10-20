<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>n8nDash v2 — All Widgets Configurable (v3.1)</title>
  <!-- Fonts & CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Icons & Charts -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>window.addEventListener('error',()=>{ if(!window.Chart){ window.Chart=function(){return{destroy(){}}}; }},{once:true});</script>
  <!-- Drag/Resize -->
  <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>

  <style>
    :root{
      --bg:#0b1020; --surface:#0f162b; --card:#121a2f; --text:#e6edf6; --muted:#9aa6b2;
      --line:rgba(255,255,255,.08); --shadow:0 8px 40px rgba(2,8,23,.35); --radius:18px;
      /* Default theme (Ocean) */
      --accent:#0ea5e9; --accent-rgb:14,165,233;
    }
    /* Themes */
    .theme-ocean{ --accent:#0ea5e9; --accent-rgb:14,165,233; }
    .theme-emerald{ --accent:#22c55e; --accent-rgb:34,197,94; }
    .theme-orchid{ --accent:#a855f7; --accent-rgb:168,85,247; }
    .theme-citrus{ --accent:#f59e0b; --accent-rgb:245,158,11; }

    *{box-sizing:border-box}
    html,body{min-height:100%;background:var(--bg);color:var(--text);font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif}
    a{color:#9ec1ff}
    .app{display:grid;grid-template-columns:240px 1fr;grid-template-rows:auto 1fr;min-height:100vh;grid-template-areas:"sidebar header" "sidebar main";}
    .sidebar{grid-area:sidebar;background:linear-gradient(180deg,#0f1530,#0b1020);border-right:1px solid var(--line);padding:20px 14px;position:sticky;top:0;height:100vh}
    .brand{display:flex;align-items:center;gap:10px;font-weight:800}
    .brand .logo{width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#04b7ff);display:grid;place-items:center;color:#00131d;box-shadow:0 8px 20px rgba(2,8,23,.35)}
    .side-nav{margin-top:18px}
    .side-nav a{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px;color:var(--text);text-decoration:none;border:1px solid transparent}
    .side-nav a:hover{background:rgba(255,255,255,.05);border-color:var(--line)}
    .header{grid-area:header;display:flex;align-items:center;justify-content:space-between;padding:14px 18px;background:var(--surface);border-bottom:1px solid var(--line);position:sticky;top:0;z-index:5}
    .header .right{display:flex;align-items:center;gap:10px}
    .chip{border:1px solid var(--line);padding:8px 10px;border-radius:999px;background:rgba(255,255,255,.03)}
    .btn-soft{border:1px solid var(--line);background:rgba(255,255,255,.03);color:var(--text);border-radius:12px;display:inline-flex;align-items:center;gap:6px;white-space:nowrap}
    .btn-soft i{width:16px;height:16px}
    .btn-soft:hover{background:rgba(255,255,255,.06)}
    .theme-dot{width:14px;height:14px;border-radius:999px;display:inline-block;margin-right:6px}

    .main{grid-area:main;padding:18px;}
    .toolbar{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;margin-bottom:14px}

    .canvas{position:relative;border:1px dashed var(--line);border-radius:16px;background:linear-gradient(180deg,#0c1226,#0a0f20);min-height:70vh;padding:14px;overflow:visible}
    .grid-bg{position:absolute;inset:0;background-image:linear-gradient(transparent 31px,var(--line) 32px), linear-gradient(90deg, transparent 31px,var(--line) 32px);background-size:32px 32px;opacity:.35;pointer-events:none}

    .panel{position:absolute;background:var(--card);border:1px solid var(--line);border-radius:var(--radius);box-shadow:var(--shadow);overflow:visible;transition:border-color .3s ease, box-shadow .3s ease}
    .panel.flash-panel{border-color:rgba(var(--accent-rgb), .65);box-shadow:0 0 0 4px rgba(var(--accent-rgb), .25), var(--shadow);animation:panelFlash 1.4s ease-out}
    @keyframes panelFlash{0%{box-shadow:0 0 0 0 rgba(var(--accent-rgb), .0), var(--shadow);}55%{box-shadow:0 0 0 10px rgba(var(--accent-rgb), .35), var(--shadow);}100%{box-shadow:0 0 0 0 rgba(var(--accent-rgb), .0), var(--shadow);}}
    .panel .head{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-bottom:1px solid var(--line);background:linear-gradient(180deg,rgba(255,255,255,.02),transparent)}
    .title{display:flex;align-items:center;gap:10px;font-weight:700}
    .title .icon{width:28px;height:28px;border-radius:8px;background:rgba(255,255,255,.07);display:grid;place-items:center}
    .panel .body{padding:12px;}
    .chart-shell{display:flex;flex-direction:column;height:100%;}
    .chart-area{flex:1;min-height:180px;}
    .chart-area canvas{width:100%!important;height:100%!important;}
    .divider{height:1px;background:var(--line);margin:10px 0}
    .badge-main{border:1px solid rgba(255,255,255,.15);padding:2px 8px;border-radius:999px;font-size:12px;background:rgba(14,165,233,.15);color:#7dd3fc}
    .toast-float{position:fixed;right:18px;bottom:18px;z-index:9999}
    .handle{display:none;cursor:move}
    .app.editing .handle{display:inline-grid}
    .ghost{opacity:.65}
    .kpi{font-size:32px;font-weight:800}
    .delta.up{color:#22c55e}.delta.down{color:#ef4444}

    /* Accent buttons (theme aware) */
    .btn-accent{
      border:1px solid rgba(var(--accent-rgb), .45) !important;
      background:rgba(var(--accent-rgb), .16) !important;
      color:var(--text) !important;
      box-shadow:0 4px 18px rgba(var(--accent-rgb), .15) !important;
      transition:.15s ease;
      display:inline-flex; align-items:center; gap:8px; white-space:nowrap; overflow:hidden;
    }
    .btn-accent i{width:16px;height:16px}
    .btn-accent:hover{ background:rgba(var(--accent-rgb), .24) !important; border-color:rgba(var(--accent-rgb), .7) !important; }
    .btn-accent:disabled{ opacity:.75 }

    /* Icon-only (square) header buttons */
    .btn-icon{
      width:32px; height:32px; padding:0; display:inline-grid; place-items:center;
      border-radius:10px; border:1px solid var(--line); background:rgba(255,255,255,.03); color:var(--text);
      position:relative; overflow:hidden;
    }
    .btn-icon.accent{ border-color:rgba(var(--accent-rgb), .45); }
    .btn-icon:hover{ background:rgba(255,255,255,.06); }
    .btn-icon i{ width:16px; height:16px; }

    /* Slim pill for Custom widget run */
    .btn-pill{ border-radius:999px; height:32px; padding:0 12px; font-size:13px; line-height:1; }

    /* Spinners */
    .btn-pill .spinner-border,
    .btn-icon .spinner-border{ width:16px; height:16px; border-width:2px; }
    .btn-icon .spinner-border{ position:relative; }

    /* Full-screen config overlay */
    .nd-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:10000;display:flex;align-items:flex-start;justify-content:center;padding:40px 20px;overflow:auto}
    .nd-sheet{width:min(1100px,96vw);background:var(--surface);border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow);overflow:hidden}
    .nd-sheet .nd-head{display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-bottom:1px solid var(--line)}
    .nd-sheet .nd-body{padding:14px}
    .cfg-help pre{white-space:pre-wrap;background:rgba(255,255,255,.03);border:1px solid var(--line);border-radius:12px;padding:10px}

    @media (max-width:1080px){.app{grid-template-columns:80px 1fr}.brand .text{display:none}.side-nav a span{display:none}}
  </style>
</head>
<body class="theme-ocean">
<div class="app" id="appRoot">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="brand mb-3">
      <div class="logo">⚡</div><div class="text">n8nDash v2</div>
    </div>
    <div class="side-nav vstack gap-1">
      <a href="#"><i data-lucide="layout"></i> <span>Dashboards</span></a>
      <a href="#"><i data-lucide="blocks"></i> <span>Widget Library</span></a>
      <a href="#"><i data-lucide="settings"></i> <span>Settings</span></a>
    </div>
    <div class="mt-4 small text-secondary">Drag in <b>Edit Mode</b>. Layout & widget configs saved to localStorage.</div>
  </aside>

  <!-- Header -->
  <header class="header">
    <div class="d-flex align-items-center gap-2"><span class="chip">Demo — Frontend-only</span></div>
    <div class="right">
      <div class="dropdown">
        <button class="btn btn-soft dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <span class="theme-dot" style="background:var(--accent)"></span> Theme
        </button>
        <ul class="dropdown-menu dropdown-menu-dark">
          <li><a class="dropdown-item" data-theme="ocean" href="#"><span class="theme-dot" style="background:#0ea5e9"></span> Ocean</a></li>
          <li><a class="dropdown-item" data-theme="emerald" href="#"><span class="theme-dot" style="background:#22c55e"></span> Emerald</a></li>
          <li><a class="dropdown-item" data-theme="orchid" href="#"><span class="theme-dot" style="background:#a855f7"></span> Orchid</a></li>
          <li><a class="dropdown-item" data-theme="citrus" href="#"><span class="theme-dot" style="background:#f59e0b"></span> Citrus</a></li>
        </ul>
      </div>
      <button id="btn-edit" class="btn btn-accent btn-pill"><i data-lucide="edit-3"></i> Edit Mode</button>
      <button id="btn-main" class="btn btn-soft"><i data-lucide="refresh-ccw"></i> Run Selected</button>
      <button id="btn-save" class="btn btn-soft"><i data-lucide="save"></i> Save Layout</button>
      <button id="btn-reset" class="btn btn-soft"><i data-lucide="undo2"></i> Reset</button>
    </div>
  </header>

  <!-- Main -->
  <main class="main">
    <div class="toolbar">
      <div>
        <h3 class="mb-0">Executive Metrics — Demo</h3>
        <div class="text-secondary small">All widgets configurable • Fetch from n8n webhooks</div>
      </div>
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge-main">Main refresh targets: Data & Charts</span>
        <button class="btn btn-soft btn-sm" id="btn-resumo-locate"><i data-lucide="map-pin"></i> Localizar gráfico resumo</button>
      </div>
    </div>

    <section class="canvas" id="canvas">
      <div class="grid-bg"></div>

      <!-- DATA widgets -->
      <section class="panel" data-id="kpi-revenue" data-kind="data" data-main="1" style="left:16px; top:16px; width:360px; height:180px;"></section>
      <section class="panel" data-id="kpi-subs" data-kind="data" data-main="1" style="left:396px; top:16px; width:320px; height:180px;"></section>
      <section class="panel" data-id="list-links" data-kind="data" data-main="1" style="left:736px; top:352px; width:560px; height:260px;"></section>

      <!-- CHART widgets -->
      <section class="panel" data-id="chart-revenue" data-kind="chart" data-main="1" style="left:736px; top:16px; width:560px; height:320px;"></section>
      <section class="panel" data-id="chart-traffic" data-kind="chart" data-main="1" style="left:16px; top:212px; width:700px; height:320px;"></section>
      <section class="panel" data-id="chart-pie" data-kind="chart" data-main="0" style="left:16px; top:884px; width:360px; height:320px;"></section>
      <section class="panel" data-id="chart-resumo" data-kind="chart" data-main="1" style="left:16px; top:548px; width:700px; height:320px;"></section>

      <!-- CUSTOM widgets (App style) -->
      <section class="panel" data-id="app-blog" data-kind="custom" data-main="0" style="left:396px; top:884px; width:520px; height:300px;"></section>
      <section class="panel" data-id="app-webhook" data-kind="custom" data-main="0" style="left:936px; top:968px; width:360px; height:420px;"></section>

    </section>

    <div class="toast-float" id="toasts"></div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ========== Utilities ========== */
const $ = (sel, root=document) => root.querySelector(sel);
const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));
const esc = s => String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
const toastBox = $('#toasts');
function toast(msg, type='info'){ const el=document.createElement('div'); el.className=`alert alert-${type==='info'?'secondary':(type==='success'?'success':(type==='danger'?'danger':'warning'))} shadow-sm mt-2`; el.innerHTML=msg; toastBox.appendChild(el); setTimeout(()=>el.remove(),4000); }
const DEMO_RESUMO_TEXT = `
🗓️ *Atualizado em:* 20/10/2025

🏢 *Resumo por empresa:*
- *Componentes*: R$ 0,00
- *Eletrônica*: R$ 417.961,14

📊 *Soma total do período:* R$ 417.961,14

💰 *Resumo por data:*
📅 20/10/2025: R$ 51.274,73
📅 17/10/2025: R$ 55.660,00
📅 16/10/2025: R$ 38.175,00
📅 15/10/2025: R$ 74.615,00
📅 14/10/2025: R$ 11.371,40
📅 10/10/2025: R$ 2.110,00
📅 09/10/2025: R$ 15.310,00
📅 08/10/2025: R$ 97.490,00
📅 07/10/2025: R$ 11.489,65
📅 03/10/2025: R$ 18.680,00
📅 02/10/2025: R$ 23.155,00
📅 01/10/2025: R$ 18.630,36
`;
function getByPath(obj, path){ if(!path) return undefined; return path.split('.').reduce((a,k)=> (a && (k in a)) ? a[k] : undefined, obj); }
function uid(){ return 'id'+Math.random().toString(36).slice(2,9); }
function parsePtBrCurrency(str){ if(typeof str!=='string') return NaN; const cleaned=str.replace(/[^0-9,\.]/g,'').replace(/\./g,'').replace(',', '.'); const val=parseFloat(cleaned); return Number.isFinite(val)?val:NaN; }
function formatDateInput(date){ const y=date.getFullYear(); const m=String(date.getMonth()+1).padStart(2,'0'); const d=String(date.getDate()).padStart(2,'0'); return `${y}-${m}-${d}`; }
function currentMonthRange(){ const now=new Date(); const start=new Date(now.getFullYear(), now.getMonth(), 1); const end=new Date(now.getFullYear(), now.getMonth()+1, 0); return {start:formatDateInput(start), end:formatDateInput(end)}; }
function formDataToObject(fd){ const obj={}; if(!fd) return obj; for(const [k,v] of fd.entries()){ const value=(v instanceof File)?{name:v.name,size:v.size,type:v.type,lastModified:v.lastModified}:v; if(obj[k]!==undefined){ if(Array.isArray(obj[k])) obj[k].push(value); else obj[k]=[obj[k], value]; }else{ obj[k]=value; } } return obj; }
function parseResumoSummary(input){
  const result={labels:[], values:[], total:null, updatedAt:null, companies:[]};
  if(typeof input!=='string') return result;
  const text=input.replace(/\r/g,'');
  const updated=text.match(/Atualizado em:\*?\s*(\d{2}\/\d{2}\/\d{4})/i);
  if(updated) result.updatedAt=updated[1];
  const total=text.match(/Soma total do período:\*?\s*R\$\s*([\d\.,]+)/i);
  if(total){ const totalVal=parsePtBrCurrency(total[1]); if(!Number.isNaN(totalVal)) result.total=totalVal; }
  const companyRegex=/-\s*\*([^*]+)\*:\s*R\$\s*([\d\.,]+)/g;
  let match;
  while((match=companyRegex.exec(text))){ const val=parsePtBrCurrency(match[2]); if(!Number.isNaN(val)) result.companies.push({label:match[1].trim(), value:val}); }
  const dateRegex=/📅\s*(\d{2}\/\d{2}\/\d{4})\s*:\s*R\$\s*([\d\.,]+)/g;
  while((match=dateRegex.exec(text))){ const val=parsePtBrCurrency(match[2]); if(!Number.isNaN(val)){ result.labels.push(match[1]); result.values.push(val); } }
  return result;
}

/* ========== Theme ========== */
lucide.createIcons();
const rootBody = document.body;
const savedTheme = localStorage.getItem('nd.theme');
if(savedTheme){ rootBody.classList.remove('theme-ocean','theme-emerald','theme-orchid','theme-citrus'); rootBody.classList.add(`theme-${savedTheme}`); }
$$('[data-theme]').forEach(a=> a.addEventListener('click',e=>{
  e.preventDefault(); const t=a.getAttribute('data-theme');
  rootBody.classList.remove('theme-ocean','theme-emerald','theme-orchid','theme-citrus');
  rootBody.classList.add(`theme-${t}`); localStorage.setItem('nd.theme',t);
  $('.theme-dot').style.background = getComputedStyle(document.body).getPropertyValue('--accent');
}));

/* ========== Layout + Scrolling ========== */
let EDIT=false;
const appRoot = $('#appRoot');
const canvas = $('#canvas');
const panels = ()=> $$('.panel', canvas);
function fitCanvasHeight(){
  const bottoms = panels().map(p=> p.offsetTop + p.offsetHeight);
  const maxBottom = bottoms.length ? Math.max(...bottoms) : 0;
  const pad = 48, min = Math.max(window.innerHeight*0.6, 480);
  canvas.style.height = Math.max(maxBottom+pad, min) + 'px';
}
const ro=new ResizeObserver(()=> fitCanvasHeight());
function observe(){ panels().forEach(p=> ro.observe(p)); }
(function loadLayout(){
  const saved = JSON.parse(localStorage.getItem('nd.layout')||'null');
  if(saved){ panels().forEach(p=>{ const s=saved[p.dataset.id]; if(s){ Object.assign(p.style,{left:s.left,top:s.top,width:s.width,height:s.height}); }}); }
  fitCanvasHeight(); observe(); window.addEventListener('resize',fitCanvasHeight);
})();
$('#btn-save').addEventListener('click', ()=>{
  const out={}; panels().forEach(p=> out[p.dataset.id]={left:p.style.left,top:p.style.top,width:p.style.width,height:p.style.height});
  localStorage.setItem('nd.layout', JSON.stringify(out)); toast('Layout saved.','success');
});
$('#btn-reset').addEventListener('click', ()=>{ localStorage.removeItem('nd.layout'); localStorage.removeItem('nd.widgets.v3_1'); location.reload(); });
$('#btn-edit').addEventListener('click', ()=>{
  EDIT=!EDIT; $('#btn-edit').innerHTML = EDIT ? '<i data-lucide="check"></i> Done' : '<i data-lucide="edit-3"></i> Edit Mode';
  appRoot.classList.toggle('editing', EDIT);
  panels().forEach(p=> p.classList.toggle('ghost', EDIT));
  lucide.createIcons();
});
interact('.panel').draggable({
  allowFrom: '.handle',
  listeners:{
    start(e){ if(!EDIT) e.interaction.stop(); },
    move(e){ const t=e.target; const x=(parseFloat(t.getAttribute('data-x'))||0)+e.dx; const y=(parseFloat(t.getAttribute('data-y'))||0)+e.dy; t.style.transform=`translate(${x}px,${y}px)`; t.dataset.x=x; t.dataset.y=y; },
    end(e){ const t=e.target; const x=parseFloat(t.dataset.x)||0, y=parseFloat(t.dataset.y)||0;
      const r=t.getBoundingClientRect(), pr=canvas.getBoundingClientRect();
      const left=Math.max(8, r.left-pr.left), top=Math.max(8, r.top-pr.top);
      t.style.left=Math.round(left/16)*16+'px'; t.style.top=Math.round(top/16)*16+'px';
      t.style.transform=''; t.dataset.x=0; t.dataset.y=0; fitCanvasHeight();
    }
  }
}).resizable({
  edges:{left:false,right:true,bottom:true,top:false},
  listeners:{ move(e){ if(!EDIT) return; e.target.style.width=Math.round(e.rect.width/16)*16+'px'; e.target.style.height=Math.round(e.rect.height/16)*16+'px'; fitCanvasHeight(); } }
});

/* ========== Store ========== */
const LS_WIDGETS='nd.widgets.v3_1';
const store = {
  load(){ try{ return JSON.parse(localStorage.getItem(LS_WIDGETS)||'{}'); }catch{return {}} },
  save(obj){ localStorage.setItem(LS_WIDGETS, JSON.stringify(obj)); },
  get(id){ const all=this.load(); return all[id]||null; },
  set(id,cfg){ const all=this.load(); all[id]=cfg; this.save(all); }
};

/* ========== Overlay ========== */
let overlayEl=null;
function openOverlay(title, innerHTML){
  closeOverlay();
  overlayEl=document.createElement('div');
  overlayEl.className='nd-overlay';
  overlayEl.innerHTML=`
    <div class="nd-sheet">
      <div class="nd-head">
        <div class="d-flex align-items-center gap-2">
          <i data-lucide="settings"></i>
          <div class="fw-bold">${esc(title)}</div>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-soft btn-sm" data-ovl="close"><i data-lucide="x"></i> Close</button>
        </div>
      </div>
      <div class="nd-body" id="nd-pane">${innerHTML}</div>
    </div>`;
  document.body.appendChild(overlayEl);
  document.body.style.overflow='hidden';
  lucide.createIcons();
  overlayEl.addEventListener('click', (e)=>{ if(e.target===overlayEl) closeOverlay(); });
  overlayEl.querySelector('[data-ovl="close"]').addEventListener('click', closeOverlay);
}
function closeOverlay(){ if(!overlayEl) return; overlayEl.remove(); overlayEl=null; document.body.style.overflow=''; }

/* ========== Header + shells ========== */
function headerTemplate(cfg){
  const actionable = (cfg.kind==='data' || cfg.kind==='chart');
  return `
  <div class="head">
    <div class="title">
      <div class="icon"><i data-lucide="${esc(cfg.icon||'box')}"></i></div>
      <span class="w-title">${esc(cfg.title||'Untitled')}</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge-main">${esc(cfg.typeLabel|| (cfg.kind==='custom'?'App':'Data'))}</span>
      ${actionable ? `<button class="btn btn-icon btn-refresh accent" title="Refresh"><i data-lucide="refresh-ccw"></i></button>`:''}
      <button class="btn btn-icon btn-config" title="Configure"><i data-lucide="settings"></i></button>
      <span class="handle" title="Drag (Edit Mode)"><i data-lucide="move"></i></span>
    </div>
  </div>`;
}
function bodyShell(){
  return `
    <div class="body">
      <div class="content"></div>
      <div class="divider d-none"></div>
      <div class="status small text-secondary d-none"></div>
    </div>
    <div class="resize-corner"></div>`;
}
function setStatus(panel, msg){
  const div = panel.querySelector('.status');
  const sep = panel.querySelector('.divider');
  if(!msg){ div.classList.add('d-none'); div.textContent=''; sep.classList.add('d-none'); }
  else{ div.classList.remove('d-none'); sep.classList.remove('d-none'); div.textContent=msg; }
}

/* ========== Button spinner helpers ========== */
function spinRefresh(btn, on){
  if(on){
    const icon = btn.querySelector('i');
    if(icon) icon.style.display='none';
    let sp = btn.querySelector('.spinner-border');
    if(!sp){ sp = document.createElement('span'); sp.className='spinner-border'; btn.appendChild(sp); }
  }else{
    const icon = btn.querySelector('i');
    if(icon) icon.style.display='';
    const sp = btn.querySelector('.spinner-border');
    if(sp) sp.remove();
  }
}

/* ========== Fetch helper ========== */
async function fetchAndApply(panel, cfg, applyFn, formDataOverride){
  const statusSetter = (txt)=> setStatus(panel, txt);
  const btn = panel.querySelector('.btn-run') || panel.querySelector('.btn-refresh');
  if(!btn){ toast('No action button found.','warning'); return; }

  const isRefreshBtn = btn.classList.contains('btn-refresh');

  btn.disabled=true;
  let spinEl=null;
  if(isRefreshBtn){ spinRefresh(btn, true); }
  else{ spinEl=document.createElement('span'); spinEl.className='spinner-border spinner-border-sm'; btn.appendChild(spinEl); }

  const {url,method,sendAsJson,headers} = cfg.n8n||{};
  if(!url){ toast('Please configure webhook URL.','warning'); statusSetter('Missing URL'); btn.disabled=false; if(isRefreshBtn){ spinRefresh(btn,false); } else if(spinEl){ spinEl.remove(); } return; }

  let fetchUrl=url, fetchOpts={ method:(method||'POST'), headers:{}, mode:'cors' };
  (headers||[]).forEach(h=>{ if(h.key) fetchOpts.headers[h.key]=h.value||''; });

  if((method||'POST')==='GET'){
    if(formDataOverride){
      const params = new URLSearchParams();
      for(const [k,v] of formDataOverride.entries()){ if(v instanceof File) continue; params.append(k,String(v)); }
      fetchUrl += (fetchUrl.includes('?')?'&':'?') + params.toString();
    }
  }else{
    if(formDataOverride){
      const hasFile=[...formDataOverride.values()].some(v=> v instanceof File);
      if(!sendAsJson || hasFile){ if(fetchOpts.headers['Content-Type']) delete fetchOpts.headers['Content-Type']; fetchOpts.body=formDataOverride; }
      else{ fetchOpts.headers['Content-Type']='application/json'; const obj={}; for(const [k,v] of formDataOverride.entries()){ obj[k]=v; } fetchOpts.body=JSON.stringify(obj); }
    }else{
      if(sendAsJson){ fetchOpts.headers['Content-Type']='application/json'; fetchOpts.body='{}'; }
    }
  }

  statusSetter('Sending…');
  try{
    const res = await fetch(fetchUrl, fetchOpts);
    const ct=(res.headers.get('content-type')||'').toLowerCase();
    let data=null, text=null, blob=null;
    if(ct.includes('application/json')) data=await res.json(); else if(ct.startsWith('text/')) text=await res.text(); else blob=await res.blob();
    if(!res.ok) toast(`Webhook returned ${res.status}`,'warning'); else toast('Webhook succeeded.','success');
    applyFn(data,text,blob,ct, formDataOverride||null);
  }catch(err){
    toast('Network/CORS error calling webhook.','danger');
    statusSetter('Error (see console / CORS)'); console.error(err);
  }finally{
    btn.disabled=false;
    if(isRefreshBtn){ spinRefresh(btn,false); } else if(spinEl){ spinEl.remove(); }
    fitCanvasHeight();
  }
}

/* ========== Data widgets ========== */
function renderDataWidget(panel, cfg){
  if(panel._payloadHandler){ window.removeEventListener('nd:payload', panel._payloadHandler); panel._payloadHandler=null; }
  const content = panel.querySelector('.content');
  const v = cfg.dataSpec||{};
  content.innerHTML = `
    <div class="row g-2 align-items-center">
      <div class="col-12">
        <div class="kpi">
          <span class="main-val" id="v1-${cfg.id}">${esc(v.demoV1||'—')}</span>
          <span class="delta ${((v.demoV2||'').toString().trim().startsWith('-'))?'down':'up'}" id="v2-${cfg.id}">${esc(v.demoV2||'')}</span>
        </div>
        <div class="small text-secondary">${esc(cfg.subtitle||'')}</div>
      </div>
      <div class="col-12" id="list-${cfg.id}"></div>
    </div>
  `;
  lucide.createIcons();

  if((cfg.dataSpec?.mode||'kpi')==='list'){
    $(`#list-${cfg.id}`).innerHTML = `
      <table class="table table-sm table-darkish align-middle mb-0"><tbody id="tbody-${cfg.id}">
        ${(v.demoList||[]).map(item=>`<tr><td><a href="${esc(item.url||'#')}" target="_blank">${esc(item.text||'Item')}</a></td></tr>`).join('') || `<tr><td class="text-secondary small">No items yet.</td></tr>`}
      </tbody></table>
    `;
  }

  const applyData = (data,{source='fetch'}={})=>{
    const mode = cfg.dataSpec?.mode||'kpi';
    const viaForm = source==='broadcast';
    if(mode==='list'){
      const listPath = cfg.dataSpec.listPath||'items';
      const labelPath = cfg.dataSpec.itemLabelPath||'title';
      const urlPath   = cfg.dataSpec.itemUrlPath||'url';
      const listData = getByPath(data, listPath);
      if(viaForm && typeof listData==='undefined') return;
      const arr = Array.isArray(listData) ? listData : [];
      const rows = arr.map(it=>`<tr><td><a href="${esc(getByPath(it,urlPath)||'#')}" target="_blank">${esc(getByPath(it,labelPath)||'Item')}</a></td></tr>`).join('');
      const tbody = $(`#tbody-${cfg.id}`);
      if(tbody) tbody.innerHTML = rows || `<tr><td class="text-secondary small">No items returned.</td></tr>`;
      setStatus(panel, `Loaded ${arr.length} items${viaForm?' • via formulário':''}.`);
    }else{
      const v1Raw = getByPath(data, cfg.dataSpec.value1Path||'value1');
      const v2Raw = getByPath(data, cfg.dataSpec.value2Path||'value2');
      const v3Raw = getByPath(data, cfg.dataSpec.value3UrlPath||'value3Url');
      if(viaForm && v1Raw===undefined && v2Raw===undefined && v3Raw===undefined) return;
      const v1 = (v1Raw!=null)?v1Raw:'—';
      const v2 = (v2Raw!=null)?v2Raw:'';
      const v3 = v3Raw;
      const v1El = $(`#v1-${cfg.id}`), v2El = $(`#v2-${cfg.id}`);
      v1El.textContent = v1;
      v2El.textContent = v2;
      v2El.classList.toggle('down', (String(v2||'').trim().startsWith('-')));
      if(v3){
        if(!v1El.parentNode.querySelector('a.link-v1')){
          const a=document.createElement('a'); a.className='link-v1'; a.style.textDecoration='none'; a.style.color='inherit'; v1El.replaceWith(a); a.appendChild(v1El);
        }
        v1El.parentNode.setAttribute('href', v3);
        v1El.parentNode.setAttribute('target','_blank');
      }
      setStatus(panel, `OK${viaForm?' • via formulário':''}`.trim());
    }
  };

  // Header refresh button
  $('.btn-refresh', panel).addEventListener('click', async (e)=>{
    e.preventDefault();
    await fetchAndApply(panel, cfg, (data)=>{ applyData(data,{source:'fetch'}); });
  });

  const payloadHandler = (evt)=>{
    const detail = evt.detail||{};
    if(!detail || detail.sourcePanelId===cfg.id) return;
    if(detail.data==null) return;
    applyData(detail.data,{source:'broadcast'});
  };
  window.addEventListener('nd:payload', payloadHandler);
  panel._payloadHandler = payloadHandler;

  attachConfig(panel, cfg, 'data');
}

/* ========== Chart widgets ========== */
function renderChartWidget(panel, cfg){
  if(panel._payloadHandler){ window.removeEventListener('nd:payload', panel._payloadHandler); panel._payloadHandler=null; }
  const content = panel.querySelector('.content');
  const canvasId = `c-${cfg.id}`;
  const metaId = `meta-${cfg.id}`;
  content.innerHTML = `
    <div class="chart-shell">
      <div class="chart-area"><canvas id="${canvasId}"></canvas></div>
      <div class="chart-meta small text-secondary mt-2 d-none" id="${metaId}"></div>
    </div>
  `;
  lucide.createIcons();
  const canvasEl = $(`#${canvasId}`);
  const ctx = canvasEl ? canvasEl.getContext('2d') : null;
  const metaEl = $(`#${metaId}`);
  let chart=null;

  function renderResumoChart(parsed, {isDemo=false, sourceLabel=''}={}){
    if(metaEl){ metaEl.classList.add('d-none'); metaEl.textContent=''; }
    if(chart){ chart.destroy(); chart=null; }
    if(!window.Chart || !ctx){
      setStatus(panel, 'Chart.js não disponível');
      return;
    }
    if(!parsed || !parsed.labels.length){
      setStatus(panel, isDemo ? 'Prévia demo: sem dados' : 'Resumo não encontrado');
      return;
    }
    const accentRgb = (getComputedStyle(document.body).getPropertyValue('--accent-rgb')||'14,165,233').trim()||'14,165,233';
    const datasetLabel = cfg.chartSpec?.datasetLabel || 'Total por data';
    const currencyFmt = new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL'});
    chart = new Chart(ctx,{
      type:'bar',
      data:{
        labels: parsed.labels,
        datasets:[{
          label: datasetLabel,
          data: parsed.values,
          backgroundColor: `rgba(${accentRgb},0.35)`,
          borderColor: `rgba(${accentRgb},0.85)`,
          borderWidth:1.5,
          borderRadius:6
        }]
      },
      options:{
        responsive:true,
        maintainAspectRatio:false,
        scales:{
          x:{ticks:{color:'#e6edf6'},grid:{color:'rgba(255,255,255,.08)'}},
          y:{
            ticks:{
              color:'#e6edf6',
              callback:(value)=> currencyFmt.format(Number(value)||0)
            },
            grid:{color:'rgba(255,255,255,.08)'},
            beginAtZero:true
          }
        },
        plugins:{
          legend:{labels:{color:'#e6edf6'}},
          tooltip:{
            callbacks:{
              label:(ctx)=>{
                const val = ctx.parsed?.y ?? ctx.parsed ?? 0;
                return `${ctx.dataset.label||''}: ${currencyFmt.format(val)}`.trim();
              }
            }
          }
        }
      }
    });
    const statusBits=[];
    if(isDemo) statusBits.push('Prévia demo');
    if(sourceLabel) statusBits.push(sourceLabel);
    if(parsed.updatedAt) statusBits.push(`Atualizado ${parsed.updatedAt}`);
    if(parsed.total!=null) statusBits.push(`Total ${currencyFmt.format(parsed.total)}`);
    statusBits.push(`${parsed.labels.length} datas`);
    const topCompany = parsed.companies.slice().sort((a,b)=> b.value-a.value)[0];
    if(topCompany && topCompany.value>0) statusBits.push(`Maior empresa: ${topCompany.label}`);
    setStatus(panel, statusBits.join(' • '));
    if(metaEl){
      const parts=[];
      if(parsed.total!=null){
        parts.push(`Total do período: <span class="text-white">${esc(currencyFmt.format(parsed.total))}</span>`);
      }
      if(parsed.companies.length){
        const companiesHtml = parsed.companies.map(c=>
          `<span class="me-3">${esc(c.label)}: <span class="text-white">${esc(currencyFmt.format(c.value))}</span></span>`
        ).join('');
        parts.push(`Empresas: ${companiesHtml}`);
      }
      if(isDemo){
        parts.push('<span class="text-secondary">Dados demonstrativos</span>');
      }
      if(parts.length){
        metaEl.innerHTML = parts.join(' • ');
        metaEl.classList.remove('d-none');
      }
    }
  }

  const updateFromData = (data,{source='fetch'}={})=>{
    if(!window.Chart || !ctx) return;
    const style = (cfg.chartSpec?.style||'line');
    const viaForm = source==='broadcast';
    if(metaEl){ metaEl.classList.add('d-none'); metaEl.textContent=''; }
    if(chart){ chart.destroy(); chart=null; }
    if(style==='line'){
      const labelsPath = cfg.chartSpec.labelsPath||'xLabels';
      const dataPath = cfg.chartSpec.dataPath||'series[0].data';
      const rawLabels = getByPath(data, labelsPath);
      const rawData = getByPath(data, dataPath);
      const hasData = Array.isArray(rawLabels) && Array.isArray(rawData);
      if(viaForm && !hasData) return;
      const labels = hasData ? rawLabels : (Array.isArray(rawLabels)?rawLabels:Array.from({length:30},(_,i)=>i+1));
      const dsData = hasData ? rawData : (Array.isArray(rawData)?rawData:Array.from({length:30},()=>Math.round(7000+Math.random()*2000)));
      const labelName = cfg.chartSpec.datasetLabel || (getByPath(data,'series[0].label')||'Series');
      chart = new Chart(ctx,{type:'line',data:{labels,datasets:[{label:labelName,data:dsData,tension:.35,fill:false,borderWidth:2}]},
        options:{responsive:true,maintainAspectRatio:false,scales:{x:{ticks:{color:'#e6edf6'},grid:{color:'rgba(255,255,255,.08)'}},y:{ticks:{color:'#e6edf6'},grid:{color:'rgba(255,255,255,.08)'},suggestedMax: getByPath(data, cfg.chartSpec.yMaxPath||'yMax')||undefined}}}});
      setStatus(panel, `Line: ${labels.length} pontos${viaForm?' • via formulário':''}`);
    }else if(style==='resumo'){
      const resumoPath = cfg.chartSpec?.resumoPath || '0.resumo';
      let resumoText = resumoPath ? getByPath(data, resumoPath) : null;
      if(typeof resumoText !== 'string'){
        if(Array.isArray(data)){
          const firstWithResumo = data.find(item=> item && typeof item.resumo==='string');
          resumoText = firstWithResumo ? firstWithResumo.resumo : resumoText;
        }else if(data && typeof data.resumo==='string'){
          resumoText = data.resumo;
        }
      }
      const parsed = parseResumoSummary(resumoText);
      if(parsed.labels.length){
        renderResumoChart(parsed, {sourceLabel: viaForm ? 'via formulário' : ''});
      }else{
        setStatus(panel, viaForm ? 'Payload recebido sem resumo.' : 'Resumo não encontrado');
      }
    }else if(style==='bar'){
      const labelsPath = cfg.chartSpec.labelsPath||'labels';
      const dataPath = cfg.chartSpec.dataPath||'data';
      const rawLabels = getByPath(data, labelsPath);
      const rawData = getByPath(data, dataPath);
      const hasData = Array.isArray(rawLabels) && Array.isArray(rawData);
      if(viaForm && !hasData) return;
      const labels = hasData ? rawLabels : (Array.isArray(rawLabels)?rawLabels:['Mon','Tue','Wed','Thu','Fri','Sat','Sun']);
      const dsData = hasData ? rawData : (Array.isArray(rawData)?rawData:[530,610,580,740,890,660,720]);
      const labelName = cfg.chartSpec.datasetLabel || (getByPath(data,'series[0].label')||'Visits');
      chart = new Chart(ctx,{type:'bar',data:{labels,datasets:[{label:labelName,data:dsData}]},
        options:{responsive:true,maintainAspectRatio:false,scales:{x:{ticks:{color:'#e6edf6'},grid:{color:'rgba(255,255,255,.08)'}},y:{ticks:{color:'#e6edf6'},grid:{color:'rgba(255,255,255,.08)'},suggestedMax: getByPath(data, cfg.chartSpec.yMaxPath||'yMax')||undefined}}}});
      setStatus(panel, `Bar: ${labels.length} barras${viaForm?' • via formulário':''}`);
    }else{ // pie
      const labelsPath = cfg.chartSpec.labelsPath||'labels';
      const dataPath = cfg.chartSpec.dataPath||'values';
      const rawLabels = getByPath(data, labelsPath);
      const rawData = getByPath(data, dataPath);
      const hasData = Array.isArray(rawLabels) && Array.isArray(rawData);
      if(viaForm && !hasData) return;
      const labels = hasData ? rawLabels : (Array.isArray(rawLabels)?rawLabels:['A','B','C']);
      const values = hasData ? rawData : (Array.isArray(rawData)?rawData:[30,40,30]);
      chart = new Chart(ctx,{type:'pie',data:{labels,datasets:[{data:values}]},options:{responsive:true,maintainAspectRatio:false}});
      setStatus(panel, `Pie: ${labels.length} fatias${viaForm?' • via formulário':''}`);
    }
  };

  async function run(){
    await fetchAndApply(panel, cfg, (data)=>{ updateFromData(data,{source:'fetch'}); });
  }

  if((cfg.chartSpec?.style||'line')==='resumo'){
    const demoText = cfg.chartSpec?.demoResumoText || DEMO_RESUMO_TEXT;
    const parsedDemo = parseResumoSummary(demoText);
    if(parsedDemo.labels.length){
      renderResumoChart(parsedDemo, {isDemo:true});
    }else{
      setStatus(panel, 'Prévia demo indisponível — clique em Refresh.');
    }
  }
  $('.btn-refresh', panel).addEventListener('click', (e)=>{ e.preventDefault(); run(); });

  const payloadHandler = (evt)=>{
    const detail = evt.detail||{};
    if(!detail || detail.sourcePanelId===cfg.id) return;
    if(detail.data==null) return;
    updateFromData(detail.data,{source:'broadcast'});
  };
  window.addEventListener('nd:payload', payloadHandler);
  panel._payloadHandler = payloadHandler;

  attachConfig(panel, cfg, 'chart');
}

/* ========== Custom (App) widgets ========== */
function ensureWebhookDateFields(cfg){
  if(!cfg || cfg.id!=='app-webhook') return;
  cfg.customSpec = cfg.customSpec || {responseOnly:false, fields:[]};
  const monthRange = currentMonthRange();
  let fields = Array.isArray(cfg.customSpec.fields) ? cfg.customSpec.fields.slice() : [];
  let startField = fields.find(f=>f && f.name==='data_inicio');
  if(startField){
    if(!startField.id) startField.id = uid();
    startField.type='date';
    startField.label = startField.label || 'Data início';
    startField.auto = 'month-start';
    startField.width = startField.width || 'col-md-6';
    startField.value = monthRange.start;
  }else{
    startField = {id:uid(), type:'date', name:'data_inicio', label:'Data início', auto:'month-start', width:'col-md-6', value:monthRange.start};
  }
  let endField = fields.find(f=>f && f.name==='data_final');
  if(endField){
    if(!endField.id) endField.id = uid();
    endField.type='date';
    endField.label = endField.label || 'Data final';
    endField.auto = 'month-end';
    endField.width = endField.width || 'col-md-6';
    endField.value = monthRange.end;
  }else{
    endField = {id:uid(), type:'date', name:'data_final', label:'Data final', auto:'month-end', width:'col-md-6', value:monthRange.end};
  }
  const others = fields.filter(f=> f && f.name!=='data_inicio' && f.name!=='data_final');
  cfg.customSpec.fields = [startField, endField, ...others];
}
function renderCustomWidget(panel, cfg){
  if(cfg.id==='app-webhook') ensureWebhookDateFields(cfg);
  const content = panel.querySelector('.content');
  content.innerHTML = `
    <form class="row g-2" id="frm-${cfg.id}">
      ${(cfg.customSpec?.responseOnly)?'': (cfg.customSpec?.fields||[
        {id:uid(),type:'text',name:'topic',placeholder:'Topic (e.g., AI for SMBs)',label:'Topic'},
        {id:uid(),type:'select',name:'tone',options:['Professional','Friendly','Playful'],label:'Tone'}
      ]).map(f=>inputHtml(f)).join('')}
      <div class="${(cfg.customSpec?.responseOnly)?'col-12 d-grid':'col-md-2 d-grid'}">
        <button class="btn btn-accent btn-pill btn-run" type="submit"><i data-lucide="play"></i> <span>${esc(cfg.runLabel||'Run')}</span></button>
      </div>
    </form>
    <div class="divider d-none"></div>
    <div class="small status d-none" id="resp-${cfg.id}"></div>
  `;
  lucide.createIcons();
  const range = currentMonthRange();
  $$('input[data-auto="month-start"]', panel).forEach(inp=>{ inp.value = range.start; });
  $$('input[data-auto="month-end"]', panel).forEach(inp=>{ inp.value = range.end; });

  $(`#frm-${cfg.id}`, panel).addEventListener('submit', async (e)=>{
    e.preventDefault();
    const respEl = $(`#resp-${cfg.id}`, panel);
    setStatus(panel, 'Sending…');
    const submitted = new FormData(e.target);
    await fetchAndApply(panel, cfg, (data,text,blob,ct)=>{
      // Show response below (nice message)
      const msgEl = respEl;
      if(ct.includes('application/json')) msgEl.innerHTML = `<pre class="small mb-0">${esc(JSON.stringify(data,null,2))}</pre>`;
      else if(ct.startsWith('text/')) msgEl.innerHTML = `<pre class="small mb-0">${esc(text)}</pre>`;
      else { const url = URL.createObjectURL(blob); msgEl.innerHTML = `Received <b>${esc(ct||'binary')}</b> (${blob.size.toLocaleString()} bytes). <a href="${url}" download="response">Download</a>`; }
      msgEl.classList.remove('d-none'); panel.querySelector('.divider').classList.remove('d-none');
      if(data!=null){
        const detail = {
          sourcePanelId: cfg.id,
          data,
          contentType: ct,
          form: formDataToObject(submitted),
          receivedAt: Date.now()
        };
        window.dispatchEvent(new CustomEvent('nd:payload', {detail}));
      }
      setStatus(panel, 'OK (payload recebido)');
    }, submitted);
  });

  attachConfig(panel, cfg, 'custom');
}
function inputHtml(f){
  const id=esc(f.id||uid());
  const nm=esc(f.name||'field');
  const labelHtml = f.label ? `<label class="form-label small" for="${id}">${esc(f.label)}</label>` : '';
  const ph=f.placeholder?` placeholder="${esc(f.placeholder)}"`:'';
  const autoAttr=f.auto?` data-auto="${esc(f.auto)}"`:'';
  const valueAttr=(f.value!=null && f.type!=='file' && f.type!=='textarea' && f.type!=='checkbox')?` value="${esc(f.value)}"`:'';
  const widthClass = esc(f.width || ((f.type==='textarea'||f.type==='file')?'col-12':(f.type==='checkbox')?'col-12 form-check ms-2':(f.type==='number'||f.type==='date'||f.type==='select')?'col-md-4':'col-md-6'));
  if(f.type==='select'){
    return `<div class="${widthClass}">${labelHtml}<select id="${id}" name="${nm}" class="form-select"${autoAttr}>${(f.options||[]).map(o=>`<option>${esc(o)}</option>`).join('')}</select></div>`;
  }
  if(f.type==='textarea'){
    return `<div class="${widthClass}">${labelHtml}<textarea id="${id}" name="${nm}" rows="4" class="form-control"${ph}${autoAttr}>${f.value!=null?esc(f.value):''}</textarea></div>`;
  }
  if(f.type==='file'){
    return `<div class="${widthClass}">${labelHtml}<input id="${id}" name="${nm}" type="file" class="form-control"${autoAttr} /></div>`;
  }
  if(f.type==='checkbox'){
    const checked = f.value ? ' checked' : '';
    const lbl = f.label || nm;
    return `<div class="${widthClass}"><input id="${id}" name="${nm}" class="form-check-input" type="checkbox"${autoAttr}${checked}> <label class="form-check-label" for="${id}">${esc(lbl)}</label></div>`;
  }
  if(f.type==='number'){
    return `<div class="${widthClass}">${labelHtml}<input id="${id}" name="${nm}" type="number" class="form-control"${ph}${valueAttr}${autoAttr}/></div>`;
  }
  if(f.type==='date'){
    return `<div class="${widthClass}">${labelHtml}<input id="${id}" name="${nm}" type="date" class="form-control"${valueAttr}${autoAttr}/></div>`;
  }
  return `<div class="${widthClass}">${labelHtml}<input id="${id}" name="${nm}" class="form-control"${ph}${valueAttr}${autoAttr}/></div>`;
}

/* ========== Config forms ========== */
function identityFields(cfg){
  return `
    <div class="row g-2">
      <div class="col-md-4"><label class="form-label small">Icon (lucide)</label><input class="form-control form-control-sm" data-cfg="icon" value="${esc(cfg.icon)}" placeholder="e.g. dollar-sign"/></div>
      <div class="col-md-6"><label class="form-label small">Title</label><input class="form-control form-control-sm" data-cfg="title" value="${esc(cfg.title)}"/></div>
      <div class="col-md-2"><label class="form-label small">Badge</label><select class="form-select form-select-sm" data-cfg="typeLabel"><option ${cfg.typeLabel==='Data'?'selected':''}>Data</option><option ${cfg.typeLabel==='App'?'selected':''}>App</option></select></div>
    </div>`;
}
function n8nFields(cfg){
  const n=cfg.n8n;
  return `
    <div class="row g-2 mt-2">
      <div class="col-md-8"><label class="form-label small">Webhook URL</label><input class="form-control form-control-sm" data-cfg="url" value="${esc(n.url||'')}" placeholder="https://your-n8n/webhook/..."/></div>
      <div class="col-md-2"><label class="form-label small">Method</label><select class="form-select form-select-sm" data-cfg="method"><option ${n.method==='POST'?'selected':''}>POST</option><option ${n.method==='GET'?'selected':''}>GET</option></select></div>
      <div class="col-md-2"><label class="form-label small">POST Body</label><select class="form-select form-select-sm" data-cfg="sendAsJson"><option value="auto" ${!n.sendAsJson?'selected':''}>Auto</option><option value="json" ${n.sendAsJson?'selected':''}>JSON</option></select></div>
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mt-1">
          <div class="small text-secondary">Headers</div>
          <button type="button" class="btn btn-soft btn-sm" data-act="add-header"><i data-lucide="plus"></i> Add Header</button>
        </div>
        <div id="hdrList">${(n.headers||[]).map(h=>`
          <div class="row g-2 align-items-end mb-1" data-hrow="${esc(h.id)}">
            <div class="col-md-5"><input class="form-control form-control-sm" data-h="key" placeholder="Header name" value="${esc(h.key||'')}" /></div>
            <div class="col-md-5"><input class="form-control form-control-sm" data-h="val" placeholder="Header value" value="${esc(h.value||'')}" /></div>
            <div class="col-md-2 d-flex justify-content-end"><button type="button" class="btn btn-soft btn-sm" data-act="del-header"><i data-lucide="trash"></i></button></div>
          </div>`).join('') || `<div class="text-secondary small">No headers set.</div>`}
        </div>
      </div>
    </div>
    <div class="cfg-help mt-2">
      <details>
        <summary class="text-secondary">n8n — HTTP Response setup (click)</summary>
        <pre>
Use an <b>HTTP Response</b> node as the last step:
- Status: 200
- Headers:
  Content-Type: application/json
  Access-Control-Allow-Origin: *
  Access-Control-Allow-Headers: *
  Access-Control-Allow-Methods: GET,POST,OPTIONS
- Response Body: (JSON examples below per widget type)
        </pre>
      </details>
    </div>`;
}
function dataConfigForm(cfg){
  const ds=cfg.dataSpec;
  return `
    <form class="wcfg" autocomplete="off">
      ${identityFields(cfg)}
      ${n8nFields(cfg)}
      <div class="divider"></div>
      <div class="row g-2">
        <div class="col-md-3">
          <label class="form-label small">Data Mode</label>
          <select class="form-select form-select-sm" data-cfg="dataMode">
            <option value="kpi" ${ds.mode!=='list'?'selected':''}>KPI (Value1/Value2 + optional link)</option>
            <option value="list" ${ds.mode==='list'?'selected':''}>Link List (title + url)</option>
          </select>
        </div>
        <div class="col-md-3"><label class="form-label small">Run Label</label><input class="form-control form-control-sm" data-cfg="runLabel" value="${esc(cfg.runLabel||'Refresh')}"/></div>
        <div class="col-md-6"><label class="form-label small">Subtitle</label><input class="form-control form-control-sm" data-cfg="subtitle" value="${esc(cfg.subtitle||'Updated just now')}"/></div>
      </div>

      <div class="row g-2 mt-1 ${ds.mode==='list'?'d-none':''}" data-map="kpi">
        <div class="col-md-4"><label class="form-label small">value1 path</label><input class="form-control form-control-sm" data-cfg="value1Path" value="${esc(ds.value1Path||'value1')}" /></div>
        <div class="col-md-4"><label class="form-label small">value2 path</label><input class="form-control form-control-sm" data-cfg="value2Path" value="${esc(ds.value2Path||'value2')}" /></div>
        <div class="col-md-4"><label class="form-label small">value3Url path</label><input class="form-control form-control-sm" data-cfg="value3UrlPath" value="${esc(ds.value3UrlPath||'value3Url')}" /></div>
      </div>

      <div class="row g-2 mt-1 ${ds.mode==='list'?'':'d-none'}" data-map="list">
        <div class="col-md-4"><label class="form-label small">list path</label><input class="form-control form-control-sm" data-cfg="listPath" value="${esc(ds.listPath||'items')}" /></div>
        <div class="col-md-4"><label class="form-label small">item label path</label><input class="form-control form-control-sm" data-cfg="itemLabelPath" value="${esc(ds.itemLabelPath||'title')}" /></div>
        <div class="col-md-4"><label class="form-label small">item url path</label><input class="form-control form-control-sm" data-cfg="itemUrlPath" value="${esc(ds.itemUrlPath||'url')}" /></div>
      </div>

      <div class="divider"></div>
      <div class="cfg-help">
        <details open>
          <summary><b>Expected JSON from n8n</b></summary>
          <pre>
KPI:
{
  "value1": "$82,440",
  "value2": "+4.3%",
  "value3Url": "https://finance.yahoo.com"   // optional (makes Value1 clickable)
}

Link list:
{
  "items": [
    { "title": "Headline A", "url": "https://..." },
    { "title": "Headline B", "url": "https://..." }
  ]
}
          </pre>
        </details>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-2">
        <button type="button" class="btn btn-soft btn-sm" data-act="close"><i data-lucide="x"></i> Close</button>
        <button type="submit" class="btn btn-accent btn-sm"><i data-lucide="save"></i> Save</button>
      </div>
    </form>`;
}
function chartConfigForm(cfg){
  const cs=cfg.chartSpec;
  const isPie = cs.style==='pie';
  const isResumo = cs.style==='resumo';
  return `
    <form class="wcfg" autocomplete="off">
      ${identityFields(cfg)}
      ${n8nFields(cfg)}
      <div class="divider"></div>
      <div class="row g-2">
        <div class="col-md-3">
          <label class="form-label small">Chart Style</label>
          <select class="form-select form-select-sm" data-cfg="chartStyle">
            <option value="line" ${cs.style==='line'?'selected':''}>Line (Revenue 30d)</option>
            <option value="bar" ${cs.style==='bar'?'selected':''}>Bar (Traffic 7d)</option>
            <option value="pie" ${cs.style==='pie'?'selected':''}>Pie</option>
            <option value="resumo" ${cs.style==='resumo'?'selected':''}>Resumo texto (pt-BR)</option>
          </select>
        </div>
        <div class="col-md-3"><label class="form-label small">Run Label</label><input class="form-control form-control-sm" data-cfg="runLabel" value="${esc(cfg.runLabel||'Refresh')}"/></div>
      </div>
      <div class="row g-2 mt-1 ${isResumo?'d-none':''}" data-map="standard">
        <div class="col-md-4"><label class="form-label small">labels path</label><input class="form-control form-control-sm" data-cfg="labelsPath" value="${esc(cs.labelsPath|| (cs.style==='bar'?'labels':'xLabels'))}" /></div>
        <div class="col-md-4"><label class="form-label small">${isPie?'values':'data'} path</label><input class="form-control form-control-sm" data-cfg="dataPath" value="${esc(cs.dataPath|| (isPie?'values':'series[0].data'))}" /></div>
        <div class="col-md-4 ${isPie?'d-none':''}"><label class="form-label small">dataset label</label><input class="form-control form-control-sm" data-cfg="datasetLabel" value="${esc(cs.datasetLabel||'Series')}" /></div>
        <div class="col-md-4"><label class="form-label small">yMax path (optional)</label><input class="form-control form-control-sm" data-cfg="yMaxPath" value="${esc(cs.yMaxPath||'yMax')}" /></div>
      </div>
      <div class="row g-2 mt-1 ${isResumo?'':'d-none'}" data-map="resumo">
        <div class="col-md-6"><label class="form-label small">Resumo text path</label><input class="form-control form-control-sm" data-cfg="resumoPath" value="${esc(cs.resumoPath||'0.resumo')}" /></div>
        <div class="col-md-4"><label class="form-label small">Dataset label</label><input class="form-control form-control-sm" data-cfg="datasetLabel" value="${esc(cs.datasetLabel||'Total por data')}" /></div>
      </div>

      <div class="divider"></div>
      <div class="cfg-help">
        <details open>
          <summary><b>Expected JSON from n8n</b> (examples)</summary>
          <pre>
Line (30d revenue):
{
  "title": "Revenue (30 days)",
  "yMax": 12000,
  "xLabels": ["2025-07-12","2025-07-13",...],
  "series": [ { "label":"Revenue", "data":[9340,9520,...] } ]
}

Bar (7d traffic):
{
  "labels": ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
  "data": [530,610,580,740,890,660,720],
  "yMax": 1000
}

Pie:
{
  "labels": ["Organic","Paid","Referral"],
  "values": [42,35,23]
}

Resumo texto (pt-BR):
[
  {
    "resumo": "\n🗓️ *Atualizado em:* 20/10/2025\n...\n📅 20/10/2025: R$ 51.274,73\n📅 17/10/2025: R$ 55.660,00\n"
  }
]
          </pre>
        </details>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-2">
        <button type="button" class="btn btn-soft btn-sm" data-act="close"><i data-lucide="x"></i> Close</button>
        <button type="submit" class="btn btn-accent btn-sm"><i data-lucide="save"></i> Save</button>
      </div>
    </form>`;
}
function customConfigForm(cfg){
  const cs=cfg.customSpec;
  return `
    <form class="wcfg" autocomplete="off">
      ${identityFields(cfg)}
      ${n8nFields(cfg)}
      <div class="divider"></div>
      <div class="row g-2">
        <div class="col-md-3"><label class="form-label small">Run Label</label><input class="form-control form-control-sm" data-cfg="runLabel" value="${esc(cfg.runLabel||'Run')}"/></div>
        <div class="col-md-3"><label class="form-label small">Response only</label><br><input type="checkbox" class="form-check-input mt-2" data-cfg="responseOnly" ${cs.responseOnly?'checked':''}/></div>
      </div>
      <div class="divider"></div>
      <div class="small text-secondary mb-1">Fields</div>
      <div id="fieldList">
        ${(cs.fields||[]).map(f=> `
        <div class="row g-2 align-items-end mb-2" data-row="${esc(f.id)}" style="border:1px dashed var(--line);border-radius:12px;padding:8px;">
          <div class="col-md-2">
            <label class="form-label small">Type</label>
            <select class="form-select form-select-sm" data-edit="type">
              ${['text','number','date','textarea','checkbox','select','file'].map(t=>`<option value="${t}" ${f.type===t?'selected':''}>${t}</option>`).join('')}
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label small">Name</label>
            <input class="form-control form-control-sm" data-edit="name" placeholder="name" value="${esc(f.name||'field')}"/>
          </div>
          <div class="col-md-3">
            <label class="form-label small">Label</label>
            <input class="form-control form-control-sm" data-edit="label" placeholder="Label" value="${esc(f.label||'')}"/>
          </div>
          <div class="col-md-3 ${f.type==='select'?'':'d-none'}">
            <label class="form-label small">Options</label>
            <input class="form-control form-control-sm" data-edit="options" placeholder="opt1, opt2" value="${esc((f.options||[]).join(', '))}"/>
          </div>
          <div class="col-md-1 d-flex justify-content-end"><button type="button" class="btn btn-soft btn-sm" data-act="del-field"><i data-lucide="trash"></i></button></div>
        </div>`).join('')}
      </div>
      <div class="d-flex gap-2">
        <select id="addType" class="form-select form-select-sm" style="width:auto">
          <option value="text">text</option><option value="number">number</option><option value="date">date</option><option value="textarea">textarea</option><option value="checkbox">checkbox</option><option value="select">select</option><option value="file">file</option>
        </select>
        <button type="button" class="btn btn-accent btn-sm" data-act="add-field"><i data-lucide="plus"></i> Add Field</button>
      </div>

      <div class="cfg-help mt-2">
        <details>
          <summary class="text-secondary">Example n8n: echo posted fields</summary>
          <pre>
// In Function node:
return [{ json: $json }];

// In HTTP Response:
Status 200, Content-Type application/json, Body: {{$json}}
          </pre>
        </details>
      </div>

      <div class="d-flex justify-content-end gap-2 mt-2">
        <button type="button" class="btn btn-soft btn-sm" data-act="close"><i data-lucide="x"></i> Close</button>
        <button type="submit" class="btn btn-accent btn-sm"><i data-lucide="save"></i> Save</button>
      </div>
    </form>`;
}

/* ========== Attach config (overlay) ========== */
function attachConfig(panel, cfg, kind){
  const header = panel.querySelector('.head');

  function rerenderHeader(){
    header.querySelector('.title .icon').innerHTML = `<i data-lucide="${esc(cfg.icon)}"></i>`;
    header.querySelector('.w-title').textContent = cfg.title;
    header.querySelector('.badge-main').textContent = cfg.typeLabel;
    lucide.createIcons();
  }

  header.querySelector('.btn-config').onclick = ()=>{
    const formHtml = kind==='data'? dataConfigForm(cfg) : kind==='chart'? chartConfigForm(cfg) : customConfigForm(cfg);
    openOverlay(`Configure: ${cfg.title}`, formHtml);
    bindOverlayEvents();
  };

  function bindOverlayEvents(){
    const pane = $('#nd-pane');
    pane.addEventListener('input', (e)=>{
      const t=e.target;
      if(t.matches('[data-cfg="icon"]')){ cfg.icon=t.value.trim()||'box'; rerenderHeader(); }
      if(t.matches('[data-cfg="title"]')){ cfg.title=t.value; rerenderHeader(); }
      if(t.matches('[data-cfg="typeLabel"]')){ cfg.typeLabel=t.value; rerenderHeader(); }
      if(t.matches('[data-cfg="url"]')) cfg.n8n.url=t.value.trim();
      if(t.matches('[data-cfg="method"]')) cfg.n8n.method=t.value;
      if(t.matches('[data-cfg="sendAsJson"]')) cfg.n8n.sendAsJson=(t.value==='json');

      if(kind==='data'){
        if(t.matches('[data-cfg="dataMode"]')){ cfg.dataSpec.mode=t.value; openOverlay(`Configure: ${cfg.title}`, dataConfigForm(cfg)); bindOverlayEvents(); return; }
        if(t.matches('[data-cfg="runLabel"]')) cfg.runLabel=t.value;
        if(t.matches('[data-cfg="subtitle"]')) cfg.subtitle=t.value;
        if(t.matches('[data-cfg="value1Path"]')) cfg.dataSpec.value1Path=t.value;
        if(t.matches('[data-cfg="value2Path"]')) cfg.dataSpec.value2Path=t.value;
        if(t.matches('[data-cfg="value3UrlPath"]')) cfg.dataSpec.value3UrlPath=t.value;
        if(t.matches('[data-cfg="listPath"]')) cfg.dataSpec.listPath=t.value;
        if(t.matches('[data-cfg="itemLabelPath"]')) cfg.dataSpec.itemLabelPath=t.value;
        if(t.matches('[data-cfg="itemUrlPath"]')) cfg.dataSpec.itemUrlPath=t.value;
      }
      if(kind==='chart'){
        if(t.matches('[data-cfg="chartStyle"]')){
          cfg.chartSpec.style=t.value;
          if(t.value==='resumo'){
            cfg.chartSpec.resumoPath = cfg.chartSpec.resumoPath || '0.resumo';
            if(!cfg.chartSpec.datasetLabel || ['Series','Revenue','Visits'].includes(cfg.chartSpec.datasetLabel)){
              cfg.chartSpec.datasetLabel = 'Total por data';
            }
            if(!cfg.chartSpec.demoResumoText){
              cfg.chartSpec.demoResumoText = DEMO_RESUMO_TEXT;
            }
          }
          openOverlay(`Configure: ${cfg.title}`, chartConfigForm(cfg)); bindOverlayEvents(); return;
        }
        if(t.matches('[data-cfg="runLabel"]')) cfg.runLabel=t.value;
        if(t.matches('[data-cfg="labelsPath"]')) cfg.chartSpec.labelsPath=t.value;
        if(t.matches('[data-cfg="dataPath"]')) cfg.chartSpec.dataPath=t.value;
        if(t.matches('[data-cfg="datasetLabel"]')) cfg.chartSpec.datasetLabel=t.value;
        if(t.matches('[data-cfg="yMaxPath"]')) cfg.chartSpec.yMaxPath=t.value;
        if(t.matches('[data-cfg="resumoPath"]')) cfg.chartSpec.resumoPath=t.value;
      }
      if(kind==='custom'){
        if(t.matches('[data-cfg="runLabel"]')) cfg.runLabel=t.value;
        if(t.matches('[data-cfg="responseOnly"]')) cfg.customSpec.responseOnly = t.checked;
        if(t.closest('[data-row]')){
          const row=t.closest('[data-row]'); const id=row.getAttribute('data-row');
          const f = cfg.customSpec.fields.find(x=>x.id===id); if(!f) return;
          if(t.matches('[data-edit="type"]')){ f.type=t.value; openOverlay(`Configure: ${cfg.title}`, customConfigForm(cfg)); bindOverlayEvents(); return; }
          if(t.matches('[data-edit="name"]')) f.name=t.value;
          if(t.matches('[data-edit="label"]')) f.label=t.value;
          if(t.matches('[data-edit="options"]')) f.options=t.value.split(',').map(x=>x.trim()).filter(Boolean);
        }
      }
      // headers
      if(t.closest('[data-hrow]')){
        const row=t.closest('[data-hrow]'); const id=row.getAttribute('data-hrow');
        const h=(cfg.n8n.headers||[]).find(x=>x.id===id);
        if(h){ if(t.matches('[data-h="key"]')) h.key=t.value; if(t.matches('[data-h="val"]')) h.value=t.value; }
      }
      const all=store.load(); all[cfg.id]=cfg; store.save(all);
    });

    pane.addEventListener('click', (e)=>{
      const b=e.target.closest('[data-act]'); if(!b) return; const act=b.getAttribute('data-act');
      if(act==='close'){ closeOverlay(); }
      if(act==='add-header'){ cfg.n8n.headers=cfg.n8n.headers||[]; cfg.n8n.headers.push({id:uid(),key:'',value:''}); openOverlay(`Configure: ${cfg.title}`, (kind==='data'?dataConfigForm:kind==='chart'?chartConfigForm:customConfigForm)(cfg)); bindOverlayEvents(); }
      if(act==='del-header'){ const row=b.closest('[data-hrow]'); const id=row.getAttribute('data-hrow'); cfg.n8n.headers=(cfg.n8n.headers||[]).filter(h=>h.id!==id); openOverlay(`Configure: ${cfg.title}`, (kind==='data'?dataConfigForm:kind==='chart'?chartConfigForm:customConfigForm)(cfg)); bindOverlayEvents(); }
      if(act==='add-field'){ const type=$('#addType',pane).value; const label=type.charAt(0).toUpperCase()+type.slice(1).replace(/[-_]/g,' '); cfg.customSpec.fields=cfg.customSpec.fields||[]; cfg.customSpec.fields.push({id:uid(),type,name:`${type}_${cfg.customSpec.fields.length+1}`,label}); openOverlay(`Configure: ${cfg.title}`, customConfigForm(cfg)); bindOverlayEvents(); }
      if(act==='del-field'){ const row=b.closest('[data-row]'); const id=row.getAttribute('data-row'); cfg.customSpec.fields=cfg.customSpec.fields.filter(f=>f.id!==id); openOverlay(`Configure: ${cfg.title}`, customConfigForm(cfg)); bindOverlayEvents(); }
    });

    pane.addEventListener('submit', (e)=>{
      if(e.target.matches('.wcfg')){ e.preventDefault(); store.set(cfg.id,cfg); toast('Saved.','success'); closeOverlay(); rerenderPanel(panel, cfg); }
    });
  }
}

/* ========== Rerender ========== */
function rerenderPanel(panel, cfg){
  panel.innerHTML = headerTemplate(cfg) + bodyShell();
  lucide.createIcons();
  if(cfg.kind==='data') renderDataWidget(panel,cfg);
  if(cfg.kind==='chart') renderChartWidget(panel,cfg);
  if(cfg.kind==='custom') renderCustomWidget(panel,cfg);
  fitCanvasHeight();
}

/* ========== Init ========== */
function initPanel(panel){
  const id = panel.dataset.id;
  const kind = panel.dataset.kind;
  const saved = store.get(id);
  const monthRange = currentMonthRange();

  const defaults = {
    id, kind,
    icon: 'box',
    title: (()=>{
      if(id==='kpi-revenue') return 'Revenue (MRR)';
      if(id==='kpi-subs') return 'YouTube Subscribers';
      if(id==='list-links') return 'Latest Headlines';
      if(id==='chart-revenue') return 'Revenue (30 days)';
      if(id==='chart-traffic') return 'Traffic (7 days)';
      if(id==='chart-pie') return 'Market Share';
      if(id==='chart-resumo') return 'Resumo por Data';
      if(id==='app-blog') return 'Blog Generator';
      if(id==='app-webhook') return 'Webhook App';
      return 'Widget';
    })(),
    typeLabel: (kind==='custom')?'App':'Data',
    runLabel: (kind==='custom')?'Run':'Refresh',
    n8n: { url:'', method:'POST', sendAsJson:false, headers:[] },
    dataSpec: kind==='data'? {
      mode: (id==='list-links')?'list':'kpi',
      value1Path:'value1', value2Path:'value2', value3UrlPath:'value3Url',
      listPath:'items', itemLabelPath:'title', itemUrlPath:'url',
      demoV1: (id==='kpi-revenue')?'$82,440':(id==='kpi-subs')?'12,873':'—', demoV2:(id==='kpi-revenue')?'+4.3%':(id==='kpi-subs')?'+142':''
    } : undefined,
    chartSpec: kind==='chart'? {
      style: (id==='chart-revenue')?'line':(id==='chart-traffic')?'bar':(id==='chart-resumo')?'resumo':'pie',
      labelsPath: (id==='chart-revenue')?'xLabels':'labels',
      dataPath: (id==='chart-revenue')?'series[0].data':(id==='chart-traffic')?'data':'values',
      datasetLabel: (id==='chart-revenue')?'Revenue':(id==='chart-traffic')?'Visits':(id==='chart-resumo')?'Total por data':'',
      yMaxPath:'yMax',
      resumoPath:'0.resumo',
      demoResumoText: (id==='chart-resumo')?DEMO_RESUMO_TEXT:undefined
    } : undefined,
    customSpec: kind==='custom'? {
      responseOnly: false,
      fields: (id==='app-blog')?[
        {id:uid(),type:'text',name:'topic',placeholder:'Topic (e.g., AI for SMBs)',label:'Topic'},
        {id:uid(),type:'select',name:'tone',options:['Professional','Friendly','Playful'],label:'Tone'}
      ]:(id==='app-webhook')?[
        {id:uid(),type:'date',name:'data_inicio',label:'Data início',auto:'month-start',width:'col-md-6',value:monthRange.start},
        {id:uid(),type:'date',name:'data_final',label:'Data final',auto:'month-end',width:'col-md-6',value:monthRange.end},
        {id:uid(),type:'file',name:'arquivo',label:'Arquivo'}
      ]:[
        {id:uid(),type:'text',name:'prompt',placeholder:'Enter prompt',label:'Prompt'},
        {id:uid(),type:'file',name:'file',label:'Arquivo'}
      ]
    }: undefined,
    subtitle: ''
  };

  const cfg = saved ? Object.assign(defaults, saved) : defaults;
  if(cfg.kind==='custom' && Array.isArray(cfg.customSpec?.fields)){
    cfg.customSpec.fields.forEach(f=>{
      if(!f.id) f.id = uid();
      if(!f.label && f.name){ const base=f.name.replace(/[_-]/g,' '); f.label = base.charAt(0).toUpperCase()+base.slice(1); }
    });
  }
  if(id==='app-webhook') ensureWebhookDateFields(cfg);
  store.set(id, cfg);

  panel.innerHTML = headerTemplate(cfg) + bodyShell();
  lucide.createIcons();

  if(kind==='data') renderDataWidget(panel, cfg);
  if(kind==='chart') renderChartWidget(panel, cfg);
  if(kind==='custom') renderCustomWidget(panel, cfg);
}
panels().forEach(initPanel);

const resumoLocateBtn = $('#btn-resumo-locate');
if(resumoLocateBtn){
  resumoLocateBtn.addEventListener('click', ()=>{
    const alvo = $('.panel[data-id="chart-resumo"]');
    if(!alvo) return;
    alvo.scrollIntoView({behavior:'smooth', block:'center'});
    alvo.classList.remove('flash-panel');
    void alvo.offsetWidth;
    alvo.classList.add('flash-panel');
    setTimeout(()=> alvo.classList.remove('flash-panel'), 1600);
  });
}

/* Global "Run Selected" */
$('#btn-main').addEventListener('click', ()=>{
  toast('Refreshing selected widgets…');
  panels().filter(p=> p.dataset.main==='1').forEach(p=>{
    const btn = p.querySelector('.btn-refresh') || p.querySelector('.btn-run');
    if(btn){ btn.click(); }
  });
});
</script>
</body>
</html>
