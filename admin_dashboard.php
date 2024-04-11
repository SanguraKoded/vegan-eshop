<?php

session_start(); // Start the session

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php"); // Replace 'admin_login.php' with your login page URL
    exit;
}

include 'config/db.php'; // Include database connection

// Function to get counts from tables with custom names
function getCounts($tables, $tableNames) {
    global $connection;

    $counts = array();

    foreach ($tables as $index => $table) {
        $query = "SELECT COUNT(*) AS count FROM $table";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $counts[$tableNames[$index]] = $row['count'];
        } else {
            $counts[$tableNames[$index]] = 0;
        }
    }

    return $counts;
}

// Function to get today's orders count
function getTodayOrdersCount() {
    global $connection;

    $today = date("Y-m-d"); // Get today's date in the format YYYY-MM-DD
    $query = "SELECT COUNT(*) AS count FROM orders WHERE DATE(order_date) = '$today'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    } else {
        return 0;
    }
}

// Tables to count from and their corresponding custom names
$tables = array('admin_users', 'products', 'orders');
$tableNames = array('Admin Users', 'Products',  'Orders');

// Get counts with custom names
$counts = getCounts($tables, $tableNames);
$todayOrdersCount = getTodayOrdersCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="/images/KN.png">
    <link rel="stylesheet" href="css/admin_styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #96F327;
            color: #333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #808080;
        }

        .dashboard {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: #A9A9A9;
            width: 80%;
            max-width: 600px;
        }

        .dashboard h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .dashboard-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 1;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .stat-box p {
            margin: 10px 0;
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .dashboard-stats {
                flex-direction: column;
                align-items: center;
            }
        }

        footer {
            background-color: #343a40;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .footer-columns {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .footer-column {
            flex: 1;
            text-align: center;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<!-- Admin Dashboard Content -->
<div class="dashboard-container">
    <div class="dashboard">
        <h1>Welcome to the Admin Dashboard!</h1>
        <div class="dashboard-stats">
            <?php foreach ($counts as $name => $count): ?>
                <div class="stat-box" style="background-color: <?php echo ($count > 0) ? '#4CAF50' : '#EF5350'; ?>">
                    <div class="stat-icon" style="color: white;">
                        <?php echo $count; ?>
                    </div>
                    <p><?php echo $name; ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Stat box for today's orders -->
            <div class="stat-box" style="background-color: #2196F3;">
                <div class="stat-icon" style="color: white;">
                    <?php echo $todayOrdersCount; ?>
                </div>
                <p>Today's Orders</p>
            </div>
        </div>
        <!-- Additional content here if needed -->
    </div>
</div>
</body>
</html>
