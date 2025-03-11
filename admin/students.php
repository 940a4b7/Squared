<?php
session_start();
require 'php/config.php'; // Database connection

$search = $_GET['search'] ?? '';

$sql = "SELECT student_id, first_name, middle_name, last_name, suffix, sex, avatar, program, course, email, created_at, qr_code 
        FROM students 
        WHERE student_id LIKE ? 
        OR first_name LIKE ? 
        OR middle_name LIKE ? 
        OR last_name LIKE ? 
        OR suffix LIKE ? 
        OR sex LIKE ? 
        OR program LIKE ? 
        OR course LIKE ? 
        OR email LIKE ? 
        OR created_at LIKE ?";

$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("ssssssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
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
    <h2 class="tt">Student Records</h2>

    <!-- Search Bar -->
    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" placeholder="Search Students" class="form-control" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-success ms-2">Search</button>
    </form>

    <!-- Table Wrapper (Ensures Table Stays Inside Content) -->
    <div class="table-responsive"> <!-- Added responsive wrapper -->
        <table class="table">
            <thead class="table-success table-striped">
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
                    <th>Edit</th>
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
                            <td>
                                <img src="../avatars/<?= htmlspecialchars($row['avatar']) ?>.png" alt="Avatar" width="50" height="50">
                            </td>
                            <td><?= htmlspecialchars($row['program']) ?></td>
                            <td><?= htmlspecialchars($row['course']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <img src="https://i.imgur.com/<?= htmlspecialchars($row['qr_code']) ?>.png" alt="QR Code" width="50">
                            </td>
                            <td>
                                <i class="bi bi-pencil-square text-warning edit-profile-btn" role="button" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#profileModal"
                                    data-id="<?= htmlspecialchars($row['student_id']) ?>"
                                    data-first="<?= htmlspecialchars($row['first_name']) ?>"
                                    data-middle="<?= htmlspecialchars($row['middle_name']) ?>"
                                    data-last="<?= htmlspecialchars($row['last_name']) ?>"
                                    data-suffix="<?= htmlspecialchars($row['suffix']) ?>"
                                    data-sex="<?= htmlspecialchars($row['sex']) ?>"
                                    data-program="<?= htmlspecialchars($row['program']) ?>"
                                    data-course="<?= htmlspecialchars($row['course']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-avatar="<?= htmlspecialchars($row['avatar']) ?>"
                                    style="font-size: 18px;"></i>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="text-center">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div> <!-- End of Table Wrapper -->
</div> <!-- End of #content -->

<?php if (isset($_SESSION['message'])): ?>
    <!-- Success/Error Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content d-flex justify-content-center align-items-center text-center p-3"
                style="background: none; border: none; box-shadow: none;">
                <div class="alert alert-<?= $_SESSION['message_type'] ?> text-dark fw-bold mb-0" role="alert">
                    <?= $_SESSION['message'] ?>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto Trigger Modal Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
            messageModal.show();
        });
    </script>

    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>


       <!-- Edit Profile Modal -->
       <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="registerForm" action="php/update.php" method="POST">

                            <!-- Avatar Selection -->
                            <div class="mb-3">
                                <label class="form-label">Choose an Avatar</label>
                                <div class="avatar-option">
                                    <label>
                                        <input type="radio" name="avatar" value="JOY" hidden required>
                                        <img src="../avatars/JOY.png" alt="JOY">
                                        <div class="text-center">JOY</div>
                                    </label>
                                    <label>
                                        <input type="radio" name="avatar" value="SEVI" hidden required>
                                        <img src="../avatars/SEVI.png" alt="SEVI">
                                        <div class="text-center">SEVI</div>
                                    </label>
                                    <label>
                                        <input type="radio" name="avatar" value="SAMANTHA" hidden required>
                                        <img src="../avatars/SAMANTHA.png" alt="SAMANTHA">
                                        <div class="text-center">SAMANTHA</div>
                                    </label>
                                    <label>
                                        <input type="radio" name="avatar" value="ZEKE" hidden required>
                                        <img src="../avatars/ZEKE.png" alt="ZEKE">
                                        <div class="text-center">ZEKE</div>
                                    </label>
                                </div>
                            </div>

                            <!-- Student ID (Read-only) -->
                            <div class="mb-3">
                                <label for="registerStudentId" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="registerStudentId" name="student_id"
                                    readonly>
                            </div>

                            <!-- Personal Information -->
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="middleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middleName" name="middle_name">
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>

                            <!-- Suffix -->
                            <div class="mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <select class="form-control" id="suffix" name="suffix">
                                    <option value="">None</option>
                                    <option value="Jr.">Jr.</option>
                                    <option value="Sr.">Sr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                </select>
                            </div>

                            <!-- Sex -->
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <!-- Program and Course -->
                            <div class="mb-3">
                                <label for="program" class="form-label">Program</label>
                                <select id="program" name="program" class="form-control" onchange="updateCourses()">
                                    <option value="">Select Program</option>
                                    <option value="ITE">Information Technology Education (ITE)</option>
                                    <option value="CELA">College of Education, Liberal Arts (CELA)</option>
                                    <option value="CBA">College of Business Administration (CBA)</option>
                                    <option value="HME">Hospitality Management & Entrepreneurship (HME)</option>
                                    <option value="CJE">College of Criminal Justice Education (CJE)</option>
                                </select>

                            </div>
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select id="course" name="course" class="form-control">
                                    <option value="">Select Course</option>
                                </select>

                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="gmail" class="form-label">Gmail</label>
                                <input type="email" class="form-control" id="gmail" name="email" required>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    let editProfileButtons = document.querySelectorAll(".edit-profile-btn");

    editProfileButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("registerStudentId").value = this.dataset.id;
            document.getElementById("firstName").value = this.dataset.first;
            document.getElementById("middleName").value = this.dataset.middle;
            document.getElementById("lastName").value = this.dataset.last;
            document.getElementById("suffix").value = this.dataset.suffix;
            document.getElementById("sex").value = this.dataset.sex;
            document.getElementById("program").value = this.dataset.program;
            document.getElementById("course").dataset.selected = this.dataset.course;
            document.getElementById("gmail").value = this.dataset.email;

            // Ensure course updates when program is selected
            updateCourses();

            // Auto-select avatar
            let selectedAvatar = this.dataset.avatar;
            document.querySelectorAll("input[name='avatar']").forEach(input => {
                input.checked = (input.value === selectedAvatar);
            });
        });
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    updateCourses(); // Ensure courses match the selected program on page load
});

