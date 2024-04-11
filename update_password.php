<?php include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId']) && isset($_POST['newPassword'])) {
    $userId = $_POST['userId'];
    $newPassword = $_POST['newPassword'];

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password for the user with the provided ID
    $updateQuery = "UPDATE admin_users SET password = ? WHERE id = ?";
    $stmt = $connection->prepare($updateQuery);
    $stmt->bind_param("si", $hashedPassword, $userId);
    $stmt->execute();

    // Handle success or error as per your application's requirements
    if ($stmt->affected_rows > 0) {
        session_start();
        $_SESSION['success_message'] = 'Password updated successfully!';
    } else {
        session_start();
        $_SESSION['error_message'] = 'Error updating password!';
    }

    $stmt->close();
}

// Redirect back to the same page
header("Location: admin_users.php");
exit();
?>
