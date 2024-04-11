<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
session_start(); // Start the session
include 'config/db.php'; // Include your database connection

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php"); // Replace 'admin_login.php' with your login page URL
    exit;
}

// Handle status update for orders
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveStatus'])) {
    $orderId = $_POST['orderId'];
    $status = $_POST['status'];

    // Update the status
    $updateStatusQuery = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmtUpdateStatus = $connection->prepare($updateStatusQuery);
    $stmtUpdateStatus->bind_param("si", $status, $orderId);

    if ($stmtUpdateStatus->execute()) {
        header("Location: customer_orders.php");
        exit();
    } else {
        echo "Failed to update status: " . $stmtUpdateStatus->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Orders</title>
    <link rel="icon" type="image/x-icon" href="/images/KN.png">
    <!-- Include your CSS styles here -->
    <link rel="stylesheet" href="css/orders.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .border-line {
            border-top: 3px solid #000;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?> <!-- Include navigation component -->
    <h1>Customer Orders</h1>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User Name</th>
                <th>User Phone</th>
                <th>User Email</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Cost</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch data from the orders table
            $query = "SELECT o.*, u.Name AS user_name, u.phone AS user_phone, u.email AS user_email, p.name AS product_name, 
                      p.price * o.quantity AS total_cost 
                      FROM orders o 
                      JOIN users u ON o.user_id = u.id 
                      JOIN products p ON o.product_id = p.product_id
                      ORDER BY u.id, o.order_date DESC";
            $result = mysqli_query($connection, $query);

            $previousUser = "";
            $previousOrderDate = "";
            $totalCostPerCluster = 0;

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Check if it's a new user or a new order date
                    if ($row['user_id'] !== $previousUser || $row['order_date'] !== $previousOrderDate) {
                        // If it's not the first row, display total cost for the previous cluster
                        if ($previousUser !== "") {
                            ?>
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="2" style="text-align: right; font-weight: bold;">Total:</td>
                                <td colspan="2" style="font-weight: bold;"><?php echo '$' . $totalCostPerCluster; ?></td>
                            </tr>
                            <?php
                            $totalCostPerCluster = 0; // Reset total cost for the new cluster
                        }
                        ?>
                        <tr class="border-line">
                            <td><?php echo $row['order_id']; ?></td>
                            <td rowspan="2"><?php echo $row['user_name']; ?></td>
                            <td rowspan="2"><a href="tel:<?php echo $row['user_phone']; ?>"><?php echo $row['user_phone']; ?></a></td>
                            <td rowspan="2"><a href="mailto:<?php echo $row['user_email']; ?>"><?php echo $row['user_email']; ?></a></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo '$' . $row['total_cost']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="orderId" value="<?php echo $row['order_id']; ?>">
                                    <select name="status">
                                        <option value="OrderPlaced" <?php echo ($row['status'] === 'OrderPlaced') ? 'selected' : ''; ?>>Order Placed</option>
                                        <option value="OnTransit" <?php echo ($row['status'] === 'OnTransit') ? 'selected' : ''; ?>>On Transit</option>
                                        <option value="Delivered" <?php echo ($row['status'] === 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="OrderCompleted" <?php echo ($row['status'] === 'OrderCompleted') ? 'selected' : ''; ?>>Order Completed</option>
                                        <option value="Cancelled" <?php echo ($row['status'] === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="saveStatus">Save</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo '$' . $row['total_cost']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="orderId" value="<?php echo $row['order_id']; ?>">
                                    <select name="status">
                                        <option value="OrderPlaced" <?php echo ($row['status'] === 'OrderPlaced') ? 'selected' : ''; ?>>Order Placed</option>
                                        <option value="OnTransit" <?php echo ($row['status'] === 'OnTransit') ? 'selected' : ''; ?>>On Transit</option>
                                        <option value="Delivered" <?php echo ($row['status'] === 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="OrderCompleted" <?php echo ($row['status'] === 'OrderCompleted') ? 'selected' : ''; ?>>Order Completed</option>
                                        <option value="Cancelled" <?php echo ($row['status'] === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="saveStatus">Save</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    $previousUser = $row['user_id'];
                    $previousOrderDate = $row['order_date'];
                    $totalCostPerCluster += $row['total_cost'];
                }
            } else {
                echo "<tr><td colspan='9'>No orders found.</td></tr>";
            }

            // Display the total cost for the last cluster
            if ($previousUser !== "") {
                ?>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="2" style="text-align: right; font-weight: bold;">Total:</td>
                    <td colspan="2" style="font-weight: bold;"><?php echo '$' . $totalCostPerCluster; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

</body>
</html>



