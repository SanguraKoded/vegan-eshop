<?php
include 'config/db.php';

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($connection, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vegan Products E-Shop</title>
    <link rel="icon" type="image/x-icon" href="/images/1.jpg">

    <link rel="stylesheet" href="css/index.css"> <!-- Link to your CSS stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <script src="js/search.js"></script>

</head>
<body>
    <header>
        <h1>Welcome to Vegan Products E-Shop</h1>
        <nav>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="login.php"><i class="fas fa-user"></i> Login</a></li>
                <li><a href="admin_login.php"><i class="fas fa-user"></i> AdminLogin</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                <!-- <li>
                <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search products...">
              <button type="button" id="searchButton">Search</button>
             </div>
         </li> -->

            </ul>
        </nav>
    </header>

    <main>
        <section class="products">
            <h2>Discover the Best Vegan Products</h2>
            <div class="product-container">
                <?php foreach($products as $product): ?>
                    <div class="product">
                        <img src="images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        <h3><?php echo $product['name']; ?></h3>
                        <p>$<?php echo $product['price']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vegan Products E-Shop. All rights reserved.</p>
    </footer>
</body>
</html>
