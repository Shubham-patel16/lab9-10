<?php
require_once 'DB/conn.php';

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signupform.php');
    exit;
}

// Required fields
$required = ['firstname', 'lastname', 'username', 'email', 'password', 'confirmpassword', 'address', 'city', 'province', 'postal'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        echo 'missing field: ' . htmlspecialchars($field);
        exit;
    }
}

// Trim raw inputs
$firstname_raw = trim($_POST['firstname']);
$lastname_raw = trim($_POST['lastname']);
$username_raw = trim($_POST['username']);
$email_raw = trim($_POST['email']);
$password_raw = $_POST['password'];
$confirmpassword_raw = $_POST['confirmpassword'];
$address_raw = trim($_POST['address']);
$city_raw = trim($_POST['city']);
$province_raw = trim($_POST['province']);
$postal_raw = trim($_POST['postal']);

// Remove special characters
// Email: allow letters, numbers and @ . _ -
$email = preg_replace('/[^A-Za-z0-9@._\-]/', '', $email_raw);
// Preserve password special characters: only trim surrounding whitespace
$password = isset($password_raw) ? trim($password_raw) : '';
$confirmpassword = isset($confirmpassword_raw) ? trim($confirmpassword_raw) : '';
// Names and city: allow letters, numbers and spaces
$firstname = preg_replace('/[^A-Za-z0-9 ]/', '', $firstname_raw);
$lastname = preg_replace('/[^A-Za-z0-9 ]/', '', $lastname_raw);
$username = preg_replace('/[^A-Za-z0-9]/', '', $username_raw);
$address = preg_replace('/[^A-Za-z0-9 \.,#\-]/', '', $address_raw);
$city = preg_replace('/[^A-Za-z0-9 ]/', '', $city_raw);
$province = preg_replace('/[^A-Za-z]/', '', $province_raw);
$postal = preg_replace('/[^A-Za-z0-9 ]/', '', $postal_raw);

// Basic validation
if ($password !== $confirmpassword) {
    echo 'Passwords do not match';
    exit;
}

// Hash password
$options = ['cost' => 12];
$hashedPassword = password_hash($password, PASSWORD_DEFAULT, $options);
if ($hashedPassword === false) {
    echo 'Failed to hash password';
    exit;
}

// Check for existing email
$checkSql = "SELECT id FROM users WHERE email = ? LIMIT 1";
if ($stmt = mysqli_prepare($conn, $checkSql)) {
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo 'Email already registered';
        mysqli_stmt_close($stmt);
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    echo 'database error';
    exit;
}

// Check for existing username
$checkUserSql = "SELECT id FROM users WHERE username = ? LIMIT 1";
if ($stmt = mysqli_prepare($conn, $checkUserSql)) {
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo 'Username already taken';
        mysqli_stmt_close($stmt);
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    echo 'database error';
    exit;
}

// Insert user
// Note: DB column is named `postalcode` (see phpMyAdmin). Use that name so INSERT succeeds.
$insertSql = "INSERT INTO users (firstname, lastname, username, email, password, address, city, province, postalcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
if ($stmt = mysqli_prepare($conn, $insertSql)) {
    mysqli_stmt_bind_param($stmt, 'sssssssss', $firstname, $lastname, $username, $email, $hashedPassword, $address, $city, $province, $postal);
    try {
        mysqli_stmt_execute($stmt);
        // Redirect to welcome page on success
        header('Location: welcome.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        // Duplicate entry (unique constraint)
        if ($e->getCode() === 1062) {
            // Determine which key caused the duplicate from message
            $msg = $e->getMessage();
            if (stripos($msg, 'username') !== false) {
                echo 'Username already taken';
            } elseif (stripos($msg, 'email') !== false) {
                echo 'Email already registered';
            } else {
                echo 'Duplicate entry';
            }
        } else {
            echo 'Insert failed: ' . htmlspecialchars($e->getMessage());
        }
    }
    mysqli_stmt_close($stmt);
} else {
    echo 'database error';
}