// Function to update course list based on selected program
function updateCourses() {
    const courses = {
        ITE: ["Bachelor of Science in Information Technology"],
        CELA: [
            "Bachelor of Arts Major in History",
            "Bachelor of Arts Major in Political Science",
            "Bachelor of Elementary Education – Generalist",
            "Bachelor of Special Needs Education",
            "Bachelor of Secondary Education Major in English",
            "Bachelor of Secondary Education Major in Mathematics",
            "Bachelor of Secondary Education Major in Science",
            "Bachelor of Secondary Education Major in Social Studies",
            "Bachelor of Technology and Livelihood Education Major in Home Economics"
        ],
        CBA: [
            "Bachelor of Science in Business Administration Major in Financial Management",
            "Bachelor of Science in Business Administration Major in Human Resource Management",
            "Bachelor of Science in Business Administration Major in Marketing Management"
        ],
        HME: ["Bachelor of Science in Hospitality Management"],
        CJE: ["Bachelor of Science in Criminology"]
    };

    const programDropdown = document.getElementById("program");
    const courseDropdown = document.getElementById("course");

    // Get selected program and existing course value
    const selectedProgram = programDropdown.value;
    const currentCourse = courseDropdown.dataset.selected || "";

    // Clear and add the default "Select Course" option
    courseDropdown.innerHTML = "<option value=''>Select Course</option>";

    if (selectedProgram && courses[selectedProgram]) {
        courses[selectedProgram].forEach(course => {
            const option = document.createElement("option");
            option.text = course;
            option.value = course;
            if (course === currentCourse) {
                option.selected = true; // Auto-select the student's saved course
            }
            courseDropdown.add(option);
        });

        // Auto-select course if only one exists (like in ITE)
        if (courses[selectedProgram].length === 1) {
            courseDropdown.value = courses[selectedProgram][0];
        }
    }
}
</script>


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