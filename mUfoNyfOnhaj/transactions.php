<?php
require_once __DIR__ . '/inc/auth_guard.php';
require_once __DIR__ . '/inc/csrf.php';
require_once __DIR__ . '/inc/flash.php';
require_once __DIR__ . '/../api/lib/db.php';

$conn = api_db_connect();

$partnerFilter = (int) ($_GET['partner_id'] ?? 0);
$statusFilter = trim($_GET['status'] ?? '');

$where = [];
$params = [];
$types = '';
if ($partnerFilter > 0) {
    $where[] = 't.partner_id = ?';
    $params[] = $partnerFilter;
    $types .= 'i';
}
if ($statusFilter !== '') {
    $where[] = 't.status = ?';
    $params[] = $statusFilter;
    $types .= 's';
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$sql = "SELECT t.*, p.partner_name FROM api_transactions t
        JOIN api_partners p ON p.id = t.partner_id
        $whereSql ORDER BY t.id DESC LIMIT 200";
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$rows = $stmt->get_result();

$partners = $conn->query('SELECT id, partner_name FROM api_partners ORDER BY partner_name');

$pageTitle = 'Transactions';
$active = 'transactions';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="card">
  <?php flash_render(); ?>
  <form method="get" style="margin-bottom:10px;">
    <select name="partner_id">
      <option value="">All partners</option>
      <?php $partners->data_seek(0); while ($p = $partners->fetch_assoc()): ?>
        <option value="<?= (int)$p['id'] ?>" <?= $partnerFilter === (int)$p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['partner_name']) ?></option>
      <?php endwhile; ?>
    </select>
    <select name="status">
      <option value="">All statuses</option>
      <?php foreach (['INITIATED','VALID','FAILED','VALIDATION_FAILED','CANCELLED','INIT_FAILED'] as $s): ?>
        <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
  </form>

  <table>
    <tr><th>Tran ID</th><th>Partner</th><th>Order Ref</th><th>Amount</th><th>Status</th><th>Commission</th><th>Created</th><th>Actions</th></tr>
    <?php while ($row = $rows->fetch_assoc()): ?>
    <tr>
      <td><code><?= htmlspecialchars($row['tran_id']) ?></code><br>
        <span class="muted"><?= htmlspecialchars($row['bank_tran_id'] ?? '') ?></span></td>
      <td><?= htmlspecialchars($row['partner_name']) ?></td>
      <td><?= htmlspecialchars($row['partner_order_ref'] ?? '') ?></td>
      <td><?= htmlspecialchars($row['currency']) ?> <?= number_format((float)$row['amount'], 2) ?></td>
      <td><span class="badge <?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
      <td><?= $row['commission_amount'] !== null ? number_format((float)$row['commission_amount'], 2) : '-' ?></td>
      <td class="muted"><?= htmlspecialchars($row['created_at']) ?></td>
      <td>
        <form class="inline" method="post" action="actions/query.php">
          <?= csrf_field() ?>
          <input type="hidden" name="transaction_id" value="<?= (int)$row['id'] ?>">
          <button type="submit" title="Re-check live status at SSLCommerz">Query</button>
        </form>
        <?php if ($row['status'] === 'VALID' && !empty($row['bank_tran_id'])): ?>
          <details style="display:inline-block;">
            <summary style="cursor:pointer;display:inline;color:var(--accent);">Refund</summary>
            <form method="post" action="actions/refund.php" style="margin-top:6px;">
              <?= csrf_field() ?>
              <input type="hidden" name="transaction_id" value="<?= (int)$row['id'] ?>">
              <input type="number" step="0.01" name="refund_amount" placeholder="Amount" max="<?= htmlspecialchars($row['amount']) ?>" required style="width:100px;">
              <input type="text" name="refund_remarks" placeholder="Remarks" required style="width:140px;">
              <button type="submit">Submit</button>
            </form>
          </details>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

<?php $conn->close(); require __DIR__ . '/inc/layout_bottom.php'; ?>
