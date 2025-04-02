<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

$class_id=$_SESSION['class_id'];

include("../library/db_conn.php");

// Fetch classes for dropdown
$class_query = "SELECT id, name FROM classes WHERE $class_id=id";
$class_result = $conn->query($class_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Student - Smart School System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .form-container {
      max-width: 600px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .btn-custom {
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="form-container">
      <h3 class="text-center mb-4 text-primary">➕ Add New Student</h3>
      <form action="sql/insert_student.php" method="POST">
        <div class="form-group">
          <label for="index_no">Index Number</label>
          <input type="text" name="index_no" id="index_no" class="form-control" required placeholder="e.g. ST123">
        </div>

        <div class="form-group">
          <label for="name">Student Name</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="e.g. John Doe">
        </div>

        <div class="form-group">
          <label for="class_id">Class</label>
          <select name="class_id" id="class_id" class="form-control" required>
            <option value="">-- Select Class --</option>
            <?php while ($row = $class_result->fetch_assoc()) : ?>
              <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <button type="submit" class="btn btn-success btn-block btn-custom">Add Student</button>
        <a href="teacher_dashboard.php" class="btn btn-secondary btn-block btn-custom mt-2">Back to Dashboard</a>
      </form>
    </div>
  </div>

</body>
</html>
