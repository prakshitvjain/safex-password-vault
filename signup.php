<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db_config_signup.php';
require 'encryption.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $cnfPassword = trim($_POST['cnfPassword'] ?? '');

    $error_message='';

    $query1 = "SELECT username FROM users;";
    $result1 = $conn->query($query1);
    if ($result1->num_rows > 0){
        while ($row = $result1->fetch_assoc()){
            $decryptedUsername = decrypt($row['username']);
            if ($decryptedUsername == $username){
                $error_message ="Username is already taken!";
                break;
            }
        }
    }
    else {
        $error_message ="No usernames found in the database.";
    }

    if ($error_message) {
        $_SESSION['error_message'] = $error_message;
        header("Location: signup.php");
        exit;
    }

    if ($password == $cnfPassword) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        // https://encryption-decryption.mojoauth.com/aes-256-encryption--php/
        $enc_Username = encrypt($username);
        $enc_Password = encrypt($hashedPassword);
        $query = "INSERT INTO users (username,hashed_password) VALUES (?,?);";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss",$enc_Username,$enc_Password);

        if($stmt->execute()){
            header("Location: login.php");
            exit;
        }
        else {
            $_SESSION['error_message'] = "error occured";
            header("Location: signup.php");
            exit;
        }
    }
    else {
        $_SESSION['error_message'] = "Passwords do not match! Please try again :)";
        header("Location: signup.php");
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeX Sign-Up</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
<style>
body {
    align-items: center;
    justify-content: center;
    background-color: rgb(255, 255, 255);
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;    
    -ms-user-select: none; 
}
.container {
    margin-left: auto;
    margin-right: auto;
    height: 500px;
    width: 400px;
    background-color: rgb(20, 20, 117);
    justify-content: center;
    margin-top: 63px;
    border-radius: 10px;
    border: 1px solid rgb(0, 0, 0);
    box-shadow: 1px 1px 25px rgb(80, 80, 80);
}
label {
    margin-top: 10px;
    padding:20px;
    padding-top:25px;
    font-size: 20px;
    color: white;
    font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
}
#unameInfoButton {
    color: rgb(255, 255, 255);
    margin-left: -15px;
    font-size: 15px;
    background-color: rgb(58, 58, 58);
    border-radius: 200px;
    position: relative;
}
.unamePopup {
    z-index:1;
    position: absolute;
    color: white;
    background-color: black;
    opacity: 0.8;
    padding: 7px;
    margin-left: 86px;
    bottom: 99%;
    visibility:hidden;
    border-radius: 5px;
    font-size: smaller;
    text-align: center;
    width: 190px;
}
.passPopup{
    color: white;
    border-radius: 5px;
    padding: 7px;
    z-index:1;
    position: absolute;
    background-color: black;
    opacity: 0.8;
    font-size: smaller;
    width: 200px;
    text-align: center;
    bottom: 95%;
    margin-left: 71px;
    visibility: hidden;
}
.showPopup {
    visibility: visible;
}

input {
    display:flex;
    margin: 19px;
    margin-top: 5px;
    width: 87%;
    height:30px;
    border-radius: 7px;
    border: 1.5px solid gray;
    font-size: 16px;
    padding-left: 8px;
}
#password , #cnfPassword {
    display:inline-block;
    margin-right: 0;
}
.warning {
    color: rgb(255, 182, 182);
    font-size: 13px;
    margin-top: 0;
    width: 350px;
    margin-left: 23px;
    text-align: center;
}
i {
    cursor: pointer;
    font-size: larger;
    margin-left: -32px;
    position: absolute;
    background-color: white;
    width:30px;
    text-align: center;
    align-items: center;
    justify-content: center;
    padding-top: 2px;
    height: 15px;
    margin-top: 7px;
    border-radius: 5px;
}

.submit {
    width: 89%;
    height:38px;
    background-color: green;
    color: white;
    font-weight: bold;
    margin-right: 23px;
    margin-top: -6px;
    font-size: 14px;
    cursor: pointer;
    border: none;
}
.submit:hover{
    background-color: darkgreen;
}

