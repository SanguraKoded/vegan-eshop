<?php
// Include database connection
include 'config/db.php';

// Check if product ID is provided and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];

    // Prepare delete statement
    $deleteQuery = "DELETE FROM products WHERE product_id = ?";
    $stmt = $connection->prepare($deleteQuery);

    // Bind parameters and execute the statement
    $stmt->bind_param("i", $productId);
    if ($stmt->execute()) {
        // Product successfully deleted
        echo json_encode(["success" => true, "message" => "Product deleted successfully."]);
    } else {
        // Failed to delete product
        echo json_encode(["success" => false, "message" => "Failed to delete product."]);
    }
} else {
    // Product ID not provided or invalid
    echo json_encode(["success" => false, "message" => "Invalid product ID."]);
}

// Close statement and database connection
$stmt->close();
$connection->close();
?>
