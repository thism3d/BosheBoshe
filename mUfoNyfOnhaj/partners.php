<?php
require_once __DIR__ . '/inc/auth_guard.php';
require_once __DIR__ . '/inc/csrf.php';
require_once __DIR__ . '/inc/flash.php';
require_once __DIR__ . '/../api/lib/db.php';

$conn = api_db_connect();
$partners = $conn->query('SELECT * FROM api_partners ORDER BY id DESC');

$pageTitle = 'Partners';
$active = 'partners';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="card">
  <?php flash_render(); ?>
  <h3>New Partner</h3>
  <form method="post" action="actions/partner_create.php">
    <?= csrf_field() ?>
    <p><input type="text" name="partner_name" placeholder="Partner / website name" required style="width:220px;"></p>
    <p><input type="text" name="website_domain" placeholder="Website domain (e.g. example.com)" style="width:220px;">
       <input type="email" name="contact_email" placeholder="Contact email" style="width:220px;"></p>
    <p><label>Commission %<br><input type="number" step="0.01" name="commission_percent" value="1.00" style="width:100px;"></label></p>
    <button type="submit">Create Partner</button>
  </form>
</div>

<div class="card">
  <h3>All Partners</h3>
  <table>
    <tr><th>Name</th><th>api_key</th><th>api_secret</th><th>Domain</th><th>Commission</th><th>Status</th><th>Actions</th></tr>
    <?php while ($p = $partners->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($p['partner_name']) ?><br><span class="muted"><?= htmlspecialchars($p['contact_email'] ?? '') ?></span></td>
      <td><code><?= htmlspecialchars($p['api_key']) ?></code></td>
      <td><code><?= htmlspecialchars($p['api_secret']) ?></code></td>
      <td><?= htmlspecialchars($p['website_domain'] ?? '') ?></td>
      <td><?= number_format((float)$p['commission_percent'], 2) ?>%</td>
      <td><span class="badge <?= $p['status'] === 'active' ? 'VALID' : 'CANCELLED' ?>"><?= htmlspecialchars($p['status']) ?></span></td>
      <td>
        <form class="inline" method="post" action="actions/partner_toggle.php">
          <?= csrf_field() ?>
          <input type="hidden" name="partner_id" value="<?= (int)$p['id'] ?>">
          <input type="hidden" name="new_status" value="<?= $p['status'] === 'active' ? 'inactive' : 'active' ?>">
          <button type="submit"><?= $p['status'] === 'active' ? 'Deactivate' : 'Activate' ?></button>
        </form>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>

<?php $conn->close(); require __DIR__ . '/inc/layout_bottom.php'; ?>
