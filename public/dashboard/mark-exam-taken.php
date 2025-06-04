<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your DB connection file (adjust path as needed)
include '../../functions/db_connect.php';

if (!isset($mycon) || !$mycon) {
    die("Database connection failed.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id']);
    if ($application_id > 0) {
        // Check if exam already marked taken
        $check = mysqli_query($mycon, "SELECT * FROM tbl_exam_results WHERE application_id = $application_id");
        if (mysqli_num_rows($check) > 0) {
            // Already marked - redirect back with message (optional)
            header("Location: approved-applications.php?msg=already_marked");
            exit;
        }

        // Insert exam taken record (score and remarks null initially)
        $insert = mysqli_query($mycon, "INSERT INTO tbl_exam_results (application_id, score, remarks, taken_at) VALUES ($application_id, NULL, NULL, NOW())");

        if ($insert) {
            // Redirect back to approved applications so button changes
            header("Location: approved-applications.php?msg=marked_taken");
            exit;
        } else {
            die("Error marking exam taken: " . mysqli_error($mycon));
        }
    } else {
        die("Invalid application ID.");
    }
} else {
    die("Invalid request.");
}
