<?php
include_once("../../functions/functions.php");

if (!isset($_GET['id'])) {
  die("No applicant ID provided.");
}

$id = intval($_GET['id']);
$query = "SELECT * FROM tbl_applications WHERE id = $id";
$result = mysqli_query($mycon, $query);

if (!$result || mysqli_num_rows($result) == 0) {
  die("Applicant not found.");
}

$applicant = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Applicant Info</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #f9f9f9;
    }
    h2 {
      color: #0d1b4c;
    }
    .info-box {
      background-color: white;
      padding: 20px;
      max-width: 600px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .info-row {
      margin-bottom: 10px;
    }
    .label {
      font-weight: bold;
      color: #333;
    }
    .back-btn {
      margin-top: 20px;
      display: inline-block;
      padding: 8px 12px;
      background-color: #0d1b4c;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    .back-btn:hover {
      background-color: #3053a5;
    }
  </style>
</head>
<body>

  <div class="info-box">
    <h2>Applicant Information</h2>
    <div class="info-row"><span class="label">Full Name:</span> <?= htmlspecialchars($applicant['firstname'] . ' ' . $applicant['lastname']) ?></div>
    <div class="info-row"><span class="label">Status:</span> <?= htmlspecialchars($applicant['status_applicant']) ?></div>
    <div class="info-row"><span class="label">Date of Birth:</span> <?= htmlspecialchars($applicant['dob']) ?></div>
    <div class="info-row"><span class="label">Email:</span> <?= htmlspecialchars($applicant['email']) ?></div>
    <div class="info-row"><span class="label">Contact:</span> <?= htmlspecialchars($applicant['contact']) ?></div>
    <div class="info-row"><span class="label">Address:</span> <?= htmlspecialchars($applicant['address']) ?></div>
    <div class="info-row"><span class="label">Course:</span> <?= htmlspecialchars($applicant['course']) ?></div>
    <div class="info-row"><span class="label">Submitted At:</span> <?= htmlspecialchars($applicant['submitted_at']) ?></div>

    <a href="exam-scheduling.php" class="back-btn">← Back to Scheduling</a>
  </div>

</body>
</html>
