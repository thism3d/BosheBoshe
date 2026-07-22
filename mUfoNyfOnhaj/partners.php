<?php
require_once __DIR__ . '/inc/auth_guard.php';
require_once __DIR__ . '/inc/csrf.php';
require_once __DIR__ . '/inc/flash.php';
require_once __DIR__ . '/../api/lib/db.php';

$conn = api_db_connect();
$partners = $conn->query('SELECT p.*,
    (SELECT COUNT(*) FROM api_transactions t WHERE t.partner_id = p.id) AS txn_count,
    (SELECT COALESCE(SUM(commission_amount),0) FROM api_transactions t WHERE t.partner_id = p.id AND t.status = "VALID") AS earned
    FROM api_partners p ORDER BY p.id DESC');

$pageTitle = 'Partners';
$active = 'partners';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="page-head">
  <h1>Partners</h1>
  <p>Websites authorized to take payments through the aggregator. Each gets its own API key, secret, and commission rate.</p>
</div>

<div class="card">
  <?php flash_render(); ?>
  <h3>Add a partner</h3>
  <form method="post" action="actions/partner_create.php" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
    <?= csrf_field() ?>
    <div><label>Partner / site name</label><br><input type="text" name="partner_name" placeholder="Acme Store" required style="width:200px;"></div>
    <div><label>Website domain</label><br><input type="text" name="website_domain" placeholder="acme.com" style="width:180px;"></div>
    <div><label>Contact email</label><br><input type="email" name="contact_email" placeholder="dev@acme.com" style="width:200px;"></div>
    <div><label>Commission %</label><br><input type="number" step="0.01" name="commission_percent" value="1.00" style="width:90px;"></div>
    <button type="submit">Create partner</button>
  </form>
</div>

<?php while ($p = $partners->fetch_assoc()): ?>
<div class="card">
  <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap;">
    <div>
      <h3 style="margin-bottom:4px;"><?= htmlspecialchars($p['partner_name']) ?>
        <span class="badge <?= $p['status'] === 'active' ? 'active' : 'inactive' ?>" style="margin-left:6px;"><?= htmlspecialchars($p['status']) ?></span>
      </h3>
      <div class="muted">
        <?= htmlspecialchars($p['website_domain'] ?? '—') ?: '—' ?> ·
        <?= htmlspecialchars($p['contact_email'] ?? '—') ?: '—' ?> ·
        <?= number_format((float)$p['commission_percent'], 2) ?>% commission
      </div>
    </div>
    <div style="text-align:right;">
      <div class="muted"><?= (int)$p['txn_count'] ?> transactions</div>
      <div style="font-weight:700; font-size:16px;"><?= number_format((float)$p['earned'], 2) ?> <span class="muted" style="font-size:12px;">BDT earned</span></div>
    </div>
  </div>

  <div class="table-scroll" style="margin-top:14px;">
    <table>
      <tbody>
        <tr><th style="width:120px;">API key</th><td><code><?= htmlspecialchars($p['api_key']) ?></code></td></tr>
        <tr><th>API secret</th><td><code><?= htmlspecialchars($p['api_secret']) ?></code> <span class="muted">(refunds only — keep server-side)</span></td></tr>
      </tbody>
    </table>
  </div>

  <div style="display:flex; gap:10px; margin-top:14px; align-items:center; flex-wrap:wrap;">
    <form class="inline" method="post" action="actions/partner_toggle.php">
      <?= csrf_field() ?>
      <input type="hidden" name="partner_id" value="<?= (int)$p['id'] ?>">
      <input type="hidden" name="new_status" value="<?= $p['status'] === 'active' ? 'inactive' : 'active' ?>">
      <button class="ghost small" type="submit"><?= $p['status'] === 'active' ? 'Deactivate' : 'Activate' ?></button>
    </form>
    <details>
      <summary style="cursor:pointer; color:var(--accent); font-size:13px;">Show integration snippet</summary>
      <pre style="background:var(--surface-2); border:1px solid var(--border); border-radius:9px; padding:14px; overflow-x:auto; font-size:12px; margin-top:10px; line-height:1.55;"><code>&lt;form action="https://bosheboshe.com/payment_proceed" method="post"&gt;
  &lt;input type="hidden" name="api_key" value="<?= htmlspecialchars($p['api_key']) ?>"&gt;
  &lt;input type="hidden" name="amount" value="1250.00"&gt;
  &lt;input type="hidden" name="currency" value="BDT"&gt;  &lt;!-- BDT,USD,EUR,GBP,AUD,CAD,SGD,INR,MYR --&gt;
  &lt;input type="hidden" name="order_ref" value="YOUR-ORDER-1"&gt;
  &lt;input type="hidden" name="cus_name" value="Jane Doe"&gt;
  &lt;input type="hidden" name="cus_email" value="jane@acme.com"&gt;
  &lt;input type="hidden" name="cus_phone" value="01712345678"&gt;
  &lt;input type="hidden" name="cus_add1" value="123 Road"&gt;
  &lt;input type="hidden" name="cus_city" value="Dhaka"&gt;
  &lt;input type="hidden" name="success_url" value="https://acme.com/paid"&gt;
  &lt;input type="hidden" name="fail_url" value="https://acme.com/failed"&gt;
  &lt;input type="hidden" name="cancel_url" value="https://acme.com/cancelled"&gt;
  &lt;button type="submit"&gt;Pay&lt;/button&gt;
&lt;/form&gt;</code></pre>
    </details>
  </div>
</div>
<?php endwhile; ?>

<?php $conn->close(); require __DIR__ . '/inc/layout_bottom.php'; ?>
