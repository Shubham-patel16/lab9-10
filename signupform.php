<?php
// If already logged in, redirect to welcome
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!empty($_SESSION['user_id'])) {
    header('Location: welcome.php');
    exit;
}

$title = 'Signup Form';
require_once 'include/header.php';
?>
<form class="row g-3 needs-validation w-75 mx-auto" action="signup.php" method="POST" novalidate>

    <div class="col-md-6">
        <label class="form-label">First Name</label>
        <input type="text" name="firstname" class="form-control" required>
        <div class="invalid-feedback">Please enter your first name.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Last Name</label>
        <input type="text" name="lastname" class="form-control" required>
        <div class="invalid-feedback">Please enter your last name.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">User Name</label>
        <input type="text" name="username" class="form-control" required>
        <div class="invalid-feedback">Please choose a username.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
        <div class="invalid-feedback">Please enter a valid email.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
        <div class="invalid-feedback">Please enter a password.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirmpassword" class="form-control" required>
        <div class="invalid-feedback">Passwords must match.</div>
    </div>

    <div class="col-12">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control" required>
        <div class="invalid-feedback">Please enter your address.</div>
    </div>

    <div class="col-md-4">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" required>
        <div class="invalid-feedback">Please enter a valid city.</div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Province</label>
        <select class="form-select" name="province" required>
            <option value="" selected disabled>Choose...</option>
            <option>ON</option>
            <option>QC</option>
            <option>BC</option>
            <option>AB</option>
            <option>MB</option>
            <option>NS</option>
        </select>
        <div class="invalid-feedback">Please select a province.</div>
    </div>

    <div class="col-md-4">
        <label class="form-label">Postal Code</label>
        <input type="text" name="postal" class="form-control" required>
        <div class="invalid-feedback">Please enter a valid postal code.</div>
    </div>

    <div class="col-12 text-center">
        <button class="btn btn-primary px-4" type="submit">Submit</button>
    </div>

</form>

<?php
require_once 'include/footer.php';
?>