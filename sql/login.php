<?php
session_start();
include("../library/db_conn.php"); // Database connection

// Get form values
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$role     = trim($_POST['role']);

if (empty($username) || empty($password) || empty($role)) {
    echo "<script>alert('All fields are required!'); window.history.back();</script>";
    exit;
}

// Check in database
$sql = "SELECT * FROM users WHERE username=? AND password=? AND role=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $password, $role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];
    $_SESSION['class_id']     = $user['class_id'];

    $class_id=$user['class_id'];


    // Redirect based on role
    if ($role === "teacher") {
        if($class_id == 1 || $class_id == 2 || $class_id == 3 || $class_id == 5){
            header("Location: ../teacher/teacher_dashboard.php");
        }
        else{
            header("Location: ../teacher_6_9/teacher_dashboard.php");
        }
    } elseif ($role === "principal") {
        header("Location: ../principle/principal_dashboard.php");
    }
    exit;
} else {
    echo "<script>alert('Invalid login credentials!'); window.location.href='login.php';</script>";
    exit;
}
?>
