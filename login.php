<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

require 'db_config_login.php';
require 'encryption.php';

if ($_SERVER['REQUEST_METHOD']=='POST'){
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $query = "SELECT username FROM users;";
    $result = $conn->query($query);

    $invalid_username = true;
    // When signing up first user, below condition will cause an logic issue, make sure to add atleast one entry in DB manually.
    if($result->num_rows > 0){

        while ($row = $result->fetch_assoc()){

            $decryptedUsername = decrypt($row['username']);

            if ($username == $decryptedUsername){

                $invalid_username = false;
                $query1 = "SELECT user_id, hashed_password FROM users WHERE username=?;";
                $stmt = $conn->prepare($query1);
                $stmt->bind_param('s',$row['username']);
                $stmt->execute();
                $result1 = $stmt->get_result();

                $row1 = $result1->fetch_assoc();

                $decrypted_hash = decrypt($row1['hashed_password']);
                $passwordCheck = password_verify($password, $decrypted_hash);
                $user_id = $row1['user_id'];

                if ($passwordCheck == true){
                    session_regenerate_id(true);
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user_id;
                    header("Location: main.php");
                    exit;
                }
                else {
                    $_SESSION['error_message'] = "Invalid Credentials!";
                    header("Location: login.php");
                    exit;
                }
                break;
            }
        }
    }
    else {
        $_SESSION['error_message'] = "No usernames in DB";
        header("Location: login.php");
        exit;
    }
    if ($invalid_username == true){
        $_SESSION['error_message'] = "Invalid Credentials!";
        header("Location: login.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeX Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

<style>
body {
    background-color: rgb(255, 255, 255);
    align-items: center;
    background-image: url('background1.png');
    background-repeat: no-repeat;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;    
    -ms-user-select: none; 
}
.container {
    width:500px;
    height:200px;
    border: 2px solid rgb(121, 120, 120);
    border-radius: 20px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 200px;
    background-color: rgb(20, 20, 117);
    padding-top: 30px;
    box-shadow: 3px 3px 14px rgb(78, 78, 78);
    position: relative;
}
label {
    margin-top: 10px;
    padding:20px;
    padding-top:25px;
    font-size: 20px;
    color: white;
    font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    
}
input {
    display:flex;
    margin: 19px;
    margin-top: 5px;
    width: 90%;
    height:30px;
    border-radius: 7px;
    border: 1.5px solid gray;
    font-size: 16px;
    padding-left: 8px;
}
#password {
    display:inline-block;
    margin-right: 0;
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
    padding-top: 4px;
    height: 15px;
    margin-top: 7px;
    border-radius: 5px;
}

.submit {
    float: right;
    width: 90px;
    height:38px;
    background-color: green;
    color: white;
    font-weight: bold;
    margin-right: 23px;
    border: 1px solid white;
    margin-top: -9px;
    font-size: 14px;
    cursor: pointer;
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
    color: blue;
    text-decoration: none;
}
#usernameError , #passwordError {
    color:red;
    font-size: 11px;
    float: left;
    margin-top: -17px;
    margin-left: 20px;
}
@media screen and (max-width:900px){
    body {
        background-image: none;
    }
    
}
</style>
</head>
<body>
    <?php if (isset($_SESSION['error_message'])): ?>
    <div id="errormessage" style="color:red;z-index:10000;text-align:center;">
    <h2> <?php echo $_SESSION['error_message']; ?> </h2>
    </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <div class="container">
        <form method="post" onsubmit="return validateForm()">
            <label>Username:</label>
            <br>
            <input name="username" id="username" type="text" placeholder="Enter your username" minlength="8" maxlength="30" required>
            <span id="usernameError"></span>
            <label>Password:</label>
            <br>
            <div>
                <input name="password" id="password" type="password" placeholder="Enter your password" minlength="16" maxlength="30" required >
                <i class="bi-eye-slash" id="togglePassword" onclick="showPass(password,togglePassword)"></i>
            </div>
            <span id="passwordError"></span>
            <input type="submit" value="Login" class="submit">
        </form>
    </div>
    <br>
    <span>Don't have an account? &nbsp; <a href="signup.php"> Sign Up</a></span>

<script>

setTimeout(() => {
    document.getElementById('errormessage').style.display = 'none';
},5000);

const username = document.getElementById("username");
const password = document.getElementById("password");
let usernameError = document.getElementById("usernameError");
let passwordError = document.getElementById("passwordError");

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

function validateForm(){
    let re1 = /^\w+$/;
    let re2 = /^(?=(.*[A-Z]){2})(?=(.*\d){2})(?=(.*[^\w\s]){2}).{16,30}$/;
    let Temp = true
    if(!re1.test(username.value) || username.value == ""){
        Temp = false;
        usernameError.innerHTML = "Enter valid username";
    }
    else {
        usernameError.innerHTML="";
        result = true;

        if (!re2.test(password.value) || password.value == ""){
            passwordError.innerHTML = "Enter valid password";
            result = false;
        }
        else {
            passwordError.innerHTML="";
            result = true;
        }
    }
    return Temp;
}

</script>

</body>
</html>
