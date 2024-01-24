    <?php
        session_start();
        include 'php/dbconn.php';
        $loggedIn = isset($_SESSION['user-email']);

        $dbconn = new DatabaseConnection();
        $dbconn->startConnection();

        if(isset($_POST['submit'])) {
           
            $message = mysqli_real_escape_string($dbconn->getConnection(), $_POST['message']);

            if (!$loggedIn) {
                $email = $_POST['email'];
                $result = $dbconn->add_Message($email, $message);
            } else {
                $result = $dbconn->add_Message($_SESSION['user-email'], $message);
            }
            
            if (!$result) {
                echo "Error: " . mysqli_error($dbconn->getConnection());
            }
            echo "<h3 style='background-color: green;text-align:center;'>Form has been Submited!</h3>";
        }
    ?>

    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="icon" type="image/x-icon" href="axes.png">
            <link rel="stylesheet" href="css/about.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <title>MovieOrk</title>

        </head>
        <body>
            <header>
                <div class="topnav" id="myTopnav">
                    <a href="landing.php">MovieOrk</a>
                    <a href="landing.php">Home</a>
                    <a href="movies.php?page=1" >Movies</a>
                    <a href="about.php" class="active">About</a>
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
                <div class="title">
                    <h1>MOVIE</h1>
                    <h1>ORK</h1>
                </div>

                <div class="container">
                    <div class="sub-container-1">
                        <h3>About Us</h3>

                        <section id="about">
                            <p><?= $dbconn->load_Landing(1, 'about') ?></p>
                    
                            <p><?= $dbconn->load_Landing(2, 'about') ?></p>
                    
                            <p><?= $dbconn->load_Landing(3, 'about') ?></p>
                    
                            <p><?= $dbconn->load_Landing(4, 'about') ?></p>
                        </section>
                    </div>

                    <div class="sub-container-2">
                        <!-- Slideshow container -->
                        <div class="slideshow-container">
                            <h2 style="text-align: center;">Our Teams!</h2>
                            <div class="mySlides fade">
                                    <img src="images/<?= $dbconn->load_Landing(5, 'about')?>" style="width:100%">
                                <div class="text">Team Work!</div>
                            </div>

                            <div class="mySlides fade">
                                    <img src="images/<?= $dbconn->load_Landing(7, 'about')?>" style="width:100%">
                                <div class="text">Patience!</div>
                            </div>

                            <div class="mySlides fade">
                                <img src="images/<?= $dbconn->load_Landing(6, 'about')?>" style="width:100%">
                                <div class="text">Most Importantly Having Fun!!</div>
                            </div>

                            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                            <a class="next" onclick="plusSlides(1)">&#10095;</a>
                        </div>
                            <br>

                            <div style="text-align:center">
                            <span class="dot" onclick="currentSlide(1)"></span>
                            <span class="dot" onclick="currentSlide(2)"></span>
                            <span class="dot" onclick="currentSlide(3)"></span>
                        </div>
                    </div>
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
        <script src="js/about.js"></script>
        <script src="logout.js"></script>
        </body>
    </html>
