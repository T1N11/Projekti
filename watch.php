<?php
    session_start();
    $loggedIn = isset($_SESSION['user-email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="axes.png">
    <link rel="stylesheet" href="css/movies.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>


    <title>MovieOrk</title>
</head>
<body>
    <header>
        <div class="topnav" id="myTopnav">
            <a href="landing.php">MovieOrk</a>
            <a href="landing.php">Home</a>
            <a href="movies.php" >Movies</a>
            <a href="about.php" >About</a>
            <?php
                if($loggedIn) {
                    echo '<a href="" onclick="logout();">LogOut</a>';
                } else {
                    echo '<a href="account.php">Account</a>';
                }
            ?>
            <a href="javascript:void(0);" class="icon" onclick="resnav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
    </header>

    <div class="watch-container">
        <div class="video-player">
            <video id="movie-player" controls>
                <source id="video-source" src="" type="video/mp4">
            </video>
        </div>
    </div>
    <div class="info-container">
        <div class="info-sub-container">
            <div class="info-poster">
                <img id='info-poster' src="" alt="">
            </div>
            <div class="info">
                <div class="watch-title">
                    <h2>Title:</h2>
                    <h2 id="title"></h2>
                </div>
                <div class="imdb">
                    <h4>IMDB: </h4>
                    <h4 id="rating"></h4>
                    <i class="fa fa-star-half-full"></i>
                </div>

                <div class="description-container">
                    <p><b>Description: </b></p>
                    <p id="desc"></p>     
                </div>
                

            </div>
        </div>
    </div>

    <footer> 
        <div class="foot">
            <div class="about">
                
                <h3>About Us</h3>
                <a href="">Terms of Service</a>
                <a href="">About Page</a>
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

    <script src="js/movies.js"></script>
    <script src="logout.js"></script>
</body>
</html>