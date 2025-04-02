<?php
include("library/db_conn.php");

// $id=$_REQUEST[];
if (!isset($_GET['index_no'])) {
    echo "Invalid request.";
    exit;
}

$student_id = $_GET['index_no'];
$term = $_GET['term'];
$grade = $_GET['grade'];

// Fetch student details
$stmt = $conn->prepare("SELECT students.*, classes.name AS class_name 
                        FROM students 
                        LEFT JOIN classes ON students.class_id = classes.id 
                        WHERE students.index_no = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();
$id=htmlspecialchars($student['id']);
echo $id;
if (!$student) {
    echo "Student not found.";
    exit;
}

if ($grade == 10 || $grade == 11){
  $marks_stmt = $conn->prepare("SELECT marks.*, subjects.name AS subject_name 
  FROM marks 
  JOIN subjects ON marks.subject_id = subjects.id 
  WHERE marks.student_id = ? AND marks.term = ?");
  $marks_stmt->bind_param("ii", $id,$term);
  $marks_stmt->execute();
  $marks_result = $marks_stmt->get_result();

}
else{
  $marks_stmt = $conn->prepare("SELECT marks.*, subject_6to9.name AS subject_name 
  FROM marks 
  JOIN subject_6to9 ON marks.subject_id = subject_6to9.id 
  WHERE marks.student_id = ? AND marks.term = ?");
  $marks_stmt->bind_param("ii", $id,$term);
  $marks_stmt->execute();
  $marks_result = $marks_stmt->get_result();
}
// Fetch marks

// Calculate total and average
$total = 0;
$count = 0;
$grades = [];

function getGrade($mark) {
    if ($mark >= 75) return 'A';
    elseif ($mark >= 65) return 'B';
    elseif ($mark >= 55) return 'C';
    elseif ($mark >= 35) return 'S';
    else return 'F';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Student</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .grade-A { color: green; font-weight: bold; }
    .grade-B { color: blue; font-weight: bold; }
    .grade-C { color: lightseagreen; font-weight: bold; }
    .grade-S { color: orange; font-weight: bold; }
    .grade-F { color: red; font-weight: bold; }
  </style>
</head>
<body class="bg-light">
  <div class="container my-5">
    <div class="card mx-auto shadow" style="max-width: 700px;">
      <div class="card-header bg-info text-white text-center">
        <h4>🎓 Student Details</h4>
      </div>
      <div class="card-body">
        <p><strong>Index No:</strong> <?= htmlspecialchars($student['index_no']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
        <p><strong>Class:</strong> <?= htmlspecialchars($student['class_name']) ?></p>

        <hr>
        <h5 class="text-primary">📘 Term <?php echo $term ;?> Results Sheet</h5>

        <?php if ($marks_result->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
              <thead class="thead-dark">
                <tr>
                  <th>Subject</th>
                  <th>Marks</th>
                  <th>Grade</th>
                </tr>
              </thead>
              <tbody>
              <?php while ($row = $marks_result->fetch_assoc()):
                $grade = getGrade($row['marks']);
                $total += $row['marks'];
                $count++;
                if ($row['subject_name']=="Category Subject-1"){
                  $sub_name=htmlspecialchars($student['category_1_sub']);
                }
                elseif ($row['subject_name']=="Category Subject-2"){
                  $sub_name=htmlspecialchars($student['category_2_sub']);
                }
                elseif ($row['subject_name']=="Category Subject-3"){
                  $sub_name=htmlspecialchars($student['category_3_sub']);
                }
                elseif ($row['subject_name']=="Religion"){
                  $sub_name=htmlspecialchars($student['religion']);
                }
                else{
                  $sub_name=$row['subject_name'];
                }
    
              ?>
                <tr>
                  <td><?= $sub_name?></td>
                  <td><?= $row['marks'] ?></td>
                  <td class="grade-<?= $grade ?>"><?= $grade ?></td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <p><strong>Total Marks:</strong> <?= $total ?></p>
          <p><strong>Average:</strong> <?= number_format($total / $count, 2) ?></p>
        <?php else: ?>
          <p class="text-muted">No marks found for this student.</p>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary btn-block mt-3">⬅ Back</a>
      </div>
    </div>
  </div>
</body>
</html>
