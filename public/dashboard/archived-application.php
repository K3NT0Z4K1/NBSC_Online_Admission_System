<?php
// Enable error reporting (for dev, disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../functions/db_connect.php';

if (!isset($mycon) || !$mycon) {
  die("Database connection failed.");
}

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
  $id = intval($_POST['delete']); // application_id

  // Then mark application as 'Deleted'
  $stmt = $mycon->prepare("UPDATE tbl_applications SET application_status = 'Deleted' WHERE id = ?");
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF'] . (isset($_GET['search']) ? "?search=" . urlencode($_GET['search']) : ""));
    exit();
  } else {
    $errorMsg = "Error updating application status: " . htmlspecialchars($stmt->error);
    $stmt->close();
  }
}

// Get search term from GET (if any)
$searchTerm = '';
if (isset($_GET['search'])) {
  $searchTerm = trim($_GET['search']);
}

$query = "
  SELECT 
    a.id,
    CONCAT(app.firstname, ' ', app.lastname) AS full_name, 
    a.submitted_at, 
    a.application_status
  FROM tbl_applications a
  LEFT JOIN tbl_courses c ON c.id = a.course_id
  INNER JOIN tbl_applicants app ON app.id = a.applicant_id
  WHERE a.application_status = 'Done'
";

// Add search condition if search term exists
if ($searchTerm !== '') {
  $searchTermEscaped = mysqli_real_escape_string($mycon, $searchTerm);
  $query .= " AND (CONCAT(app.firstname, ' ', app.lastname) LIKE '%$searchTermEscaped%' OR c.name LIKE '%$searchTermEscaped%') ";
}

// Order by submitted_at descending
$query .= " ORDER BY a.submitted_at DESC ";

$result = mysqli_query($mycon, $query);

