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
    <title>Squared Admin Dashboard</title>
    <link rel="icon" type="image/png" href="../images/Squared_Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>

<nav class="navbar navbar-dark px-3">
    <button class="btn btn-outline-light" id="sidebarToggle"><i class=" bi-list"></i></button>
    <span class="navbar-brand">Squared Admin</span>
</nav>

<div id="sidebar" >
    <ul class="nav flex-column">
        <li class="nav-item"><a href="dashboard.php" class="nav-link text-white"><i class="bi bi-house-door"></i>Dashboard</a></li>
        <li class="nav-item"><a href="students.php" class="nav-link text-white"><i class="bi bi-people"></i>Students</a></li>
        <li class="nav-item"><a href="announcements.php" class="nav-link text-white"><i class="bi bi-megaphone"></i>Announcements</a></li>
        <li class="nav-item"><a href="php/logout.php" class="nav-link text-white"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
    </ul>
</div>

<div id="content">

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
                            <img src="../qrcodes/<?= htmlspecialchars($row['qr_code']) ?>" alt="QR Code" width="50">
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

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var sidebar = document.getElementById("sidebar");
        var content = document.getElementById("content");
        var toggleBtn = document.getElementById("sidebarToggle");

        // Ensure the sidebar starts collapsed
        sidebar.classList.add("collapsed");
        content.classList.add("expanded");

        toggleBtn.addEventListener("click", function () {
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("expanded");
        });
    });
</script>

</body>
</html>