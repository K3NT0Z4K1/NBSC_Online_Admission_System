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

?>