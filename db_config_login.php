<?php 
require 'config.php';

$db_servername = $_ENV['DB_HOST'];
$db_username = $_ENV['DB_USERNAME_LOGIN'];
$db_password = $_ENV['DB_PASSWORD_LOGIN'];
$db_name = $_ENV['DB_NAME'];

$conn = new mysqli($db_servername,$db_username,$db_password,$db_name);

if ($conn->connect_error){
        die("connection failed: " . $conn->connect_error);
}

?>
