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
            background-color: #f2f2f2; /* softer than pure white */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            width: 460px; /* wider box */
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(to right, #002855, #0077b6);
            color: white;
            padding: 24px;
            font-weight: bold;
            font-size: 18px;
        }

        .login-form {
            padding: 30px;
        }

        .login-form label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .login-form button {
            width: 100%;
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
        <form class="login-form" method="POST" action="login_process.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
