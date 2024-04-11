<?php
session_start();

// Connect to MySQL (Change these credentials based on your MySQL setup)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vegan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare SQL statement using prepared statement
    $sql = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_username, $db_password);
        $stmt->fetch();

        // Verify the entered password against the hashed password from the database
        if (password_verify($password, $db_password)) {
            // Set session variables upon successful login
            $_SESSION['user'] = true;
            $_SESSION['user_id'] = $user_id; // Set the user's ID in the session
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['flash_error'] = "Invalid credentials. Please try again.";
            header("Location: login.php"); // Redirect to the login page
            exit();
        }
    } else {
        $_SESSION['flash_error'] = "User not found in the database.";
        header("Location: login.php"); // Redirect to the login page
        exit();
    }
}

$conn->close();
?>
