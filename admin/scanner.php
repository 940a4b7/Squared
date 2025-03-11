<?php
session_start();
require 'php/config.php';

// Fetch all scanners with full name (including middle name and suffix)
$query = "SELECT es.id, es.student_id, 
                 s.first_name, 
                 s.middle_name, 
                 s.last_name, 
                 s.suffix, 
                 es.status 
          FROM event_scanners es
          JOIN students s ON es.student_id = s.student_id
          ORDER BY es.status DESC, s.last_name ASC";
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

    <div id="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a href="dashboard.php" class="nav-link text-white"><i
                        class="bi bi-house-door"></i>Dashboard</a></li>
            <li class="nav-item"><a href="students.php" class="nav-link text-white"><i
                        class="bi bi-people"></i>Students</a></li>
            <li class="nav-item"><a href="scanner.php" class="nav-link text-white"><i
                        class="bi bi-people"></i>Scanners</a></li>
            <li class="nav-item"><a href="event.php" class="nav-link text-white"><i
                        class="bi bi-calendar-event"></i>Events</a></li>
            <li class="nav-item"><a href="announcement.php" class="nav-link text-white"><i
                        class="bi bi-megaphone"></i>Announcements</a></li>
            <li class="nav-item"><a href="php/logout.php" class="nav-link text-white"><i
                        class="bi bi-box-arrow-right"></i>Logout</a></li>
        </ul>
    </div>

    <div id="content">
        <h2 class="tt">Authorize to Scan QR</h2>

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScanner">
                <i class="bi bi-plus-lg"></i> Add Authorize Student</button>
        </div>

        <!-- Table of Current Scanners -->
        <table class="table">
        <thead class="table-success table-striped">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($row['student_id']); ?>
                    </td>
                    <td>
                        <?php 
        $full_name = $row['first_name'];
        if (!empty($row['middle_name'])) {
            $full_name .= ' ' . $row['middle_name'];
        }
        $full_name .= ' ' . $row['last_name'];
        if (!empty($row['suffix'])) {
            $full_name .= ' ' . $row['suffix'];
        }
        echo htmlspecialchars($full_name);
    ?>
                    </td>

                    <td>
                        <span class="badge <?php echo ($row['status'] == 'Allow') ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <!-- Toggle Allow/Deny -->
                        <form action="php/scannerstatus.php" method="POST" style="display:inline;">
                            <input type="hidden" name="scanner_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="new_status"
                                value="<?php echo ($row['status'] == 'Allow') ? 'Deny' : 'Allow'; ?>">
                            <button type="submit"
                                class="btn btn-<?php echo ($row['status'] == 'Allow') ? 'danger' : 'success'; ?> btn-sm">
                                <i class="bi bi-toggle-<?php echo ($row['status'] == 'Allow') ? 'off' : 'on'; ?>"></i>
                            </button>
                        </form>

                        <!-- Delete Scanner Button -->
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteScannerModal<?php echo $row['id']; ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteScannerModal<?php echo $row['id']; ?>" tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to remove
                                    <strong>
                                        <?php $full_name = $row['first_name'];
                                        if (!empty($row['middle_name'])) {
                                            $full_name .= ' ' . $row['middle_name'];
                                        }
                                        $full_name .= ' ' . $row['last_name'];
                                        if (!empty($row['suffix'])) {
                                            $full_name .= ' ' . $row['suffix'];
                                        }
                                        echo htmlspecialchars($full_name);?>
                                    </strong> as a scanner?</p>
                                <form action="php/deletescanner.php" method="POST">
                                    <input type="hidden" name="scanner_id" value="<?php echo $row['id']; ?>">
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

    <!-- Add Scanner Modal -->
    <div class="modal fade" id="addScanner" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="php/addscanner.php" method="POST">
                        <div id="scanner-container">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control scanner-id" name="scanner_ids[]"
                                    placeholder="Enter Student ID" required>
                                <button type="button" class="btn btn-success add-scanner"><i
                                        class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Scanners</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Adding More Scanner Fields -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Add Scanner ID Input Fields
            $(document).on("click", ".add-scanner", function () {
                let scannerField = `
            <div class="input-group mb-2">
                <input type="text" class="form-control scanner-id" name="scanner_ids[]" placeholder="Enter Student ID" required>
                <button type="button" class="btn btn-danger remove-scanner"><i class="bi bi-x"></i></button>
            </div>`;
                $("#scanner-container").append(scannerField);
            });

            // Remove Scanner ID Input Field
            $(document).on("click", ".remove-scanner", function () {
                $(this).closest(".input-group").remove();
            });
        });
    </script>
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