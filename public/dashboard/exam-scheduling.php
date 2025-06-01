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
      cursor: pointer;
    }

    .info-btn:hover {
      background-color: #0056b3;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      background-color: #fff;
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
      color: #aaa;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .close-btn:hover {
      color: #333;
    }

    .modal .label {
      font-weight: bold;
    }

    .modal .info-item {
      margin-bottom: 10px;
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
            $statusClass = $statusText === 'approved' ? 'approved' : ($statusText === 'failed' ? 'failed' : 'sending');

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
            echo "<td>" . htmlspecialchars(date("F d, Y h:i A", strtotime($row['submitted_at']))) . "</td>";
            echo "<td><span class='{$statusClass}'>" . htmlspecialchars($row['status_applicant']) . "</span></td>";
            echo "<td><button class='info-btn' onclick='openModal(" . $row['id'] . ")'>View Info</button></td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5'>No applications found.</td></tr>";
        }
        ?>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div id="infoModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <h3>Applicant Info</h3>
      <div class="info-item"><span class="label">Full Name:</span> <span id="infoName"></span></div>
      <div class="info-item"><span class="label">Email:</span> <span id="infoEmail"></span></div>
      <div class="info-item"><span class="label">Contact:</span> <span id="infoContact"></span></div>
      <div class="info-item"><span class="label">Address:</span> <span id="infoAddress"></span></div>
      <div class="info-item"><span class="label">Course:</span> <span id="infoCourse"></span></div>
      <div class="info-item"><span class="label">Status:</span> <span id="infoStatus"></span></div>
      <div class="info-item"><span class="label">Submitted:</span> <span id="infoSubmitted"></span></div>
    </div>
  </div>

  <script>
    function openModal(id) {
      fetch("get-applicant.php?id=" + id)
        .then(res => res.json())
        .then(data => {
          document.getElementById("infoName").textContent = data.firstname + " " + data.lastname;
          document.getElementById("infoEmail").textContent = data.email;
          document.getElementById("infoContact").textContent = data.contact;
          document.getElementById("infoAddress").textContent = data.address;
          document.getElementById("infoCourse").textContent = data.course;
          document.getElementById("infoStatus").textContent = data.status_applicant;
          document.getElementById("infoSubmitted").textContent = data.submitted_at;

          document.getElementById("infoModal").style.display = "block";
        });
    }

    function closeModal() {
      document.getElementById("infoModal").style.display = "none";
    }

    window.onclick = function(event) {
      const modal = document.getElementById("infoModal");
      if (event.target === modal) {
        closeModal();
      }
    };
  </script>
</body>

</html>