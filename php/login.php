<?php
session_start();
require_once 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $password = trim($_POST['password']);

    if (empty($student_id) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    // Check if the student ID exists in the database
    $sql = "SELECT student_id, password_hash FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        // Student ID does not exist
        echo json_encode(["status" => "error", "message" => "Invalid Student ID."]);
    } elseif (!password_verify($password, $student['password_hash'])) {
        // Student ID exists but password is incorrect
        echo json_encode(["status" => "error", "message" => "Wrong Password."]);
    } else {
        // Successful login
        $_SESSION['student_id'] = $student['student_id'];
        echo json_encode(["status" => "success", "message" => "Login successful."]);
    }
}
?>
