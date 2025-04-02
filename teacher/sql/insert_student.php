<?php
session_start();
include("../../library/db_conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $index_no = trim($_POST['index_no']);
    $name = trim($_POST['name']);
    $class_id = $_POST['class_id'];
    $cat_1 = $_POST['cat_1_sub'];
    $cat_2 = $_POST['cat_2_sub'];
    $cat_3 = $_POST['cat_3_sub'];
    $religion = $_POST['religion'];

    if (!empty($index_no) && !empty($name) && !empty($class_id)) {
        $stmt = $conn->prepare("INSERT INTO students (index_no, name, class_id, category_1_sub, category_2_sub, category_3_sub, religion) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissss", $index_no, $name, $class_id, $cat_1, $cat_2, $cat_3, $religion);

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
