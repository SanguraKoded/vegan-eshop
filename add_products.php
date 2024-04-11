<?php include 'navbar.php'; 
session_start(); // Start the session

// Check if the user is not logged in as admin, redirect to login page
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

include 'config/db.php'; // Include database connection

// Initialize variables
$name = $description = $price = $image_url = $stock_quantity = '';
$update = false;

// Handle form submission for adding or editing products
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];

    // Check if a file has been uploaded
    if (!empty($_FILES["image"]["tmp_name"])) {
        $image_url = $_FILES['image']['name']; // Get the uploaded image filename

        // File upload handling
        $targetDirectory = "images/"; // Directory where images will be stored
        $targetFile = $targetDirectory . basename($image_url);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); // Get the file extension

        // Check if the file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Allow only certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $_SESSION['error'] = "Sorry, only JPG, JPEG, and PNG files are allowed.";
                header("Location: {$_SERVER['PHP_SELF']}");
                exit;
            }
            // Upload the file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $_SESSION['success'] = "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            } else {
                $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                header("Location: {$_SERVER['PHP_SELF']}");
                exit;
            }
        } else {
            $_SESSION['error'] = "File is not an image.";
            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        }
    } else {
        $_SESSION['error'] = "No file uploaded.";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $query = "UPDATE products SET name='$name', description='$description', price='$price', image_url='$image_url', stock_quantity='$stock_quantity' WHERE product_id=$id";
    } else {
        $query = "INSERT INTO products (name, description, price, image_url, stock_quantity) VALUES ('$name', '$description', '$price', '$image_url', '$stock_quantity')";
    }

    if (mysqli_query($connection, $query)) {
        $_SESSION['success'] = "Product " . (isset($_POST['id']) ? "updated" : "added") . " successfully";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($connection);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    }
}

// Check if product ID is provided for editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $query = "SELECT * FROM products WHERE product_id=$id";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $description = $row['description'];
        $price = $row['price'];
        $image_url = $row['image_url'];
        $stock_quantity = $row['stock_quantity'];
    }
}

// Fetch all products from the database
$productsQuery = "SELECT * FROM products";
$productsResult = mysqli_query($connection, $productsQuery);

// Check for errors in query execution
if (!$productsResult) {
    $_SESSION['error'] = "Error: " . mysqli_error($connection);
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $update ? 'Edit Product' : 'Add Product'; ?></title>
    <link rel="stylesheet" href="css/products.css">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div style="text-align: center;">
    <h2><?php echo $update ? 'Edit Product' : 'Add Product'; ?></h2>
</div>
<form method="post" enctype="multipart/form-data">
    <?php if ($update): ?>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
    <?php endif; ?>
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description" required><?php echo $description; ?></textarea><br><br>
    <label for="price">Price:</label><br>
    <input type="text" id="price" name="price" value="<?php echo $price; ?>" required><br><br>
    <label for="image">Upload Image:</label><br>
    <input type="file" id="image" name="image" required><br><br>
    <label for="stock_quantity">Stock Quantity:</label><br>
    <input type="text" id="stock_quantity" name="stock_quantity" value="<?php echo $stock_quantity; ?>" required><br><br>
    <button type="submit"><?php echo $update ? 'Update' : 'Add'; ?> Product</button>
</form>

    <div style="text-align: center;">
    <h2>Product List</h2>
</div>
<table class="products-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Stock Quantity</th>
            <!-- <th>Action</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch products from the database and loop through them
        while ($row = mysqli_fetch_assoc($productsResult)):
        ?>
        <tr>
            <td><?php echo $row['product_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td>
                <img src="images/<?php echo $row['image_url']; ?>" alt="Product Image" style="width: 100px;">
            </td>
            <td><?php echo $row['stock_quantity']; ?></td>
            <!-- <td>
                <button class="save-button">Save</button>
                <button class="delete-button" data-productid="<?php echo $row['product_id']; ?>">Delete</button>
            </td> -->
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>


    <script>
    // Add event listeners to editable table cells
    const editableCells = document.querySelectorAll('.editable');
    editableCells.forEach(cell => {
        cell.addEventListener('blur', updateProduct);
    });

    // Add event listeners to save buttons
    const saveButtons = document.querySelectorAll('.save-button');
    saveButtons.forEach(button => {
        button.addEventListener('click', saveProduct);
    });

    // Add event listeners to delete buttons
    const deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', deleteProduct);
    });

    // Function to update product details
    function updateProduct(event) {
        const cell = event.target;
        const row = cell.closest('tr');
        const productId = row.querySelector('.delete-button').getAttribute('data-productid');
        const columnName = cell.getAttribute('data-column');
        const updatedValue = cell.textContent.trim();
        const updatedData = {
            product_id: productId,
            [columnName]: updatedValue
        };
        // Send AJAX request to update product data in the database
        fetch('update_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedData)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Optionally handle success or error messages
        })
        .catch(error => console.error('Error:', error));
    }

    // Function to save product details
    function saveProduct(event) {
        // Implementation of saveProduct function goes here
    }

    // Function to delete product
    function deleteProduct(event) {
        const productId = event.target.getAttribute('data-productid');
        // Send AJAX request to delete product from the database
        fetch(`delete_product.php?id=${productId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Optionally handle success or error messages
        })
        .catch(error => console.error('Error:', error));
    }
</script>

</body>
</html>

<?php
mysqli_close($connection); // Close the database connection
?>
