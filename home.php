<?php
session_start();
require_once 'php/config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: php/login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student data
$sql = "SELECT student_id, first_name, middle_name, last_name, suffix, sex, avatar, program, course, email, created_at, qr_code 
        FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Custom Styles -->
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg border-0 mx-auto" style="max-width: 600px;">
            <div class="card-header bg-success text-white text-center">
                <h3>Welcome, <?php echo htmlspecialchars($student['first_name']); ?>!</h3>
            </div>
            <div class="card-body text-center">
                <img src="avatars/<?php echo htmlspecialchars($student['avatar']); ?>.png" class="rounded-circle border mb-3" width="100" height="100" alt="Avatar">
                <h5><?php echo htmlspecialchars($student['first_name'] . ' ' . ($student['middle_name'] ? $student['middle_name'] . ' ' : '') . $student['last_name'] . ($student['suffix'] ? ' ' . $student['suffix'] : '')); ?></h5>
                <p class="text-muted"><?php echo htmlspecialchars($student['program']); ?> - <?php echo htmlspecialchars($student['course']); ?></p>
                <hr>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></p>
                <p><strong>Sex:</strong> <?php echo htmlspecialchars($student['sex']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                <p><strong>Joined on:</strong> <?php echo date("F j, Y", strtotime($student['created_at'])); ?></p>
                <div class="mb-3">
                    <p><strong>QR Code:</strong></p>
                    <img src="qrcodes/<?php echo htmlspecialchars($student['qr_code']); ?>" class="border p-2" width="120" alt="QR Code">
                </div>
                <a href="logout.php" class="btn btn-danger w-100">Logout</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
