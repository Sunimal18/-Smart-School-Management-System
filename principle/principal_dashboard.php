<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Principal Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .dashboard-header {
      margin-top: 30px;
      margin-bottom: 40px;
    }
    .card-icon {
      font-size: 32px;
      color: #007bff;
    }
    .card-title {
      font-size: 18px;
      margin-top: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="text-center dashboard-header">
    <h2 class="text-primary">🎓 Principal Dashboard</h2>
    <p class="text-muted">Manage and view overall school academic performance</p>
  </div>

  <div class="row">
    <!-- Grade 6-9 -->
    <div class="col-md-6 mb-4">
      <a href="view_students.php?range=6-15" class="text-decoration-none text-dark">
        <div class="card p-4 text-center">
          <div class="card-icon"><i class="bi bi-people-fill"></i></div>
          <div class="card-title">Grade 6–9 Students</div>
          <p class="text-muted">View marks, averages, and student details</p>
        </div>
      </a>
    </div>

    <!-- Grade 10-11 -->
    <div class="col-md-6 mb-4">
      <a href="view_students.php?range=1-5" class="text-decoration-none text-dark">
        <div class="card p-4 text-center">
          <div class="card-icon"><i class="bi bi-person-lines-fill"></i></div>
          <div class="card-title">Grade 10–11 Students</div>
          <p class="text-muted">Check senior grades and performance</p>
        </div>
      </a>
    </div>

    <!-- Teacher Details -->
    <div class="col-md-6 mb-4">
      <a href="teacher_list.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center">
          <div class="card-icon"><i class="bi bi-person-badge-fill"></i></div>
          <div class="card-title">Teacher Details</div>
          <p class="text-muted">View all teacher information & assigned classes</p>
        </div>
      </a>
    </div>

    <!-- Generate Reports -->
    <div class="col-md-6 mb-4">
      <a href="report_generat_menu.php" class="text-decoration-none text-dark">
        <div class="card p-4 text-center">
          <div class="card-icon"><i class="bi bi-file-earmark-bar-graph-fill"></i></div>
          <div class="card-title">Generate Class Reports</div>
          <p class="text-muted">Create PDF reports per class & term</p>
        </div>
      </a>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="sql/logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-left"></i> Logout</a>
  </div>
</div>

</body>
</html>
