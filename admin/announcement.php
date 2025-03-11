<?php
session_start();
require 'php/config.php'; // Database connection

// Fetch announcements from the database
$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Squared Admin</title>
    <link rel="icon" type="image/png" href="../images/Squared_Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/avatar.css">
    <link rel="stylesheet" href="../css/notify.css">
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
        <li class="nav-item"><a href="scanner.php" class="nav-link text-white"><i class="bi bi-people"></i>Scanners</a></li>
        <li class="nav-item"><a href="event.php" class="nav-link text-white"><i class="bi bi-calendar-event"></i>Events</a></li>
        <li class="nav-item"><a href="announcement.php" class="nav-link text-white"><i class="bi bi-megaphone"></i>Announcements</a></li>
        <li class="nav-item"><a href="php/logout.php" class="nav-link text-white"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
    </ul>
</div>

<div id="content">

    <h2 class="tt">Announcement</h2>

    <div class="d-flex justify-content-center">
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">Add Announcement</button>
</div>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="cards mb-3 position-relative p-3">
            <div class="card-bodys">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                
                <!-- Flexbox to align "Posted on" and delete icon -->
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">Posted on: <?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></small>
                    
                    <!-- Delete Icon (No Button Container, Just Icon in Red) -->
                    <i class="bi bi-trash text-danger delete-btn" role="button" data-bs-toggle="modal" 
                       data-bs-target="#deleteModal" data-id="<?php echo $row['id']; ?>" 
                       data-title="<?php echo htmlspecialchars($row['title']); ?>" style="font-size: 14px;"></i>
                </div>
            </div>

            <!-- Small logo positioned outside lower-left -->
            <img src="../images/Squared_Logo.png" alt="Logo" class="announcement-logo">
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="alert alert-warning text-center">No announcement available.</div>
<?php endif; ?>

    </div>

 <!-- Add Announcement Modal -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAnnouncementModalLabel">Add Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="php/addnotify.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content:</label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Post Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center"> <!-- Center text -->
            <div class="modal-header d-flex flex-column align-items-center"> <!-- Flexbox for better centering -->
                <h5 class="modal-title text-center">
                    Are you sure you want to delete "<span id="deleteTitle"></span>"?
                </h5>
            </div>
            <div class="modal-footer d-flex justify-content-center"> <!-- Center buttons -->
                <form action="php/deletenotify.php" method="POST">
                    <input type="hidden" name="delete_id" id="deleteId">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>



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

<script>
document.addEventListener("DOMContentLoaded", function () {
    var deleteModal = document.getElementById("deleteModal");
    var deleteTitle = document.getElementById("deleteTitle");
    var deleteId = document.getElementById("deleteId");

    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            var id = this.getAttribute("data-id");
            var title = this.getAttribute("data-title");

            deleteTitle.textContent = title;
            deleteId.value = id;
        });
    });
});
</script>


</body>
</html>