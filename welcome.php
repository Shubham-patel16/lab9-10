<?php
// Protect this page: require login
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: loginform.php');
    exit;
}

$title = 'Welcome';
require_once 'include/header.php';
?>

<div class="container text-center">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></h1>
</div>

<?php
require_once 'include/footer.php';
?>