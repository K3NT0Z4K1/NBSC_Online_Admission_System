<?php
session_start();
include_once('../functions/functions.php');  // Your existing functions and DB connection

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get and sanitize form inputs
  $fname = $conn->real_escape_string(trim($_POST['firstname']));
  $lname = $conn->real_escape_string(trim($_POST['lastname']));
  $dob = $_POST['dob'];
  $email = $conn->real_escape_string(trim($_POST['email']));
  $contact = $conn->real_escape_string(trim($_POST['contact']));
  $address = $conn->real_escape_string(trim($_POST['address']));
  $course = $conn->real_escape_string(trim($_POST['course']));

  // Basic validation
  if (empty($fname) || empty($lname) || empty($dob) || empty($email) || empty($contact) || empty($address) || empty($course)) {
    $error = 'Please fill in all required fields.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Invalid email format.';
  } else {
    // Insert into applicants table
    $sql_applicant = "INSERT INTO applicants (firstname, lastname, dob, email, contact, address, course) VALUES ('$fname', '$lname', '$dob', '$email', '$contact', '$address', '$course')";

    if ($conn->query($sql_applicant) === TRUE) {
      $applicant_id = $conn->insert_id; // get inserted applicant_id

      // Insert initial application record with default 'Pending' status
      $sql_application = "INSERT INTO applications (applicant_id, status) VALUES ($applicant_id, 'Pending')";

      if ($conn->query($sql_application) === TRUE) {
        $success = "Your application has been submitted successfully!";
        $_POST = array(); // reset form data
      } else {
        $error = "Failed to submit application status: " . $conn->error;
      }
    } else {
      $error = "Failed to submit applicant info: " . $conn->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <title>NBSC Online Admission System - Apply</title>

  <link href="components/img/apple-touch-icon.png" rel="apple-touch-icon" />

  <link href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet" />

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
      padding: 30px 40px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      color: #000;
    }

    .application-card h2 {
      font-weight: 600;
      margin-bottom: 25px;
      text-align: center;
      color: #1e5a8a;
    }

    label {
      font-weight: 600;
      color: #00264d;
    }

    .form-control:focus {
      border-color: #1e5a8a;
      box-shadow: 0 0 5px #1e5a8a;
    }

    .btn-submit {
      background-color: #1e5a8a;
      color: #fff;
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      border: none;
      margin-top: 15px;
    }

    .btn-submit:hover {
      background-color: #16486d;
    }

    .alert {
      margin-top: 15px;
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
        <label for="firstname" class="form-label">First Name *</label>
        <input type="text" class="form-control" id="firstname" name="firstname" required value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="lastname" class="form-label">Last Name *</label>
        <input type="text" class="form-control" id="lastname" name="lastname" required value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="dob" class="form-label">Date of Birth *</label>
        <input type="date" class="form-control" id="dob" name="dob" required value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address *</label>
        <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="contact" class="form-label">Contact Number *</label>
        <input type="tel" class="form-control" id="contact" name="contact" required value="<?= htmlspecialchars($_POST['contact'] ?? '') ?>" />
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Address *</label>
        <textarea class="form-control" id="address" name="address" rows="2" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label for="course" class="form-label">Course Applying For *</label>
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
