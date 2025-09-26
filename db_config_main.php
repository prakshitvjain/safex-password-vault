<?php
require 'config.php';
$db_servername = $_ENV['DB_HOST'];
$db_username = $_ENV['DB_USERNAME_MAIN'];
$db_password = $_ENV['DB_PASSWORD_MAIN'];
$db_name = $_ENV['DB_NAME'];

$conn = new mysqli($db_servername,$db_username,$db_password,$db_name);

if ($conn->connect_error){
    die("Connection Failed" . $conn->connect_error);
}

?>
