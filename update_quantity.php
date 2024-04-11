<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    include 'config/db.php';

    // Sanitize input
    $cartId = mysqli_real_escape_string($connection, $_POST['cart_id']);
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);

    // Update quantity in the cart table
    $updateQuery = "UPDATE cart SET quantity = $quantity WHERE cart_id = $cartId";
    $result = mysqli_query($connection, $updateQuery);

    if ($result) {
        // Quantity updated successfully
        header("Location: cart.php");
        exit;
    } else {
        // Error updating quantity
        echo "Error: " . mysqli_error($connection);
    }

    mysqli_close($connection);
} else {
    // Redirect to cart page if accessed without proper POST data
    header("Location: cart.php");
    exit;
}
?>
