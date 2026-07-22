<?php
/** @var string $pageTitle */
/** @var string $active */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($pageTitle ?? 'Payment Dashboard') ?> | BosheBoshe Pay</title>
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  /* Palette: dataviz reference instance (validated). Roles, not raw hex. */
  :root {
    color-scheme: light;
    --plane:        #f9f9f7;
    --surface:      #ffffff;
    --surface-2:    #fcfcfb;
    --ink:          #0b0b0b;
    --ink-2:        #52514e;
    --muted:        #898781;
    --grid:         #e1e0d9;
    --baseline:     #c3c2b7;
    --border:       rgba(11,11,11,0.10);
    --accent:       #2a78d6;
    --accent-ink:   #184f95;
    --series-1:     #2a78d6;
    --good:         #0ca30c;
    --good-ink:     #006300;
    --warning:      #fab219;
    --serious:      #ec835a;
    --critical:     #d03b3b;
    --shadow:       0 1px 2px rgba(11,11,11,0.04), 0 1px 3px rgba(11,11,11,0.06);
  }
  @media (prefers-color-scheme: dark) {
    :root:where(:not([data-theme="light"])) {
      color-scheme: dark;
      --plane:      #0d0d0d;
      --surface:    #1a1a19;
      --surface-2:  #1a1a19;
      --ink:        #ffffff;
      --ink-2:      #c3c2b7;
      --muted:      #898781;
      --grid:       #2c2c2a;
      --baseline:   #383835;
      --border:     rgba(255,255,255,0.10);
      --accent:     #3987e5;
      --accent-ink: #86b6ef;
      --series-1:   #3987e5;
      --good:       #0ca30c;
      --good-ink:   #0ca30c;
      --shadow:     0 1px 3px rgba(0,0,0,0.4);
    }
  }
  :root[data-theme="dark"] {
    color-scheme: dark;
    --plane:#0d0d0d; --surface:#1a1a19; --surface-2:#1a1a19; --ink:#fff; --ink-2:#c3c2b7;
    --muted:#898781; --grid:#2c2c2a; --baseline:#383835; --border:rgba(255,255,255,0.10);
    --accent:#3987e5; --accent-ink:#86b6ef; --series-1:#3987e5; --good:#0ca30c; --good-ink:#0ca30c;
    --shadow:0 1px 3px rgba(0,0,0,0.4);
  }

  * { box-sizing: border-box; }
  body {
    margin: 0;
    font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
    background: var(--plane);
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
    font-size: 14px;
    line-height: 1.5;
  }
  a { color: var(--accent); text-decoration: none; }
  a:hover { text-decoration: underline; }

  header.topbar {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 0 24px;
    display: flex; align-items: center; justify-content: space-between;
    height: 58px; position: sticky; top: 0; z-index: 10;
  }
  .brand { display: flex; align-items: center; gap: 9px; font-weight: 700; font-size: 15px; letter-spacing: -0.01em; }
  .brand .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--accent); display: inline-block; }
  .brand small { font-weight: 500; color: var(--muted); }
  nav.mainnav { display: flex; align-items: center; gap: 4px; }
  nav.mainnav a {
    color: var(--ink-2); font-size: 13.5px; font-weight: 500;
    padding: 7px 12px; border-radius: 7px; text-decoration: none;
  }
  nav.mainnav a:hover { background: var(--surface-2); color: var(--ink); text-decoration: none; }
  nav.mainnav a.active { background: color-mix(in srgb, var(--accent) 12%, transparent); color: var(--accent-ink); }
  nav.mainnav a.logout { color: var(--muted); }

  .wrap { max-width: 1160px; margin: 26px auto; padding: 0 22px; }
  .page-head { margin: 0 0 18px; }
  .page-head h1 { font-size: 20px; font-weight: 700; margin: 0; letter-spacing: -0.02em; }
  .page-head p { color: var(--muted); margin: 3px 0 0; font-size: 13px; }

  .card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: var(--shadow);
  }
  .card h3 { margin: 0 0 16px; font-size: 14px; font-weight: 650; letter-spacing: -0.01em; }
  .card h3 .sub { font-weight: 400; color: var(--muted); font-size: 12.5px; }
  .grid { display: grid; gap: 18px; }
  .grid.cols-2 { grid-template-columns: 1fr 1fr; }
  @media (max-width: 820px) { .grid.cols-2 { grid-template-columns: 1fr; } }

  /* Stat tiles */
  .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px,1fr)); gap: 16px; margin-bottom: 20px; }
  .stat {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 12px; padding: 18px; box-shadow: var(--shadow);
  }
  .stat .label { color: var(--muted); font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }
  .stat .num { font-size: 27px; font-weight: 750; margin-top: 8px; letter-spacing: -0.02em; }
  .stat .num small { font-size: 14px; font-weight: 600; color: var(--muted); }
  .stat .foot { font-size: 12px; color: var(--ink-2); margin-top: 5px; }
  .stat .foot .up { color: var(--good-ink); font-weight: 600; }

  /* Tables */
  table { width: 100%; border-collapse: collapse; font-size: 13px; }
  th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid var(--border); vertical-align: middle; }
  th { color: var(--muted); font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; }
  tbody tr:hover { background: var(--surface-2); }
  td.num, th.num { text-align: right; font-variant-numeric: tabular-nums; }
  .table-scroll { overflow-x: auto; }
  code { background: var(--surface-2); border: 1px solid var(--border); padding: 1.5px 6px; border-radius: 5px; font-size: 12px; font-family: ui-monospace, "SF Mono", Menlo, monospace; }

  /* Badges — status palette, always with a text label (never colour alone) */
  .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 20px; font-size: 11.5px; font-weight: 650; white-space: nowrap; }
  .badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; opacity: 0.9; }
  .badge.VALID, .badge.active, .badge.success, .badge.refunded { color: var(--good-ink); background: color-mix(in srgb, var(--good) 14%, transparent); }
  .badge.INITIATED, .badge.processing { color: #7a5200; background: color-mix(in srgb, var(--warning) 22%, transparent); }
  .badge.FAILED, .badge.VALIDATION_FAILED, .badge.INIT_FAILED, .badge.failed { color: var(--critical); background: color-mix(in srgb, var(--critical) 13%, transparent); }
  .badge.CANCELLED, .badge.inactive { color: var(--ink-2); background: var(--surface-2); border: 1px solid var(--border); }
  @media (prefers-color-scheme: dark) { :root:where(:not([data-theme="light"])) .badge.INITIATED { color: var(--warning); } }
  :root[data-theme="dark"] .badge.INITIATED { color: var(--warning); }

  /* Forms & buttons */
  input, select, textarea {
    font: inherit; padding: 8px 11px; border: 1px solid var(--border); border-radius: 8px;
    background: var(--surface); color: var(--ink); outline: none;
  }
  input:focus, select:focus, textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 18%, transparent); }
  label { font-size: 12.5px; color: var(--ink-2); font-weight: 600; }
  button, .btn {
    font: inherit; font-weight: 600; font-size: 13px; background: var(--accent); color: #fff;
    border: none; border-radius: 8px; padding: 8px 14px; cursor: pointer;
  }
  button:hover, .btn:hover { background: var(--accent-ink); text-decoration: none; }
  button.ghost { background: var(--surface); color: var(--ink-2); border: 1px solid var(--border); }
  button.ghost:hover { background: var(--surface-2); color: var(--ink); }
  button.small { padding: 5px 10px; font-size: 12px; }
  form.inline { display: inline; }
  .muted { color: var(--muted); font-size: 12.5px; }

  .flash { padding: 11px 15px; border-radius: 9px; margin-bottom: 18px; font-size: 13px; font-weight: 500; border: 1px solid transparent; }
  .flash.error { background: color-mix(in srgb, var(--critical) 10%, transparent); color: var(--critical); border-color: color-mix(in srgb, var(--critical) 25%, transparent); }
  .flash.ok { background: color-mix(in srgb, var(--good) 10%, transparent); color: var(--good-ink); border-color: color-mix(in srgb, var(--good) 25%, transparent); }

  /* Bar chart (magnitude over time) — single hue, no legend */
  .barchart { display: flex; align-items: flex-end; gap: 5px; height: 150px; padding-top: 8px; }
  .barchart .col { flex: 1; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; height: 100%; gap: 6px; }
  .barchart .bar { width: 100%; max-width: 30px; background: var(--series-1); border-radius: 4px 4px 2px 2px; min-height: 2px; transition: opacity .12s; }
  .barchart .col:hover .bar { opacity: 0.78; }
  .barchart .xlab { font-size: 10px; color: var(--muted); font-variant-numeric: tabular-nums; white-space: nowrap; }
  .chart-baseline { height: 1px; background: var(--baseline); margin-top: -1px; }

  /* Horizontal breakdown bars */
  .hbars { display: flex; flex-direction: column; gap: 12px; }
  .hbar-row { display: grid; grid-template-columns: 130px 1fr 64px; align-items: center; gap: 12px; }
  .hbar-row .name { font-size: 12.5px; color: var(--ink-2); display: flex; align-items: center; gap: 7px; }
  .hbar-track { background: var(--surface-2); border: 1px solid var(--border); border-radius: 6px; height: 20px; overflow: hidden; }
  .hbar-fill { height: 100%; border-radius: 5px; min-width: 3px; }
  .hbar-row .val { text-align: right; font-size: 12.5px; font-weight: 650; font-variant-numeric: tabular-nums; }
  .swatch { width: 9px; height: 9px; border-radius: 2px; display: inline-block; flex: none; }

  .empty { text-align: center; color: var(--muted); padding: 28px 10px; font-size: 13px; }
</style>
</head>
<body>
<?php if (!empty($_SESSION['panel_admin_id'])): ?>
<header class="topbar">
  <div class="brand"><span class="dot"></span> BosheBoshe Pay <small>· aggregator</small></div>
  <nav class="mainnav">
    <a href="dashboard.php" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">Overview</a>
    <a href="transactions.php" class="<?= ($active ?? '') === 'transactions' ? 'active' : '' ?>">Transactions</a>
    <a href="refunds.php" class="<?= ($active ?? '') === 'refunds' ? 'active' : '' ?>">Refunds</a>
    <a href="partners.php" class="<?= ($active ?? '') === 'partners' ? 'active' : '' ?>">Partners</a>
    <a href="logout.php" class="logout">Logout</a>
  </nav>
</header>
<?php endif; ?>
<div class="wrap">
