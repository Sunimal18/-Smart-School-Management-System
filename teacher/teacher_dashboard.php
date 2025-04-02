<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Dashboard - Smart School System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/teacher_dashboard.css">
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand font-weight-bold" href="#">📘 Smart School</a>
      <span class="ml-auto text-white">👨‍🏫 Welcome, <?php echo $_SESSION['username']; ?></span>
    </div>
  </nav>

  <!-- Dashboard Content -->
  <div class="container my-5">
    <div class="text-center mb-4">
      <h2>Teacher Dashboard</h2>
      <p class="text-muted">Manage your students and their academic progress</p>
    </div>

    <div class="row">
        <!-- Add Student -->
        <div class="col-md-3 mb-4">
            <div class="card text-center shadow h-100">
            <div class="card-body">
                <i class="bi bi-person-plus display-4 text-primary mb-3"></i>
                <h5 class="card-title">Add Student</h5>
                <p class="card-text">Register new students to the system.</p>
                <a href="add_student.php" class="btn btn-primary btn-block">Go</a>
            </div>
            </div>
        </div>

        <!-- Manage Students -->
        <div class="col-md-3 mb-4">
            <div class="card text-center shadow h-100">
            <div class="card-body">
                <i class="bi bi-people-fill display-4 text-warning mb-3"></i>
                <h5 class="card-title">Manage Students</h5>
                <p class="card-text">View, Edit or delete student records.</p>
                <a href="manage_students.php" class="btn btn-warning btn-block text-white">Go</a>
            </div>
            </div>
        </div>

        <!-- Manage Marks -->
        <div class="col-md-3 mb-4">
            <div class="card text-center shadow h-100">
            <div class="card-body">
                <i class="bi bi-pencil-square display-4 text-success mb-3"></i>
                <h5 class="card-title">Manage Marks</h5>
                <p class="card-text">Enter or update student marks per subject.</p>
                <a href="manage_marks.php" class="btn btn-success btn-block">Go</a>
            </div>
            </div>
        </div>

        <!-- View Reports -->
        <div class="col-md-3 mb-4">
            <div class="card text-center shadow h-100">
            <div class="card-body">
                <i class="bi bi-bar-chart-line-fill display-4 text-info mb-3"></i>
                <h5 class="card-title">View Reports</h5>
                <p class="card-text">View performance, grades, and rankings.</p>
                <a href="view_reports.php" class="btn btn-info btn-block">Go</a>
            </div>
            </div>
        </div>
    </div>

  </div>

  <!-- Footer -->
  <footer class="text-center text-muted py-4">
    &copy; <?php echo date('Y'); ?> Smart School System. All rights reserved.
  </footer>

</body>
</html>
