
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../login.php");
    exit;
}

include("../library/db_conn.php");

$student_id = $_GET['id'] ?? null;
if (!$student_id) {
    die("Invalid student.");
}

// Get student details
$stmt = $conn->prepare("
    SELECT s.name, s.index_no, c.name AS class_name 
    FROM students s 
    JOIN classes c ON s.class_id = c.id 
    WHERE s.id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

// Get all subjects
$subjects = $conn->query("SELECT id, name FROM subject_6to9")->fetch_all(MYSQLI_ASSOC);

// Fetch marks per term and subject
$marks = [];
foreach ([1, 2, 3] as $term) {
    foreach ($subjects as $subject) {
        $stmt = $conn->prepare("SELECT marks FROM marks WHERE student_id = ? AND subject_id = ? AND term = ?");
        $stmt->bind_param("iii", $student_id, $subject['id'], $term);
        $stmt->execute();
        $result = $stmt->get_result();
        $mark = $result->fetch_assoc()['marks'] ?? null;
        $marks[$subject['id']]["term$term"] = $mark;
    }
}

// Grade function
function getGrade($mark) {
    if (!is_numeric($mark)) return "-";
    if ($mark >= 75) return "A";
    if ($mark >= 50) return "B";
    if ($mark >= 35) return "C";
    return "F";
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Student Marks</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f9f9f9; font-family: 'Segoe UI', sans-serif; }
    .container { margin-top: 40px; }
    .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .table th, .table td { vertical-align: middle; text-align: center; }
  </style>
</head>
<body>

<div class="container">
  <div class="text-center mb-4">
    <h4>📘 Student Mark Sheet</h4>
    <h5><?= htmlspecialchars($student['name']) ?> (<?= $student['index_no'] ?>)</h5>
    <p class="text-muted">Class: <?= $student['class_name'] ?></p>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>Subject</th>
            <th>Term 1</th>
            <th>Grade</th>
            <th>Term 2</th>
            <th>Grade</th>
            <th>Term 3</th>
            <th>Grade</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $term1Total = $term2Total = $term3Total = 0;
        $subjectCount = count($subjects);

        foreach ($subjects as $sub):
            $t1 = $marks[$sub['id']]['term1'] ?? '-';
            $t2 = $marks[$sub['id']]['term2'] ?? '-';
            $t3 = $marks[$sub['id']]['term3'] ?? '-';

            if (is_numeric($t1)) $term1Total += $t1;
            if (is_numeric($t2)) $term2Total += $t2;
            if (is_numeric($t3)) $term3Total += $t3;
        ?>
          <tr>
            <td><?= htmlspecialchars($sub['name']) ?></td>
            <td><?= $t1 ?></td>
            <td><?= getGrade($t1) ?></td>
            <td><?= $t2 ?></td>
            <td><?= getGrade($t2) ?></td>
            <td><?= $t3 ?></td>
            <td><?= getGrade($t3) ?></td>
          </tr>
        <?php endforeach; ?>
        <tr class="font-weight-bold table-info">
          <td>Total</td>
          <td><?= $term1Total ?></td>
          <td></td>
          <td><?= $term2Total ?></td>
          <td></td>
          <td><?= $term3Total ?></td>
          <td></td>
        </tr>
        <tr class="font-weight-bold table-secondary">
          <td>Average</td>
          <td><?= round($term1Total / $subjectCount, 2) ?></td>
          <td></td>
          <td><?= round($term2Total / $subjectCount, 2) ?></td>
          <td></td>
          <td><?= round($term3Total / $subjectCount, 2) ?></td>
          <td></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="javascript:history.back()" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Back</a>
  </div>
</div>

</body>
</html>
