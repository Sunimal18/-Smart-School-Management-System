<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../library/db_conn.php");

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$id = $_GET['id'];
$student = $conn->query("SELECT * FROM students WHERE id = $id")->fetch_assoc();
$classes = $conn->query("SELECT * FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Student</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container my-5">
    <div class="card mx-auto shadow" style="max-width: 500px;">
      <div class="card-header bg-warning text-white text-center">
        <h4>✏️ Edit Student</h4>
      </div>
      <div class="card-body">
        <form action="sql/edit_student.php" method="POST">
          <input type="hidden" name="id" value="<?= $student['id'] ?>">
          <div class="form-group">
            <label>Index Number</label>
            <input type="text" name="index_no" class="form-control" value="<?= $student['index_no'] ?>" required>
          </div>
          <div class="form-group">
            <label>Student Name</label>
            <input type="text" name="name" class="form-control" value="<?= $student['name'] ?>" required>
          </div>
          <div class="form-group">
            <label>Class</label>
            <select name="class_id" class="form-control" required>
              <?php while ($row = $classes->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id'] == $student['class_id'] ? 'selected' : '' ?>>
                  <?= $row['name'] ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-success btn-block">Update</button>
          <a href="manage_students.php" class="btn btn-secondary btn-block mt-2">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
