<?php
// Destroy session and redirect to home
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Unset all session variables
$_SESSION = [];

// Delete session cookie
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session
session_destroy();

header('Location: index.php');
exit;
