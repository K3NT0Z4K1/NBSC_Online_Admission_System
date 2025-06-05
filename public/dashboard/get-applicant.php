<?php
include_once("../../functions/functions.php");

if (!isset($_GET['id'])) {
  echo json_encode(['error' => 'No ID']);
  exit;
}

$id = intval($_GET['id']);
$query = "
  SELECT 
    a.*, 
    c.name AS course 
  FROM tbl_applications a
  INNER JOIN tbl_courses c ON a.course_id = c.id
  WHERE a.id = $id
  LIMIT 1
";

$result = mysqli_query($mycon, $query);

if (!$result || mysqli_num_rows($result) === 0) {
  echo json_encode(['error' => 'Not found']);
  exit;
}

$data = mysqli_fetch_assoc($result);
echo json_encode($data);
