<?php
session_start();
include_once('../functions/functions.php'); // defines $pdo

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $firstname = $_POST['firstname'] ?? '';
  $lastname = $_POST['lastname'] ?? '';
  $middlename = $_POST['middlename'] ?? null;
  $suffix = $_POST['suffix'] ?? null;
  $gender = $_POST['gender_select'] ?? 'Male';  
  $gender_other = $_POST['gender_other'] ?? null;
  $dob = $_POST['dob'] ?? '';
  $place_of_birth = $_POST['place_of_birth'] ?? null;
  $nationality = $_POST['nationality'] ?? null;
  $email = $_POST['email'] ?? '';
  $contact = $_POST['contact'] ?? '';
  $address = $_POST['address'] ?? '';

  $status_applicant = $_POST['status_applicant_select'] ?? '';
  $status_applicant_other = $_POST['status_applicant_other'] ?? null;
  $high_school = $_POST['high_school'] ?? null;
  $year_graduated = $_POST['year_graduated'] ?? null;

  $parent_name = $_POST['parent_name'] ?? null;
  $parent_contact = $_POST['parent_contact'] ?? null;

  $exam_date = $_POST['exam_date'] ?? null;
  $exam_site = $_POST['exam_site'] ?? null;
  $application_status = $_POST['application_status'] ?? 'Pending';
  $course_id = $_POST['course_id'] ?? null;

  try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO tbl_applicants 
        (firstname, lastname, middlename, suffix, gender, gender_other, dob, place_of_birth, nationality, email, contact, address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
      $firstname,
      $lastname,
      $middlename,
      $suffix,
      $gender,
      $gender_other,
      $dob,
      $place_of_birth,
      $nationality,
      $email,
      $contact,
      $address
    ]);



    $applicant_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO tbl_applicant_status 
            (applicant_id, status_applicant, status_applicant_other, high_school, year_graduated)
            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
      $applicant_id,
      $status_applicant,
      $status_applicant_other,
      $high_school,
      $year_graduated
    ]);

    $stmt = $pdo->prepare("INSERT INTO tbl_applicant_guardians 
            (applicant_id, parent_name, parent_contact)
            VALUES (?, ?, ?)");
    $stmt->execute([
      $applicant_id,
      $parent_name,
      $parent_contact
    ]);

    $stmt = $pdo->prepare("INSERT INTO tbl_applications
            (applicant_id, exam_date, exam_site, application_status, course_id)
            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
      $applicant_id,
      $exam_date,
      $exam_site,
      $application_status,
      $course_id
    ]);

    $pdo->commit();

    // Set success message variable instead of echoing
    $success = "Application submitted successfully! Applicant ID: " . $applicant_id;
  } catch (PDOException $e) {
    $pdo->rollBack();
    // Set error message variable instead of echoing
    $error = "Failed to submit application: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <title>NBSC Online Admission System - Apply</title>

  <link href="components/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="components/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link href="components/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />

  <style>
    body {
      background: linear-gradient(to right, #00264d, #007acc);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .application-card {
      background: #fff;
      border-radius: 15px;
      padding: 40px 50px;
      max-width: 650px;
      width: 100%;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
      color: #000;
    }

    .application-card h2 {
      font-weight: 700;
      margin-bottom: 30px;
      text-align: center;
      color: #1e5a8a;
      letter-spacing: 1px;
    }

    label {
      font-weight: 600;
      color: #00264d;
      margin-bottom: 6px;
      display: block;
      font-size: 1rem;
    }

    input.form-control,
    select.form-select,
    textarea.form-control {
      font-size: 1.1rem;
      padding: 12px 14px;
      border: 2px solid #ccc;
      border-radius: 8px;
      transition: all 0.3s ease;
      width: 100%;
      box-sizing: border-box;
      resize: vertical;
      min-height: 45px;
    }

    textarea.form-control {
      min-height: 80px;
      padding-top: 10px;
    }

    input.form-control:focus,
    select.form-select:focus,
    textarea.form-control:focus {
      border-color: #1e5a8a;
      box-shadow: 0 0 8px #1e5a8a;
      outline: none;
    }

    .mb-3 {
      margin-bottom: 22px;
    }

    .btn-submit {
      background-color: #1e5a8a;
      color: #fff;
      width: 100%;
      padding: 14px 0;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 700;
      border: none;
      margin-top: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      letter-spacing: 0.8px;
    }

    .btn-submit:hover {
      background-color: #16486d;
    }

    .alert {
      margin-top: 20px;
      font-size: 1rem;
      padding: 14px 20px;
      border-radius: 10px;
    }

    a[title="Back to Home"]:hover {
      color: #003f66;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="application-card">


    <div style="text-align: right;">
      <a href="../index.php" title="Back to Home" style="text-decoration: none; font-size: 35px; color: #1e5a8a; font-weight: bold;">&times;</a>
    </div>

    <h2>Student Admission Application</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success" role="alert"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="application.php" novalidate>
      <div class="mb-3">
        <label for="firstname">First Name *</label>
        <input type="text" class="form-control" id="firstname" name="firstname" required
          value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="lastname">Last Name *</label>
        <input type="text" class="form-control" id="lastname" name="lastname" required
          value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="middlename">Middle Name</label>
        <input type="text" class="form-control" id="middlename" name="middlename"
          value="<?= htmlspecialchars($_POST['middlename'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="suffix">Suffix (Jr., III, etc.)</label>
        <input type="text" class="form-control" id="suffix" name="suffix"
          value="<?= htmlspecialchars($_POST['suffix'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="gender">Gender *</label>
        <select class="form-select" id="gender" name="gender_select" required>
          <option value="" disabled <?= empty($_POST['gender_select']) ? 'selected' : '' ?>>-- Select Gender --</option>
          <option value="Male" <?= (($_POST['gender_select'] ?? '') === 'Male') ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= (($_POST['gender_select'] ?? '') === 'Female') ? 'selected' : '' ?>>Female</option>
          <option value="Other" <?= (($_POST['gender_select'] ?? '') === 'Other') ? 'selected' : '' ?>>Other</option>
        </select>
      </div>

      <div class="mb-3" id="gender_other_div" style="display: none;">
        <label for="gender_other">Please specify your gender *</label>
        <input type="text" class="form-control" id="gender_other" name="gender_other" value="<?= htmlspecialchars($_POST['gender_other'] ?? '') ?>" />
      </div>

      <script>
        const genderSelect = document.getElementById('gender');
        const genderOtherDiv = document.getElementById('gender_other_div');
        const genderOtherInput = document.getElementById('gender_other');

        function toggleOtherGender() {
          if (genderSelect.value === 'Other') {
            genderOtherDiv.style.display = 'block';
            genderOtherInput.required = true;
          } else {
            genderOtherDiv.style.display = 'none';
            genderOtherInput.required = false;
            genderOtherInput.value = '';
          }
        }

        genderSelect.addEventListener('change', toggleOtherGender);

        // On page load - to maintain state if form is reloaded
        window.onload = toggleOtherGender;
      </script>


      <div class="mb-3">
        <label for="place_of_birth">Place of Birth</label>
        <input type="text" class="form-control" id="place_of_birth" name="place_of_birth"
          value="<?= htmlspecialchars($_POST['place_of_birth'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="nationality">Nationality</label>
        <input type="text" class="form-control" id="nationality" name="nationality"
          value="<?= htmlspecialchars($_POST['nationality'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="high_school">High School Name</label>
        <input type="text" class="form-control" id="high_school" name="high_school"
          value="<?= htmlspecialchars($_POST['high_school'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="year_graduated">Year Graduated</label>
        <input type="number" min="1900" max="<?= date('Y') ?>" class="form-control" id="year_graduated" name="year_graduated"
          value="<?= htmlspecialchars($_POST['year_graduated'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="parent_name">Parent/Guardian Name</label>
        <input type="text" class="form-control" id="parent_name" name="parent_name"
          value="<?= htmlspecialchars($_POST['parent_name'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="parent_contact">Parent/Guardian Contact Number</label>
        <input type="tel" class="form-control" id="parent_contact" name="parent_contact"
          value="<?= htmlspecialchars($_POST['parent_contact'] ?? '') ?>" />
      </div>


      <div class="mb-3">
        <label for="status_applicant">Applicant Status *</label>
        <select class="form-select" id="status_applicant" name="status_applicant_select" required>
          <option value="" disabled <?= empty($_POST['status_applicant_select']) ? 'selected' : '' ?>>-- Select Status --</option>
          <option value="Senior High Graduate" <?= (($_POST['status_applicant_select'] ?? '') === 'Senior High Graduate') ? 'selected' : '' ?>>Senior High Graduate</option>
          <option value="ALS Graduate" <?= (($_POST['status_applicant_select'] ?? '') === 'ALS Graduate') ? 'selected' : '' ?>>ALS Graduate</option>
          <option value="Transferee" <?= (($_POST['status_applicant_select'] ?? '') === 'Transferee') ? 'selected' : '' ?>>Transferee</option>
          <option value="Shiftee" <?= (($_POST['status_applicant_select'] ?? '') === 'Shiftee') ? 'selected' : '' ?>>Shiftee</option>
          <option value="Others" <?= (($_POST['status_applicant_select'] ?? '') === 'Others') ? 'selected' : '' ?>>Others</option>
        </select>
      </div>

      <div class="mb-3" id="status_other_div" style="display: none;">
        <label for="status_applicant_other">Please specify your status *</label>
        <input type="text" class="form-control" id="status_applicant_other" name="status_applicant_other" value="<?= htmlspecialchars($_POST['status_applicant_other'] ?? '') ?>" />
      </div>

      <script>
        const statusSelect = document.getElementById('status_applicant');
        const statusOtherDiv = document.getElementById('status_other_div');
        const statusOtherInput = document.getElementById('status_applicant_other');

        function toggleOtherStatus() {
          if (statusSelect.value === 'Others') {
            statusOtherDiv.style.display = 'block';
            statusOtherInput.required = true;
          } else {
            statusOtherDiv.style.display = 'none';
            statusOtherInput.required = false;
            statusOtherInput.value = '';
          }
        }

        statusSelect.addEventListener('change', toggleOtherStatus);

        // Maintain visibility on page reload
        window.onload = toggleOtherStatus;
      </script>


      <div class="mb-3">
        <label for="dob">Date of Birth *</label>
        <input type="date" class="form-control" id="dob" name="dob" required value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="email">Email Address *</label>
        <input type="email" class="form-control" id="email" name="email" required
          value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="contact">Contact Number *</label>
        <input type="tel" class="form-control" id="contact" name="contact" required
          value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="address">Address *</label>
        <textarea class="form-control" id="address" name="address" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label for="course_id">Course Applying For *</label>
        <select class="form-select" id="course_id" name="course_id" required>
          <option value="" disabled <?= empty($_POST['course_id']) ? 'selected' : '' ?>>-- Select Course --</option>
          <?php
          try {
            $stmtCourses = $pdo->query("SELECT id, code, name FROM tbl_courses ORDER BY name ASC");
            while ($course = $stmtCourses->fetch(PDO::FETCH_ASSOC)) {
              $selected = (($_POST['course_id'] ?? '') == $course['id']) ? 'selected' : '';
              echo '<option value="' . htmlspecialchars($course['id']) . '" ' . $selected . '>'
                . htmlspecialchars($course['code'] . ' - ' . $course['name'])
                . '</option>';
            }
          } catch (PDOException $e) {
            echo '<option disabled>Error loading courses</option>';
          }
          ?>
        </select>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="consent" name="consent" required <?= isset($_POST['consent']) ? 'checked' : '' ?> />
        <label class="form-check-label" for="consent">I confirm that the information provided is true and correct *</label>
      </div>

      <button type="submit" class="btn-submit">Submit Application</button>
    </form>
  </div>
</body>

</html>