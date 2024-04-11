<?php
// Include your database connection
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_id'])) {
    $user_id = $_POST['delete_user_id'];

    // Delete the user with the specified ID
    $query = "DELETE FROM users WHERE id = $user_id";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "User deleted successfully!";
        // Redirect to the admin users page or any other appropriate location
         header("Location: users.php");
         exit();
    } else {
        echo "Error deleting user: " . mysqli_error($connection);
    }
} else {
    echo "Invalid request!";
}
?>
