<?php
include_once('../functions/db_connect.php');
session_start();

if (isset($_POST["submit"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tbl_admin WHERE username = '$username' AND password = '$password'";
    $res = $mycon->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_array()) {
            $_SESSION['user'] = $row['User_value'];
            $user = $_SESSION['user'];

            echo "<script>alert('Welcome $user');</script>";
            echo "User: " . $_SESSION['user'];
            echo "<script>window.location.href = 'dashboard/dashboard.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Unknown username and password');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin Officer Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color:rgb(186, 186, 186);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;

            /* Start hidden for fade-in */
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        /* Fade-in class */
        body.fade-in {
            opacity: 1;
        }

        /* Fade-out class */
        body.fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
        }

        .login-container {
            background-color: #f2f2f2;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            width: 460px;
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(to right, #002855, #0077b6);
            color: white;
            padding: 24px;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }

        .login-form {
            padding: 30px;
            text-align: center;
        }

        .login-form label {
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

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 12px 40px 12px 12px; /* padding-right for icon space */
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

        .login-form button {
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

        .login-form button:hover {
            background-color: #2980b9;
        }

        .login-form p {
            margin-top: 20px;
            font-size: 14px;
        }

        .login-form a {
            color: #0077b6;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h2>Welcome, Log into your account</h2>

    <div class="login-container">
        <div class="login-header">Admin Officer Login</div>
        <form class="login-form" method="POST">
            <label for="username">Username</label>
            <div class="input-group">
                <input type="text" id="username" name="username" required />
            </div>

            <label for="password">Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password" required />
                <!-- Eye icon SVG -->
                <svg class="toggle-password" id="togglePassword" viewBox="0 0 24 24" aria-hidden="true" tabindex="0"
                    role="button" aria-label="Toggle password visibility">
                    <path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </div>

            <button type="submit" name="submit">Login</button>

            <p>Don't have an account? <a href="signup.php" id="signUpLink">Sign Up</a></p>
        </form>
    </div>

    <script>
        // Fade in body on load
       
        // Toggle password visibility
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

         window.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('fade-in');
        });


        
        const signUpLink = document.getElementById('signUpLink');

        signUpLink.addEventListener('click', function (e) {
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
