<?php
include_once("../../functions/functions.php");

header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila'); 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

if (isset($_POST['id'], $_POST['exam_date'], $_POST['exam_site'])) {
    $id = intval($_POST['id']);
    $examDateInput = trim($_POST['exam_date']);
    $examSite = trim($_POST['exam_site']);

    if (empty($examSite)) {
        echo json_encode(['success' => false, 'message' => 'Exam site cannot be empty.']);
        exit;
    }

    $timestamp = strtotime($examDateInput);
    if ($timestamp === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
        exit;
    }

    // Convert to full datetime format for DB storage
    $examDate = date('Y-m-d H:i:s', $timestamp);

    // Check if applicant exists
    $checkQuery = "SELECT id FROM tbl_applications WHERE id = ?";
    if (!$checkStmt = mysqli_prepare($mycon, $checkQuery)) {
        echo json_encode(['success' => false, 'message' => 'Database error on prepare check.']);
        exit;
    }
    mysqli_stmt_bind_param($checkStmt, 'i', $id);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) === 0) {
        mysqli_stmt_close($checkStmt);
        echo json_encode(['success' => false, 'message' => 'Applicant not found.']);
        exit;
    }
    mysqli_stmt_close($checkStmt);

    // Update exam date and site
    $updateQuery = "UPDATE tbl_applications SET exam_date = ?, exam_site = ? WHERE id = ?";
    if (!$updateStmt = mysqli_prepare($mycon, $updateQuery)) {
        echo json_encode(['success' => false, 'message' => 'Database error on prepare update.']);
        exit;
    }
    mysqli_stmt_bind_param($updateStmt, 'ssi', $examDate, $examSite, $id);

    if (mysqli_stmt_execute($updateStmt)) {
        mysqli_stmt_close($updateStmt);
        echo json_encode([
            'success' => true,
            'message' => 'Exam date and site set successfully.',
            'exam_date' => $examDate,
            'exam_site' => $examSite
        ]);
    } else {
        mysqli_stmt_close($updateStmt);
        echo json_encode(['success' => false, 'message' => 'Failed to set exam date and site.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required data.']);
}
