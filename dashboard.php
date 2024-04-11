<?php
session_start(); // Start the session

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user']) || $_SESSION['user'] !== true) {
    header("Location: login.php"); // Replace 'login.php' with your login page URL
    exit;
}

include 'config/db.php';

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($connection, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

$userId = $_SESSION['user_id'];

$userNameQuery = "SELECT name FROM users WHERE id = $userId"; // Assuming 'name' is the column in 'users' table
$userNameResult = mysqli_query($connection, $userNameQuery);

$userName = 'Guest';
if ($userNameResult && mysqli_num_rows($userNameResult) > 0) {
    $userNameRow = mysqli_fetch_assoc($userNameResult);
    $userName = $userNameRow['name'];
}
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- jQuery Library -->
      <script src="js/search.js"></script>
    <style>
        .product {
            transition: all 0.3s ease-in;
        }

        .product:hover {
            cursor: pointer;
            border: 4px solid #ff6f61;
        }
        .quantity-popup {
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 999; /* Ensure the popup appears above other elements */
        }

        .add-to-cart-btn {
           
            background-color: #ff6f61;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .cart-count {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #4CAF50; /* Green background color */
        color: #000; /* Black text color */
        text-align: center;
        line-height: 20px; /* Center the text vertically */
        font-size: 12px; /* Adjust font size as needed */
    }
    </style>
</head>
<body>
<header>
        <h1>Welcome to Vegan Products E-Shop</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="myorders.php"><i class="fas fa-home"></i> MyOrders</a></li>
                <li>
    <a href="cart.php">
        <i class="fas fa-shopping-cart"></i> Cart
        <?php
            // Fetch cart count for the specific user
            $cartCountQuery = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = $userId";
            $cartCountResult = mysqli_query($connection, $cartCountQuery);
            $cartCountRow = mysqli_fetch_assoc($cartCountResult);
            $cartCount = $cartCountRow['cart_count'];

            // Display cart count
            if ($cartCount > 0) {
                echo "<span class='cart-count'>$cartCount</span>";
            }
        ?>
    </a>
</li>
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
        <section class="products">
            <h2>Discover the Best Vegan Products</h2>
            <div class="product-container">
                <?php foreach($products as $product): ?>
                    <div class="product" data-product-id="<?php echo $product['product_id']; ?>" data-product-name="<?php echo $product['name']; ?>" data-product-price="<?php echo $product['price']; ?>">
                        <img src="images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                        <h3><?php echo $product['name']; ?></h3>
                        <p>$<?php echo $product['price']; ?></p>
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vegan Products E-Shop. All rights reserved.</p>
    </footer>

    <script>
    $(document).ready(function() {
        $('.add-to-cart-btn').click(function(event) {
            event.stopPropagation(); // Prevent the click event from bubbling up to the .product container
            var productContainer = $(this).closest('.product');
            var productId = productContainer.data('product-id');
            var productName = productContainer.data('product-name');
            var productPrice = productContainer.data('product-price');

            var quantity = prompt('Enter quantity for ' + productName + ':', '1');
            if (quantity !== null && !isNaN(quantity) && quantity > 0) {
                // Send an AJAX request to add the product to the cart with the given quantity
                $.ajax({
                    type: "POST",
                    url: "add_to_cart.php", // Adjust URL to your server-side script
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        alert("Product '" + productName + "' successfully added to cart!"); // Display success message
                        console.log(response); // Log response from the server
                        location.reload(); // Reload the page
                        // Optionally, you can perform further actions here such as updating the UI
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText); // Log any errors
                    }
                });
            }
        });
    });
</script>

</body>
</html>