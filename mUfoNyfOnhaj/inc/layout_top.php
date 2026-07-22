<?php
/** @var string $pageTitle */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($pageTitle ?? 'Payment Dashboard') ?> | BosheBoshe</title>
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  :root { --accent:#1c80d9; --bg:#f4f6f8; --card:#ffffff; --border:#dfe3e6; --text:#222; --muted:#6b7280; }
  * { box-sizing: border-box; }
  body { margin:0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: var(--bg); color: var(--text); }
  a { color: var(--accent); text-decoration: none; }
  header.topbar { background:#132030; color:#fff; padding: 14px 24px; display:flex; align-items:center; justify-content:space-between; }
  header.topbar .brand { font-weight:700; font-size:18px; }
  header.topbar nav a { color:#cfd8e3; margin-left:18px; font-size:14px; }
  header.topbar nav a.active, header.topbar nav a:hover { color:#fff; }
  .wrap { max-width: 1100px; margin: 28px auto; padding: 0 20px; }
  .card { background: var(--card); border: 1px solid var(--border); border-radius: 8px; padding: 20px; margin-bottom: 20px; }
  .stat-grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap:16px; }
  .stat { background: var(--card); border:1px solid var(--border); border-radius:8px; padding:16px; }
  .stat .num { font-size: 24px; font-weight:700; }
  .stat .label { color: var(--muted); font-size: 13px; margin-top:4px; }
  table { width:100%; border-collapse: collapse; font-size: 14px; }
  th, td { text-align:left; padding: 8px 10px; border-bottom: 1px solid var(--border); vertical-align: top; }
  th { color: var(--muted); font-weight:600; font-size: 12px; text-transform: uppercase; }
  .badge { display:inline-block; padding: 2px 8px; border-radius: 20px; font-size: 12px; font-weight:600; }
  .badge.VALID { background:#dcfce7; color:#166534; }
  .badge.INITIATED { background:#fef9c3; color:#854d0e; }
  .badge.FAILED, .badge.VALIDATION_FAILED, .badge.INIT_FAILED { background:#fee2e2; color:#991b1b; }
  .badge.CANCELLED { background:#e5e7eb; color:#374151; }
  input, select, textarea, button { font: inherit; padding: 8px 10px; border:1px solid var(--border); border-radius:6px; }
  button, .btn { background: var(--accent); color:#fff; border:none; cursor:pointer; }
  button:hover, .btn:hover { background:#1567b3; }
  form.inline { display:inline; }
  .muted { color: var(--muted); font-size: 13px; }
  .flash { padding:10px 14px; border-radius:6px; margin-bottom:16px; font-size:14px; }
  .flash.error { background:#fee2e2; color:#991b1b; }
  .flash.ok { background:#dcfce7; color:#166534; }
  code { background:#f1f5f9; padding:2px 6px; border-radius:4px; font-size:12.5px; }
</style>
</head>
<body>
<?php if (!empty($_SESSION['panel_admin_id'])): ?>
<header class="topbar">
  <div class="brand">BosheBoshe Payment Dashboard</div>
  <nav>
    <a href="dashboard.php" class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>">Overview</a>
    <a href="transactions.php" class="<?= ($active ?? '') === 'transactions' ? 'active' : '' ?>">Transactions</a>
    <a href="partners.php" class="<?= ($active ?? '') === 'partners' ? 'active' : '' ?>">Partners</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<?php endif; ?>
<div class="wrap">
