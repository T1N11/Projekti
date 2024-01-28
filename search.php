
<?php
    session_start();
    include 'php/dbconn.php';
    include 'php/MovieController.php';

    $loggedIn = isset($_SESSION['user-email']);

    $dbconn = new DataBaseConnection();
    $dbconn->startConnection();
    $database = $dbconn->getConnection();
    $movieController = new MovieController($database);

    $moviesData = [];

    $search_text = isset($_GET['search']) ? $_GET['search'] : null;

    $searchResults = $movieController->search($search_text);

    foreach ($searchResults as $result) {
        $movieData = $movieController->getMovieByID($result['movieid']);
        if ($movieData) {
            $moviesData[] = $movieData;
        }
    }


    $dbconn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <link rel="stylesheet" href="css/movies.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="icon" type="image/x-icon" href="axes.png">
        <title>MovieOrk</title>

    </head>
    <body>
        <header>
            <div class="topnav" id="myTopnav">
                <a href="landing.php">MovieOrk</a>
                <a href="landing.php">Home</a>
                <a href="movies.php?page=1">Movies</a>
                <a href="about.php">About</a>
                <a href="contact.php" >Contact Us </a>


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
        
        <?php if (!empty($moviesData)) { ?>
        <main>
            
            <section class="full">
                <div class="posters">
                    <div class="movies">
                        <?php foreach ($moviesData as $movie): 
                            $movieUrl = 'watch.php?video=mp4/' . $movie->getVideoFile() . '&id=' . $movie->getMovieId();
                        ?>
                            <div class="movie">
                                <a href=<?= $movieUrl ?>>
                                    <img src="posters/<?= $movie->getPoster() ?>" alt="">
                                </a>
                                <div class="movie-info">
                                    <a href="watch.php?video=mp4/<?= $movie->getVideoFile() ?>">
                                        <h3><?= $movie->getTitle(); ?></h3>
                                        <p><?= $movie->getRating() ?> · <?= $movie->getDuration() ?>min</p>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            
         
        </main>
                        
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

        <?php } else {
                        echo '<h1 id="warning">No Such Movie Could be Found!!!<h1>';
                    }
        ?>
        <script src="js/movies.js"></script>
        <script src="logout.js"></script>
    </body>
</html>

<style> 
    #warning {
        margin-top: 20vw    ;
        text-align: center;
        color: white;
    }
</style>