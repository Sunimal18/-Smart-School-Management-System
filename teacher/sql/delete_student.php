<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../../library/db_conn.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Student deleted successfully.'); window.location.href='../manage_students.php';</script>";
    } else {
        echo "<script>alert('Error deleting student.'); window.history.back();</script>";
    }
} else {
    echo "Invalid request.";
}
?>
