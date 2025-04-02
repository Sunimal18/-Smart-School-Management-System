<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../login.php");
    exit;
}

include("../library/db_conn.php");

// Fetch all teachers with assigned class
$query = "
    SELECT t.id, t.name, t.email, c.name AS class_name
    FROM teachers t
    LEFT JOIN classes c ON t.class_id = c.id
    ORDER BY t.name ASC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .container { margin-top: 40px; }
    .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>

<div class="container">

  <div class="text-center mb-4">
    <h3>👨‍🏫 Teacher Details</h3>
    <p class="text-muted">List of teachers and their assigned classes</p>

    <a href="add_new_teacher.php" class="btn btn-success mt-2">
      <i class="bi bi-person-plus-fill"></i> Add New Teacher
    </a>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Assigned Class</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): $i = 1; ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= $row['class_name'] ?? '<span class="text-danger">Not Assigned</span>' ?></td>
              <td>
                <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="btn btn-outline-primary btn-sm">
                  <i class="bi bi-envelope-fill"></i> Email
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">No teachers found.</td></tr>
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
