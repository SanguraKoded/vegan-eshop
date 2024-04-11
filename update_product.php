<?php
// Include database connection
include 'config/db.php';

// Check if form is submitted and all required fields are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['stock_quantity'])) {
    // Retrieve form data
    $productId = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stock_quantity'];

    // Prepare update statement
    $updateQuery = "UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ? WHERE product_id = ?";
    $stmt = $connection->prepare($updateQuery);

    // Bind parameters and execute the statement
    $stmt->bind_param("ssdii", $name, $description, $price, $stockQuantity, $productId);
    if ($stmt->execute()) {
        // Product updated successfully
        echo json_encode(["success" => true, "message" => "Product updated successfully."]);
    } else {
        // Failed to update product
        echo json_encode(["success" => false, "message" => "Failed to update product."]);
    }

    // Close statement
    $stmt->close();
} else {
    // Invalid request or missing required fields
    echo json_encode(["success" => false, "message" => "Invalid request or missing required fields."]);
}

// Close database connection
$connection->close();
?>
