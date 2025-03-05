<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $avatar = $_POST["avatar"] ?? "";

    if (empty($avatar)) {
        echo "<p style='color: red;'>⚠ No avatar selected. Please go back and choose one.</p>";
        exit;
    }

    $data = "Avatar: $avatar | Timestamp: " . date("Y-m-d H:i:s") . "\n";
    $file = "avatar_selections.txt";

    // Save data to a text file
    file_put_contents($file, $data, FILE_APPEND);

    echo "<p style='color: green;'>✅ Avatar selection saved successfully!</p>";
    echo "<p><a href='test.php'>Go Back</a></p>";
} else {
    echo "<p style='color: red;'>Invalid request.</p>";
}
?>
