<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'principal') {
    header("Location: ../login.php");
    exit;
}

include("../../library/db_conn.php");

// Fetch classes
$class_result = $conn->query("SELECT id, name FROM classes ORDER BY name ASC");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password_plain = $_POST["password"];
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);
    $class_id = $_POST["class_id"];

    // START TRANSACTION
    $conn->begin_transaction();

    try {
        // Insert into teachers table
        $stmt1 = $conn->prepare("INSERT INTO teachers (name, email, password, class_id) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("sssi", $name, $email, $password_hashed, $class_id);
        $stmt1->execute();

        // Insert into users table (if exists)
        $role = 'teacher';
        $stmt2 = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("ssss", $name, $email, $password_hashed, $role);
        $stmt2->execute();

        // COMMIT changes
        $conn->commit();

        echo "<script>alert('Teacher added successfully!'); window.location.href='teacher_list.php';</script>";
        exit;
    } catch (Exception $e) {
        // ROLLBACK on error
        $conn->rollback();
        echo "<script>alert('Failed to add teacher: " . $e->getMessage() . "');</script>";
    }
}
?>
