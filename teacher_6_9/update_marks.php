<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../library/db_conn.php");

if (!isset($_GET['id'])) {
    echo "Invalid student Index.";
    exit;
}

$student_id = $_GET['id'];
// echo $student_id;

// Fetch student info
$student_stmt = $conn->prepare("SELECT s.*, c.name AS class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = ?");
$student_stmt->bind_param("s", $student_id);
$student_stmt->execute();
$student = $student_stmt->get_result()->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit;
}

// Fetch subjects
$subjects_result = $conn->query("SELECT * FROM subject_6to9");

// Fetch current marks for student
$marks_stmt = $conn->prepare("SELECT subject_id, marks FROM marks WHERE student_id = ?");
$marks_stmt->bind_param("i", $student_id);
$marks_stmt->execute();
$marks_result = $marks_stmt->get_result();

$current_marks = [];
while ($row = $marks_result->fetch_assoc()) {
    $current_marks[$row['subject_id']] = $row['marks'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Marks - <?= htmlspecialchars($student['name']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .form-container {
      max-width: 700px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h4 class="text-center text-primary mb-4">✏️ Update Marks for <?= htmlspecialchars($student['name']) ?> (<?= htmlspecialchars($student['index_no']) ?>)</h4>

    <form action="sql/save_marks.php" method="POST">
      <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
    
      <div class="form-group">
          <label for="term">Term</label>
          <select name="term" id="term" class="form-control" required>
            <option value="">-- Select term --</option>
            <option value="1">1st Term Test</option>
            <option value="2">2nd Term Test</option>
            <option value="3">3rd Term Test</option>
          </select>
       </div>

      <?php while ($subject = $subjects_result->fetch_assoc()): 
        $subject_id = $subject['id'];
        $existing_mark = isset($current_marks[$subject_id]) ? $current_marks[$subject_id] : '';
      ?>
        <div class="form-group">
          <label><?= htmlspecialchars($subject['name']) ?> (Marks)</label>
          <input type="number" name="marks[<?= $subject_id ?>]" class="form-control" min="0" max="100" placeholder="Enter marks" value="<?= $existing_mark ?>">
        </div>
      <?php endwhile; ?>

      <button type="submit" class="btn btn-success btn-block">💾 Save Marks</button>
      <a href="manage_marks.php" class="btn btn-secondary btn-block mt-2">⬅ Back to Student List</a>
    </form>
  </div>
</div>

</body>
</html>
