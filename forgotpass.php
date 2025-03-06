<?php
include 'php/config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $email = trim($_POST['email']);

    // Check if the student ID and email exist in the database
    $sql = "SELECT student_id FROM students WHERE student_id = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $student_id, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Redirect user to the password reset page
        header("Location: php/resetpass.php?student_id=$student_id");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Invalid Student ID or Email.</div>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h3>Forgot Password</h3>
                    </div>
                    <div class="card-body">
                        <?php echo $message; ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Student ID</label>
                                <input type="text" class="form-control" name="student_id" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Retrieve Password</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
