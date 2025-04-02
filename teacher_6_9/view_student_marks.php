<?php
session_start();
include("../library/db_conn.php");

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$student_id = intval($_GET['id']);

// Get student + class info
$student_stmt = $conn->prepare("SELECT s.*, c.name AS class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = ?");
$student_stmt->bind_param("i", $student_id);
$student_stmt->execute();
$student = $student_stmt->get_result()->fetch_assoc();
$class_id = $student['class_id'];

function getGrade($mark) {
    if ($mark >= 75) return 'A';
    elseif ($mark >= 65) return 'B';
    elseif ($mark >= 55) return 'C';
    elseif ($mark >= 35) return 'S';
    else return 'W';
}

// Get subject list
$subjects = $conn->query("SELECT id, name FROM subject_6to9");

// Get all marks grouped by term + subject
$marks = [];
$total = [1 => 0, 2 => 0, 3 => 0];
$count = [1 => 0, 2 => 0, 3 => 0];

$mark_stmt = $conn->prepare("SELECT subject_id, term, marks FROM marks WHERE student_id = ?");
$mark_stmt->bind_param("i", $student_id);
$mark_stmt->execute();
$mark_result = $mark_stmt->get_result();

while ($row = $mark_result->fetch_assoc()) {
    $marks[$row['subject_id']][$row['term']] = $row['marks'];
    $total[$row['term']] += $row['marks'];
    $count[$row['term']]++;
}

// Get class rank per term
function getRank($conn, $student_id, $class_id, $term) {
    $sql = "SELECT student_id, SUM(marks) as total
            FROM marks
            WHERE class_id = ? AND term = ?
            GROUP BY student_id
            ORDER BY total DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $class_id, $term);
    $stmt->execute();
    $result = $stmt->get_result();

    $rank = 1;
    while ($row = $result->fetch_assoc()) {
        if ($row['student_id'] == $student_id) {
            return $rank;
        }
        $rank++;
    }
    return '-';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Full Marksheet</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .grade-A { color: green; font-weight: bold; }
    .grade-B { color: blue; font-weight: bold; }
    .grade-C { color: yellowgreen; font-weight: bold; }
    .grade-S { color: orange; font-weight: bold; }
    .grade-F { color: red; font-weight: bold; }
  </style>
</head>
<body class="bg-light">
<div class="container my-5">
  <div class="card shadow-lg">
    <div class="card-header bg-info text-white text-center">
      <h4>📘 Full Marksheet - <?= htmlspecialchars($student['name']) ?> (<?= htmlspecialchars($student['index_no']) ?>)</h4>
      <p><?= htmlspecialchars($student['class_name']) ?></p>
    </div>

    <div class="card-body table-responsive">
      <table class="table table-bordered text-center bg-white">
        <thead class="thead-dark">
          <tr>
            <th>Subject</th>
            <th>1st Term</th>
            <th>Grade</th>
            <th>2nd Term</th>
            <th>Grade</th>
            <th>3rd Term</th>
            <th>Grade</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($sub = $subjects->fetch_assoc()):
            $s_id = $sub['id'];
            $t1 = $marks[$s_id][1] ?? null;
            $t2 = $marks[$s_id][2] ?? null;
            $t3 = $marks[$s_id][3] ?? null;
          ?>
          <tr>
            <td><?= $sub['name'] ?></td>
            <td><?= $t1 !== null ? $t1 : '-' ?></td>
            <td class="grade-<?= getGrade($t1 ?? 0) ?>"><?= $t1 !== null ? getGrade($t1) : '-' ?></td>
            <td><?= $t2 !== null ? $t2 : '-' ?></td>
            <td class="grade-<?= getGrade($t2 ?? 0) ?>"><?= $t2 !== null ? getGrade($t2) : '-' ?></td>
            <td><?= $t3 !== null ? $t3 : '-' ?></td>
            <td class="grade-<?= getGrade($t3 ?? 0) ?>"><?= $t3 !== null ? getGrade($t3) : '-' ?></td>
          </tr>
          <?php endwhile; ?>
          <tr class="bg-light font-weight-bold">
            <td>Total</td>
            <td><?= $total[1] ?></td>
            <td></td>
            <td><?= $total[2] ?></td>
            <td></td>
            <td><?= $total[3] ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Average</td>
            <td><?= $count[1] ? round($total[1]/$count[1], 2) : '-' ?></td>
            <td></td>
            <td><?= $count[2] ? round($total[2]/$count[2], 2) : '-' ?></td>
            <td></td>
            <td><?= $count[3] ? round($total[3]/$count[3], 2) : '-' ?></td>
            <td></td>
          </tr>
          <tr>
            <td>Rank</td>
            <td><?= getRank($conn, $student_id, $class_id, 1) ?></td>
            <td></td>
            <td><?= getRank($conn, $student_id, $class_id, 2) ?></td>
            <td></td>
            <td><?= getRank($conn, $student_id, $class_id, 3) ?></td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <a href="manage_marks.php" class="btn btn-secondary mt-3">⬅ Back</a>
    </div>
  </div>
</div>
</body>
</html>
