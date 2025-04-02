<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../../library/db_conn.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $id       = intval($_POST['id']);
    $index_no = trim($_POST['index_no']);
    $name     = trim($_POST['name']);
    $class_id = intval($_POST['class_id']);

    // Validate
    if (empty($index_no) || empty($name) || empty($class_id)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Update query
    $stmt = $conn->prepare("UPDATE students SET index_no = ?, name = ?, class_id = ? WHERE id = ?");
    $stmt->bind_param("ssii", $index_no, $name, $class_id, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Student updated successfully!'); window.location.href='../manage_students.php';</script>";
    } else {
        echo "<script>alert('Failed to update student.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
