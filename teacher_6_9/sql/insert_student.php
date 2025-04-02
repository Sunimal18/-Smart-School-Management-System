<?php
session_start();
include("../../library/db_conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $index_no = trim($_POST['index_no']);
    $name = trim($_POST['name']);
    $class_id = $_POST['class_id'];

    if (!empty($index_no) && !empty($name) && !empty($class_id)) {
        $stmt = $conn->prepare("INSERT INTO students (index_no, name, class_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $index_no, $name, $class_id);

        if ($stmt->execute()) {
            echo "<script>alert('Student added successfully!'); window.location.href='../add_student.php';</script>";
        } else {
            echo "<script>alert('Error adding student.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Please fill all fields.'); window.history.back();</script>";
    }
}
?>
