<?php
require __DIR__ . '/../../vendor/autoload.php';
require 'chart_image.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
include("../../library/db_conn.php");

if (!isset($_SESSION['class_id'])) {
    die("Unauthorized access.");
}

$class_id = $_SESSION['class_id'];
$term = isset($_GET['term']) ? intval($_GET['term']) : 1;

$subjects = $conn->query("SELECT id, name FROM subjects");
$report = [];

while ($subject = $subjects->fetch_assoc()) {
    $sid = $subject['id'];
    $name = $subject['name'];

    $stmt = $conn->prepare("SELECT marks FROM marks WHERE class_id = ? AND subject_id = ? AND term = ?");
    $stmt->bind_param("iii", $class_id, $sid, $term);
    $stmt->execute();
    $res = $stmt->get_result();

    $total = 0;
    $count = 0;
    $gradeA = $gradeB = $gradeC = $gradeS = $gradeF = 0;

    while ($row = $res->fetch_assoc()) {
        $mark = intval($row['marks']);
        $total += $mark;
        $count++;
        if ($mark >= 75) $gradeA++;
        elseif ($mark >= 65) $gradeB++;
        elseif ($mark >= 55) $gradeC++;
        elseif ($mark >= 35) $gradeS++;
        else $gradeF++;
    }

    $avg = $count ? round($total / $count, 2) : 0;
    $report[] = [
        'subject' => $name,
        'avg' => $avg,
        'A' => $gradeA,
        'B' => $gradeB,
        'C' => $gradeC,
        'S' => $gradeS,
        'F' => $gradeF
    ];
}

$chartImage = generateChartImage($report);

ob_start();
?>

<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
    h2 { text-align: center; color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #444; padding: 6px; text-align: center; }
    th { background-color: #f0f0f0; }
    img.chart { display: block; margin: 30px auto; max-width: 90%; }
  </style>
</head>
<body>

<h2>📘 Class Performance Report - Term <?= $term ?></h2>

<?php if (!empty($report)): ?>
<table>
  <thead>
    <tr>
      <th>Subject</th>
      <th>Average</th>
      <th>A (≥75)</th>
      <th>B (65–74)</th>
      <th>C (55–64)</th>
      <th>S (35–54)</th>
      <th>F (&lt;35)</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($report as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['subject']) ?></td>
      <td><?= $row['avg'] ?></td>
      <td><?= $row['A'] ?></td>
      <td><?= $row['B'] ?></td>
      <td><?= $row['C'] ?></td>
      <td><?= $row['S'] ?></td>
      <td><?= $row['F'] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <p style="text-align:center;">No marks data available for Term <?= $term ?>.</p>
<?php endif; ?>

<img class="chart" src="<?= $chartImage ?>" alt="Chart">
</body>
</html>

<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("term_{$term}_report.pdf", ["Attachment" => false]);
