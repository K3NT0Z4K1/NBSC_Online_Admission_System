<?php
include_once("../../functions/functions.php");  // Make sure $mycon is your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($id <= 0 || !in_array($status, ['Approved', 'Declined'])) {
        echo "Invalid data";
        exit;
    }

    // Update the application status
    $stmt = $mycon->prepare("UPDATE tbl_applications SET application_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Database update failed";
    }

    $stmt->close();
} else {
    echo "Invalid request method";
}
?>
