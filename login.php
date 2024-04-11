<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login</title>
    <link rel="icon" type="image/x-icon" href="/images/1.jpg">

    <link rel="stylesheet" href="css/login.css"> <!-- Link to your CSS stylesheet -->

    <!-- Bootstrap CSS -->
    <link href="assets/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
// Start the session at the very beginning
session_start();
?>
<!-- Display success message -->
<?php if (isset($_SESSION['success_message'])) : ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success_message']; ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<!-- Display error message -->
<?php if (isset($_SESSION['error_message'])) : ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error_message']; ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>


<div class="container mt-5">
    <?php
    // Display flash error if it exists
    if (isset($_SESSION['flash_error'])) {
        echo '<div class="alert alert-danger" role="alert">';
        echo $_SESSION['flash_error'];
        echo '</div>';

        // Remove the flash error after displaying it
        unset($_SESSION['flash_error']);
    }
    if (isset($_SESSION['reset_link_sent']) && $_SESSION['reset_link_sent']) {
    echo '<div class="alert alert-success" role="alert">';
    echo 'A password reset link has been sent to your email. Please check your inbox.';
    echo '</div>';

    // Remove the session message after displaying it
    unset($_SESSION['reset_link_sent']);
}
    ?>
    <?php
    // Display signup success or error message
    if (isset($_SESSION['signup_success'])) {
        echo '<div class="alert alert-success" role="alert">';
        echo $_SESSION['signup_success'];
        echo '</div>';

        // Remove the signup success message after displaying it
        unset($_SESSION['signup_success']);
    } elseif (isset($_SESSION['signup_error'])) {
        echo '<div class="alert alert-danger" role="alert">';
        echo $_SESSION['signup_error'];
        echo '</div>';

        // Remove the signup error message after displaying it
        unset($_SESSION['signup_error']);
    }
    ?>
   <form id="loginForm" class="login-form" action="process_login.php" method="post">
    <h2>Login</h2>
    <div class="form-group">
        <label for="username">Email:</label>
        <input type="text" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <button type="button" class="btn btn-secondary" onclick="showSignupForm()">Signup</button>
 <a href="reset.php" class="btn btn-secondary">Forgot Password</a>

</form>

<!-- Forgot Password Form (Initially Hidden) -->
<form id="forgotPasswordForm" class="forgot-password-form" action="process_forgot_password.php" method="post" style="display: none;">
    <h2>Forgot Password</h2>
    <div class="form-group">
        <label for="resetEmail">Enter Email:</label>
        <input type="text" class="form-control" id="resetEmail" name="resetEmail" required>
    </div>
    <button type="submit" class="btn btn-primary">Reset Password</button>
    <button type="button" class="btn btn-secondary" onclick="hideForgotPasswordForm()">Cancel</button>
</form>

<script>
    function showForgotPasswordForm() {
        document.getElementById('forgotPasswordForm').style.display = 'block';
        document.getElementById('loginForm').style.display = 'none';
    }

    function hideForgotPasswordForm() {
        document.getElementById('forgotPasswordForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
    }
</script>


    <!-- Signup Form -->
    <form id="signupForm" class="signup-form" action="process_signup.php" method="post" onsubmit="return validateForm()">
    <h2>Signup</h2>
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="phone">Phone:</label>
        <input type="number" class="form-control" id="phone" name="phone" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Signup</button>
</form>

    <script>
        function showSignupForm() {
            // Hide the login form
            document.getElementById('loginForm').style.display = 'none';
            // Show the signup form
            document.getElementById('signupForm').style.display = 'block';
        }
    </script>
    <script>
    // Add this script to set the user's email before form submission
    document.getElementById('signupForm').addEventListener('submit', function () {
        var userEmail = document.getElementById('email').value;
        document.getElementById('userEmail').value = userEmail;
    });
</script>

</div>


</body>
</html>
