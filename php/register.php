<?php
session_start(); // Start the session
require 'config.php'; // Database connection

// Imgur API credentials
$client_id = "21658e3a7ece3ee";  // Replace with your actual Client ID
$client_secret = "6a30353726cf311c0b18a9ef701d9f0da1d25f1d";  // Replace with your actual Client Secret
$refresh_token = "bfff8f67de1fbe1a53ce081166ad228adad72e5a";  // Replace with your actual Refresh Token
$token_file = "access_token.json";  // File to store the access token

// Function to get a valid access token (refresh if expired)
function getAccessToken() {
    global $client_id, $client_secret, $refresh_token, $token_file;

    // Check if access token exists and is still valid
    if (file_exists($token_file)) {
        $token_data = json_decode(file_get_contents($token_file), true);
        if ($token_data && isset($token_data['expires_at']) && $token_data['expires_at'] > time()) {
            return $token_data['access_token'];
        }
    }

    // Refresh the token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.imgur.com/oauth2/token");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "refresh_token" => $refresh_token,
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "grant_type" => "refresh_token"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($response, true);

    if (isset($response_data['access_token'])) {
        // Save new token
        $response_data['expires_at'] = time() + $response_data['expires_in'];
        file_put_contents($token_file, json_encode($response_data, JSON_PRETTY_PRINT));
        return $response_data['access_token'];
    }

    die("Error: Could not refresh access token.");
}

// Get a valid access token
$access_token = getAccessToken();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'] ?? '';
    $last_name = $_POST['last_name'];
    $suffix = $_POST['suffix'] ?? '';
    $sex = $_POST['sex'];
    $avatar = $_POST['avatar'];
    $program = $_POST['program'];
    $course = $_POST['course'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // QR Code API URL (use student ID as the image content)
    $qr_api_url = "https://quickchart.io/qr?text=" . urlencode($student_id) . "&size=1000";

    // Fetch the QR code image
    $qr_image = file_get_contents($qr_api_url);
    if (!$qr_image) {
        $_SESSION['modal_message'] = "❌ Error generating QR code! Please try again.";
        $_SESSION['modal_type'] = "danger";
        header("Location: ../index.php");
        exit();
    }

    // Upload the QR code to Imgur
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.imgur.com/3/upload");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "image" => base64_encode($qr_image),
        "type" => "base64",
        "title" => "QR Code for " . $student_id,
        "description" => "Generated QR Code for student ID " . $student_id
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        $_SESSION['modal_message'] = "❌ Error uploading QR code to Imgur! Please try again.";
        $_SESSION['modal_type'] = "danger";
        header("Location: ../index.php");
        exit();
    }

    // Decode Imgur API response
    $response_data = json_decode($response, true);
    if (!$response_data['success']) {
        $_SESSION['modal_message'] = "❌ Imgur upload failed! Please try again.";
        $_SESSION['modal_type'] = "danger";
        header("Location: ../index.php");
        exit();
    }

    // Extract the Imgur image ID from the link (remove "https://i.imgur.com/" and ".png")
    $imgur_link = $response_data['data']['link'];
    $imgur_filename = str_replace(["https://i.imgur.com/", ".png"], "", $imgur_link);

    // Insert into Database (saving only the Imgur image ID)
    $sql = "INSERT INTO students (student_id, first_name, middle_name, last_name, suffix, sex, avatar, program, course, email, password_hash, qr_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $student_id, $first_name, $middle_name, $last_name, $suffix, $sex, $avatar, $program, $course, $email, $password, $imgur_filename);

    try {
        if ($stmt->execute()) {
            $_SESSION['modal_message'] = "🎉 Registration Successful! Your account has been registered.";
            $_SESSION['modal_type'] = "success";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Error code for duplicate entry
            $_SESSION['modal_message'] = "⚠️ Duplicate Entry! The Student ID <b>$student_id</b> is already registered.";
            $_SESSION['modal_type'] = "warning";
        } else {
            $_SESSION['modal_message'] = "❌ Registration Failed! Something went wrong.<br>" . $e->getMessage();
            $_SESSION['modal_type'] = "danger";
        }
    }

    $stmt->close();
    $conn->close();

    // Redirect back to index.php
    header("Location: ../index.php");
    exit();
}
?>
