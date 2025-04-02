<?php
require __DIR__ . '/../vendor/autoload.php';
include("../library/db_conn.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Validate student ID
$student_id = $_GET['id'] ?? null;
if (!$student_id) die("Invalid student.");

// Fetch student info
$stmt = $conn->prepare("SELECT s.*, c.name AS class_name FROM students s JOIN classes c ON s.class_id = c.id WHERE s.id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
if (!$student) die("Student not found.");

// Fetch subjects
$subjects = $conn->query("SELECT id, name FROM subjects")->fetch_all(MYSQLI_ASSOC);

// Fetch marks
$marks = [];
foreach ([1, 2, 3] as $term) {
    foreach ($subjects as $subject) {
        $stmt = $conn->prepare("SELECT marks FROM marks WHERE student_id = ? AND subject_id = ? AND term = ?");
        $stmt->bind_param("iii", $student_id, $subject['id'], $term);
        $stmt->execute();
        $result = $stmt->get_result();
        $mark = $result->fetch_assoc()['marks'] ?? '-';
        $marks[$subject['id']]["term$term"] = $mark;
    }
}

// Grade function
function getGrade($m) {
    if (!is_numeric($m)) return '-';
    if ($m >= 75) return 'A';
    if ($m >= 50) return 'B';
    if ($m >= 35) return 'C';
    return 'F';
}

// Start buffering HTML
ob_start();
?>

<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
    h3, h4 { text-align: center; color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #333; padding: 6px; text-align: center; }
    th { background-color: #f0f0f0; }
  </style>
</head>
<body>

<h3>🎓 Student Report Card</h3>
<h4><?= htmlspecialchars($student['name']) ?> (<?= $student['index_no'] ?>) | Class: <?= $student['class_name'] ?></h4>

<table>
  <thead>
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
    $count = count($subjects);

    foreach ($subjects as $subject):
        $t1 = $marks[$subject['id']]['term1'] ?? '-';
        $t2 = $marks[$subject['id']]['term2'] ?? '-';
        $t3 = $marks[$subject['id']]['term3'] ?? '-';

        if (is_numeric($t1)) $term1Total += $t1;
        if (is_numeric($t2)) $term2Total += $t2;
        if (is_numeric($t3)) $term3Total += $t3;

        if ($subject['name']=="Category Subject-1"){
          $sub_name=htmlspecialchars($student['category_1_sub']);
        }
        elseif ($subject['name']=="Category Subject-2"){
          $sub_name=htmlspecialchars($student['category_2_sub']);
        }
        elseif ($subject['name']=="Category Subject-3"){
          $sub_name=htmlspecialchars($student['category_3_sub']);
        }
        elseif ($subject['name']=="Religion"){
          $sub_name=htmlspecialchars($student['religion']);
        }
        else{
          $sub_name=$subject['name'];
        }
    ?>
    <tr>
      <td><?= $sub_name ?></td>
      <td><?= $t1 ?></td>
      <td><?= getGrade($t1) ?></td>
      <td><?= $t2 ?></td>
      <td><?= getGrade($t2) ?></td>
      <td><?= $t3 ?></td>
      <td><?= getGrade($t3) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr style="font-weight:bold; background:#e2e2e2;">
      <td>Total</td>
      <td><?= $term1Total ?></td><td></td>
      <td><?= $term2Total ?></td><td></td>
      <td><?= $term3Total ?></td><td></td>
    </tr>
    <tr style="font-weight:bold;">
      <td>Average</td>
      <td><?= round($term1Total / $count, 2) ?></td><td></td>
      <td><?= round($term2Total / $count, 2) ?></td><td></td>
      <td><?= round($term3Total / $count, 2) ?></td><td></td>
    </tr>
  </tbody>
</table>

</body>
</html>

<?php
$html = ob_get_clean();

// DomPDF Config
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Student_Report_" . $student['index_no'] . ".pdf", ["Attachment" => false]);
