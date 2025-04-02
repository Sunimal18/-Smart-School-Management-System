<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../library/db_conn.php");

$class_id=$_SESSION['class_id'];

// Fetch student data with class names
$query = "SELECT students.id, students.index_no, students.name, classes.name AS class_name 
          FROM students 
          LEFT JOIN classes ON students.class_id = classes.id 
          WHERE $class_id=students.class_id
          ORDER BY students.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Students - Smart School System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .btn-sm {
      border-radius: 8px;
    }
    @media (max-width: 768px) {
      .table-responsive {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="mb-4 text-center">
      <h3 class="text-primary">📋 Manage Students</h3>
      <p class="text-muted">View, edit, or remove students from the system</p>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Index Number</th>
            <th>Name</th>
            <th>Class</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): 
          $count = 1;
          while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $count++ ?></td>
              <td><?= htmlspecialchars($row['index_no']) ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['class_name']) ?></td>
              <td class="text-center">
                <a href="view_student.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="View">
                  <i class="bi bi-eye"></i> View
                </a>
                <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm text-white" title="Edit">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="sql/delete_student.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this student?');">
                  <i class="bi bi-trash"></i> Delete
                </a>
              </td>
            </tr>
        <?php endwhile; else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">No students found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="text-center mt-4">
      <a href="teacher_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
    </div>
  </div>

</body>
</html>
