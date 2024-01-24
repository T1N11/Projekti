
<?php
    include 'php/dbconn.php';
    include 'php/MovieController.php';
    session_start();

    $_SESSION['mov-page'] = $_GET['page'];
    $loggedIn = isset($_SESSION['user-email']);

    $dbconn = new DataBaseConnection();
    $dbconn->startConnection();
    $movieController = new MovieController($dbconn);


    // $movieData = $dbconn->get_MovieData();
    $totalMovies = $movieController->totalMovies();
    $moviesPerPage = 12;
    $totalPages = ceil($totalMovies / $moviesPerPage);
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $startIndex = ($currentPage - 1) * $moviesPerPage;
    $movieData = $movieController->getByPage($startIndex, $moviesPerPage);


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
                <a href="movies.php?page=1" class="active">Movies</a>
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

        <main>
            <section class="full">
                <div class="ad">
                    <h1>MovieOrk</h1>
                    <h3>Your ad here</h3>
                </div>
                <div class="posters">
                    <div class="movies">
                        <?php foreach ($movieData as $movie): {
                                $movieUrl = 'watch.php?video=mp4/' . $movie['videofile'] . '&id=' . $movie['movieid'];
                        }?>
                            <div class="movie">
                                <a href=<?= $movieUrl ?>>
                                    <img src="posters/<?= $movie['poster'] ?>" alt="">
                                </a>
                                <div class="movie-info">
                                    <a href="watch.php?video=mp4/<?= $movie['videofile'] ?>">
                                        <h3><?= $movie['title'] ?></h3>
                                        <p><?= $movie['releaseyear'] ?> · <?= $movie['duration'] ?>min</p>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="ad2">
                    <h1>MovieOrk</h1>
                    <h3>Your ad here</h3>
                </div>
            </section>

-             <div class="navpage">
                <ul class="navpage-list">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="?page=<?= $i ?>" <?= ($i == $currentPage) ? 'class="active"' : '' ?>>
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>

            

         
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
        <script src="js/movies.js"></script>
        <script src="logout.js"></script>
    </body>
</html>


