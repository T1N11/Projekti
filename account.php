
<?php
    session_start();
    include 'php/dbconn.php';
    include 'php/userController.php';


        

    $loggedIn = isset($_SESSION['user-email']);
    if($loggedIn) {
        header("Location: movies.php");
    }
    if(isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];



        $dbconn = new DataBaseConnection();
        $dbconn->startConnection();
        $database = $dbconn->getConnection();
        $userController = new UserController($database);
    
        $users = $userController->getUsers();
    
        $authenticated = $userController->authenticateUser($email, $password);


        if ($authenticated) {
            $role = $userController->is_Admin($email);
            $_SESSION['user-role'] = $role;
            $_SESSION['user-email'] = $email;
            if ($role === 'admin') {
                header('Location: dashboard.php');
            } else {
                header('Location: movies.php?page=1');
            }
            exit();
        }else {
            echo"<h3 style='color: white; background-color: red; text-align: center; '>The Email or password is wrong!!!!<h3>";

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
            <p>Would you like to, <br>log in!</p>
    
            <form action="" method="post" onsubmit="return validateLog()">
                <div id='error-message' style='color: red;'></div>
                <input type="email" name="email" id="email" placeholder="Enter your email..." required>
                <input type="password" name="password" placeholder="Password" required>
                <button name="submit" onclick="validateLog()" >Sign In</button>
    
                <div class="not-member">
                    Not a member? <a href="register.php">Register Now</a>
                </div>
            </form>
        </div>


        <script src="js/account.js"></script>
    </body>
</html>
