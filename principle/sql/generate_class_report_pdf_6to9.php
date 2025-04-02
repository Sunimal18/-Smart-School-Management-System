<?php
require __DIR__ . '/../../vendor/autoload.php';
include("../../library/db_conn.php");

use Dompdf\Dompdf;
use Dompdf\Options;

// Get filters from URL
$class_id = $_GET['class_id'] ?? null;
$term = $_GET['term'] ?? null;
$subject_id = $_GET['subject_id'] ?? null;

if (!$class_id || !$term) {
    die("Missing class or term selection.");
}

// Get class name
$stmt = $conn->prepare("SELECT name FROM classes WHERE id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$class_name = $stmt->get_result()->fetch_assoc()['name'] ?? 'N/A';

// If specific subject selected
if ($subject_id) {
    $stmt = $conn->prepare("SELECT name FROM subject_6to9 WHERE id = ?");
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $subject_name = $stmt->get_result()->fetch_assoc()['name'] ?? 'N/A';
}

// Get students in this class
$stmt = $conn->prepare("SELECT id, name, index_no FROM students WHERE class_id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$students = $stmt->get_result();

// Get subjects
if ($subject_id) {
    $subjects = [ ['id' => $subject_id, 'name' => $subject_name] ];
} else {
    $subjects = $conn->query("SELECT id, name FROM subjects")->fetch_all(MYSQLI_ASSOC);
}

// Collect marks
$report = [];
foreach ($students as $student) {
    $student_row = [
        'name' => $student['name'],
        'index_no' => $student['index_no'],
        'marks' => [],
        'total' => 0
    ];

    foreach ($subjects as $sub) {
        $stmt = $conn->prepare("SELECT marks FROM marks WHERE student_id = ? AND subject_id = ? AND term = ?");
        $stmt->bind_param("iii", $student['id'], $sub['id'], $term);
        $stmt->execute();
        $mark = $stmt->get_result()->fetch_assoc()['marks'] ?? '-';

        $student_row['marks'][] = $mark;
        if (is_numeric($mark)) {
            $student_row['total'] += $mark;
        }
    }

    $report[] = $student_row;
}

// Start output buffering for HTML
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

<h3>📘 Class Report - <?= htmlspecialchars($class_name) ?></h3>
<h4>Term <?= $term ?> <?= isset($subject_name) ? " | Subject: " . htmlspecialchars($subject_name) : '' ?></h4>

<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Index No</th>
      <th>Name</th>
      <?php foreach ($subjects as $sub): ?>
        <th><?= htmlspecialchars($sub['name']) ?></th>
      <?php endforeach; ?>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; foreach ($report as $row): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= $row['index_no'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <?php foreach ($row['marks'] as $m): ?>
        <td><?= $m ?></td>
      <?php endforeach; ?>
      <td><?= $row['total'] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</body>
</html>

<?php
$html = ob_get_clean();

// Generate PDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("class_report.pdf", ["Attachment" => false]);
