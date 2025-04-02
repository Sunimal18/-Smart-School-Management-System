<?php
// login.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Smart School System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

  <div class="login-wrapper d-flex align-items-center justify-content-center">
    <div class="login-card shadow-lg">
      <div class="text-center mb-4">
        <img src="https://img.icons8.com/clouds/100/school.png" alt="School Logo" class="login-logo mb-2">
        <h4 class="text-primary">Smart School Login</h4>
      </div>
      <form action="sql/login.php" method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" class="form-control" required autofocus>
        </div>

        <div class="form-group">
          <label for="role">User Role</label>
          <select name="role" id="role" class="form-control" required>
            <option value="">-- Select Role --</option>
            <option value="teacher">Teacher</option>
            <option value="principal">Principal</option>
          </select>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>

        <div class="text-center mt-3">
          <small class="text-muted">Teacher / Principal Access Only</small>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
