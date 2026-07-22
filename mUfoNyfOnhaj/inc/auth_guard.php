<?php
/**
 * Include at the top of every protected dashboard page.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['panel_admin_id'])) {
    header('Location: index.php');
    exit;
}
