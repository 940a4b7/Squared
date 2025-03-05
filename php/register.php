<?php
session_start(); // Start the session
require 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'] ?? '';
    $sex = $_POST['sex'];
    $avatar = $_POST['avatar'];
    $program = $_POST['program'];
    $course = $_POST['course'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // QR Code filename & API URL
    $qr_filename = $student_id . ".png"; // Store only the filename in the database
    $qr_filepath = "../qrcodes/". $qr_filename; // Full path for saving the file
    $qr_api_url = "https://quickchart.io/qr?text=" . urlencode($student_id) . "&size=1000";

    // Download the QR code and save it locally
    $qr_image = file_get_contents($qr_api_url);
    
    if ($qr_image) {
        file_put_contents($qr_filepath, $qr_image);
    } else {
        $_SESSION['modal_message'] = "Error generating QR code!";
        $_SESSION['modal_type'] = "danger";
        header("Location: ../index.php");
        exit();
    }

    // Insert into Database
    $sql = "INSERT INTO students (student_id, first_name, middle_name, last_name, suffix, sex, avatar, program, course, email, password_hash, qr_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $student_id, $first_name, $middle_name, $last_name, $suffix, $sex, $avatar, $program, $course, $email, $password, $qr_filename);

    try {
        if ($stmt->execute()) {
            $_SESSION['modal_message'] = "🎉 Registration Successful! 🎉<br>Your account has been registered successfully.";
            $_SESSION['modal_type'] = "success";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Error code for duplicate entry
            $_SESSION['modal_message'] = "⚠️ Duplicate Entry! ⚠️<br>The Student ID <b>$student_id</b> is already registered.<br>Login if you have already an account.";
            $_SESSION['modal_type'] = "warning";
        } else {
            $_SESSION['modal_message'] = "❌ Registration Failed! ❌<br>Something went wrong.<br>" . $e->getMessage();
            $_SESSION['modal_type'] = "danger";
        }
    }

    $stmt->close();
    $conn->close();

    // Redirect back to index.html
    header("Location: ../index.php");
    exit();
}
?>
