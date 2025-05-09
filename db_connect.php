<?php

$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "db_admission"; 

$mycon = new mysqli($servername, $username, $password, $dbname);

if ($mycon->connect_error) {
    die("Connection failed: " . $mycon->connect_error);
}

echo "Connected successfully";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $mycon->prepare($sql);
    $stmt->bind_param("ss", $user_email, $user_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Login successful!";
        // Redirect to another page or start a session
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}
?>