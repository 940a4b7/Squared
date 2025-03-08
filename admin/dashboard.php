<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'php/config.php'; // Include database connection

// Fetch total students
$student_query = "SELECT COUNT(*) as total FROM students";
$student_result = mysqli_query($conn, $student_query);
$student_count = mysqli_fetch_assoc($student_result)['total'];

// Fetch gender distribution
$gender_query = "SELECT sex, COUNT(*) as count FROM students GROUP BY sex";
$gender_result = mysqli_query($conn, $gender_query);
$gender_counts = ['Male' => 0, 'Female' => 0];

while ($row = mysqli_fetch_assoc($gender_result)) {
    $gender_counts[$row['sex']] = $row['count'];
}

// Fetch student counts per program
$program_gender_query = "
    SELECT program, 
           COUNT(*) AS total_count,
           SUM(CASE WHEN sex = 'Male' THEN 1 ELSE 0 END) AS male_count,
           SUM(CASE WHEN sex = 'Female' THEN 1 ELSE 0 END) AS female_count
    FROM students 
    WHERE program IN ('ITE', 'CELA', 'CBA', 'HME', 'CJE') 
    GROUP BY program
";

$program_gender_result = mysqli_query($conn, $program_gender_query);

$program_gender_counts = [
    'ITE' => ['Total' => 0, 'Male' => 0, 'Female' => 0],
    'CELA' => ['Total' => 0, 'Male' => 0, 'Female' => 0],
    'CBA' => ['Total' => 0, 'Male' => 0, 'Female' => 0],
    'HME' => ['Total' => 0, 'Male' => 0, 'Female' => 0],
    'CJE' => ['Total' => 0, 'Male' => 0, 'Female' => 0]
];

while ($row = mysqli_fetch_assoc($program_gender_result)) {
    $program_gender_counts[$row['program']]['Total'] = $row['total_count'];
    $program_gender_counts[$row['program']]['Male'] = $row['male_count'];
    $program_gender_counts[$row['program']]['Female'] = $row['female_count'];
}


// Fetch student counts per course
$course_gender_query = "
    SELECT course, 
           COUNT(*) AS total_count,
           SUM(CASE WHEN sex = 'Male' THEN 1 ELSE 0 END) AS male_count,
           SUM(CASE WHEN sex = 'Female' THEN 1 ELSE 0 END) AS female_count
    FROM students 
    GROUP BY course
";

$course_gender_result = mysqli_query($conn, $course_gender_query);

$course_gender_counts = [];

while ($row = mysqli_fetch_assoc($course_gender_result)) {
    $course_gender_counts[$row['course']] = [
        'Total' => $row['total_count'],
        'Male' => $row['male_count'],
        'Female' => $row['female_count']
    ];
}


// Define Programs and Courses
$program_courses = [
    "ITE" => ["Bachelor of Science in Information Technology"],
    "CELA" => [
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
    "CBA" => [
        "Bachelor of Science in Business Administration Major in Financial Management",
        "Bachelor of Science in Business Administration Major in Human Resource Management",
        "Bachelor of Science in Business Administration Major in Marketing Management"
    ],
    "HME" => ["Bachelor of Science in Hospitality Management"],
    "CJE" => ["Bachelor of Science in Criminology"]
];

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
    <h2 class="tt">Squared Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card bg1 text-white p-3">
                <h5><i class="bi bi-people"></i> Total Students</h5>
                <h3 class="nu"><?= $student_count; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg2 text-white p-3">
                <h5><i class="bi bi-gender-male"></i> Male</h5>
                <h3 class="nu"><?= $gender_counts['Male']; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg3 p-3">
                <h5><i class="bi bi-gender-female"></i> Female</h5>
                <h3 class="nu"><?= $gender_counts['Female']; ?></h3>
            </div>
        </div>
    </div>

    <div class="chart-container mt-4">
        <h3 class="tt mt-4">Students in the Programs</h3>
        <canvas id="programGenderChart"></canvas>
    </div>
    

    <h3 class="tt mt-4" class="mt-4">Students per Course</h3>
    <table class="table table-striped">
    <thead>
        <tr>
            <th >Program</th>
            <th>Course</th>
            <th>Male</th>
            <th>Female</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($program_courses as $program => $courses) : ?>
            <?php foreach ($courses as $course) : ?>
                <tr>
                    <td><?= $program; ?></td>
                    <td><?= $course; ?></td>
                    <td><?= isset($course_gender_counts[$course]['Male']) ? $course_gender_counts[$course]['Male'] : 0; ?></td>
                    <td><?= isset($course_gender_counts[$course]['Female']) ? $course_gender_counts[$course]['Female'] : 0; ?></td>
                    <td><?= isset($course_gender_counts[$course]['Total']) ? $course_gender_counts[$course]['Total'] : 0; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

    <script>
    var ctx = document.getElementById("programGenderChart").getContext("2d");
    var programGenderChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["ITE", "CELA", "CBA", "HME", "CJE"],
            datasets: [
                {
                    label: "Total",
                    data: [<?= $program_gender_counts['ITE']['Total']; ?>, <?= $program_gender_counts['CELA']['Total']; ?>, <?= $program_gender_counts['CBA']['Total']; ?>, <?= $program_gender_counts['HME']['Total']; ?>, <?= $program_gender_counts['CJE']['Total']; ?>],
                    backgroundColor: "#004b23" 
                },
                {
                    label: "Male",
                    data: [<?= $program_gender_counts['ITE']['Male']; ?>, <?= $program_gender_counts['CELA']['Male']; ?>, <?= $program_gender_counts['CBA']['Male']; ?>, <?= $program_gender_counts['HME']['Male']; ?>, <?= $program_gender_counts['CJE']['Male']; ?>],
                    backgroundColor: "#38b000" 
                },
                {
                    label: "Female",
                    data: [<?= $program_gender_counts['ITE']['Female']; ?>, <?= $program_gender_counts['CELA']['Female']; ?>, <?= $program_gender_counts['CBA']['Female']; ?>, <?= $program_gender_counts['HME']['Female']; ?>, <?= $program_gender_counts['CJE']['Female']; ?>],
                    backgroundColor: "#ccff33" 
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
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
