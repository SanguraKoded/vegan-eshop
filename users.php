<?php 
    session_start(); // Start the session
    
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php"); // Replace 'admin_login.php' with your login page URL
    exit;
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Users</title>
          <link rel="icon" type="image/x-icon" href="/images/KN.png">

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
<?php include 'navbar.php'; ?>
<body>
    <!-- Display Admin Users -->
    <h2>Client Users</h2>
    <table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <!--<th>Username</th>-->
            <th>Password</th>
            <th>Action</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'config/db.php';

        $query = "SELECT * FROM users";
        $result = mysqli_query($connection, $query);

        $serialNumber = 1; // Initialize serial number

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $serialNumber++ . '</td>'; // Display serial number
                echo '<td>' . $row['Name'] . '</td>';
                echo '<td><a href="tel:' . $row['phone'] . '">' . $row['phone'] . '</a></td>';
                echo '<td><a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a></td>';
                // echo '<td>' . $row['username'] . '</td>';
                echo '<td class="password-field" contenteditable="true">' . $row['password'] . '</td>';
                echo '<td>';
                echo '<button class="save-button" style="background-color: green; color: white;" data-id="' . $row['id'] . '">Save Changes</button>';
                echo '</td>';

                echo '<td>';
                echo '<form method="post" action="delete_client.php">';
                echo '<input type="hidden" name="delete_user_id" value="' . $row['id'] . '">';
                echo '<input type="submit" value="Delete" style="background-color: red; color: white;">';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7">No users found</td></tr>';
        }
        ?>
    </tbody>
</table>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.save-button').on('click', function() {
            var userId = $(this).data('id');
            var newPassword = $(this).closest('tr').find('.password-field').text();

            $.ajax({
                url: 'update_client_password.php', // Replace with your server-side script handling password updates
                method: 'POST',
                data: { userId: userId, newPassword: newPassword },
                success: function(response) {
                    // Handle success response (if needed)
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    // Handle error (if needed)
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>


</body>
</html>
