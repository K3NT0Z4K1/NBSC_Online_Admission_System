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
                echo "<script>window.location.href = 'public/dashboard.php';</script>";
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
    <meta charset="UTF-8">
    <title>Admin Officer Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f7f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
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

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 80%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
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
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="submit">Login</button>
        </form>
    </div>

</body>
</html>
