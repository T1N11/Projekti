<?php
    session_start();
    include 'php/dbconn.php';

    $loggedIn = isset($_SESSION['user-email']);
    
    $dbconn = new DataBaseConnection();
    $dbconn->startConnection();

    if(isset($_POST['submit'])) {
        $first = $_POST['first'];
        $last = $_POST['last'];
        $email = $_POST['email'];
        $message = mysqli_real_escape_string($dbconn->getConnection(), $_POST['message']);    



        if ($dbconn->add_Message($first, $last, $email, $message)) {
            echo "<h3 style='background-color: green;text-align:center;'>The form has been submitted succesfully!!!</h3>";
        };

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
        <a href="contact.php" class="active">Contact Us </a>

        <?php
            if ($loggedIn) {
                if ($_SESSION['user-role'] === 'admin') {
                    echo '<a href="dashboard.php">Dashboard</a>';
                }else{
                    echo '<a href="watchlist.php">WatchList</a>';
                }
                echo '<a href="javascript:void(0);" onclick="logout()">LogOut</a>';
            } else {
                echo '<a href="account.php">Account</a>';
            }
                    ?>
        <a href="javascript:void(0);" class="icon" onclick="resnav()">
            <i class="fa fa-bars"></i>
        </a>
    </div>



    <div class="sign-in">
        <h1>MovieOrk</h1>
        <p style="font-size: 13px;">Have questions, suggestions, or just want to say hello?<br> Fill out the form below, and we'll get back to you as soon as possible.

</p>

        <form action="" method="post" onsubmit="return validateReg();">
            <div id='error-message' style='color: red;'></div>
            <input type="text" name="first" id="username" placeholder="Enter your First Name" required>
            <input type="text" name="last" id="username" placeholder="Enter your Last Name" required>
            <input type="email" name="email" id="email" placeholder="Enter your email..." required>            <h3>Message!</h3>
            <p><textarea id="message" name="message" rows="8" cols="50" required></textarea><p>   
            <button name="submit" onclick="validateReg()" >Submit Form</button>
        </form>
    </div>
    <script src="js/account.js"></script>
    <script src="logout.js"></script>
    
</body>
</html>

