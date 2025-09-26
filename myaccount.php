<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!==true){
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeX</title>
  <link rel="stylesheet" href="main.css">
  <style>
    body {
      background-color:rgb(186, 223, 255);
    }
    button{
      z-index:10;
      color:white;
      font-size:16px;
      border-radius:8px;
      background-color:rgba(235, 3, 3, 0.966);
      width:150px;
      height:40px;
      margin-top:40px;
      font-weight:bold;
    }
    button:hover {
      background-color:rgb(255, 44, 44);
    } 
    #logout{
      margin-top:63px;
      }
    .main {
      
      margin-top: 200px;
      display:flex;
    }
    .logoutContainer, .AccountDeleteContainer {
      background-color:rgb(17, 3, 54);
      font-size:20px;
      color:white;
      height:300px;
      width:230px;
      border-radius:12%;
      text-align:center;
      padding:20px;
    }
    .AccountDeleteContainer {
      margin-left:40px;
    }
    h1 {
      margin-left:280px;
      margin-top:120px;
    }
  </style>
</head>
<body>
    <div class="navBar">
        <p>SafeX</p>
        <hr class="tophr">
        <a href="main.php">My Safe</a><hr>
        <a href="generator.php">Generator</a><hr>
        <a href="myaccount.php">My Account</a><hr>
    </div>
    <div class="container">
        <div class="header">
            <p class="headerText">&nbsp;Hello<span></span>&nbsp;Welcome to SafeX password manager<p>
        </div>
        
    </div>
    <h1>Account Actions</h1>
    <div class="main">
      
      <div class="logoutContainer">
        <p><b>Logout from this device</b></p>
        <p><i>(Recommended)</i></p>
        <form method='POST' action='logout.php'>
          <button type="submit" id="logout" class="mainlogout" name='logout' value='logout'>Logout</button>
        </form>
      </div>
      <div class="AccountDeleteContainer">
        <p><b>Delete my Account</b></p>
        <p><i>&nbsp;Warning: your account and all your data will be permanently deleted</i></p>
        <form method='POST' action='delete_account.php'>
          <button type='submit' id="Delete" class="Delete" name='Delete' value='Delete Account'> Delete Account </button>
        </form>
      </div>
    </div>
    <script>
    
    </script>
</body>
</html>
