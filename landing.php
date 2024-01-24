<?php
    session_Start();
    include 'php/dbconn.php';
    $loggedIn = isset($_SESSION['user-email']);

    $dbconn = new DatabaseConnection();
    $dbconn->startConnection();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/landing.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="icon" type="image/x-icon" href="axes.png">
        <title>MovieOrk</title>

</head>
    <body>
        <header>
            <div class="navbar">

                <div class="topnav" id="myTopnav"> 
                    <a href="landing.php" id="logo">MovieOrk</a>
                    <a href="landing.php" class="active">Home</a>
                    <a href="movies.php?page=1">Movies</a>
                    <a href="about.php">About</a>
                    <a href="contact.php">Contact Us </a>
                    
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
                    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                
            </div>
        </header>

        <main>
            
            <div class="info">
                
                <div>
                    <h2><?= $dbconn->load_Landing(1, 'landing') ?></h2>
                    <div class="searchbar">
                        <input type="search" placeholder="Search for a Movie....">
                        <button><i class="fa fa-search	"></i></button>
                    </div>
                </div>
            </div>


        </main>

        <footer>
            <footer> 
                <div class="foot">
                    <div class="about">
                        
                        <h3>About Us</h3>
                        <a href="">Terms of Service</a>
                        <a href="about.html">About Page</a>
                    </div>
                    <div class="contact">
                        <h3>Contact Us</h3>
                        <div class="socials">
                            <a href="#" class="fa fa-facebook"></a>
                            <a href="#" class="fa fa-instagram"></a>
                            <a href="#" class="fa fa-twitter"> </a>
                        </div>
                        
                    </div>
                    
                    <div><p style="font-size: small;">All rights reserved · UBT · 2023</p></div>
                </div> 
                
            </footer>
         
        </main>
        </footer>


    <script src="js/landing.js"></script>
    <script src="logout.js"></script>
    </body>
</html>
