<?php
    include 'php/dbconn.php';
    include 'php/MovieController.php';
    session_start();
    $loggedIn = isset($_SESSION['user-email']);

    $dbconn = new DataBaseConnection();
    $dbconn->startConnection();
    $database = $dbconn->startConnection();
    $movieController = new MovieController($database);

    $movieId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($movieId !== null) {
        $movieDetails = $movieController->getMovieByID($movieId);
        // print_r($movieDetails);
        if ($movieDetails !== null) {
            $_SESSION['current_movie'] = $movieDetails;
        }
    }

    if (isset($_POST['add'])) {
        $movieDetails = $movieController->getMovieByID($movieId);

        $movieController->addToWatchList($_SESSION['user-email'], $movieId);
        header("location: watchlist.php?added=1");
        echo "wtf";
    } 
    if(isset($_POST['remove'])) {
        $movieController->removeFromWatchList($_SESSION['user-email'], $movieId);
        header("location: watchlist.php?added=0");

    }
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
            <a href="movies.php?page=<?= $_SESSION['mov-page'] ?>">Movies</a>
            <a href="about.php" >About</a>
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
                <img id='info-poster' src="posters/<?= $movieDetails->getPoster() ?>" alt="">
            </div>
            <div class="info">
                <div class="watch-title">
                    <h2>Title:</h2>
                    <h2 id="title"><?= $movieDetails->getTitle() ?></h2>
                </div>
                <div class="imdb">
                    <h4>IMDB: </h4>
                    <h4 id="rating"></h4>
                    <i class="fa fa-star-half-full">   <?= $movieDetails->getRating()?></i>
                </div>

                <div class="description-container">
                    <p><b>Description: </b></p>
                    <p id="desc"><?= $movieDetails->getDescription()?></p>     
                </div>
                
                <div class="info-but-container">
                    <form action="" method='post' onsubmit="handleWatchlistAction()">
                        <?php  
                        if($loggedIn) {
                            if($_SESSION['user-role'] == 'user') {
                                $isInWatchlist = $movieController->inWatchList($_SESSION['user-email'], $movieId);

                                if ($isInWatchlist) {
                                    echo '<button style="background-color: red;" type="submit" name="remove" id="info-button"><b>-</b>Remove from WatchList</button>';
                                } else {
                                    echo '<button type="submit" name="add" id="info-button"><b>+</b> Add to WatchList</button>';
                                }
    
                            }
                        } 

                        ?>
                    </form>
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
<script src="logout.js"></script>

</html>
