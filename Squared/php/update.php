<?php
session_start();
include 'config.php'; // Ensure this file correctly connects to your database

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    die("Access Denied: You must be logged in.");
}

// Get student ID from session
$student_id = $_SESSION['student_id'];

// Get form data
$first_name = $_POST['first_name'] ?? '';
$middle_name = $_POST['middle_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$suffix = $_POST['suffix'] ?? '';
$sex = $_POST['sex'] ?? '';
$avatar = $_POST['avatar'] ?? '';
$email = $_POST['email'] ?? '';

// Get program and course selection (can be empty if unchanged)
$program = $_POST['program'] ?? '';
$course = $_POST['course'] ?? '';

// Fetch the existing program and course from the database
$sql = "SELECT program, course FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // If user didn't change program or course, retain the old values
    if (empty($program)) {
        $program = $row['program'];
    }
    if (empty($course)) {
        $course = $row['course'];
    }
}

// Update student profile
$update_sql = "UPDATE students SET 
    first_name = ?, 
    middle_name = ?, 
    last_name = ?, 
    suffix = ?, 
    sex = ?, 
    avatar = ?, 
    email = ?, 
    program = ?, 
    course = ? 
    WHERE student_id = ?";

$stmt = $conn->prepare($update_sql);
$stmt->bind_param("ssssssssss", 
    $first_name, 
    $middle_name, 
    $last_name, 
    $suffix, 
    $sex, 
    $avatar, 
    $email, 
    $program, 
    $course, 
    $student_id
);

// Check if update was successful and pass the message via session
if ($stmt->execute()) {
    $_SESSION['message'] = "🎉 <b>Profile updated successfully!</b> 🎉";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "❌ <b>Error updating profile</b> ❌" . $conn->error;
    $_SESSION['message_type'] = "danger"; // Bootstrap "danger" for errors
}

$stmt->close();
$conn->close();

// Redirect to home.php
header("Location: ../home.php");
exit();
?>
