<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['place_order'])) {
    include 'config/db.php';

    // Get the user ID
    $userId = $_SESSION['user_id'];

    // Check if the quantity ordered exceeds the available stock
    $checkStockQuery = "SELECT p.product_id, p.stock_quantity, c.quantity 
                        FROM products p
                        JOIN cart c ON p.product_id = c.product_id
                        WHERE c.user_id = ?";
    $stmt = mysqli_prepare($connection, $checkStockQuery);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orderValid = true; // Flag to check if the order is valid

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['quantity'] > $row['stock_quantity']) {
            $orderValid = false;
            $productName = "Product ID: " . $row['product_id'];
            $availableQuantity = $row['stock_quantity'];
            $_SESSION['error_message'] = "Quantity ordered for $productName exceeds available stock ($availableQuantity). Please adjust your cart.";
            header("Location: error.php");
            exit;
        }
    }

    // If order is valid, proceed to place the order
    if ($orderValid) {
        // Insert cart items into orders table
        $insertQuery = "INSERT INTO orders (user_id, product_id, quantity) 
                        SELECT user_id, product_id, quantity FROM cart WHERE user_id = ?";
        $stmt = mysqli_prepare($connection, $insertQuery);
        mysqli_stmt_bind_param($stmt, "i", $userId);

        if (mysqli_stmt_execute($stmt)) {
            // Orders placed successfully, now update stock quantity and delete cart items
            $updateQuery = "UPDATE products p
                            JOIN cart c ON p.product_id = c.product_id
                            SET p.stock_quantity = p.stock_quantity - c.quantity
                            WHERE c.user_id = ?";
            $stmt = mysqli_prepare($connection, $updateQuery);
            mysqli_stmt_bind_param($stmt, "i", $userId);
            
            if (mysqli_stmt_execute($stmt)) {
                // Stock quantity updated successfully, now delete cart items
                $deleteQuery = "DELETE FROM cart WHERE user_id = ?";
                $stmt = mysqli_prepare($connection, $deleteQuery);
                mysqli_stmt_bind_param($stmt, "i", $userId);
                
                if (mysqli_stmt_execute($stmt)) {
                    // Cart items deleted successfully
                    echo "<script>alert('Order placed successfully.');</script>";
                    header("Location: myorders.php"); // Redirect to myorders page
                    exit;
                } else {
                    // Error deleting cart items
                    $_SESSION['error_message'] = "Error placing order.";
                    header("Location: error.php");
                    exit;
                }
            } else {
                // Error updating stock quantity
                $_SESSION['error_message'] = "Error placing order.";
                header("Location: error.php");
                exit;
            }
        } else {
            // Error inserting orders
            $_SESSION['error_message'] = "Error placing order.";
            header("Location: error.php");
            exit;
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    header("Location: cart.php");
    exit;
}
?>
