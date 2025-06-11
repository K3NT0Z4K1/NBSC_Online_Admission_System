<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Admin Officer Sign Up</title>

  <?php

  include_once('../functions/db_connect.php'); 
  if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $stmt = $mycon->prepare("INSERT INTO tbl_admin (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
      echo "<script>alert('Registration successful!');</script>";
    } else {
      echo "<script>alert('Registration failed: " . $stmt->error . "');</script>";
    }

    $stmt->close();
  }
  ?>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: rgb(186, 186, 186);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;

      opacity: 0;
      transition: opacity 0.5s ease;
    }

    body.fade-in {
      opacity: 1;
    }

    body.fade-out {
      opacity: 0;
    }

    h2 {
      color: #333;
      margin-bottom: 30px;
    }

    .signup-container {
      background-color: #f2f2f2;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      border-radius: 12px;
      width: 460px;
      overflow: hidden;
    }

    .signup-header {
      background: linear-gradient(to right, #002855, #0077b6);
      color: white;
      padding: 24px;
      font-weight: bold;
      font-size: 18px;
      text-align: center;
    }

    .signup-form {
      padding: 30px;
      text-align: center;
    }

    .signup-form label {
      display: block;
      margin-bottom: 8px;
      color: #333;
      font-weight: 500;
      text-align: left;
      margin-left: 10%;
    }

    .input-group {
      position: relative;
      width: 80%;
      margin: 0 auto 20px;
    }

    .signup-form input[type="text"],
    .signup-form input[type="password"] {
      width: 100%;
      padding: 12px 40px 12px 12px;
      /* padding-right for icon space */
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      box-sizing: border-box;
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      width: 24px;
      height: 24px;
      fill: #555;
      transition: fill 0.3s ease;
    }

    .toggle-password:hover {
      fill: #0077b6;
    }

    .signup-form button {
      width: 80%;
      padding: 12px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
    }

    .signup-form button:hover {
      background-color: #2980b9;
    }

    .signup-form p {
      margin-top: 20px;
      font-size: 14px;
    }

    .signup-form a {
      color: #0077b6;
      text-decoration: none;
      font-weight: bold;
    }

    .error {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

  <h2>Create an Admin Officer Account</h2>

  <div class="signup-container">
    <div class="signup-header">Sign Up</div>
    <form class="signup-form" method="POST">
      <label for="username">Username</label>
      <div class="input-group">
        <input type="text" id="username" name="username" required />
      </div>

      <label for="password">Password</label>
      <div class="input-group">
        <input type="password" id="password" name="password" required />
        <!-- Eye icon SVG -->
        <svg class="toggle-password" id="togglePassword" viewBox="0 0 24 24" aria-hidden="true" tabindex="0" role="button" aria-label="Toggle password visibility">
          <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z" />
          <circle cx="12" cy="12" r="3" />
        </svg>
      </div>

      <button type="submit" name="register">Register</button>

      <p>Already have an account? <a href="login.php" id="logInLink">Login here</a></p>
    </form>
  </div>

  <script>
    // On DOM load, add fade-in class to body
    window.addEventListener('DOMContentLoaded', () => {
      document.body.classList.add('fade-in');
    });

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);

      if (type === 'text') {
        togglePassword.style.fill = '#0077b6';
      } else {
        togglePassword.style.fill = '#555';
      }
    });

    togglePassword.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        togglePassword.click();
      }
    });

    // Fix fade-out effect on link click
    const logInLink = document.getElementById('logInLink');

    logInLink.addEventListener('click', function(e) {
      e.preventDefault();

      document.body.classList.remove('fade-in');
      document.body.classList.add('fade-out');

      setTimeout(() => {
        window.location.href = logInLink.href;
      }, 500); // match CSS transition time
    });
  </script>

</body>

</html>