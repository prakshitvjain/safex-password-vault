<?php
ini_set('session.use_strict_mode', 1);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true ){
    header("Location: login.php");
    exit;
    //hello
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeX</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
   <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"> -->

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
        <div class="tipBox">
            <p class="tip">Security Tip: Always use safex generated passwords to secure your accounts.<a href="generator.php">Click here</a></p>
        </div>
        <!-- //Extra Functionality , not required now
        <div class="filterBar">
            <i class="bi bi-filter-square-fill"></i>
            <label for="category" class="filterText">Category</label>
            
            <div class="category">
                <span class="selectedCategory" id="selectedCategory">All</span>

                <ul class="dropdownContent" id="categoryList">
                    <li class="categoryItem" onclick="selectCategory('All')">All</li>
                    <li class="categoryItem" onclick="selectCategory('Banking')">Banking</li>
                    <li class="categoryItem" onclick="selectCategory('Social Media')">Social Media</li>
                    <li class="categoryItem" onclick="selectCategory('Education')">Education</li>
                    <li class="categoryItem" onclick="selectCategory('Office')">Office</li>
                    <li class="categoryItem" onclick="selectCategory('Secret')">Secret</li>
                </ul>
            </div>
            <i class="bi bi-chevron-down dropdownButton" onclick="toggleDropdown()"></i>
        </div>
        -->
        <div class="indexBar">
            <p class="indexName">Name</p>   
            <i class="bi bi-three-dots-vertical"></i>
        </div>
        <div class="itemsContainer">
            <ul class="itemsList" id="itemsList">
                    
            </ul>
        </div>
        <button class="addItemButton" onclick="toggleAddItemContainer()">+</button>
        <div class="overlay" id="overlay">
        <div class="addItemContainer" id="addItemContainer">
            <form method='POST' onsubmit="return validateForm()">
                <div class="credentials" >
                <label>Name:</label>
                <br>
                <input name="name" id="name" type="text" placeholder="Enter a name" maxlength="30" required>
                <span id="nameError"></span>
                <br>
                <label>Username:</label>
                <br>
                <input name="username" id="username" type="text" placeholder="Enter the username" maxlength="30" required>
                <span id="usernameError"></span>
                <i class="bi bi-copy c1" title="Copy Username" onclick="copyText(username)"></i>
                <br>
                <label>Password:</label>
                <br>
                <input name="password" id="password" type="password" oninput="checkPasswordStrength()" placeholder="Enter the password" maxlength="30" required >
                <i class="bi bi-eye-slash" id="togglePassword" onclick="togglePass()"></i>
                <i class="bi bi-copy c2" title="Copy Password" onclick="copyText(password)"></i>
                <span id="passwordError"></span>
                </div>
                <div class="notesDiv">
                    <textarea name="notes" class="notesInput" placeholder="Secure notes" maxlength="150" id="notes"></textarea>
                </div>
                <br>
                <div class="passwordStrengthContainer">
                
                    <p class="passwordStrength" id="passwordStrength">Password Strength:</p>
                    <div class="strengthBar" id="strengthBar">
                        <div class="strength" id="strength"></div>
                    </div>
                </div>
                <br>
                <div class="generatorDiv">
                    <p>Password Generator</p>
                    <input id="generatedPassword" type="text" placeholder=" Generate strong passwords">
                    <br>
                    <div class="genCp">
                    <p class="generate" onclick="generateRandomPassword()">Generate</p>
                    <p class="copy" onclick="copyText(generatedPassword)">Copy</p>
                    </div>
                </div>
                <button type="submit" class="submit" id="submit" name="submit">Save</button>
                <button onclick="toggleAddItemContainer()" class="cancel">cancel</button>
            </form>
        </div>
        </div>
    
    
    </div>
<script src="main.js"></script>
</body>
</html>
