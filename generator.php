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
        .generatorDiv {
            z-index:10;
            color:white;
            margin-left: 300px;
            margin-top: 140px;
            font-size: 30px;
        }
        #generatedPassword {
        width:350px;
        height:50px;
        padding-left: 10px;
            font-size:16px;
        }
        .generate , .copy {
            
            height: 40px;
            font-size: 20px;
            width:171px;
            align-items:center;
            vertical-align:middle;
            padding-top:7px;
        }
        body {
            background-color:rgb(186,223,255);
            user-select: none;
            -webkit-user-select: none; 
            -moz-user-select: none;    
            -ms-user-select: none; 
        }
        
        .rangeBar {
            display: flex;
            align-items: center;
            font-size: 20px;
            color: black;
            gap: 8px;
            width: 320px;
        }

        .rangeBar input[type="range"] {
            flex-grow: 1;
            margin: 0;
            padding: 0;
            
            background: transparent;
        }
        #lengthInput {
            vertical-align:middle;
        
            
        }

        .heading{
            margin-bottom:40px;
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
        <form method='POST' action='logout.php'>
        <button type="submit" id="logout" class="logout" name='logout' value='logout'>Logout</button>
        </form>
            
        <div class="generatorDiv">
                <p class="heading">Password Generator</p>
                
                <p style="font-size:18px;margin-top:15px;margin-bottom:18px;">Please select the length of the password you desire to generate</p>
                
                <div class="rangeBar">
                    <span>8</span>
                    <input type='range' id="lengthInput" max="30" min="8" value="16">
                    <span>30</span>
                </div>                
                <p style="font-size:18px;margin-top:10px;margin-bottom:-10px;"> Selected Length: <output class="selectedLength" id="selectedLength" > </output></p>
                <br>
                <input id="generatedPassword" type="text" placeholder=" Generate strong passwords">
            <br>
            <div class="genCp">
                <p class="generate" onclick="generateRandomPassword()">Generate</p>
                <p class="copy" onclick="copyText(generatedPassword)">Copy</p>
            </div>
        </div>
    </div>
<script src="generator.js"></script> 
</body>
</html>
