<?php
require_once __DIR__ . '/inc/auth_guard.php';
require_once __DIR__ . '/inc/csrf.php';
require_once __DIR__ . '/inc/flash.php';
require_once __DIR__ . '/../api/lib/db.php';
require_once __DIR__ . '/../api/config.php';
require_once __DIR__ . '/../api/lib/functions.php';
require_once __DIR__ . '/../api/providers/factory.php';

$conn = api_db_connect();

// Admin-triggered live refund status re-check.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check() && isset($_POST['refund_id'])) {
    $rid = (int) $_POST['refund_id'];
    $stmt = $conn->prepare('SELECT * FROM api_refunds WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $rid);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($r && !empty($r['refund_ref_id']) && ($provider = payment_provider($r['provider']))) {
        $q = $provider->queryRefund($r['refund_ref_id']);
        $newStatus = $q['status'] ?: $r['status'];
        $raw = json_encode($q['raw']);
        $u = $conn->prepare('UPDATE api_refunds SET status = ?, raw_response = ? WHERE id = ?');
        $u->bind_param('ssi', $newStatus, $raw, $rid);
        $u->execute();
        $u->close();
        flash_set('ok', "Refund {$r['refund_trans_id']} — gateway status: $newStatus");
    } else {
        flash_set('error', 'Refund has no gateway reference to query.');
    }
    header('Location: refunds.php');
    exit;
}

$rows = $conn->query('SELECT r.*, t.tran_id, t.currency, p.partner_name
    FROM api_refunds r
    JOIN api_transactions t ON t.id = r.transaction_id
    JOIN api_partners p ON p.id = t.partner_id
    ORDER BY r.id DESC LIMIT 200');

$totalRefunded = (float) $conn->query('SELECT COALESCE(SUM(refund_amount),0) v FROM api_refunds')->fetch_assoc()['v'];
$refundCount = (int) $conn->query('SELECT COUNT(*) c FROM api_refunds')->fetch_assoc()['c'];

$pageTitle = 'Refunds';
$active = 'refunds';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="page-head">
  <h1>Refunds</h1>
  <p>Refunds initiated from the dashboard or by partners through the API.</p>
</div>

<div class="stat-grid">
  <div class="stat"><div class="label">Refunds logged</div><div class="num"><?= number_format($refundCount) ?></div></div>
  <div class="stat"><div class="label">Total refunded</div><div class="num"><?= number_format($totalRefunded, 0) ?> <small>BDT</small></div></div>
</div>

<div class="card">
  <?php flash_render(); ?>
  <div class="table-scroll">
  <table>
    <thead><tr>
      <th>Refund ID</th><th>Transaction</th><th>Partner</th><th>Provider</th>
      <th class="num">Amount</th><th>Remarks</th><th>Status</th><th>Created</th><th></th>
    </tr></thead>
    <tbody>
    <?php $any = false; while ($row = $rows->fetch_assoc()): $any = true; ?>
    <tr>
      <td><code><?= htmlspecialchars($row['refund_trans_id']) ?></code>
        <?php if (!empty($row['refund_ref_id'])): ?><br><span class="muted">ref: <?= htmlspecialchars($row['refund_ref_id']) ?></span><?php endif; ?></td>
      <td><code><?= htmlspecialchars($row['tran_id']) ?></code></td>
      <td><?= htmlspecialchars($row['partner_name']) ?></td>
      <td><?= htmlspecialchars($row['provider']) ?></td>
      <td class="num"><?= number_format((float)$row['refund_amount'], 2) ?> <span class="muted"><?= htmlspecialchars($row['currency']) ?></span></td>
      <td><?= htmlspecialchars($row['refund_remarks'] ?? '') ?></td>
      <td><span class="badge <?= htmlspecialchars(strtolower($row['status'])) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
      <td class="muted"><?= htmlspecialchars($row['created_at']) ?></td>
      <td>
        <?php if (!empty($row['refund_ref_id'])): ?>
        <form class="inline" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="refund_id" value="<?= (int)$row['id'] ?>">
          <button class="ghost small" type="submit" title="Re-check refund status at the gateway">Query</button>
        </form>
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
    <?php if (!$any): ?><tr><td colspan="9"><div class="empty">No refunds yet. Initiate one from the Transactions page.</div></td></tr><?php endif; ?>
    </tbody>
  </table>
  </div>
</div>

<?php $conn->close(); require __DIR__ . '/inc/layout_bottom.php'; ?>
