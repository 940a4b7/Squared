<?php
session_start();
require 'php/config.php'; // Include database connection

// Fetch announcements from the database
$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="icon" type="image/png" href="images/Squared_Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/notify.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="images/Squared_Logo.png" alt="Squared Logo"> Squared Announcements
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="qrcard.php">QR-Card</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notify.php">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="php/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<!-- Announcements Section -->
<div class="container mt-5 pt-4">
        <h2 class="mb-4 text-success text-center">📢 Announcements 📢</h2>

        <!-- Display Announcements -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="cards mb-3 position-relative">
    <div class="card-bodys">
        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <small class="text-muted">Posted on: <?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></small>
    </div>

    <!-- Small logo positioned outside lower-left -->
    <img src="images/Squared_Logo.png" alt="Logo" class="announcement-logo">
</div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">No announcements available.</div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>