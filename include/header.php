<?php
// Stronger session cookie settings and inactivity timeout
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
$cookieParams = [
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'] ?? '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
];
session_set_cookie_params($cookieParams);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Inactivity timeout (seconds)
$timeout_seconds = 1800; // 30 minutes
if (!empty($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_seconds) {
    // destroy session on timeout
    $_SESSION = [];
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
    session_destroy();
    header('Location: loginform.php?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();

$title = $title ?? "Home Page";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg bg-secondary mb-5 custom-padding">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="algomau.php">Algoma University</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-info" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-info" href="labsolution.php">Lab Solutions</a>
                    </li>
                </ul>

                <?php if (!empty($_SESSION['user_id'])): ?>
                    <span class="navbar-text text-white me-2">Hello, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                    <a href="welcome.php" class="btn btn-outline-light btn-sm me-2">My Account</a>
                    <a href="logout.php" class="btn btn-info btn-sm">Logout</a>
                <?php else: ?>
                    <a href="signupform.php" class="btn btn-info btn-sm me-2">Sign Up</a>
                    <a href="loginform.php" class="btn btn-info btn-sm me-2">Login</a>
                <?php endif; ?>

            </div>
        </div>
    </nav>