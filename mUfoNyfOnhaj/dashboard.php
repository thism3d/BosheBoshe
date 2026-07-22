<?php
require_once __DIR__ . '/inc/auth_guard.php';
require_once __DIR__ . '/../api/lib/db.php';

$conn = api_db_connect();

$partnerCount = $conn->query('SELECT COUNT(*) c FROM api_partners')->fetch_assoc()['c'];
$activePartnerCount = $conn->query('SELECT COUNT(*) c FROM api_partners WHERE status = "active"')->fetch_assoc()['c'];
$txnCount = $conn->query('SELECT COUNT(*) c FROM api_transactions')->fetch_assoc()['c'];
$volume = $conn->query('SELECT COALESCE(SUM(amount),0) v FROM api_transactions WHERE status = "VALID"')->fetch_assoc()['v'];
$commission = $conn->query('SELECT COALESCE(SUM(commission_amount),0) v FROM api_transactions WHERE status = "VALID"')->fetch_assoc()['v'];

$recent = $conn->query('SELECT t.*, p.partner_name FROM api_transactions t
    JOIN api_partners p ON p.id = t.partner_id
    ORDER BY t.id DESC LIMIT 10');

$pageTitle = 'Overview';
$active = 'dashboard';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="stat-grid">
  <div class="stat"><div class="num"><?= (int)$activePartnerCount ?> / <?= (int)$partnerCount ?></div><div class="label">Active / Total Partners</div></div>
  <div class="stat"><div class="num"><?= (int)$txnCount ?></div><div class="label">Total Transactions</div></div>
  <div class="stat"><div class="num"><?= number_format((float)$volume, 2) ?></div><div class="label">Successful Volume (BDT)</div></div>
  <div class="stat"><div class="num"><?= number_format((float)$commission, 2) ?></div><div class="label">Commission Earned (BDT)</div></div>
</div>

<div class="card">
  <h3>Recent Transactions</h3>
  <table>
    <tr><th>Tran ID</th><th>Partner</th><th>Amount</th><th>Status</th><th>Created</th></tr>
    <?php while ($row = $recent->fetch_assoc()): ?>
    <tr>
      <td><code><?= htmlspecialchars($row['tran_id']) ?></code></td>
      <td><?= htmlspecialchars($row['partner_name']) ?></td>
      <td><?= htmlspecialchars($row['currency']) ?> <?= number_format((float)$row['amount'], 2) ?></td>
      <td><span class="badge <?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
      <td class="muted"><?= htmlspecialchars($row['created_at']) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <p class="muted" style="margin-top:10px;"><a href="transactions.php">View all transactions &rarr;</a></p>
</div>

<?php $conn->close(); require __DIR__ . '/inc/layout_bottom.php'; ?>
