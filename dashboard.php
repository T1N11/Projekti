<?php
    session_start();
    include 'php/dbconn.php'; 
    include 'php/MovieController.php';
    include 'php/UserController.php';

    $loggedIn = isset($_SESSION['user-email']);

    if(!$loggedIn || !($_SESSION['user-role'] === 'admin')) {
        header('location: landing.php');
    }

    $dbconn = new DataBaseConnection();
    $dbconn->startConnection();
    $database = $dbconn->getConnection();
    $movieController = new MovieController($database);
    $userController = new UserController($database);

    $MovieData  = $movieController->getMovies();
    $messages = $dbconn->get_Messages();
    $username = $userController->getUsername($_SESSION['user-email']);
    $users = $userController->getUsers();
    $usersOnly = $userController->UsersOnly();


    if (isset($_POST['submit'])) {
        $title =  mysqli_real_escape_string($dbconn->getConnection(), $_POST['title']);    
        $duration = $_POST['duration'];
        $releaseyear = $_POST['releaseyear'];
        $poster = $_FILES['poster']['name'];
        $videofile = $_FILES['videofile']['name'];
        $rating = $_POST['rating'];
        $description =  mysqli_real_escape_string($dbconn->getConnection(), $_POST['description']);    
        if ($movieController->insertMovie($title, $duration, $rating, $releaseyear, $poster, $videofile, $description, $username)) {
            header('Location: dashboard.php');
            echo "<h3 style='background-color: green; text-align: center;'>The Movie has been inserted!</h3>";
        } else {
            echo "<h3 style='background-color: red; text-align: center;'>Movie already exists in the database!</h3>";
        }
    }

    if (isset($_POST['update'])) {
        $movieid = mysqli_real_escape_string($database, $_POST['movieid']);
        $title = mysqli_real_escape_string($database, $_POST['title']);
        $duration = $_POST['duration'];
        $releaseyear = $_POST['releaseyear'];
        $rating = $_POST['rating'];
    
        if ($movieController->updateMovie($movieid, $title, $duration, $releaseyear, $rating, $username)) {
            header('Location: dashboard.php');
            echo "<h3 style='background-color: green; text-align: center;'>The Movie has been updated!</h3>";
        } else {
            echo "<h3 style='background-color: red; text-align: center;'>Failed to update the movie!</h3>";
        }
    }

    if (isset($_POST['delete'])) {
        $movieid = mysqli_real_escape_string($dbconn->getConnection(), $_POST['movieid']);

        if($movieController->deleteMovie($movieid)) {
            header('Location: dashboard.php');
        } else {
            echo "<h3 style='background-color: red; text-align: center;'>Failed to delete the movie!</h3>";
        }
    }

    if (isset($_POST['delete-user'])) {
        $userid = $_POST['userid'];

        if($userController->deleteUser($userid)) {
            header('Location: dashboard.php');
        }
    }

    $dbconn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="axes.png">

    <title>Dashboard</title>
