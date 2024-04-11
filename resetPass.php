<!DOCTYPE html>
<html>
<head>
  <title>Password Reset</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }

    .container {
      max-width: 400px;
      margin: 0 auto;
      padding-top: 100px;
    }

    h2 {
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .btn {
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Reset Password</h2>
 <form action="/passwordReseter.php?token=<?=$_GET['token']??'invalid' ?>" method="post">
  <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" required>
  </div>
  <div class="form-group">
    <label for="password">New Password:</label>
    <input type="password" class="form-control" id="password" placeholder="Enter new password" name="new_password" required>
  </div>
  <div class="form-group">
    <label for="confirmPassword">Confirm Password:</label>
    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password" name="confirm_password" required>
  </div>
  <button type="submit" class="btn btn-primary">Reset Password</button>
</form>

  </div>
</body>
</html>
