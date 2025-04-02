<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../login.php");
    exit;
}

include("../library/db_conn.php");

// Fetch available classes
$class_result = $conn->query("SELECT id, name FROM classes ORDER BY name ASC");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"]; // ⚠️ No encryption
    $class_id = $_POST["class_id"];

    $conn->begin_transaction();

    try {
        // Insert into teachers table
        $stmt1 = $conn->prepare("INSERT INTO teachers (name, email, password, class_id) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("sssi", $name, $email, $password, $class_id);
        $stmt1->execute();

        // Insert into users table
        $role = 'teacher';
        $stmt2 = $conn->prepare("INSERT INTO users (username, password, role, class_id) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("ssss", $email, $password, $role, $class_id);
        $stmt2->execute();

        $conn->commit();
        echo "<script>alert('Teacher added successfully!'); window.location.href='teacher_list.php';</script>";
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Teacher</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 600px; margin-top: 50px; }
    .card { border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

<div class="container">
  <div class="card p-4">
    <h3 class="text-center text-primary">➕ Add New Teacher</h3>
    <form method="POST">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" class="form-control" required placeholder="e.g. John Doe">
      </div>
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" required placeholder="e.g. john@example.com">
      </div>
      <div class="form-group">
        <label>Password (Plain Text)</label>
        <input type="text" name="password" class="form-control" required placeholder="e.g. abc123">
      </div>
      <div class="form-group">
        <label>Assign Class</label>
        <select name="class_id" class="form-control" required>
          <option value="">-- Select Class --</option>
          <?php while ($class = $class_result->fetch_assoc()): ?>
            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success btn-block">Add Teacher</button>
        <a href="teacher_list.php" class="btn btn-secondary btn-block mt-2">Back to Teacher List</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
