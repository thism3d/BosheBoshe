<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_render(): void
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        echo '<p class="flash ' . htmlspecialchars($f['type']) . '">' . htmlspecialchars($f['message']) . '</p>';
        unset($_SESSION['flash']);
    }
}
