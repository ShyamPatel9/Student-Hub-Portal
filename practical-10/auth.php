<?php
// Include this file at the top of every protected PHP page.
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_start();
}

const SESSION_TIMEOUT = 900; // 15 minutes of inactivity

if (!isset($_SESSION['user_id'], $_SESSION['role'], $_SESSION['last_activity'])) {
    header('Location: login.php');
    exit;
}

if (time() - (int) $_SESSION['last_activity'] >= SESSION_TIMEOUT) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    header('Location: login.php?message=timeout');
    exit;
}

$_SESSION['last_activity'] = time();

function requireRole(string $role): void {
    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        exit('Access denied: you do not have permission to view this page.');
    }
}
