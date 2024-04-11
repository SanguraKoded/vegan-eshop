<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- navigation.html -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #10D3DF;">
    <div class="container">
     <a class="navbar-brand" href="#" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
    <!-- Replace 'logo.png' with the actual path to your logo -->
    <img src="images/1.jpg" alt="Logo" height="120" class="d-inline-block align-top">
    <span style="font-family: 'Roboto', sans-serif; font-size: 24px; font-weight: bold; color: #9927F3;">Vegan Products E-shop</span>
</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" onclick="toggleNav()">
            <span class="navbar-toggler-icon"></span>

        </button>
        <div id="navbarNav" class="collapse navbar-collapse" style="text-align: right;">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-secondary mr-lg-3" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary mr-lg-3" href="add_products.php"> Add/Check Products </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary mr-lg-3" href="customer_orders.php"> Check Customer Orders</a>
                </li>
               
                 <li class="nav-item">
                    <a class="btn btn-secondary mr-lg-3" href="users.php">Client Users</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-secondary mr-lg-3" href="admin_users.php">Add Admin Users</a>
                </li>
                
                <!-- If you want to add the Admin logout button -->
                <li class="nav-item">
                    <a class="btn btn-secondary" href="admin_logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Bootstrap JS dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<style>
    /* Style the navigation buttons */
    .navbar-nav .nav-item {
        margin-right: 10px; /* Adjust the margin as needed */
        border-radius: 5px; /* Add some border-radius for rounded corners */
    }

    /* Style the last navigation button without margin to avoid extra space */
    .navbar-nav .nav-item:last-child {
        margin-right: 0;
    }

</style>

<script>
    function toggleNav() {
        var nav = document.getElementById("navbarNav");
        if (nav.style.display === "block") {
            nav.style.display = "none";
        } else {
            nav.style.display = "block";
        }
    }
</script>

</body>
</html>

