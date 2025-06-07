<?php
include '../../functions/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id']);

    // Check if exam result already exists for this application
    $check_query = "SELECT result_id FROM tbl_exam_results WHERE application_id = $application_id";
    $check_result = mysqli_query($mycon, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: dashboard.php?msg=already_marked");
        exit;
    }

    // Insert new exam result with score 0 (default) and use current timestamp
    $insert_query = "INSERT INTO tbl_exam_results (application_id, score) VALUES ($application_id, 0)";
    if (mysqli_query($mycon, $insert_query)) {
        header("Location: dashboard.php?msg=marked_taken");
        exit;
    } else {
        die("Failed to mark as taken: " . mysqli_error($mycon));
    }
}
?>

