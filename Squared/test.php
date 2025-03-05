<?php
session_start();
require 'php/config.php'; // Database connection

// Fetch all students from the database
$sql = "SELECT student_id, first_name, middle_name, last_name, suffix, sex, avatar, program, course, email, created_at, qr_code FROM students";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Student Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4">Student Records</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Suffix</th>
                <th>Sex</th>
                <th>Avatar</th>
                <th>Program</th>
                <th>Course</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>QR Code</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['suffix']) ?></td>
                        <td><?= htmlspecialchars($row['sex']) ?></td>
                        <td><?= htmlspecialchars($row['avatar']) ?></td>
                        <td><?= htmlspecialchars($row['program']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <img src="qrcodes/<?= htmlspecialchars($row['qr_code']) ?>" alt="QR Code" width="50">
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" class="text-center">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php" class="btn btn-primary">Back to Home</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
