<?php
include_once("../../functions/functions.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST['id'];
  $status = $_POST['status'];

  $stmt = $mycon->prepare("UPDATE tbl_applications SET status_applicant = ? WHERE id = ?");
  $stmt->bind_param("si", $status, $id);

  if ($stmt->execute()) {
    echo "success";
  } else {
    echo "error";
  }
}
?>
