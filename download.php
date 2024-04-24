<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "ewsd";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_GET['id'])) {
    $coursework_id = $_GET['id'];

    // Retrieve file path from the database based on coursework ID
    $sql = "SELECT file_path FROM coursework WHERE id = $coursework_id";
    $result = mysqli_query($data, $sql);
    $row = mysqli_fetch_assoc($result);
    $file_path = $row['file_path'];

    // Check if file exists
    if (file_exists($file_path)) {
        // Set headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Content-Length: ' . filesize($file_path));

        // Read the file and output it to the browser
        readfile($file_path);
        exit;
    } else {
        // File not found, redirect back to the page where download was initiated
        header("Location: student_coursework.php");
        exit;
    }
} else {
    // If coursework ID is not provided, redirect back to the page where download was initiated
    header("Location: student_coursework.php");
    exit;
}
?>
