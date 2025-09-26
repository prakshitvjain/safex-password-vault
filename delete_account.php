<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!==true){
  header('Location: login.php');
  exit;
}

require "db_config_main.php";

if($_SERVER["REQUEST_METHOD"] === 'POST'){
  $user_id = $_SESSION['user_id'];
  $query = 'DELETE FROM items WHERE user_id=?'; 
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i',$user_id);
  if($stmt->execute()){
    $query1 = 'DELETE FROM users WHERE user_id=?';
    $stmt1 = $conn->prepare($query1);
    if ($stmt1 === false){
      $conn->rollback();
      die('Delete user falied',$conn->error);
    }
    $stmt1->bind_param('i',$user_id);
    if($stmt1->execute()){
      session_destroy();
      header('Location: signup.php');
      exit;
    }
    else {
      echo "Error Occured";
    }
  }
  else {
    echo "Error Occured";
  }
}
else {
  echo "Ivalid Request Method";
}
?>
