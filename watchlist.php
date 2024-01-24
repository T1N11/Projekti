<?php
   session_start();
   include 'php/dbconn.php';
   include 'php/MovieController.php';

   $dbconn = new DatabaseConnection();
   $dbconn->startConnection();

   
   $loggedIn = isset($_SESSION['user-email']);
   

   $movieController = new MovieController($dbconn);
   $userWatchlist = $loggedIn ? $movieController->loadWatchList($_SESSION['user-email']) : [];

   if (isset($_GET['added'])) {
        $added = $_GET['added'];
        
        if($added > 0) {
            echo "<h3 style='background-color: green;text-align:center;'>The movie has been added to your watchlist!</h3>";
        } else {
            echo "<h3 style='background-color: red;text-align:center;'>The movie has been removed from your watchlist!</h3>";
        }
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/watchlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="axes.png">
    <title>Document</title>
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
                            echo '<a href="watchlist.php" class="active">WatchList</a>';
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

    <main>
        <div class="movies">
            <?php
                if ($loggedIn) {
                    if (!empty($userWatchlist)) {
                        foreach ($userWatchlist as $movie) {
                            $movieUrl = 'watch.php?video=mp4/' . $movie['videofile'] . '&id=' . $movie['movieid'];
                            echo '<div class="movie">';
                            echo '<div class=poster>';   
                            echo '<a href="' . $movieUrl . '">';
                            echo '<img src="posters/' . $movie['poster'] . '" alt="">';
                            echo '</a>';
                            echo '<p>'. $movie['title'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<h1 style="color: white;text-align: center;">Your watchlist is empty. <br> Movies that you add to your watchlist are shown here!</h1>';
                    }
                } else {
                    echo '<h1 style="color: white;text-align: center;">Please Log In to have access to features like the WatchList!!!</h1>';
                }
            ?>
        </div>
    </main>
</body>
<script src="logout.js"></script>
<script src="js/about.js"></script>
</html>