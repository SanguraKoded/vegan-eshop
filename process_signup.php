<?php
// Enable error reporting for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include 'config/db.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Check if passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: login.php");
        exit();
    }

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        // Email already exists, handle accordingly
        $_SESSION['signup_error'] = "Email already exists. Please use a different email.";
        header("Location: login.php");
        exit();
    }

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user details into the 'users' table
    $insertQuery = "INSERT INTO users (Name, phone, email, password) VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($connection, $insertQuery);

    mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $email, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['signup_success'] = "Signup successful! You can now login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['signup_error'] = "Failed to sign up. Please try again.";
        header("Location: login.php");
        exit();
    }

    mysqli_stmt_close($stmt);
}

?>
