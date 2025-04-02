<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../library/db_conn.php");

$class_id = $_SESSION['class_id'];

if (!$class_id) {
    echo "<h4>Your class was not found in the classes table.</h4>";
    exit;
}

// Get students in this teacher's class
$stmt = $conn->prepare("SELECT id, index_no, name FROM students WHERE class_id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Marks</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      margin-top: 50px;
    }
    .btn-sm {
      border-radius: 8px;
    }
    table th, table td {
      vertical-align: middle;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="text-center mb-4">
    <h3 class="text-primary">📚 Manage Marks</h3>
    <p class="text-muted">View or update marks for students</p>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered bg-white shadow-sm">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>Index Number</th>
          <th>Name</th>
          <th colspan="2">1st Term <br> Total Marks / Rank</th>
          <th colspan="2">2nd Term <br> Total Marks / Rank</th>
          <th colspan="2">3rd Term <br> Total Marks / Rank</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($result->num_rows > 0):
          $serial = 1;
          while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $serial++ ?></td>
          <td><?= htmlspecialchars($row['index_no']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>

          <?php
          // Loop through each term
          for ($term = 1; $term <= 3; $term++) {
              // Get total marks for student in this term
              $total_stmt = $conn->prepare("SELECT SUM(marks) AS total FROM marks WHERE student_id = ? AND term = ?");
              $total_stmt->bind_param("ii", $row['id'], $term);
              $total_stmt->execute();
              $total_result = $total_stmt->get_result()->fetch_assoc();
              $total_marks = $total_result['total'] ?? 0;

              // Get rank within the class for this term
              $rank_stmt = $conn->prepare("
                  SELECT student_id, SUM(marks) AS total 
                  FROM marks 
                  WHERE class_id = ? AND term = ?
                  GROUP BY student_id 
                  ORDER BY total DESC
              ");
              $rank_stmt->bind_param("ii", $class_id, $term);
              $rank_stmt->execute();
              $rank_result = $rank_stmt->get_result();

              $rank = 1;
              $found = false;
              while ($rank_row = $rank_result->fetch_assoc()) {
                  if ($rank_row['student_id'] == $row['id']) {
                      $found = true;
                      break;
                  }
                  $rank++;
              }

              echo "<td>" . ($total_marks ?: '-') . "</td>";
              echo "<td>" . ($found ? $rank : '-') . "</td>";
          }
          ?>

          <td>
            <a href="update_marks.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm text-white mb-1" title="Update Marks">
              <i class="bi bi-pencil-square"></i>
            </a>
            <a href="view_student_marks.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm" title="View Marks">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="10" class="text-center text-muted">No students found</td>
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
