<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your DB connection file (adjust the path if needed)
include '../../functions/db_connect.php';

// Check if $mycon is set and is a valid mysqli connection
if (!isset($mycon) || !$mycon) {
    die("Database connection failed.");
}

// SQL query to fetch approved applicants
$query = "SELECT CONCAT(firstname, ' ', lastname) AS full_name, course, submitted_at, application_status 
          FROM tbl_applications 
          WHERE application_status = 'Approved'";

$result = mysqli_query($mycon, $query);

// Check for query error
if (!$result) {
    die("Query error: " . mysqli_error($mycon));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>NBSC Online Admission - Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
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
      gap: 15px;
      margin-bottom: 30px;
    }

    .logo-img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: contain;
      border: 2px solid white;
      padding: 10px;
      background-color: white;
    }

    .nav {
      list-style: none;
      padding: 0;
    }

    .nav-item {
      padding: 12px;
      cursor: pointer;
      border-radius: 5px;
    }

    .nav-item.active,
    .nav-item:hover {
      background-color: #3053a5;
    }

    .main {
      flex: 1;
      padding: 20px;
      background: #f5f5f5;
    }

    .top-bar {
      text-align: right;
      margin-bottom: 20px;
    }

    .logout-btn {
      padding: 10px 15px;
      background-color: #5aa6e5;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .tabs {
      margin-bottom: 15px;
    }

    .tab-button {
      padding: 10px;
      background-color: #eee;
      border: none;
      cursor: pointer;
      margin-right: 5px;
    }

    .tab-button.active {
      background-color: white;
      border-bottom: 2px solid #0d1b4c;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table,
    th,
    td {
      border: 1px solid #ddd;
    }

    th,
    td {
      padding: 10px;
      text-align: left;
    }

    .sending {
      background-color: #c8e6c9;
      color: #256029;
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <div class="logo">
      <img src="../../components/img/nbsclogo.png" alt="Logo" class="logo-img" />
      <h2>NBSC Online Admission</h2>
    </div>
    <ul class="nav">
      <li class="nav-item active">Dashboard</li>
    </ul>
  </div>

  <div class="main">
    <div class="top-bar">
      <button class="logout-btn" onclick="window.location.href='../goodbye.php'">Log out</button>
    </div>

    <div class="tabs">
      <button class="tab-button active">Approved Applications</button>
      <button onclick="window.location.href='exam-scheduling.php'" class="tab-button">Exam Scheduling</button>
      <button onclick="window.location.href='result-management.php'" class="tab-button">Result Management</button>
    </div>

    <div id="approved" class="tab-content active">
      <h3>Approved Applications</h3>
      <table>
        <tr>
          <th>Applicant</th>
          <th>Course</th>
          <th>Date Applied</th>
          <th>Status</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                echo "<td>" . date('F j, Y', strtotime($row['submitted_at'])) . "</td>";
                echo "<td><span class='sending'>" . htmlspecialchars($row['application_status']) . "</span></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No approved applicants found.</td></tr>";
        }
        ?>
      </table>
    </div>
  </div>
</body>

</html>
