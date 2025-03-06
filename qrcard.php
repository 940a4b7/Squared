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

// Format the filename (e.g., "20231234_Dela_Cruz_Juan_A.png")
$student_name = strtoupper($student['last_name']) . "_" . strtoupper($student['first_name']);
if (!empty($student['middle_name'])) {
    $student_name .= "_" . strtoupper(substr($student['middle_name'], 0, 1));
}
if (!empty($student['suffix'])) {
    $student_name .= "_" . strtoupper($student['suffix']);
}
$filename = $student_id . "_" . $student_name . ".png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="css/qrcard.css">
</head>
<body>
    <div id="profile-card" class="profile-container">
        <div class="qr-code">
            <img src="qrcodes/<?php echo htmlspecialchars($student['qr_code']); ?>" alt="QR Code">
        </div>
        <div class="student-name">
            <span class="last-name"><?php echo htmlspecialchars(strtoupper($student['last_name'])); ?></span><br>
            <span class="first-name">
                <?php echo htmlspecialchars(strtolower($student['first_name'])) . ' ' . (!empty($student['middle_name']) ? strtoupper(substr($student['middle_name'], 0, 1)) . '.' : '') . ' ' . htmlspecialchars(strtolower($student['suffix'])); ?>
            </span>
        </div>
        <div class="badge-container">
            <?php echo htmlspecialchars($student['student_id']); ?>
        </div>
        <img class="avatar" src="avatars/<?php echo htmlspecialchars($student['avatar']); ?>.png" alt="Avatar">
        <div class="student-info">
            <p>PROGRAM:<span><?php echo htmlspecialchars($student['program']); ?></span></p>
            <p>COURSE:<span><?php echo htmlspecialchars($student['course']); ?></span></p>
        </div>
    </div>
    <div class="button-container">
        <button class="btn btn-green btn-lg" onclick="downloadProfileCard()">Download Image</button>
        <a href="home.php" class="btn btn-secondary btn-lg">Back to Home</a>
    </div>

    <script>
        function downloadProfileCard() {
            const profileCard = document.getElementById('profile-card');
            html2canvas(profileCard, {
                scale: 9, // High resolution
                useCORS: true // Ensure external images load properly
            }).then(canvas => {
                let link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = "<?php echo $filename; ?>";
                link.click();
            });
        }
    </script>
</body>
</html>
