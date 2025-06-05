<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../functions/db_connect.php';

if (!isset($mycon) || !$mycon) {
  die("Database connection failed.");
}

// Handle score and remarks update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
  $id = intval($_POST['save']); // application_id
  $newScore = isset($_POST['score'][$id]) ? intval($_POST['score'][$id]) : null;
  $newRemarks = isset($_POST['remarks'][$id]) ? mysqli_real_escape_string($mycon, $_POST['remarks'][$id]) : null;

  if (!is_null($newScore)) {
    $update = "
            UPDATE tbl_exam_results
            SET score = $newScore, remarks = '$newRemarks'
            WHERE application_id = $id
        ";

    if (mysqli_query($mycon, $update)) {
      echo "<script>alert('Result updated for Applicant ID: $id'); window.location.href = window.location.href;</script>";
    } else {
      echo "<script>alert('Error updating result: " . mysqli_error($mycon) . "');</script>";
    }
  }
}

// Fetch applicants who took exam with their results
$query = "
SELECT
    a.id,
    CONCAT(a.firstname, ' ', a.lastname) AS full_name,
    c.name AS course,
    r.score,
    r.remarks,
    r.exam_taken_at
FROM tbl_exam_results r
INNER JOIN tbl_applications a ON a.id = r.application_id
LEFT JOIN tbl_courses c ON a.course_id = c.id
ORDER BY r.exam_taken_at DESC

";

$result = mysqli_query($mycon, $query);

if (!$result) {
  die("Query error: " . mysqli_error($mycon));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>NBSC Online Admission - Result Management</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
    }

    .sidebar {
      width: 250px;
      background-color: #0d1b4c;
      color: white;
      min-height: 100vh;
      padding: 20px;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 40px;
    }

    .logo-img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      background-color: white;
      padding: 5px;
    }

    .nav {
      list-style: none;
    }

    .nav-item {
      padding: 12px;
      margin-bottom: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .nav-item.active,
    .nav-item:hover {
      background-color: #3053a5;
    }

    .main {
      flex: 1;
      background-color: #f5f5f5;
      padding: 30px;
    }

    .top-bar {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }

    .logout-btn {
      background-color: #0d1b4c;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .tabs {
      margin-bottom: 20px;
    }

    .tab-button {
      padding: 10px 20px;
      border: none;
      background-color: #ddd;
      margin-right: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .tab-button.active {
      background-color: #0d1b4c;
      color: white;
    }

    h2 {
      margin-bottom: 15px;
      margin-top: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    th,
    td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #0d1b4c;
      color: white;
    }

    input[type="number"],
    input[type="text"] {
      width: 100%;
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button[type="submit"] {
      padding: 6px 12px;
      background-color: #0d1b4c;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #254899;
    }

    .no-data {
      text-align: center;
      color: #999;
      padding: 20px 0;
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <div class="logo">
      <img src="../../components/img/nbsclogo.png" alt="Logo" class="logo-img" />
      <h3>NBSC Online Admission</h3>
    </div>
    <ul class="nav">
      <li class="nav-item active">Dashboard</li>
    </ul>
  </div>

  <div class="main">
    <div class="top-bar">
      <button class="logout-btn" onclick="window.location.href='../../index.php'">Log out</button>
    </div>

    <div class="tabs">
      <button onclick="window.location.href='dashboard.php'" class="tab-button">Approved Applications</button>
      <button onclick="window.location.href='exam-scheduling.php'" class="tab-button">Exam Scheduling</button>
      <button class="tab-button active">Result Management</button>
    </div>

    <h2>Result Management</h2>

    <form method="POST">
      <table>
        <tr>
          <th>Applicant</th>
          <th>Course</th>
          <th>Score</th>
          <th>Remarks</th>
          <th>Exam Taken At</th>
          <th>Action</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['course']) . "</td>";

            echo "<td><input type='number' name='score[{$row['id']}]' value='" . htmlspecialchars($row['score']) . "' min='0' max='100' required></td>";
            echo "<td><input type='text' name='remarks[{$row['id']}]' value='" . htmlspecialchars($row['remarks']) . "' placeholder='Enter remarks'></td>";
            echo "<td>" . date('F j, Y, g:i A', strtotime($row['exam_taken_at'])) . "</td>";
            echo "<td><button type='submit' name='save' value='{$row['id']}'>Save</button></td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='6' class='no-data'>No exam results found.</td></tr>";
        }
        ?>
      </table>
    </form>
  </div>
</body>

</html>