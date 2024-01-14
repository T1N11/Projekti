<?php
    include 'php/dbconn.php';

    if(isset($_POST['submit'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        $dbconnect = new DataBaseConnection();
        $conn = $dbconnect->startConnection();

        if ($dbconnect->insertData($username, $email, $password, $confirmPassword)) {
            header("Location: account.php");
            exit();
        };

        $dbconnect->closeConnection();
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
        <a href="landing.html">MovieOrk</a>
        <a href="landing.html">Home</a>
        <a href="movies.html" >Movies</a>
        <a href="about.html" >About</a>
        <a href="account.php" class="active">Account</a>
        <a href="javascript:void(0);" class="icon" onclick="resnav()">
            <i class="fa fa-bars"></i>
        </a>
    </div>



    <div class="sign-in">
        <h1>MovieOrk</h1>
        
        <form action="" method="post" onsubmit="return validateReg();">
            <div id='error-message' style='color: red;'></div>
            <input type="text" name="username" id="username" placeholder="Enter your name" required>
            <input type="email" name="email" id="email" placeholder="Enter your email..." required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
            <p class="recover">
                <a href="#">Recover Password</a>
            </p>
            <button name="submit" >Register</button>
            <!-- onclick='validateReg();' -->
            <div class="Already signed in?">
                Already signed in? <a href="account.php">Log in</a>
            </div>
        </form>
    </div>
    <script src="js/account.js"></script>
    
</body>
</html>

