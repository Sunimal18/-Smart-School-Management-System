<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../library/db_conn.php");

$class_id = $_SESSION['class_id'];
$term = isset($_GET['term']) ? intval($_GET['term']) : 1;

// Get subjects
$subjects = $conn->query("SELECT id, name FROM subject_6to9");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Class Reports</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      margin-top: 40px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="text-center mb-4">
    <h3 class="text-primary">📊 Class Report - Term <?= $term ?></h3>
    <p class="text-muted">Subject-wise performance analysis</p>
  </div>

  <!-- Term Filter -->
  <form method="GET" class="form-inline justify-content-center mb-4">
    <label class="mr-2">Select Term:</label>
    <select name="term" class="form-control mr-2" onchange="this.form.submit()">
      <option value="1" <?= $term == 1 ? 'selected' : '' ?>>Term 1</option>
      <option value="2" <?= $term == 2 ? 'selected' : '' ?>>Term 2</option>
      <option value="3" <?= $term == 3 ? 'selected' : '' ?>>Term 3</option>
    </select>
    <noscript><input type="submit" value="View" class="btn btn-primary"></noscript>
  </form>

  <!-- Report Table -->
  <div class="table-responsive">
    <table class="table table-bordered text-center bg-white shadow-sm">
      <thead class="thead-dark">
        <tr>
          <th>Subject</th>
          <th>Average</th>
          <th>Grade A (≥75)</th>
          <th>Grade B (65–74)</th>
          <th>Grade C (55–64)</th>
          <th>Grade S (35–54)</th>
          <th>Grade F (<35)</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($subject = $subjects->fetch_assoc()):
            $subject_id = $subject['id'];
            $subject_name = $subject['name'];

            // Get total marks & count
            $stmt = $conn->prepare("SELECT marks FROM marks WHERE class_id = ? AND subject_id = ? AND term = ?");
            $stmt->bind_param("iii", $class_id, $subject_id, $term);
            $stmt->execute();
            $result = $stmt->get_result();

            $total = 0;
            $count = 0;
            $gradeA = $gradeB = $gradeC = $gradeS = $gradeF = 0;

            while ($row = $result->fetch_assoc()) {
                $mark = intval($row['marks']);
                $total += $mark;
                $count++;

                if ($mark >= 75) $gradeA++;
                elseif ($mark >= 65) $gradeB++;
                elseif ($mark >= 55) $gradeC++;
                elseif ($mark >= 35) $gradeS++;
                else $gradeF++;
            }

            $avg = $count > 0 ? round($total / $count, 2) : '-';
            $percent = function($g) use ($count) {
                return $count > 0 ? round(($g / $count) * 100, 1) . '%' : '0%';
            };
        ?>
        <tr>
          <td><?= htmlspecialchars($subject_name) ?></td>
          <td><?= $avg ?></td>
          <td><?= $percent($gradeA) ?></td>
          <td><?= $percent($gradeB) ?></td>
          <td><?= $percent($gradeC) ?></td>
          <td><?= $percent($gradeS) ?></td>
          <td><?= $percent($gradeF) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <div class="text-center mt-4">
    <a href="sql/generate_report_pdf.php?term=<?= $term ?>" class="btn btn-danger mt-3">
      <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF Report
    </a>
  </div>



  <div class="text-center mt-4">
    <a href="teacher_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</div>

</body>
</html>
