<?php
session_start(); // Start the session

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    header("Location: login.php"); // Replace 'login.php' with your login page URL
    exit;
}

include 'config/db.php';

// Fetch user's cart items
$userId = $_SESSION['user_id'];

$userNameQuery = "SELECT name FROM users WHERE id = $userId"; // Assuming 'name' is the column in 'users' table
$userNameResult = mysqli_query($connection, $userNameQuery);

$userName = 'Guest';
if ($userNameResult && mysqli_num_rows($userNameResult) > 0) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
}
$cartQuery = "SELECT cart.cart_id, products.product_id, products.name AS product_name, products.price, cart.quantity 
              FROM cart 
              INNER JOIN products ON cart.product_id = products.product_id 
              WHERE cart.user_id = $userId";

// Execute the query
$cartResult = mysqli_query($connection, $cartQuery);

// Check if the query executed successfully
if (!$cartResult) {
    // Query execution failed
    echo "Error: " . mysqli_error($connection);
} else {
    // Initialize total price
    $totalPrice = 0;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/index.css"> <!-- Link to your CSS stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
<style>
    .cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.cart-table th,
.cart-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

.cart-table th {
    background-color: #f2f2f2;
}

.cart-table td {
    background-color: #fff;
}

.cart-table tbody tr:hover {
    background-color: #f9f9f9;
}

.cart-table tbody .total-row {
    font-weight: bold;
}

.cart-table tfoot td {
    font-weight: bold;
    text-align: right;
}
/* Style for the remove button */
.cart-table button.remove-button {
    background-color: #f9dddd; /* Pale red background color */
    color: #c0392b; /* Text color */
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-table button.remove-button:hover {
    background-color: #f2c7c0; /* Darker red on hover */
}
.order-button {
    background-color: #4caf50; /* Green background color */
    color: white; /* Text color */
    border: 2px solid #4caf50; /* Green border */
    border-radius: 20px; /* Rounded border shape */
    padding: 10px 20px; /* Padding */
    cursor: pointer;
    display: block; /* Make it a block-level element */
    margin: 0 auto; /* Center horizontally */
    transition: background-color 0.3s ease;
}

.order-button:hover {
    background-color: #45a049; /* Darker green on hover */
}

/* Responsive styles */
@media (max-width: 768px) {
    .cart-table {
        font-size: 14px;
    }
}

</style>
</head>
<body>
    <header>
        <h1>Shopping Cart</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                <li>
                    <div class="user-info-logout">
                        <span class="user-info"><?php echo $userName; ?></span>
                        <a href="logout.php"><i class="fas fa-user"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>

    <?php if (mysqli_num_rows($cartResult) > 0): ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th> <!-- New column for Remove action -->
            </tr>
        </thead>
        <tbody>
            <?php 
            $serialNumber = 1; // Initialize the serial number counter
            while ($row = mysqli_fetch_assoc($cartResult)): 
                $subtotal = $row['price'] * $row['quantity']; // Calculate subtotal for each item
                $totalPrice += $subtotal; // Add subtotal to total price
            ?>
                <tr>
                    <td><?php echo $serialNumber++; ?></td>
                    <td><strong><?php echo $row['product_id']; ?></strong></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td>$<?php echo $row['price']; ?></td>
                    <td>
                        <!-- Quantity input within form -->
                        <form action="update_quantity.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" class="editable" data-column="quantity">
                            <button type="submit" class="update-button">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <!-- Remove button -->
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                            <button type="submit" class="remove-button">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <tr class="total-row">
                <td colspan="5">Total:</td>
                <td>$<?php echo number_format($totalPrice, 2); ?></td>
                <td></td> <!-- Empty column for alignment -->
            </tr>
            <tr class="order-row">
                <td colspan="7">
                    <!-- Order button -->
                    <form action="place_order.php" method="POST">
                        <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
                        <?php mysqli_data_seek($cartResult, 0); // Reset the result set pointer ?>
                        <?php while ($row = mysqli_fetch_assoc($cartResult)): ?>
                            <input type="hidden" name="quantity[]" value="<?php echo $row['quantity']; ?>">
                        <?php endwhile; ?>
                        <button type="submit" name="place_order" class="order-button">CheckOut</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

               
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vegan Products E-Shop. All rights reserved.</p>
    </footer>
</body>
</html>
