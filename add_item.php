<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true ){
  echo json_encode(['error' => true, 'message' => 'Unauthorized!']);
  exit;
}

require "db_config_main.php";
require "encryption.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = trim($_POST['name'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $notes = trim($_POST['notes'] ?? '');
  $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

  if (empty($name) || empty($username) || empty($password)){
    echo json_encode(['error' => true, 'message' => 'Missing required fields!']);
    exit;
  }
  
  $enc_name = encrypt($name);
  $enc_username = encrypt($username);
  $enc_password = encrypt($password);
  $enc_notes = encrypt($notes);

  $user_id = $_SESSION['user_id'];
  
  if($id !== null){
    $query = "UPDATE items SET name=?, username=?, password=?, notes=? WHERE item_id =? AND user_id =?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssii', $enc_name, $enc_username, $enc_password, $enc_notes, $id, $user_id);
  }
  else {
    $query = "INSERT INTO items (name,username,password,notes,user_id) VALUES (?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssi',$enc_name,$enc_username,$enc_password,$enc_notes,$user_id);
  }
  if($stmt->execute()){
    echo json_encode(['error' => false, 'message' => 'Item successfully added']);
  }
  else {
    echo json_encode(['error' => true, 'message' => 'Failed to add item']);
  }
  $stmt->close();
  $conn->close();
}
else {
    echo json_encode(['error' => true, 'message' => 'Invalid Request']);
}

?>
