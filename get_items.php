<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true ){
  echo json_encode(['error' => true, 'message' => 'Unauthorized!']);
  exit;
}

require 'db_config_main.php';
require 'encryption.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM items WHERE user_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s',$user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0){
    $items = [];
    while($row = $result->fetch_assoc()) {
        
        $item_name = decrypt($row['name']);
        $item_username = decrypt($row['username']);
        $item_password = decrypt($row['password']);
        $item_id = $row['item_id'];
        $item_notes = decrypt($row['notes']);
        /*
        echo "<h3 style='z-index:10000;color:aqua;'>" . $item_name ." ". $item_username . " " . $item_password . "</h3>";
        */
        $items[] = [
            'id' => $item_id,
            'name' => $item_name,
            'username' => $item_username,
            'password' => $item_password,
            'notes' => $item_notes
        ];
    }

    echo json_encode(['error' => false , 'items' => $items]);
}
else {
    echo json_encode(['error' => true , 'message' => 'No items found!']);
}

?>
