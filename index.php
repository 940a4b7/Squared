<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Squared - QR Attendance</title>
    <link rel="icon" type="image/png" href="images/Squared_Logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="images/Squared_Logo.png" alt="Squared Logo"> Squared
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#loginModal">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#registerModal">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="hero">
        <h1>Effortless QR-Based Attendance</h1>
        <p>Track and manage attendance with ease using Squared's QR scanning system.</p>
        <button class="btn btn-hero" data-bs-toggle="modal" data-bs-target="#registerModal">Get Started</button>
    </div>

    <!-- Image Tiles Section -->
    <div class="container avatars text-center">
        <h1>Squared Avatars</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow">
                    <img src="avatars/JOY.png" class="card-img-top" alt="Image 1">
                    <div class="card-body text-center">
                        <h5>JOY</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <img src="avatars/SEVI.png" class="card-img-top" alt="Image 2">
                    <div class="card-body text-center">
                        <h5>SEVI</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <img src="avatars/SAMANTHA.png" class="card-img-top" alt="Image 3">
                    <div class="card-body text-center">
                        <h5>SAMANTHA</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow">
                    <img src="avatars/ZEKE.png" class="card-img-top" alt="Image 4">
                    <div class="card-body text-center">
                        <h5>ZEKE</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container features">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow">
                    <h5>Scan QR Codes</h5>
                    <p>Quick and secure attendance tracking.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <h5>View Reports</h5>
                    <p>Detailed analytics and attendance reports.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow">
                    <h5>Manage Users</h5>
                    <p>Easily manage users and their attendance records.</p>
                </div>
            </div>
        </div>
    </div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Student Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" method="POST" action="php/login.php">
                    <div class="mb-3">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="studentId" name="student_id"
                               autocomplete="username" required>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                   autocomplete="current-password" required>
                            <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('password', 'togglePasswordIcon')">
                                <i id="togglePasswordIcon" class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Remember Me & Forgot Password -->
                    <div id="loginMessage" class="text-danger mb-3"></div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember Me</label>
                        </div>
                        <a href="forgotpass.php" class="text-primary">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" action="php/register.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Choose an Avatar <span class="text-danger"></span></label>
                            <div class="avatar-option">
                                <label>
                                    <input type="radio" name="avatar" value="JOY" hidden required>
                                    <img src="avatars/JOY.png" alt="JOY">
                                    <div class="text-center">JOY</div>
                                </label>
                                <label>
                                    <input type="radio" name="avatar" value="SEVI" hidden required>
                                    <img src="avatars/SEVI.png" alt="SEVI">
                                    <div class="text-center">SEVI</div>
                                </label>
                                <label>
                                    <input type="radio" name="avatar" value="SAMANTHA" hidden required>
                                    <img src="avatars/SAMANTHA.png" alt="SAMANTHA">
                                    <div class="text-center">SAMANTHA</div>
                                </label>
                                <label>
                                    <input type="radio" name="avatar" value="ZEKE" hidden required>
                                    <img src="avatars/ZEKE.png" alt="ZEKE">
                                    <div class="text-center">ZEKE</div>
                                </label>
                            </div>
                            <p id="avatarError" class="text-danger" style="display: none;"></p>
                        </div>
                        <div class="mb-3">
                            <label for="registerStudentId" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="registerStudentId" name="student_id" required>
                        </div>
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
                        <div class="mb-3">
                            <label for="sex" class="form-label">Sex</label>
                            <select class="form-control" id="sex" name="sex" required>
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="program" class="form-label">Program</label>
                            <select class="form-control" id="program" name="program" onchange="updateCourses()"
                                required>
                                <option value="">Select Program</option>
                                <option value="ITE">Information Technology Education - ITE</option>
                                <option value="CELA">College of Education and Liberal Arts - CELA</option>
                                <option value="CBA">College of Business Administration - CBA</option>
                                <option value="HME">Hospitality Management Education - HME</option>
                                <option value="CJE">Criminal Justice Education - CJE</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <select class="form-control" id="course" name="course" required>
                                <option value="">Select Course</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gmail" class="form-label">Gmail</label>
                            <input type="email" class="form-control" id="gmail" name="email" required>
                            <p id="emailError" class="text-danger" style="display: none;">⚠ Please enter a valid email.
                            </p>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="registerPassword" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="registerConfirmps" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="registerConfirmps" name="confirmps"
                                    required>
                                <button class="btn btn-outline-secondary" type="button"
                                    onclick="togglePassword('registerConfirmps', 'togglePasswordIcon')">
                                    <i id="togglePasswordIcon" class="bi bi-eye"></i>
                                </button>
                            </div>
                            <p id="matchMessage" class="text-danger" style="display: none;"></p>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center"> <!-- Center text in modal -->
                <div class="modal-header d-flex flex-column align-items-center border-0">
                    <h5 class="modal-title w-100" id="messageModalLabel">Notification</h5>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                <div class="modal-footer d-flex justify-content-center border-0">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>


    <script src="js/pass.js"></script>
    <script src="js/avatarerror.js"></script>
    <script>
        document.getElementById("loginForm").addEventListener("submit", function (event) {
            event.preventDefault();

            let studentId = document.getElementById("studentId").value.trim();
            let password = document.getElementById("password").value.trim();
            let loginMessage = document.getElementById("loginMessage");

            fetch("php/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `student_id=${encodeURIComponent(studentId)}&password=${encodeURIComponent(password)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        window.location.href = "home.php"; // Redirect on successful login
                    } else {
                        loginMessage.textContent = data.message;
                    }
                })
                .catch(error => {
                    loginMessage.textContent = "An error occurred. Please try again.";
                });
        });
    </script>
    <script>
        fetch('php/session.php')
            .then(response => response.json())
            .then(data => {
                if (data.show_modal) {
                    document.getElementById("modalMessage").innerHTML = data.message;
                    document.getElementById("messageModal").classList.add(data.type === "success" ? "text-success" : "text-danger");
                    var messageModal = new bootstrap.Modal(document.getElementById("messageModal"));
                    messageModal.show();
                }
            });

        document.querySelector("form").addEventListener("submit", function (event) {
            const avatarSelected = document.querySelector('input[name="avatar"]:checked');
            if (!avatarSelected) {
                document.getElementById("avatarError").style.display = "block";
                event.preventDefault(); // Prevent form submission
            }
        });

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
            const program = document.getElementById("program").value;
            const courseDropdown = document.getElementById("course");
            courseDropdown.innerHTML = "<option value=''>Select Course</option>"; // Reset options
            if (program && courses[program]) {
                courses[program].forEach(course => {
                    const option = document.createElement("option");
                    option.text = course;
                    courseDropdown.add(option);
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>