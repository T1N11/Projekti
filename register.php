<?php
    session_start();
    include 'php/dbconn.php';
    include 'php/UserController.php';

    $dbconn = new DataBaseConnection();
    $dbconn->startConnection();
    $database = $dbconn->getConnection();
    $userController = new UserController($database);

    $loggedIn = isset($_SESSION['user-email']);
    if($loggedIn) {
        header("Location: movies.php");
    }

    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($userController->insertUser($username, $email, $password, $confirmPassword)) {
            header("Location: account.php");
            exit();
        } else {
            echo "<h3 style='background-color: red; text-align: center;'> Email already exists!</h3>";
        }

        $dbconn->closeConnection();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="axes.png">
    <title>MovieOrk</title>

</head>
<body>
    <div class="topnav" id="myTopnav">
        <a href="landing.php">MovieOrk</a>
        <a href="landing.php">Home</a>
        <a href="movies.php?page=1" >Movies</a>
        <a href="about.php" >About</a>
        <a href="contact.php">Contact Us </a>
        <a href="account.php" class="active">Account</a>
        <a href="javascript:void(0);" class="icon" onclick="resnav()">
            <i class="fa fa-bars"></i>
        </a>
    </div>



    <div class="sign-in">
        <h1>MovieOrk</h1>
        <p>Would you like to become a member<br> of the ORK?</p>

        <form action="" method="post" onsubmit="return validateReg();">
            <div id='error-message' style='color: red;'></div>
            <input type="text" name="username" id="username" placeholder="Enter your name" required>
            <input type="email" name="email" id="email" placeholder="Enter your email..." required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
            <button name="submit" onclick="validateReg()" >Register</button>
            <div class="Already signed in?">
                Already signed in? <a href="account.php">Log in</a>
            </div>
        </form>
    </div>
    <script src="js/account.js"></script>
    
</body>
</html>

