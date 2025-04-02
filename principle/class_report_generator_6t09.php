<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../login.php");
    exit;
}

include("../library/db_conn.php");

// Fetch classes and subjects
$classes = $conn->query("SELECT id, name FROM classes WHERE id>5 ORDER BY name ASC");
$subjects = $conn->query("SELECT id, name FROM subject_6to9 ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Class Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f9f9f9; font-family: 'Segoe UI', sans-serif; }
    .container { margin-top: 40px; max-width: 700px; }
    .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .form-group label { font-weight: 500; }
  </style>
</head>
<body>

<div class="container">
  <div class="text-center mb-4">
    <h3>📊 Generate Class Report</h3>
    <p class="text-muted">Filter by class, term, and subject</p>
  </div>

  <div class="card p-4">
    <form action="sql/generate_class_report_pdf_6to9.php" method="GET" target="_blank">
      <div class="form-group">
        <label for="class_id">Select Class</label>
        <select class="form-control" name="class_id" required>
          <option value="">-- Select Class --</option>
          <?php while ($row = $classes->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="term">Select Term</label>
        <select class="form-control" name="term" required>
          <option value="">-- Select Term --</option>
          <option value="1">Term 1</option>
          <option value="2">Term 2</option>
          <option value="3">Term 3</option>
        </select>
      </div>

      <div class="form-group">
        <label for="subject_id">Select Subject (Optional)</label>
        <select class="form-control" name="subject_id">
          <option value="">-- All Subjects --</option>
          <?php while ($row = $subjects->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-file-earmark-bar-graph-fill"></i> Generate PDF Report
        </button>
      </div>
    </form>
  </div>

  <div class="text-center mt-4">
    <a href="principal_dashboard.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
    </a>
  </div>
</div>

</body>
</html>