</head>
<body>

    <div class="topnav" id="myTopnav">
            <a href="landing.php">MovieOrk</a>
            <a href="landing.php">Home</a>
            <a href="movies.php?page=1" >Movies</a>
            <a href="about.php" >About</a>
            <a href="contact.php">Contact Us </a>
            <?php
                    if ($loggedIn) {
                        if ($_SESSION['user-role'] === 'admin') {
                            echo '<a href="dashboard.php" class="active">Dashboard</a>';
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
    <div class="dashboard">
        <div>
            <button id="message-button" onclick="openMessages()">Messages</button>
            <button id="message-button" onclick="openUsers()">Users</button>
        </div>

        <div class="section-1">
            <div class="table-con">
                <table>
                        <tr>
                            <th>MovieID</th>
                            <th>Title</th>
                            <th>Duration</th>
                            <th>ReleaseYear</th>
                            <th>Poster</th>
                            <th>Added-By</th>
                            <th>Operations</th>
                        </tr>
                        <?php foreach ($MovieData as $movie): ?>
                            <tr>
                            <td><?= $movie->getMovieID() ?></td>
                            <td><?= $movie->getTitle() ?></td>
                            <td><?= $movie->getDuration() ?></td>
                            <td><?= $movie->getReleaseYear() ?></td>
                            <td><?= $movie->getPoster()?></td>
                            <td><?= $movie->getAddedBy()?></td>
                            <td>
                                <div class="buttons">
                                    <button onclick="openModal(
                                        '<?= $movie->getMovieID() ?>',
                                        '<?= $movie->getTitle() ?>',
                                        '<?= $movie->getDuration()?>',
                                        '<?= $movie->getReleaseYear() ?>',
                                        '<?= $movie->getRating()?>')">edit</button>                                
                                        <form action="" method="post">
                                            <input type="hidden" name="movieid" value="<?= $movie->getMovieID()?>">
                                            <button type="submit" name="delete" style="background-color: red;">Delete</button>
                                        </form>
                                </div>
                            </td>

                            </tr>
                        <?php endforeach; ?>
                </table>

                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <form class="dash-form" action="" method="post" enctype="multipart/form-data">
                            <h2>Edit Data</h2>
                            <input type="text" id="show-id" name="movieid" readonly>
                            <input type="text" id="edit-title" name="title" placeholder="Title" required>
                            <input type="number" id="edit-duration" name="duration" placeholder="Duration" required>
                            <input type="number" id="edit-releaseyear" name="releaseyear" placeholder="ReleaseYear" required>
                            <input type="float" id="edit-rating" name="rating" placeholder="Rating" required>
                            <p><button name="update" id="update">Update</button></p>
                        </form>
                    </div>
                </div>
            </div>

                <div class="form">
                    <form class="dash-form" action="" method="post" enctype="multipart/form-data">
                        <h1>Insert a Movie</h1>
                        <input type="text" name="title" placeholder="Title" required>
                        <input type="number" name="duration" placeholder="Duration" required>
                        <input type="number" name="releaseyear" placeholder="ReleaseYear" required>
                        <input type="file" accept="image/*" name="poster" placeholder="Poster" required>
                        <input type="file" accept=".mp4" name="videofile" placeholder="VideoFile" >
                        <input type="float" name="rating" placeholder="Rating" required>
                        <input type="text" name="description" placeholder="Description">
                        <p><button name="submit" id="insert" >Insert</button></p>
                    </form> 
                </div>

               
        </div> 
        
        
        <div id="messages">

            <div class="messages-content">
            <span class="close" onclick="closeMessages()">&times;</span>

                <table class="message-table">
                    <tr>
                        <td>Message ID</td>
                        <td>Name</td>
                        <td>Surname</td>
                        <td>Email</td>
                        <td>Message</td>
                        <td>Operations</td>
                    </tr>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td><?= $message['messageid']    ?></td>
                            <td><?= $message['name'] ?></td>
                            <td><?= $message['surname'] ?></td>
                            <td><?= $message['email'] ?></td>
                            <td id="message-box"><?= $message['message'] ?></td>
                            <td onclick='openMessage(<?= json_encode($message["message"], JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS) ?>);'><button id="read-button">Read</button></td>
                        </tr>
                    <?php endforeach ?>
                </table>
                
            </div>
        </div>

        <div id="messageModal" class="modal"    >
            <div class="messages-content" style="max-width: 800px;">
                <span class="close" onclick="closeMessageModal()">&times;</span>
                <p id="messageContent" style="max-width: 80vw;"></p>
            </div>
        </div>
        
        <div id="users">


            <div class="messages-content">
                <span class="close" onclick="closeUsers()">&times;</span>

                    <table class="message-table">
                         <tr>
                            <td>UserID</td>
                            <td>UserName</td>
                            <td>Email</td>
                            <td>AccountType</td>
                            <td>Operations</td>
                        </tr>
                        <?php 
                            foreach ($usersOnly as $user): ?>
                            <tr>
                                <td><?= $user->getUserID()?></td>
                                <td><?= $user->getUsername()?></td>
                                <td><?= $user->getEmail()?></td>
                                <td><?= $user->getAccountType()?></td>
                                <td>
                                        <form action="" method="post">
                                            <input type="hidden" name="userid" value="<?= $user->getUserID() ?>">
                                            <button type="submit" name="delete-user" style="background-color: red;">Delete</button>
                                        </form>
                                    </td>

                                </tr>
                            <?php endforeach ?>
                        </table>
            </div>
         </div>
            
    </div>

    <script src="js/dashboard.js"></script> 
    <script src="js/movies.js"></script>
    <script src="logout.js"></script>

</body>
</html>



<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        font-family: 'Montserrat', sans-serif;
    }

    body {
        background: #171717;
    }

    #message-button, #update {
        width: 200px;
        min-width: 100px;
        font-size: 20px;
    }

    #messageContent {
        font-size: 14px;
    }

    #read-button {
        max-width: 100px;
    }

    #messageModal, #messages, #users {
        display: none;
        position: fixed;
        justify-content: center;
        align-items: center;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .messages-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        max-width: 80vw;
        max-height: 60vh;
        overflow: hidden;
    }

    .message-table {
        display: inherit;
        text-align: center;
        max-width: 80vw;
        overflow-x: auto;
        overflow-y: auto;
    }

    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    .table-con {
        max-height: 60vh;
        max-width: 60vw;
        overflow-y: auto;
        overflow-x: auto;
        border: solid 1px black;
        border-radius: 5px;
    }

    .section-1 table {
        max-width: 100vw;
        padding: 10px;
    }

    th {
        padding: 5px 5px 5px 5px;
        background-color: #004898;
        color: white;
    }

    td {
        max-height: 40px;
    }

    td button {
        margin: 10px 10px 10px 10px;
    }

    .buttons {
        display: flex;
    }

    .dashboard {
        margin: 30px auto 0px auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 90vw;
        max-height: 100vh;
        background-color: white;
        overflow: hidden;
    }

    .form {
        max-width: 40vw;
        max-height: 80%;
        display: flex;
    }

    form h1 {
        margin-bottom: 20px;
    }

    form input {
        width: 65%;
        outline: none;
        border: none;
        background: #dfe9f5;
        padding: 12px 14px;
        margin-bottom: 10px;
        border-radius: 10px;
    }

    #insert {
        width: 150px;
    }

    .section-1 {
        display: flex;
        justify-content: space-between;
        gap: 50px;
        max-width: 100vw;
        background: #fff;
        margin: 10px 20px 100px 20px;
        border-radius: 10px;
        overflow-y: auto;
        text-align: center;
    }

    button {
        font-size: 1.1rem;
        margin-top: 1rem;
        padding: 10px 5px 10px 5px;
        border-radius: 5px;
        outline: none;
        border: none;
        background-color: #004898;
        color: #fff;
        cursor: pointer;
        width: 70px;
    }

    button:hover {
        background: blue;
    }

    .topnav {
        overflow: hidden;
        background-color: #004898;
    }

    .topnav a {
        float: left;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
    }

    .topnav a:hover:not(:first-child) {
        background-color: #ddd;
        color: black;
    }

    .topnav a.active {
        background-color: #ddd;
        color: black;
    }

    .topnav .icon {
        display: none;
    }

    .modal {
        display: none;
        position: fixed;
        justify-content: center;
        align-items: center;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .modal-content input {
        width: 65%;
        outline: none;
        border: none;
        background: #dfe9f5;
        padding: 12px 14px;
        margin-bottom: 30px;
        border-radius: 10px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    #message-box {
        overflow: hidden;
        max-width: 300px;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 1.1rem;
    }

    @media screen and (max-width: 800px) {
        .topnav a:not(:first-child) {
            display: none;
        }

        .topnav a.icon {
            float: right;
            display: block;
        }

        .topnav.responsive {
            position: relative;
        }

        .topnav.responsive .icon {
            position: absolute;
            right: 0;
            top: 0;
        }

        .topnav.responsive a {
            float: none;
            display: block;
            text-align: left;
        }

        .section-1 {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 100vw;
            max-height: 100vh;
            font-size: 10px;
        }

        .table-con {
            max-width: 80vw;
            max-height: 100vh;
        }

        button {
            width: 50px;
            font-size: 10px;
        }

        form input {
            width: 100px;
            font-size: 10px;
        }

        #insert {
            width: 130px;
            font-size: 10px;
        }

        #message-button {
            width: 100px;
            font-size: 10px;
        }

        .message-table {
            display: inherit;
            max-width: 80vw;
            overflow-x: auto;
            overflow-y: auto;
        }
    }
</style>


