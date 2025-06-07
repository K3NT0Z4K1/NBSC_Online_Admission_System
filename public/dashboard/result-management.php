<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../functions/db_connect.php';
require '../../components/vendor/PHPMailer-6.10.0/PHPMailer-6.10.0/src/Exception.php';
require '../../components/vendor/PHPMailer-6.10.0/PHPMailer-6.10.0/src/PHPMailer.php';
require '../../components/vendor/PHPMailer-6.10.0/PHPMailer-6.10.0/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($mycon) || !$mycon) {
  die("Database connection failed.");
}

// Handle score and remarks update securely
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
  $id = intval($_POST['save']); // application_id
  $newScore = isset($_POST['score'][$id]) ? intval($_POST['score'][$id]) : null;
  $newRemarks = isset($_POST['remarks'][$id]) ? $_POST['remarks'][$id] : null;

  if (!is_null($newScore)) {
    // Prepared statement to update exam_results
    $stmt = $mycon->prepare("UPDATE tbl_exam_results SET score = ?, remarks = ? WHERE application_id = ?");
    $stmt->bind_param('isi', $newScore, $newRemarks, $id);

    if ($stmt->execute()) {
      echo "<script>alert('Result updated for Applicant ID: $id'); window.location.href = window.location.href;</script>";
    } else {
      echo "<script>alert('Error updating result: " . htmlspecialchars($stmt->error) . "');</script>";
    }
    $stmt->close();
  }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "
    SELECT
        a.id,
        CONCAT(app.firstname, ' ', app.lastname) AS full_name,
        c.name AS course,
        r.score,
        r.remarks,
        r.exam_taken_at
    FROM tbl_exam_results r
    INNER JOIN tbl_applications a ON a.id = r.application_id
    INNER JOIN tbl_applicants app ON app.id = a.applicant_id
    LEFT JOIN tbl_courses c ON a.course_id = c.id
";

// Add search condition if search input is provided
if (!empty($search)) {
  $search = mysqli_real_escape_string($mycon, $search); // Escape user input
  $query .= " WHERE CONCAT(app.firstname, ' ', app.lastname) LIKE '%$search%' OR c.name LIKE '%$search%'";
}

