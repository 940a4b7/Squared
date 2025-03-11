<?php
session_start();
require 'php/config.php'; // Database connection

// Fetch events from the database
$query = "SELECT * FROM events ORDER BY event_date DESC";
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
    <style>
        /* Green theme */
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        /* Center modal vertically & horizontally */
        .modal-dialog-centered {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
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
    <h2 class="tt">Events</h2>

    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="bi bi-plus-lg"></i> Add New Event
        </button>
    </div>
 
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="eventTable">
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['event_name']); ?></td>
            <td><?php echo htmlspecialchars($row['event_date']); ?></td>
            <td><?php echo htmlspecialchars($row['start_time']); ?></td>
            <td><?php echo htmlspecialchars($row['end_time']); ?></td>
            <td>
                <span class="badge <?php echo ($row['status'] == 'Active') ? 'bg-success' : 'bg-secondary'; ?>">
                    <?php echo htmlspecialchars($row['status']); ?>
                </span>
            </td>
            <td>
                <!-- Activate/Deactivate Button -->
                <a href="php/eventstatus.php?id=<?php echo $row['event_id']; ?>&status=<?php echo ($row['status'] == 'Active') ? 'Inactive' : 'Active'; ?>" 
                   class="btn btn-<?php echo ($row['status'] == 'Active') ? 'danger' : 'success'; ?> btn-sm">
                    <i class="bi bi-toggle-<?php echo ($row['status'] == 'Active') ? 'off' : 'on'; ?>"></i>
                </a>


                <!-- Edit Button (Opens Modal) -->
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editEventModal<?php echo $row['event_id']; ?>">
                    <i class="bi bi-pencil-square"></i>
                </button>

                <!-- Delete Button (Opens Confirmation Modal) -->
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteEventModal<?php echo $row['event_id']; ?>">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>

        <!-- Edit Event Modal -->
        <div class="modal fade" id="editEventModal<?php echo $row['event_id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="php/edit_event.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Event Name:</label>
                                <input type="text" class="form-control" name="event_name" value="<?php echo htmlspecialchars($row['event_name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Event Date:</label>
                                <input type="date" class="form-control" name="event_date" value="<?php echo htmlspecialchars($row['event_date']); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Time:</label>
                                    <input type="time" class="form-control" name="start_time" value="<?php echo htmlspecialchars($row['start_time']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Time:</label>
                                    <input type="time" class="form-control" name="end_time" value="<?php echo htmlspecialchars($row['end_time']); ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteEventModal<?php echo $row['event_id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($row['event_name']); ?></strong>?</p>
                        <form action="php/delete_event.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                            <button type="submit" class="btn btn-danger w-100">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile; ?>
</tbody>


    </table>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEventForm" action="php/addevent.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Event Name:</label>
                        <input type="text" class="form-control" name="event_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Event Date:</label>
                        <input type="date" class="form-control" name="event_date" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time:</label>
                            <input type="time" class="form-control" name="start_time" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time:</label>
                            <input type="time" class="form-control" name="end_time" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Event</button>
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


</body>
</html>