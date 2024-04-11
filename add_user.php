<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert new user into the database
    $query = "INSERT INTO admin_users (Name, email, password) VALUES (?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sss", $name, $username, $password);

    if ($stmt->execute()) {
        // Redirect to admin_users.php after successful insertion
        header("Location: admin_users.php");
        exit();
    } else {
        echo "Error: " . $connection->error;
    }
}
?>
