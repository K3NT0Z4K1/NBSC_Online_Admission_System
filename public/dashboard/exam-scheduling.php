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
      margin-top: 15px;
    }

    .search-bar {
      margin-bottom: 20px;
    }

    .search-input {
      padding: 10px;
      width: 300px;
      border-radius: 5px;
      border: 1px solid #ccc;
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

    .info-btn,
    .approve-btn,
    .decline-btn {
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

    .exam-date-input,
    .exam-site-input {
      padding: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
      margin-right: 5px;
    }

    .set-btn {
      padding: 5px 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .set-btn:hover {
      background-color: #218838;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      padding-top: 100px;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fff;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 50%;
      border-radius: 10px;
    }

    .close-btn {
      float: right;
      font-size: 24px;
      font-weight: bold;
      cursor: pointer;
    }

    .info-item {
      margin-bottom: 10px;
    }

    .label {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <div class="logo">
      <img src="../../components/img/nbsclogo.png" alt="Logo" class="logo-img">
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
      <button class="tab-button active">Exam Scheduling</button>
      <button onclick="window.location.href='result-management.php'" class="tab-button">Result Management</button>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 style="margin: 0;">Exam Scheduling</h2>
      <form method="get" action="" style="display: flex; gap: 10px;">
        <input type="text" id="searchInput" name="search" placeholder="Search applicant name or course..." class="search-input"
          value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" style="padding: 8px 12px; border-radius: 5px; border:none; background-color:#0d1b4c; color:white; cursor:pointer;">
          Search
        </button>
        <?php if (!empty($_GET['search'])): ?>
          <button type="button" onclick="window.location.href='exam-scheduling.php'" style="padding: 8px 12px; border-radius: 5px; border:none; background-color:#ccc; color:black; cursor:pointer;">
            Clear
          </button>
        <?php endif; ?>
      </form>
    </div>

    <table id="applicantsTable">
      <thead>
        <tr>
          <th>Applicant</th>
          <th>Course</th>
          <th>Submitted At</th>
          <th>Exam Date</th>
          <th>Exam Site</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (!isset($mycon) || !$mycon) die("Database connection not established.");

        $search = isset($_GET['search']) ? mysqli_real_escape_string($mycon, $_GET['search']) : '';
        $query = "SELECT tbl_applications.id, CONCAT(tbl_applications.firstname, ' ', tbl_applications.lastname) AS full_name, 
                  c.code AS course, tbl_applications.submitted_at 
                  FROM tbl_applications 
                  INNER JOIN tbl_courses c ON tbl_applications.course_id = c.id 
                  WHERE tbl_applications.application_status = 'Pending'";

        if (!empty($search)) {
          $query .= " AND (CONCAT(tbl_applications.firstname, ' ', tbl_applications.lastname) LIKE '%$search%' OR c.code LIKE '%$search%')";
        }

        $result = mysqli_query($mycon, $query);
        if (!$result) die("Query failed: " . mysqli_error($mycon));

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr id='row_{$row['id']}'>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
            echo "<td>" . htmlspecialchars(date("F d, Y h:i A", strtotime($row['submitted_at']))) . "</td>";
            echo "<td><input type='datetime-local' class='exam-date-input' id='exam_date_{$row['id']}' /></td>";
            echo "<td><input type='text' class='exam-site-input' id='exam_site_{$row['id']}' placeholder='Enter site' /></td>";
            echo "<td>
                    <button class='set-btn' onclick='setExamDetails({$row['id']})'>Set</button>
                    <button class='info-btn' onclick='openModal({$row['id']})'>View Info</button>
                    <button class='approve-btn' onclick='updateStatus({$row['id']}, \"Approved\")'>Approve</button>
                    <button class='decline-btn' onclick='updateStatus({$row['id']}, \"Declined\")'>Decline</button>
                  </td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='6'>No applications found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
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
        })
        .catch(err => alert("Error fetching applicant info."));
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

    function setExamDetails(id) {
      const dateInput = document.getElementById("exam_date_" + id);
      const siteInput = document.getElementById("exam_site_" + id);
      const date = dateInput.value;
      const site = siteInput.value;

      if (!date || !site) {
        alert("Please fill out both date and exam site.");
        return;
      }

      fetch("set-exam-date.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: "id=" + id + "&exam_date=" + encodeURIComponent(date) + "&exam_site=" + encodeURIComponent(site)
        })
        .then(res => res.text())
        .then(msg => alert(msg))
        .catch(err => alert("Error: " + err));
    }

    function updateStatus(id, status) {
      fetch("update-status.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: "id=" + id + "&status=" + encodeURIComponent(status)
        })
        .then(res => res.text())
        .then(msg => {
          if (msg.trim() === "success") {
            const row = document.getElementById('row_' + id);
            if (row) row.remove();
            alert(`Applicant #${id} has been ${status}.`);
          } else {
            alert("Failed to update status: " + msg);
          }
        })
        .catch(err => alert("Error: " + err));
    }
  </script>
</body>

</html>