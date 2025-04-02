<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../login.php");
    exit;
}

include("../library/db_conn.php");

$range = $_GET['range'] ?? '6-15';

if ($range == '6-15'){
  $class_range = '6-9';
}
else{
  $class_range = '10-11';
}
// Extract grade limits
[$minGrade, $maxGrade] = explode('-', $range);

// Get student & class info
$stmt = $conn->prepare("
    SELECT s.id, s.index_no, s.name, c.name AS class_name 
    FROM students s 
    JOIN classes c ON s.class_id = c.id 
    WHERE c.id BETWEEN ? AND ?
    ORDER BY c.id ASC, s.name ASC
");
$stmt->bind_param("ii", $minGrade, $maxGrade);
$stmt->execute();
$students = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Students - Grade <?= htmlspecialchars($class_range) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      margin-top: 30px;
    }
    .card {
      border-radius: 10px;
      padding: 15px;
    }
    .table th, .table td {
      vertical-align: middle !important;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="mb-4 text-center">
    <h3>🎓 Grade <?= $class_range ?> Student Details</h3>
    <p class="text-muted">Viewing students and basic info by grade range</p>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-striped table-bordered mb-0">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Index No</th>
            <th>Name</th>
            <th>Class</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($students->num_rows > 0): $i = 1; ?>
          <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['index_no']) ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['class_name']) ?></td>
              <td>
                <?php 
                  if ($range == '6-15'){
                ?>
                <a href="view_student_marks_6to9.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                  <i class="bi bi-eye-fill"></i> View Marks
                </a>
                <a href="generate_student_pdf_6to9.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">
                  <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                </a>

                <?php } 
                  else {
                ?>
                    <a href="view_student_marks.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                      <i class="bi bi-eye-fill"></i> View Marks
                    </a>
                    <a href="generate_student_pdf.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">
                      <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                    </a>
                <?php
                  }
                ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">No students found in this range.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="principal_dashboard.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Back</a>
  </div>
</div>

</body>
</html>