if (!$result) {
  die("Query error: " . mysqli_error($mycon));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>NBSC Online Admission - Archived Applications</title>
  <style>
    /* (Your CSS remains unchanged) */
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

    .done-status {
      background-color: #e0e0e0;
      color: #333;
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: bold;
      display: inline-block;
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
      width: 30%;
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

    .view-btn {
      background-color: #0d1b4c;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
    }

    .view-btn:hover {
      background-color: #3053a5;
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
      <button onclick="window.location.href='result-management.php'" class="tab-button">Result Management</button>
      <button class="tab-button active">Archived Applications</button>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 style="margin: 0;">Archived Applications</h2>
      <form method="get" action="" style="display: flex; gap: 10px;">
        <input
          type="text"
          id="searchInput"
          name="search"
          placeholder="Search applicant name or course..."
          class="search-input"
          value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" style="padding: 8px 12px; border-radius: 5px; border:none; background-color:#0d1b4c; color:white; cursor:pointer;">
          Search
        </button>
        <?php if (!empty($_GET['search'])): ?>
          <button type="button" onclick="window.location.href='archived-application.php'" style="padding: 8px 12px; border-radius: 5px; border:none; background-color:#ccc; color:black; cursor:pointer;">
            Clear
          </button>
        <?php endif; ?>
      </form>
    </div>

    <?php if (!empty($errorMsg)): ?>
      <p style="color: red; margin-bottom: 10px;"><?php echo $errorMsg; ?></p>
    <?php endif; ?>

    <table>
      <tr>
        <th>Applicant</th>
        <th>Date Applied</th>
        <th>Status</th>
        <th>Action</th>
        <th>----------</th>
      </tr>

      <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
          <td><?php echo htmlspecialchars($row['full_name']); ?></td>
          <td><?php echo date('F j, Y', strtotime($row['submitted_at'])); ?></td>
          <td><span class="done-status"><?php echo htmlspecialchars($row['application_status']); ?></span></td>
          <td>
            <button
              class="view-btn"
              data-id="<?php echo $row['id']; ?>"
              data-name="<?php echo htmlspecialchars($row['full_name']); ?>"
              data-date="<?php echo date('F j, Y', strtotime($row['submitted_at'])); ?>"
              data-status="<?php echo htmlspecialchars($row['application_status']); ?>">
              View Info
            </button>
          </td>
          <td>
            <form method="post" onsubmit="return confirm('Are you sure you want to delete this applicant result?')">
              <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
              <button type="submit" style="background-color: #f44336; color: white; border: none; padding: 6px 12px; border-radius: 4px;">
                Delete
              </button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <!-- Modal -->
    <div id="infoModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3>Applicant Info</h3>
        <div class="info-item"><span class="label">Full Name:</span> <span id="infoName"></span></div>
        <div class="info-item"><span class="label">Middle Name:</span> <span id="infoMiddleName"></span></div>
        <div class="info-item"><span class="label">Suffix:</span> <span id="infoSuffix"></span></div>
        <div class="info-item"><span class="label">Gender:</span> <span id="infoGender"></span></div>
        <div class="info-item"><span class="label">Place of Birth:</span> <span id="infoPlaceOfBirth"></span></div>
        <div class="info-item"><span class="label">Nationality:</span> <span id="infoNationality"></span></div>
        <div class="info-item"><span class="label">High School:</span> <span id="infoHighSchool"></span></div>
        <div class="info-item"><span class="label">Year Graduated:</span> <span id="infoYearGraduated"></span></div>
        <div class="info-item"><span class="label">Parent/Guardian Name:</span> <span id="infoParentName"></span></div>
        <div class="info-item"><span class="label">Parent/Guardian Contact:</span> <span id="infoParentContact"></span></div>
        <div class="info-item"><span class="label">Date of Birth:</span> <span id="infoDOB"></span></div>
        <div class="info-item"><span class="label">Email:</span> <span id="infoEmail"></span></div>
        <div class="info-item"><span class="label">Contact:</span> <span id="infoContact"></span></div>
        <div class="info-item"><span class="label">Address:</span> <span id="infoAddress"></span></div>
        <div class="info-item"><span class="label">Course Code:</span> <span id="infoCourseCode"></span></div>
        <div class="info-item"><span class="label">Course:</span> <span id="infoCourse"></span></div>
        <div class="info-item"><span class="label">Status:</span> <span id="infoStatus"></span></div>
        <div class="info-item"><span class="label">Submitted:</span> <span id="infoSubmitted"></span></div>
        <div class="info-item"><span class="label">Exam Date:</span> <span id="infoExamDate"></span></div>
        <div class="info-item"><span class="label">Exam Site:</span> <span id="infoExamSite"></span></div>
        <div class="info-item"><span class="label">Application Status:</span> <span id="infoApplicationStatus"></span></div>
        <div class="info-item"><span class="label">Exam Score:</span> <span id="infoExamScore"></span></div>
        <div class="info-item"><span class="label">Exam Taken At:</span> <span id="infoExamTakenAt"></span></div>

      </div>
    </div>


    <script>
      // Function to open the modal and fetch applicant data
      function openModal(id) {
        fetch("get-applicant-archived.php?id=" + id)
          .then(res => res.json())
          .then(data => {
            document.getElementById("infoName").textContent = data.firstname + " " + data.lastname;
            document.getElementById("infoMiddleName").textContent = data.middlename || '-';
            document.getElementById("infoSuffix").textContent = data.suffix || '-';
            document.getElementById("infoGender").textContent = data.gender_select || data.gender_other || '-';
            document.getElementById("infoPlaceOfBirth").textContent = data.place_of_birth || '-';
            document.getElementById("infoNationality").textContent = data.nationality || '-';
            document.getElementById("infoHighSchool").textContent = data.high_school || '-';
            document.getElementById("infoYearGraduated").textContent = data.year_graduated || '-';
            document.getElementById("infoParentName").textContent = data.parent_name || '-';
            document.getElementById("infoParentContact").textContent = data.parent_contact || '-';
            document.getElementById("infoDOB").textContent = data.dob || '-';
            document.getElementById("infoEmail").textContent = data.email || '-';
            document.getElementById("infoContact").textContent = data.contact || '-';
            document.getElementById("infoAddress").textContent = data.address || '-';
            document.getElementById("infoCourseCode").textContent = data.course_code || '-';
            document.getElementById("infoCourse").textContent = data.course || '-';
            document.getElementById("infoStatus").textContent = data.status_applicant_select || data.status_applicant_other || '-';
            document.getElementById("infoSubmitted").textContent = data.submitted_at || '-';
            document.getElementById("infoExamDate").textContent = data.exam_date || '-';
            document.getElementById("infoExamSite").textContent = data.exam_site || '-';
            document.getElementById("infoApplicationStatus").textContent = data.application_status || '-';
            document.getElementById("infoExamScore").textContent = data.exam_score !== null ? data.exam_score : '-';
            document.getElementById("infoExamTakenAt").textContent = data.exam_taken_at || '-';

            document.getElementById("infoModal").style.display = "block";
          })
          .catch(err => {
            alert("Error fetching applicant info.");
            console.error(err);
          });
      }

      // Function to close the modal
      function closeModal() {
        document.getElementById("infoModal").style.display = "none";
      }

      // Close modal when clicking outside of modal content
      window.onclick = function(event) {
        const modal = document.getElementById("infoModal");
        if (event.target === modal) {
          closeModal();
        }
      };

      // Attach event listeners after DOM content is loaded
      document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.view-btn').forEach(button => {
          button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            openModal(id);
          });
        });
      });
    </script>




</body>

</html>