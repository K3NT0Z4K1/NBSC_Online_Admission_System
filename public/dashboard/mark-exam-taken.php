<?php
include '../../functions/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id']);

    // Check if already marked
    $check_query = "SELECT * FROM tbl_exam_results WHERE application_id = $application_id";
    $check_result = mysqli_query($mycon, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: dashboard.php?msg=already_marked");
        exit;
    }

    // Insert into exam results
    $insert_query = "INSERT INTO tbl_exam_results (application_id, score, remarks) VALUES ($application_id, 0, NULL)";
    if (mysqli_query($mycon, $insert_query)) {
        header("Location: dashboard.php?msg=marked_taken");
        exit;
    } else {
        die("Failed to mark as taken: " . mysqli_error($mycon));
    }
}
?>