span {
    justify-content: center;
    align-items: center;
    margin-left:auto;
    margin-right: auto;
    display: flex;
    font-size: large;
}
a {
    color: rgb(255, 255, 255);
    text-decoration: none;
    font-weight: bolder;
}
#usernameError , #passwordError ,#cnfPasswordError{
    color:red;
    font-size: 11px;
    float: left;
    margin-top: -17px;
    margin-left: 20px;
}
h1 {
    color: aliceblue;
    margin-left: 70px;
    margin-top: 30px;
}
@media screen and (max-width:900px){
    body {
        background-image: none;
    }
    
}
.loginLink {
    color: aliceblue;
    text-align: center;
    width: 240px;
    margin-left: 75px;
    font-size: 17px;
    margin-top: -4px;
}
</style>
</head>
<body>

    <?php if (isset($_SESSION['error_message'])): ?>
    <div id='errormessage' style="z-index:10000;color:red;text-align:center;">
        <h2> <?php echo $_SESSION['error_message']; ?></h2>
    </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="container">
        <h1 class="heading">Welcome to SafeX</h1>
        <br>
        <br>
        <form method="post" onsubmit="return validateForm()">
            <label>Create Username:</label>
            <i class="bi-info-circle" id="unameInfoButton" onclick="showUserInfo()"><span class="unamePopup" id="unamePopup">Username should be between 8 to 30 characters. Only alphabets, numbers and underscores are allowed</span></i>
            <br>
            <input name="username" id="username" type="text" placeholder="Enter your username" minlength="8" maxlength="30" required>
            <span id="usernameError"></span>
            <label>Create Password:</label>
            <i class="bi-info-circle" id="unameInfoButton" onclick="showPassInfo()"><span class="passPopup" id="passPopup">Password must be atleast 16 characters. All characters are allowed except whitespaces. Must contain atleast two digits, two uppercase and two special characters (excluding '_' )</span></i>
            <br>
            <div>
                <input name="password" id="password" type="password" placeholder="Enter your password" minlength="15" maxlength="30" required >
                <i class="bi-eye-slash" id="togglePassword1" onclick="showPass(password,togglePassword1)"></i>
            </div>
            <span id="passwordError"></span>
            <label>Confirm Password:</label>
            <br>
            <div>
                <input name="cnfPassword" id="cnfPassword" type="password" placeholder="Enter your password" minlength="15" maxlength="30" required >
                <i class="bi-eye-slash" id="togglePassword2" onclick="showPass(cnfPassword,togglePassword2)"></i>
            </div>
            <span id="cnfPasswordError"></span>
            <p class="warning">Warning: If you forget this password, you will never ever be able to retrieve your account<p>
            <input type="submit" value="Create Account" class="submit">
        </form>
        
        <p class="loginLink">Already have an account? &nbsp;<a href="login.php">Login</a></p>
    </div>
<script>
let username = document.getElementById("username");
let password = document.getElementById("password");
let cnfPassword = document.getElementById("cnfPassword");

setTimeout(() => {
    document.getElementById('errormessage').style.display = 'none';
},5000);

function showPass(x,y){
    let type = x.getAttribute("type");
    type = (type == "password")? "text" : "password";
    x.setAttribute("type",type);
    // we are not creating DOM element for 'y' since the id name is passed to function as a element itself and not as a string.
    y.classList.toggle("bi-eye");
    let Color = y.style.color;
    Color = (Color == "blue")? "black":"blue";
    y.style.color = Color;
}

function showUserInfo(){
    let showUserInfo = document.getElementById("unamePopup");
    showUserInfo.classList.toggle("showPopup");
}

function showPassInfo(){
    let showPassInfo = document.getElementById("passPopup");
    showPassInfo.classList.toggle("showPopup");
}

let usernameError = document.getElementById("usernameError");
let passwordError = document.getElementById("passwordError");
let cnfPasswordError = document.getElementById("cnfPasswordError");

function validateForm(){
    let result = true;
    let re1 = /^\w{8,30}$/;
    // let re2 = /^(?=(.*[A-Z]){2})(?=(.*\d){2})(?=(.*[^\w\s_]){2})[\S]{16,30}$/; // external regex
    let re2 = /^(?=(.*[A-Z]){2})(?=(.*\d){2})(?=(.*[^\w\s]){2})[\S]{16,30}$/; // your regex // make sure to revise
    if (!re1.test(username.value) || username.value.trim() == ""){
        usernameError.innerHTML = "Enter valid username";
        result = false;
    }
    else {
        usernameError.innerHTML="";
        result = true;

        if (!re2.test(password.value) || password.value.trim() == ""){
            passwordError.innerHTML = "Enter valid password";
            result = false;
        }
        else {
            passwordError.innerHTML="";
            result = true;

            if (password.value!=cnfPassword.value) {
                cnfPasswordError.innerHTML = "Password don't match the above password";
                result = false;
            }
            else {
                cnfPasswordError.innerHTML="";
                result = true;
            }
        }
    }
    return result;
}

//nfelkf&*^67GHGLKO
</script>

</body>
</html>
