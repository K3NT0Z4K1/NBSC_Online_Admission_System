<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>NBSC Online Admission System</title>
  <meta name="description" content="NBSC Online Admission System – Apply for entrance exams or login as an officer to manage applications.">
  <meta name="keywords" content="NBSC, admission, application, online portal, entrance exam">
  <meta name="author" content="K3NT0Z4K1 & Lloydy">

  <!-- Favicons -->
  <link rel="icon" href="components/img/favicon.png">
  <link href="components/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="components/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="components/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="components/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

  <?php
  include_once('functions/functions.php');
  session_start();
  ?>

  <style>
    body, html {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      height: 100%;
    }

    .welcome-container {
      height: 100vh;
      background: linear-gradient(to right, #00264d, #007acc);
      display: flex;
      justify-content: center;
      align-items: center;
      color: #fff;
      text-align: center;
      padding: 20px;
    }

    .welcome-card {
      background-color: #fff;
      color: #000;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      max-width: 500px;
      width: 100%;
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .welcome-card img {
      height: 80px;
      margin-bottom: 20px;
    }

    .welcome-card h2 {
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 24px;
    }

    .welcome-card p {
      font-size: 15px;
      margin-bottom: 25px;
      color: #444;
    }

    .welcome-card .btn {
      width: 100%;
      margin: 10px 0;
      padding: 12px;
      font-size: 16px;
      border-radius: 8px;
    }

    .btn-apply {
      background-color: #1e5a8a;
      color: #fff;
    }

    .btn-apply:hover {
      background-color: #16486d;
    }

    .btn-login {
      background-color: #2da4f2;
      color: #fff;
    }

    .btn-login:hover {
      background-color: #1c8ad1;
    }

    .credits {
      margin-top: 20px;
      font-size: 14px;
    }

    .credits a {
      color: #1e5a8a;
      text-decoration: none;
    }

    .credits a:hover {
      text-decoration: underline;
    }

    @media (max-width: 576px) {
      .welcome-card {
        padding: 30px 20px;
      }

      .welcome-card h2 {
        font-size: 20px;
      }

      .welcome-card img {
        height: 60px;
      }
    }
  </style>
</head>

<body>

  <div class="welcome-container">
    <div class="welcome-card">
      <img src="components/img/nbsclogo.png" alt="NBSC Logo">
      <h2>NBSC Online Admission System</h2>
      <p>Start your journey at NBSC by applying online. It's simple, secure, and fast.</p>
      <a href="public/application.php" class="btn btn-apply">Apply For Entrance</a>
      <a href="public/login.php" class="btn btn-login">Officer Login</a>
      <div class="credits mt-3">
        Designed by <a href="https://github.com/K3NT0Z4K1" target="_blank">K3NT0Z4K1 & Lloydy</a>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="components/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="components/javascript/main.js"></script>

</body>

</html>
