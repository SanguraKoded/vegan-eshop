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
    $admin_username = $_POST["email"];
    $admin_password = $_POST["password"];

    // Prepare SQL statement using prepared statement
    $sql = "SELECT email, password FROM admin_users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_username, $db_password);
        $stmt->fetch();

        // Verify the entered password against the hashed password from the database
        if (password_verify($admin_password, $db_password)) {
            // Set session variable to indicate the admin is logged in
            $_SESSION['admin'] = true;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['flash_error'] = "Invalid credentials. Please try again.";
            header("Location: admin_login.php"); // Redirect to the login page
            exit();
        }
    } else {
        $_SESSION['flash_error'] = "Admin user not found in the database.";
        header("Location: admin_login.php"); // Redirect to the login page
        exit();
    }
}

$conn->close();
?>
