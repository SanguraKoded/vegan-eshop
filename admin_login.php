
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css"> <!-- Link to your CSS stylesheet -->

</head>
<body>
<?php
// Start the session at the very beginning
session_start();

?>

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
    ?>
    <form action="admin_process_login.php" method="post">
    <h2>Admin Login</h2>

        <div class="form-group">
            <label for="admin_username">Email:</label>
            <input type="email" class="form-control" id="admin_username" name="email" required>
        </div>
        <div class="form-group">
            <label for="admin_password">Password:</label>
            <input type="password" class="form-control" id="admin_password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
         <a href="reset.php" class="btn btn-secondary">Forgot Password</a>
</form>

<!-- Forgot Password Form (Initially Hidden) -->
<form id="forgotPasswordForm" class="forgot-password-form" action="process_admin_forgot_password.php" method="post" style="display: none;">
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
    </form>
    
</div>

<!-- Bootstrap JS and dependencies -->
<script src="assets/jquery-3.3.1.slim.min.js"></script>
<script src="assets/bootstrap.min.js"></script>


</body>
</html>
