<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
  echo json_encode(['error' => true, 'message' => 'Unauthorized!']);
  exit;
}

require 'db_config_main.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $user_id = $_SESSION['user_id'];
  // $item_id = $_POST['item_id'];
  $data = json_decode(file_get_contents("php://input"),true);
  $item_id = $data['item_id'];

  $query = "DELETE FROM items WHERE item_id=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('i',$item_id);

  if($stmt->execute()){
    echo json_encode(['error' => false, 'message' => 'Item Successfully Deleted']);
  }
  else{
    echo json_encode(['error' => true, 'message' => 'Cannot Delete']);
  }
}
else {
  echo json_encode(['error' => true, 'message' => 'Invalid Request']);
}
?>
