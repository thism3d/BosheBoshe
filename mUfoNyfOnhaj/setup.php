<?php
/**
 * First-run only: creates the single admin account. Works only while
 * panel_admins is empty, so the real password never has to be written
 * into a file or SQL dump — you type it once, here, and it's stored as a
 * bcrypt hash immediately.
 */

require_once __DIR__ . '/../api/lib/db.php';
require_once __DIR__ . '/inc/csrf.php';

$conn = api_db_connect();
$existing = $conn->query('SELECT COUNT(*) AS c FROM panel_admins')->fetch_assoc();

$error = '';
$done = false;

if ($existing['c'] > 0) {
    // Already set up — do nothing sensitive, just point to login.
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $error = 'Session expired, please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['confirm'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Enter a valid email address.';
        } elseif (strlen($password) < 10) {
            $error = 'Password must be at least 10 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO panel_admins (email, password_hash) VALUES (?, ?)');
            $stmt->bind_param('ss', $email, $hash);
            if ($stmt->execute()) {
                $done = true;
            } else {
                $error = 'Could not create the admin account.';
            }
            $stmt->close();
        }
    }
}

$pageTitle = 'First-time Setup';
$active = '';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="card" style="max-width:420px;margin:60px auto;">
  <h2>Payment Dashboard Setup</h2>

  <?php if ($existing['c'] > 0): ?>
    <p class="flash ok">Setup already complete.</p>
    <p><a href="index.php" class="btn" style="display:inline-block;padding:8px 14px;border-radius:6px;">Go to login</a></p>
  <?php elseif ($done): ?>
    <p class="flash ok">Admin account created.</p>
    <p><a href="index.php" class="btn" style="display:inline-block;padding:8px 14px;border-radius:6px;">Go to login</a></p>
  <?php else: ?>
    <?php if ($error): ?><p class="flash error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <p><label>Email<br><input type="email" name="email" required style="width:100%;"></label></p>
      <p><label>Password (min 10 chars)<br><input type="password" name="password" required minlength="10" style="width:100%;"></label></p>
      <p><label>Confirm Password<br><input type="password" name="confirm" required minlength="10" style="width:100%;"></label></p>
      <button type="submit">Create Admin Account</button>
    </form>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/inc/layout_bottom.php'; ?>
