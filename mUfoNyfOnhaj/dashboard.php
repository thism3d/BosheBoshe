<?php
require_once __DIR__ . '/inc/auth_guard.php';
require_once __DIR__ . '/../api/lib/db.php';
require_once __DIR__ . '/inc/charts.php';

$conn = api_db_connect();

$partnerCount = (int) $conn->query('SELECT COUNT(*) c FROM api_partners')->fetch_assoc()['c'];
$activePartnerCount = (int) $conn->query('SELECT COUNT(*) c FROM api_partners WHERE status = "active"')->fetch_assoc()['c'];
$txnCount = (int) $conn->query('SELECT COUNT(*) c FROM api_transactions')->fetch_assoc()['c'];
$validCount = (int) $conn->query('SELECT COUNT(*) c FROM api_transactions WHERE status = "VALID"')->fetch_assoc()['c'];
// Successful volume in BDT terms: prefer the gateway-settled BDT amount when
// the charge was in a foreign currency, else the (BDT) amount as charged.
$volume = (float) $conn->query('SELECT COALESCE(SUM(COALESCE(base_amount_bdt, amount)),0) v FROM api_transactions WHERE status = "VALID"')->fetch_assoc()['v'];
$commission = (float) $conn->query('SELECT COALESCE(SUM(commission_amount),0) v FROM api_transactions WHERE status = "VALID"')->fetch_assoc()['v'];
$refundCount = (int) $conn->query('SELECT COUNT(*) c FROM api_refunds')->fetch_assoc()['c'];
$successRate = $txnCount > 0 ? round($validCount / $txnCount * 100) : 0;

// 14-day transaction volume (count per day)
$daily = [];
for ($i = 13; $i >= 0; $i--) {
    $daily[date('Y-m-d', strtotime("-$i day"))] = 0;
}
$res = $conn->query('SELECT DATE(created_at) d, COUNT(*) c FROM api_transactions WHERE created_at >= (CURDATE() - INTERVAL 13 DAY) GROUP BY DATE(created_at)');
while ($row = $res->fetch_assoc()) {
    if (isset($daily[$row['d']])) $daily[$row['d']] = (int) $row['c'];
}
$barPoints = [];
foreach ($daily as $day => $count) {
    $barPoints[] = ['label' => date('j/n', strtotime($day)), 'value' => $count, 'title' => date('M j', strtotime($day)) . ': ' . $count . ' txn'];
}

// Status breakdown
$statusRows = [];
$res = $conn->query('SELECT status, COUNT(*) c FROM api_transactions GROUP BY status ORDER BY c DESC');
while ($row = $res->fetch_assoc()) {
    $statusRows[] = ['name' => $row['status'], 'value' => (int) $row['c'], 'display' => (string) (int) $row['c'], 'color' => status_color($row['status'])];
}

// Volume by currency (successful), shown in original currency
$currencyRows = [];
$res = $conn->query('SELECT currency, COUNT(*) c, COALESCE(SUM(amount),0) v FROM api_transactions WHERE status = "VALID" GROUP BY currency ORDER BY v DESC');
while ($row = $res->fetch_assoc()) {
    $currencyRows[] = ['name' => $row['currency'] . ' (' . (int) $row['c'] . ')', 'value' => (float) $row['v'], 'display' => number_format((float) $row['v'], 0), 'color' => 'var(--series-1)'];
}

$recent = $conn->query('SELECT t.*, p.partner_name FROM api_transactions t
    JOIN api_partners p ON p.id = t.partner_id
    ORDER BY t.id DESC LIMIT 8');

$pageTitle = 'Overview';
$active = 'dashboard';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="page-head">
  <h1>Overview</h1>
  <p>Payment aggregator activity across all partner sites.</p>
</div>

<div class="stat-grid">
  <div class="stat">
    <div class="label">Partners</div>
    <div class="num"><?= $activePartnerCount ?> <small>/ <?= $partnerCount ?></small></div>
    <div class="foot">active of total</div>
  </div>
  <div class="stat">
    <div class="label">Transactions</div>
    <div class="num"><?= number_format($txnCount) ?></div>
    <div class="foot"><span class="up"><?= $successRate ?>%</span> success rate</div>
  </div>
  <div class="stat">
    <div class="label">Successful Volume</div>
    <div class="num"><?= number_format($volume, 0) ?> <small>BDT</small></div>
    <div class="foot"><?= number_format($validCount) ?> paid transactions</div>
  </div>
  <div class="stat">
    <div class="label">Commission Earned</div>
    <div class="num"><?= number_format($commission, 0) ?> <small>BDT</small></div>
    <div class="foot"><?= $refundCount ?> refund<?= $refundCount === 1 ? '' : 's' ?> logged</div>
  </div>
</div>

<div class="card">
  <h3>Transactions <span class="sub">· last 14 days</span></h3>
  <?php chart_bars($barPoints); ?>
</div>

<div class="grid cols-2">
  <div class="card">
    <h3>Status breakdown <span class="sub">· all time</span></h3>
    <?php chart_hbars($statusRows); ?>
  </div>
  <div class="card">
    <h3>Successful volume by currency</h3>
    <?php chart_hbars($currencyRows); ?>
  </div>
</div>

<div class="card">
  <h3>Recent activity</h3>
  <div class="table-scroll">
  <table>
    <thead><tr><th>Transaction</th><th>Partner</th><th>Provider</th><th class="num">Amount</th><th>Status</th><th>Created</th></tr></thead>
    <tbody>
    <?php while ($row = $recent->fetch_assoc()): ?>
    <tr>
      <td><code><?= htmlspecialchars($row['tran_id']) ?></code></td>
      <td><?= htmlspecialchars($row['partner_name']) ?></td>
      <td><?= htmlspecialchars($row['provider']) ?></td>
      <td class="num"><?= number_format((float)$row['amount'], 2) ?> <span class="muted"><?= htmlspecialchars($row['currency']) ?></span></td>
      <td><span class="badge <?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
      <td class="muted"><?= htmlspecialchars($row['created_at']) ?></td>
    </tr>
    <?php endwhile; ?>
    <?php if ($txnCount === 0): ?>
      <tr><td colspan="6"><div class="empty">No transactions yet. They'll appear here once a partner sends their first payment.</div></td></tr>
    <?php endif; ?>
    </tbody>
  </table>
  </div>
  <p class="muted" style="margin:12px 0 0;"><a href="transactions.php">View all transactions &rarr;</a></p>
</div>

<?php $conn->close(); require __DIR__ . '/inc/layout_bottom.php'; ?>
