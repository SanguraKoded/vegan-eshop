<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Check if cart_id is provided via POST request
if (isset($_POST['cart_id'])) {
    // Get the cart_id from the POST data
    $cartId = $_POST['cart_id'];

    // Include database connection
    include 'config/db.php';

    // Prepare a DELETE query to remove the product from the cart
    $deleteQuery = "DELETE FROM cart WHERE cart_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($connection, $deleteQuery);

    // Bind the cart_id parameter
    mysqli_stmt_bind_param($stmt, "i", $cartId);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Product removed successfully
        $_SESSION['success_message'] = "Product removed from cart.";
    } else {
        // Error occurred while removing product
        $_SESSION['error_message'] = "Error: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Close the database connection
    mysqli_close($connection);
} else {
    // Redirect to the cart page if cart_id is not provided
    $_SESSION['error_message'] = "No product specified for removal.";
}

// Redirect back to the cart page
header("Location: cart.php");
exit;
?>
