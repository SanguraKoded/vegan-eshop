<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Users</title>
          <link rel="icon" type="image/x-icon" href="/images/KN.png">
   <?php include 'navbar.php'; 
    session_start(); // Start the session

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php"); // Replace 'admin_login.php' with your login page URL
    exit;
}?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"], input[type="submit"] {
            padding: 8px;
            margin: 5px;
        }
          /* Additional styling for form elements */
form label {
    display: block;
    margin-bottom: 5px;
  }
  
  form select,
  form input[type="text"],
  form button {
    width: calc(100% - 10px);
    padding: 8px;
    margin-bottom: 10px;
    font-size: 16px;
    background-color: #d3f9d8; /* Light green background */
    border: 1px solid #ccc; /* Border color */
    border-radius: 4px; /* Rounded corners */
    color: #333; /* Text color */
  }
  
  form button {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s; /* Smooth transition on hover */
  }
  
  form button:hover {
    background-color: #ff7f50; /* Orange color on hover */
  }
    </style>
</head>
<body>
    <!-- Display Admin Users -->
    <h2>Admin Users</h2>
    <table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Action</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'config/db.php';

        $query = "SELECT * FROM admin_users";
        $result = mysqli_query($connection, $query);

        $serialNumber = 1; // Initialize serial number

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $serialNumber++ . '</td>'; // Display serial number
                echo '<td>' . $row['Name'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '<td contenteditable="true" class="password-field">' . $row['password'] . '</td>';
                echo '<td>';
                echo '<button class="save-button" data-id="' . $row['id'] . '">Save Changes</button>';
                echo '</td>';
                echo '<td>';
                echo '<form method="post" action="delete_user.php">';
                echo '<input type="hidden" name="delete_user_id" value="' . $row['id'] . '">';
                echo '<input type="submit" value="Delete">';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="6">No admin users found</td></tr>';
        }
        ?>
    </tbody>
</table>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script to handle password updates -->
<script>
$(document).ready(function() {
    $(document).on('click', '.save-button', function() {
        var userId = $(this).data('id');
        var newPassword = $(this).closest('tr').find('.password-field').text().trim();

        $.ajax({
            url: 'update_password.php',
            method: 'POST',
            data: { userId: userId, newPassword: newPassword },
            success: function(response) {
                console.log(response); // Log the response from the server
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});

</script>

<!-- Form to Add Admin Users -->
<h2>Add Admin User</h2>
<form action="add_user.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="username">Email:</label>
    <input type="text" id="username" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Add User">
</form>



</body>
</html>
