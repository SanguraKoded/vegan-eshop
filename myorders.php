<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    header("Location: login.php");
    exit;
}

include 'config/db.php';

$userId = $_SESSION['user_id'];
$userNameQuery = "SELECT name FROM users WHERE id = $userId"; // Assuming 'name' is the column in 'users' table
$userNameResult = mysqli_query($connection, $userNameQuery);

$userName = 'Guest';
if ($userNameResult && mysqli_num_rows($userNameResult) > 0) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
}
// Query to fetch orders grouped by user, order_date, and status
$ordersQuery = "SELECT order_date, 
                       GROUP_CONCAT(CONCAT(name, ' (', status, ')') ORDER BY order_id ASC SEPARATOR ', ') AS products,
                       SUM(quantity) AS total_quantity,
                       SUM(price * quantity) AS total_price
                FROM orders 
                JOIN products ON orders.product_id = products.product_id 
                WHERE orders.user_id = $userId 
                GROUP BY user_id, order_date
                ORDER BY order_date DESC";


$ordersResult = mysqli_query($connection, $ordersQuery);

// Check for errors in query execution
if (!$ordersResult) {
    echo "Error: " . mysqli_error($connection);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
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

    <main>
        <section class="my-orders">
            <h2>My Orders</h2>
            <?php if (mysqli_num_rows($ordersResult) > 0): ?>
                <table class="cart-table">
    <thead>
        <tr>
            <th>#</th> <!-- Serial number column -->
            <th>Order Date</th>
            <th>Products(Status)</th>
            <th>Total Quantity</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $serialNumber = 1; // Initialize the serial number counter
        while ($row = mysqli_fetch_assoc($ordersResult)): ?>
            <tr>
                <td><?php echo $serialNumber++; ?></td> <!-- Display serial number -->
                <td><?php echo $row['order_date']; ?></td>
                <td>
    <?php
    $productsWithStatus = explode(', ', $row['products']); // Split products and status
    foreach ($productsWithStatus as $productWithStatus) {
        // Split product name and status
        list($productName, $status) = explode(' (', $productWithStatus);
        $status = rtrim($status, ')'); // Remove trailing ')'
        // Display product name and status
        echo "$productName <span style='color: green;'>($status)</span><br>";
    }
    ?>
</td>

                <td><?php echo $row['total_quantity']; ?></td>
                <td>$<?php echo number_format($row['total_price'], 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vegan Products E-Shop. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
mysqli_close($connection);
?>
