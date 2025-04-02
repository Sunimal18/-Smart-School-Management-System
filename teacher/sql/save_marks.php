<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit;
}

include("../../library/db_conn.php");
$class_id=$_SESSION['class_id'];
$term=$_REQUEST['term'];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = intval($_POST['student_id']);
    $marks_data = $_POST['marks']; // Array: [subject_id => marks]

    foreach ($marks_data as $subject_id => $mark) {
        $subject_id = intval($subject_id);
        $mark = trim($mark);

        // Skip empty entries
        if ($mark === '') continue;

        $mark = intval($mark);

        // Validate mark range
        if ($mark < 0 || $mark > 100) continue;

        // Check if a record already exists
        $check_stmt = $conn->prepare("SELECT id FROM marks WHERE student_id = ? AND subject_id = ? AND class_id = ? AND term = ?");
        $check_stmt->bind_param("iiii", $student_id, $subject_id, $class_id, $term);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Update existing mark
            $update_stmt = $conn->prepare("UPDATE marks SET marks = ? WHERE student_id = ? AND subject_id = ? AND class_id = ? AND term = ?");
            $update_stmt->bind_param("iiiii", $mark, $student_id, $subject_id, $class_id, $term);
            $update_stmt->execute();
        } else {
            // Insert new mark
            $insert_stmt = $conn->prepare("INSERT INTO marks (student_id, subject_id, class_id, term, marks) VALUES (?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("iiiii", $student_id, $subject_id, $class_id, $term, $mark);
            $insert_stmt->execute();
        }
        
    }

    echo "<script>alert('Marks saved successfully!'); window.location.href='../manage_marks.php';</script>";
} else {
    echo "Invalid request.";
}
?>
