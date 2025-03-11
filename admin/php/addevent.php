<?php
require 'config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = trim($_POST["event_name"]);
    $event_date = trim($_POST["event_date"]);
    $start_time = trim($_POST["start_time"]);
    $end_time = trim($_POST["end_time"]);

    if (empty($event_name) || empty($event_date) || empty($start_time) || empty($end_time)) {
        // Redirect back with an error message
        header("Location: ../event.php?error=All fields are required!");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, start_time, end_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $event_name, $event_date, $start_time, $end_time);

        if ($stmt->execute()) {
            // Redirect back with success message
            header("Location: ../event.php?success=Event added successfully!");
        } else {
            // Redirect back with error message
            header("Location: ../event.php?error=Failed to add event.");
        }

        $stmt->close();
    }
} else {
    // If accessed directly, redirect to events page
    header("Location: ../event.php");
}
?>
