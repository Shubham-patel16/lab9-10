<?php
require_once 'DB/conn.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: loginform.php');
    exit;
}

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_POST['email'], $_POST['password'])) {
    echo 'bad request';
    exit;
}

// Raw inputs
$email_raw = $_POST['email'];
$password_raw = $_POST['password'];

// Sanitize email but preserve password special characters
// For email allow letters, numbers, @, ., _ -
$email = preg_replace('/[^A-Za-z0-9@._\-]/', '', $email_raw);
// Preserve password characters; only trim surrounding whitespace
$password = isset($password_raw) ? trim($password_raw) : '';

// Retrieve user id, username and hashed password using prepared statement
$sql = "SELECT id, username, password FROM users WHERE email = ? LIMIT 1";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $db_id = 0;
    $db_username = '';
    $dbpassword = '';
    mysqli_stmt_bind_result($stmt, $db_id, $db_username, $dbpassword);
    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $dbpassword)) {
            // Successful login: initialize session and store user data
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            session_regenerate_id(true);
            $_SESSION['user_id'] = $db_id;
            $_SESSION['username'] = $db_username;
            // Redirect to membership area
            header('Location: welcome.php');
            exit;
        } else {
            echo "bad password";
        }
    } else {
        // no such user / empty result
        echo "bad password";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "database error";
}
