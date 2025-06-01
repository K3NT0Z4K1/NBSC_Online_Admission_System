<?php
include_once("../../functions/functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>NBSC Online Admission - Exam Scheduling</title>
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

    .info-btn, .approve-btn, .decline-btn {
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 6px 12px;
      cursor: pointer;
    }

    .approve-btn {
      background-color: #28a745;
    }

    .decline-btn {
      background-color: #dc3545;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: white;
      margin: 10% auto;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
      max-width: 90%;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      color: #888;
      cursor: pointer;
    }

    .info-item {
      margin-bottom: 10px;
    }

    .exam-date-input {
      padding: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    .set-btn {
      padding: 5px 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-left: 5px;
    }

    .set-btn:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="sidebar">
     <div class="logo">
      <img src="../../components/img/nbsclogo.png" alt="Logo" class="logo-img">
      <h3>NBSC Admission</h3>
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

    <h2>Exam Scheduling</h2>
    <table>
      <tr>
        <th>Applicant</th>
        <th>Course</th>
        <th>Submitted At</th>
        <th>Exam Date</th>
        <th>Actions</th>
      </tr>
      <?php
      $query = "SELECT id, CONCAT(firstname, ' ', lastname) AS full_name, course, submitted_at FROM tbl_applications WHERE application_status = 'Pending'";
      $result = mysqli_query($mycon, $query);

      if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr id='row_{$row['id']}'>";
          echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['course']) . "</td>";
          echo "<td>" . htmlspecialchars(date("F d, Y h:i A", strtotime($row['submitted_at']))) . "</td>";
          echo "<td>
                  <input type='datetime-local' class='exam-date-input' id='exam_date_{$row['id']}' />
                  <button class='set-btn' onclick='setExamDate({$row['id']})'>Set</button>
                </td>";
          echo "<td>
                  <button class='info-btn' onclick='openModal(" . $row['id'] . ")'>View Info</button>
                  <button class='approve-btn' onclick='updateStatus(" . $row['id'] . ", \"Approved\")'>Approve</button>
                  <button class='decline-btn' onclick='updateStatus(" . $row['id'] . ", \"Declined\")'>Decline</button>
                </td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='5'>No applications found.</td></tr>";
      }
      ?>
    </table>
  </div>

  <!-- Modal code -->

  <script>
    // Your existing openModal, closeModal, setExamDate functions...

    function updateStatus(id, status) {
      fetch("update-status.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "id=" + id + "&status=" + encodeURIComponent(status),
      })
      .then(res => res.text())
      .then(msg => {
        if (msg.trim() === "success") {
          // Remove the applicant row from the table by row id
          const row = document.getElementById('row_' + id);
          if(row) row.remove();
        } else {
          alert("Failed to update status: " + msg);
        }
      })
      .catch(err => alert("Error: " + err));
    }
  </script>
</body>
</html>
