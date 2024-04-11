<?php
session_start(); // Start the session

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    header("Location: login.php"); // Replace 'login.php' with your login page URL
    exit;
}

include 'config/db.php';

// Check if product_id and quantity are received
if(isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $userId = $_SESSION['user_id'];
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Prepare and execute the SQL query to insert into the cart table
    $stmt = $connection->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $userId, $productId, $quantity);

    if ($stmt->execute()) {
        echo "Product added to cart successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Product ID and quantity are required!";
}
?>