$query .= " ORDER BY r.exam_taken_at DESC";

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
    /* Your CSS unchanged */
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

    select[name^="remarks"] {
      padding: 6px 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: white;
      color: #333;
      font-family: inherit;
      width: 100%;
      /* makes it fill the cell nicely */
      box-sizing: border-box;
      /* include padding/border in width */
      cursor: pointer;
      transition: border-color 0.3s ease;
    }

    select[name^="remarks"]:focus {
      outline: none;
      border-color: #007BFF;
      /* or your primary color */
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
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

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h2 style="margin: 0;">Results Management</h2>
      <form method="get" action="" style="display: flex; gap: 10px;">
        <input
          type="text"
          id="searchInput"
          name="search"
          placeholder="Search applicant name or course..."
          style="padding: 10px; width: 300px; border-radius: 5px; border: 1px solid #ccc;"
          value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" style="padding: 8px 12px; border-radius: 5px; border:none; background-color:#0d1b4c; color:white; cursor:pointer;">
          Search
        </button>
        <?php if (!empty($_GET['search'])): ?>
          <button type="button" onclick="window.location.href='result-management.php'" style="padding: 8px 12px; border-radius: 5px; border:none; background-color:#ccc; color:black; cursor:pointer;">
            Clear
          </button>
        <?php endif; ?>
      </form>
    </div>


    <form method="POST">
      <table>
        <tr>
          <th>Applicant</th>
          <th>Course</th>
          <th>Score</th>
          <th>Remarks</th>
          <th>Exam Taken At</th>
          <th>Actions</th>
        </tr>

        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['course'] ?? 'Not assigned') ?></td>
              <td>
                <input type="number" name="score[<?= $row['id'] ?>]" value="<?= htmlspecialchars($row['score']) ?>" min="0" max="100" required>
              </td>
              <td>
                <select name="remarks[<?= $row['id'] ?>]">
                  <?php
                  // Define possible remarks options
                  $options = ['Passed', 'Failed', 'Pending', 'Needs Review', 'Incomplete'];

                  foreach ($options as $option) {
                    $selected = ($option === $row['remarks']) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($option) . "\" $selected>" . htmlspecialchars($option) . "</option>";
                  }
                  ?>
                </select>
              </td>

              <td><?= htmlspecialchars($row['exam_taken_at']) ?></td>
              <td>
                <button type="submit" name="save" value="<?= $row['id'] ?>">Save</button>
                <button type="submit" name="send" value="<?= $row['id'] ?>">Send Result</button>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="no-data">No exam results found.</td>
          </tr>
        <?php endif; ?>

        <?php
        // Email sending logic when Send Result button clicked
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
          $id = intval($_POST['send']); // application_id
          $score = isset($_POST['score'][$id]) ? intval($_POST['score'][$id]) : null;
          $remarks = isset($_POST['remarks'][$id]) ? $_POST['remarks'][$id] : '';

          if (is_null($score)) {
            echo "<script>alert('Score is missing.');</script>";
            return;
          }

          // Get applicant details from tbl_applicants via join with tbl_applications
          $sql = "SELECT app.firstname, app.lastname, app.email FROM tbl_applications a INNER JOIN tbl_applicants app ON app.id = a.applicant_id WHERE a.id = ?";
          $stmt = $mycon->prepare($sql);
          $stmt->bind_param('i', $id);
          $stmt->execute();
          $res = $stmt->get_result();
          $applicant = $res->fetch_assoc();
          $stmt->close();

          if (!$applicant) {
            echo "<script>alert('Applicant not found.');</script>";
            return;
          }

          // Course program groups based on score
          $programs = [
            'BSBA' => [
              "Bachelor of Science in Business Administration Major in Marketing Management",
              "Bachelor of Science in Business Administration Major in Financial Management",
              "Bachelor of Science in Business Administration Major in Operations Management"
            ],
            'IT' => [
              "Bachelor of Science in Information Technology"
            ],
            'TEP' => [
              "Bachelor of Secondary in Education Major in English",
              "Bachelor of Secondary in Education Major in Math",
              "Bachelor in Elementary Education",
              "Bachelor of Early Childhood Education"
            ]
          ];

          $qualifiedGroups = [];
          if ($score >= 0 && $score <= 65) $qualifiedGroups[] = 'BSBA';
          if ($score >= 66 && $score <= 80) $qualifiedGroups[] = 'IT';
          if ($score > 80) $qualifiedGroups[] = 'TEP';

          $allQualifiedPrograms = [];
          foreach ($qualifiedGroups as $group) {
            $allQualifiedPrograms = array_merge($allQualifiedPrograms, $programs[$group]);
          }
          $programListString = implode("<br>", $allQualifiedPrograms);

          $firstProgramName = $allQualifiedPrograms[0] ?? null;

          if ($firstProgramName) {
            $courseNameEscaped = mysqli_real_escape_string($mycon, $firstProgramName);
            $updateCourseSql = "
      UPDATE tbl_applications
      SET course_id = (SELECT id FROM tbl_courses WHERE name = '$courseNameEscaped' LIMIT 1)
      WHERE id = $id
    ";
            mysqli_query($mycon, $updateCourseSql);
          }

          $mail = new PHPMailer(true);
          try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kentryanpagongpong@gmail.com';
            $mail->Password = 'wkzjqmcsebmjoxhh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kentryanpagongpong@gmail.com', 'NBSC Admissions');
            $mail->addAddress($applicant['email'], $applicant['firstname'] . ' ' . $applicant['lastname']);

            $mail->isHTML(true);
            $mail->Subject = 'NBSC Entrance Exam Result & Course Recommendation';
            $mail->Body = "
                    Dear " . htmlspecialchars($applicant['firstname']) . " " . htmlspecialchars($applicant['lastname']) . "!<br><br>

                    CONGRATULATIONS for PASSING the NBSC Entrance Examination!<br><br>

                    Below are the results of your examination:<br><br>

                    <b>OLSAT Score:</b> <strong>$score</strong><br>
                    <b>Remarks:</b> <em>" . htmlspecialchars($remarks) . "</em><br><br>

                    Based on the score you obtained, you are eligible to enroll in one of the following programs:<br><br>

                    {$programListString}<br><br>

                    Fill out the enrollment form provided in our NBSC Portal. Click the button provided below to access the enrollment form. To complete the enrollment process, please also submit the following credentials in hard copy to the Registrar's Office.<br><br>

                    <b>For Senior High School Completers</b><br>
                    Original Senior High School/ HS Card (Form 138)<br>
                    Original Certificate of Good Moral Character<br>
                    Photocopy of Birth Certificate (NSO/PSA)<br>
                    1 Long Folder<br>
                    1 pc 2x2 picture<br>
                    For Married Females: Photocopy of Marriage Certificate<br><br>

                    <b>For Transferee</b><br>
                    Transfer Credential/Honorable Dismissal w/ Evaluation Copy of Grades<br>
                    Original Certificate of Good Moral Character<br>
                    Photocopy of Birth Certificate (NSO)<br>
                    1 Long Folder<br>
                    1 pc 2x2 picture<br>
                    For Married Females: Photocopy of Marriage Certificate<br><br>

                    Go to NBSC Portal<br><br>

                    PLEASE DO NOT SHARE this message to anyone, only one success attempt is given per student.<br><br>

                    For further questions/concerns, feel free to contact:<br><br>

                    Northern Bukidnon State College<br>
                    Tankulan, Manolo Fortich, Bukidnon<br>
                    Admin Number: (088) 356-0102<br>
                    Registrar's Office Number: (088) 356-0101<br><br>

                    Thank you for choosing NBSC! We look forward to having you in our community.<br><br>

                    NBSC - OFFICIAL
                    ";

            $mail->send();
            echo "<script>alert('Email successfully sent to " . htmlspecialchars($applicant['firstname']) . ".'); window.location.href=window.location.href;</script>";
          } catch (Exception $e) {
            echo "<script>alert('Mailer Error: " . htmlspecialchars($mail->ErrorInfo) . "');</script>";
          }
        }
        ?>


      </table>
    </form>
  </div>
</body>

</html>