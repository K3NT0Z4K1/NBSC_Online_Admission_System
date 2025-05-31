<?php
include_once("../../functions/functions.php"); 
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

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    .approved {
      background-color: #7dff97;
      padding: 5px 10px;
      border-radius: 5px;
    }

    .failed {
      background-color: #f5a623;
      padding: 5px 10px;
      border-radius: 5px;
    }

    .sending {
      background-color: #b0a8f5;
      padding: 5px 10px;
      border-radius: 5px;
    }

    .info-btn {
      padding: 5px 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      text-decoration: none;
    }

    .info-btn:hover {
      background-color: #0056b3;
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
      <button class="logout-btn">Log out</button>
    </div>

    <div class="tabs">
      <button onclick="window.location.href='dashboard.php'" class="tab-button">Approved Applications</button>
      <button class="tab-button active">Exam Scheduling</button>
      <button onclick="window.location.href='result-management.php'" class="tab-button">Result Management</button>
    </div>

    <div id="schedule" class="tab-content active">
      <h2>Exam Scheduling</h2>
      <table>
        <tr>
          <th>Applicant</th>
          <th>Course</th>
          <th>Submitted At</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
        <?php
        $query = "SELECT id, CONCAT(firstname, ' ', lastname) AS full_name, course, submitted_at, status_applicant FROM tbl_applications";
        $result = mysqli_query($mycon, $query);

        if ($result && mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $statusText = strtolower($row['status_applicant']);
            $statusClass = 'sending'; // default

            if ($statusText === 'approved') {
              $statusClass = 'approved';
            } elseif ($statusText === 'failed') {
              $statusClass = 'failed';
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
            echo "<td>" . htmlspecialchars(date("F d, Y h:i A", strtotime($row['submitted_at']))) . "</td>";
            echo "<td><span class='{$statusClass}'>" . htmlspecialchars($row['status_applicant']) . "</span></td>";
            echo "<td><a href='view-applicant.php?id=" . $row['id'] . "' class='info-btn'>View Info</a></td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No applications found.</td></tr>";
        }
        ?>
      </table>
    </div>
  </div>
</body>
</html>
