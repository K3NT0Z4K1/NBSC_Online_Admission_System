<?php
session_start();
include_once('../functions/functions.php');

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fname = trim($_POST['firstname']);
  $lname = trim($_POST['lastname']);
  $dob = $_POST['dob'];
  $email = trim($_POST['email']);
  $contact = trim($_POST['contact']);
  $address = trim($_POST['address']);
  $course = trim($_POST['course']);

  if (empty($fname) || empty($lname) || empty($dob) || empty($email) || empty($contact) || empty($address) || empty($course)) {
    $error = 'Please fill in all required fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Invalid email format.';
  } else {
    $success = 'Your application has been submitted successfully!';
    $_POST = array();
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
  </style>
</head>

<body>

  <div class="application-card">
    <h2>Student Admission Application</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="alert alert-success" role="alert"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" action="apply.php" novalidate>
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
        <label for="status_applicant">Applicant Status *</label>
        <select class="form-select" id="status_applicant" name="status_applicant" required>
          <option value="" disabled <?= empty($_POST['status_applicant']) ? 'selected' : '' ?>>-- Select Status --</option>
          <option value="Senior High Graduate" <?= (($_POST['status_applicant'] ?? '') === 'Senior High Graduate') ? 'selected' : '' ?>>Senior High Graduate</option>
          <option value="ALS Graduate" <?= (($_POST['status_applicant'] ?? '') === 'ALS Graduate') ? 'selected' : '' ?>>ALS Graduate</option>
          <option value="Transferee" <?= (($_POST['status_applicant'] ?? '') === 'Transferee') ? 'selected' : '' ?>>Transferee</option>
          <option value="Shiftee" <?= (($_POST['status_applicant'] ?? '') === 'Shiftee') ? 'selected' : '' ?>>Shiftee</option>
          <option value="Others" <?= (($_POST['status_applicant'] ?? '') === 'Others') ? 'selected' : '' ?>>Others</option>
        </select>
      </div>

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
        <textarea class="form-control" id="address" name="address"
          required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label for="course">Course Applying For *</label>
        <select class="form-select" id="course" name="course" required>
          <option value="" disabled <?= empty($_POST['course']) ? 'selected' : '' ?>>-- Select Course --</option>
          <option value="BSIT" <?= (($_POST['course'] ?? '') === 'BSIT') ? 'selected' : '' ?>>Bachelor of Science in Information Technology</option>
          <option value="BSCS" <?= (($_POST['course'] ?? '') === 'BSCS') ? 'selected' : '' ?>>Bachelor of Science in Computer Science</option>
          <option value="BSA" <?= (($_POST['course'] ?? '') === 'BSA') ? 'selected' : '' ?>>Bachelor of Science in Accountancy</option>
        </select>
      </div>

      <button type="submit" class="btn-submit">Submit Application</button>
    </form>
  </div>

  <script src="components/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>