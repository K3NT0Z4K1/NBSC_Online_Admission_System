<?php
// Enable error reporting (for dev, disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../functions/db_connect.php';

if (!isset($mycon) || !$mycon) {
  die("Database connection failed.");
}

// Get search term from GET (if any)
$searchTerm = '';
if (isset($_GET['search'])) {
  $searchTerm = trim($_GET['search']);
}

// Base query
$query = "
  SELECT 
    a.id,
    CONCAT(app.firstname, ' ', app.lastname) AS full_name, 
    c.name AS course, 
    a.submitted_at, 
    a.application_status
FROM tbl_applications a
INNER JOIN tbl_courses c ON c.id = a.course_id
INNER JOIN tbl_applicants app ON app.id = a.applicant_id
WHERE a.application_status = 'Approved'
AND NOT EXISTS (
    SELECT 1 FROM tbl_exam_results r WHERE r.application_id = a.id
)

  ";

// Add search condition if search term exists
if ($searchTerm !== '') {
  // Use prepared statement style ? placeholders but mysqli_query won't accept that directly,
  // so we safely escape the input here:
  $searchTermEscaped = mysqli_real_escape_string($mycon, $searchTerm);
  $query .= " AND (CONCAT(a.firstname, ' ', a.lastname) LIKE '%$searchTermEscaped%' OR c.name LIKE '%$searchTermEscaped%') ";
}

// Order by submitted_at descending (optional)
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
  <title>NBSC Online Admission - Approved Applications</title>
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

    .sending {
      background-color: #c8e6c9;
      color: #256029;
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: bold;
    }

    .manage-btn {
      background-color: #3053a5;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 5px;
      cursor: pointer;
    }

    .manage-btn:hover {
      background-color: #1f3b76;
    }

    .msg {
      padding: 10px 20px;
      margin-bottom: 20px;
      border-radius: 5px;
      font-weight: bold;
    }

    .msg.success {
      background-color: #d4edda;
      color: #155724;
    }

    .msg.warning {
      background-color: #fff3cd;
      color: #856404;
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
      <button class="tab-button active">Approved Applications</button>
      <button onclick="window.location.href='exam-scheduling.php'" class="tab-button">Exam Scheduling</button>
      <button onclick="window.location.href='result-management.php'" class="tab-button">Result Management</button>
    </div>

    <?php
    if (isset($_GET['msg'])) {
      if ($_GET['msg'] == 'marked_taken') {
        echo "<div class='msg success'>Exam successfully marked as taken.</div>";
      } elseif ($_GET['msg'] == 'already_marked') {
        echo "<div class='msg warning'>Exam already marked as taken.</div>";
      }
    }
    ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 style="margin: 0;">Approved Applications</h2>
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
          <button type="button" onclick="window.location.href='dashboard.php'" style="padding: 8px 12px; border-radius: 5px; border:none; background-color:#ccc; color:black; cursor:pointer;">
            Clear
          </button>
        <?php endif; ?>
      </form>
    </div>

    <table>
      <tr>
        <th>Applicant</th>
        <th>Course</th>
        <th>Date Applied</th>
        <th>Status</th>
        <th>Action</th>
      </tr>

      <?php
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['course']) . "</td>";
          echo "<td>" . date('F j, Y', strtotime($row['submitted_at'])) . "</td>";
          echo "<td><span class='sending'>" . htmlspecialchars($row['application_status']) . "</span></td>";
          echo "<td>
                    <form action='mark-exam-taken.php' method='post' style='display:inline;'>
                      <input type='hidden' name='application_id' value='" . $row['id'] . "'>
                      <button type='submit' class='manage-btn'>Mark as Taken</button>
                    </form>
                  </td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='5'>No approved applicants found.</td></tr>";
      }
      ?>
    </table>

  </div>

  <script>
    window.addEventListener('DOMContentLoaded', () => {
      document.body.classList.add('fade-in');
    });



    const signUpLink = document.getElementById('signUpLink');

    signUpLink.addEventListener('click', function(e) {
      e.preventDefault(); // prevent default navigation

      document.body.classList.remove('fade-in');
      document.body.classList.add('fade-out');

      setTimeout(() => {
        window.location.href = signUpLink.href;
      }, 500); // match the CSS transition duration
    });
  </script>
</body>

</html>