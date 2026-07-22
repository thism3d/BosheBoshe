<?php
require_once __DIR__ . '/../api/lib/db.php';
require_once __DIR__ . '/inc/csrf.php';

if (!empty($_SESSION['panel_admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $error = 'Session expired, please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        $conn = api_db_connect();
        $stmt = $conn->prepare('SELECT id, password_hash FROM panel_admins WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['panel_admin_id'] = $admin['id'];
            $_SESSION['panel_admin_email'] = $email;
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Invalid email or password.';
    }
}

$pageTitle = 'Login';
$active = '';
require __DIR__ . '/inc/layout_top.php';
?>

<div class="card" style="max-width:380px;margin:60px auto;">
  <h2>Payment Dashboard</h2>
  <?php if ($error): ?><p class="flash error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <p><label>Email<br><input type="email" name="email" required style="width:100%;" autofocus></label></p>
    <p><label>Password<br><input type="password" name="password" required style="width:100%;"></label></p>
    <button type="submit">Log in</button>
  </form>
  <p class="muted" style="margin-top:16px;">First time here? <a href="setup.php">Run setup</a>.</p>
</div>

<?php require __DIR__ . '/inc/layout_bottom.php'; ?>
