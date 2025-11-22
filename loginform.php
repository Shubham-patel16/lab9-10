<?php
// If already logged in, send to welcome page
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!empty($_SESSION['user_id'])) {
    header('Location: welcome.php');
    exit;
}

$title = 'login Form';
require_once 'include/header.php';
?>
<div class="container d-flex justify-content-center align-items-start" style="min-height: 70vh; padding-top: 40px;">
    <form class="w-50" method="POST" action="login.php">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary px-4">Login</button>
    </form>
</div>

<?php
require_once 'include/footer.php';
?>