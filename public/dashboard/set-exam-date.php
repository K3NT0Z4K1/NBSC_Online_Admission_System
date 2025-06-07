<?php
include_once("../../functions/functions.php");

header('Content-Type: application/json');

if (isset($_POST['id'], $_POST['exam_date'], $_POST['exam_site'])) {
    $id = intval($_POST['id']);
    $examDateInput = trim($_POST['exam_date']);
    $examSite = trim($_POST['exam_site']);

    // Convert input date to timestamp to validate and reformat
    $timestamp = strtotime($examDateInput);
    if ($timestamp === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
        exit;
    }

    // Format date to YYYY-MM-DD for the database
    $examDate = date('Y-m-d', $timestamp);

    // Optional: double-check the format after conversion
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $examDate)) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format after conversion.']);
        exit;
    }

    // Check if applicant exists
    $checkQuery = "SELECT id FROM tbl_applications WHERE id = ?";
    $checkStmt = mysqli_prepare($mycon, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, 'i', $id);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) === 0) {
        echo json_encode(['success' => false, 'message' => 'Applicant not found.']);
        exit;
    }

    // Update exam date and site
    $updateQuery = "UPDATE tbl_applications SET exam_date = ?, exam_site = ? WHERE id = ?";
    $updateStmt = mysqli_prepare($mycon, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, 'ssi', $examDate, $examSite, $id);

    if (mysqli_stmt_execute($updateStmt)) {
        echo json_encode(['success' => true, 'message' => 'Exam date and site set successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to set exam date and site.']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
