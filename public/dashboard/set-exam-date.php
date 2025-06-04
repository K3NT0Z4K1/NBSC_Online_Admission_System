<?php
include_once("../../functions/functions.php");

if (isset($_POST['id']) && isset($_POST['exam_date'])) {
    $id = $_POST['id'];
    $examDate = $_POST['exam_date'];

    $query = "UPDATE tbl_applications SET exam_date = ? WHERE id = ?";
    $stmt = mysqli_prepare($mycon, $query);
    mysqli_stmt_bind_param($stmt, 'si', $examDate, $id);
    if (mysqli_stmt_execute($stmt)) {
        echo "Exam date set successfully!";
    } else {
        echo "Failed to set exam date.";
    }
} else {
    echo "Invalid request.";
}
?>
