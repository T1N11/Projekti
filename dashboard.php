<?php
    session_start();
    include 'php/dbconn.php'; 
    $loggedIn = isset($_SESSION['user-email']);

    if(!$loggedIn) {
        header('location: landing.php');
    }

    $dbconn = new DataBaseConnection();
    $dbconn->startConnection();
    $MovieData = $dbconn->get_MovieData();

    if (isset($_POST['submit'])) {
        $title =  mysqli_real_escape_string($dbconn->getConnection(), $_POST['title']);    
        $duration = $_POST['duration'];
        $releaseyear = $_POST['releaseyear'];
        $poster = $_FILES['poster']['name'];
        $videofile = $_FILES['videofile']['name'];
        $rating = $_POST['rating'];
        $description =  mysqli_real_escape_string($dbconn->getConnection(), $_POST['description']);    
        if ($dbconn->insertMovie($title, $duration, $rating, $releaseyear, $poster, $videofile, $description)) {
            header('Location: dashboard.php');
            echo "<h3 style='background-color: green; text-align: center;'>The Movie has been inserted!</h3>";
        } else {
            echo "<h3 style='background-color: red; text-align: center;'>Movie already exists in the database!</h3>";
        }
    }

    if (isset($_POST['update'])) {
        $movieid = mysqli_real_escape_string($dbconn->getConnection(), $_POST['movieid']);
        $title = mysqli_real_escape_string($dbconn->getConnection(), $_POST['title']);
        $duration = $_POST['duration'];
        $releaseyear = $_POST['releaseyear'];
    
        if ($dbconn->updateMovie($movieid, $title, $duration, $releaseyear)) {
            header('Location: dashboard.php');
            echo "<h3 style='background-color: green; text-align: center;'>The Movie has been updated!</h3>";
        } else {
            echo "<h3 style='background-color: red; text-align: center;'>Failed to update the movie!</h3>";
        }
    }

    if (isset($_POST['delete'])) {
        $movieid = mysqli_real_escape_string($dbconn->getConnection(), $_POST['movieid']);

        if($dbconn->deleteMovie($movieid)) {
            header('Location: dashboard.php');
        } else {
            echo "<h3 style='background-color: red; text-align: center;'>Failed to delete the movie!</h3>";
        }
    }

    $dbconn->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/account.css"> -->
    <title>Document</title>
</head>
<body>

    <div class="topnav" id="myTopnav">
            <a href="landing.php">MovieOrk</a>
            <a href="landing.php">Home</a>
            <a href="movies.php" >Movies</a>
            <a href="about.php" >About</a>
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
        <div class="section-1">
            <div class="table-con">
                <table>
                        <tr>
                            <th>MovieID</th>
                            <th>Title</th>
                            <th>Duration</th>
                            <th>ReleaseYear</th>
                            <th>Poster</th>
                            <th>Operations</th>
                        </tr>
                        <?php foreach ($MovieData as $movie): ?>
                            <tr>
                            <td><?= $movie['movieid'] ?></td>
                            <td><?= $movie['title'] ?></td>
                            <td><?= $movie['duration'] ?></td>
                            <td><?= $movie['releaseyear'] ?></td>
                            <td><?= $movie['poster'] ?></td>
                            <td>
                                <div class="buttons">
                                    <button onclick="openModal(
                                        '<?= $movie['movieid'] ?>',
                                        '<?= $movie['title'] ?>',
                                        '<?= $movie['duration'] ?>',
                                        '<?= $movie['releaseyear'] ?>',
                                        '<?= $movie['rating'] ?>')">edit</button>                                
                                        <form action="" method="post">
                                            <input type="hidden" name="movieid" value="<?= $movie['movieid'] ?>">
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
                            <!-- <input type="text" id="edit-description" name="description" placeholder="Description"> -->
                            <button name="update" id="update">Update</button>
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
                        <input type="file" accept=".mp4" name="videofile" placeholder="Poster" >
                        <input type="float" name="rating" placeholder="Rating" required>
                        <input type="text" name="description" placeholder="Description">
                        <p><button name="submit" id="insert" >Insert</button></p>
                    </form> 
                </div>
        </div> 
    </div>
        
    <script src="js/movies.js"></script>
    <script src="logout.js"></script>
    <script src="js/dashboard.js"></script>

</body>
</html>

<style>

    * {
        margin: 0;
        padding: 0;
        font-family: 'Montserrat', sans-serif;

    }

    body {
        background: #171717;
    }
    table, th, td{
        border: 1px solid black;
        border-collapse: collapse;
        
    }

    .table-con {
        max-height: 500px;
        overflow-y: auto;
        border: solid 1px black;
        border-radius: 5px;
        display: flex;
        justify-content: center;
    }

    table {

        padding: 10px;
    }

    th {
        height: 50px;
        padding: 0 10px 0  10px;
        background-color: #004898;
        color: white;
    }

    td {
        height: 40px;
        padding: 5px 5px 5px 5px;
    }
    td button {
        margin: 10px 10px 10px 10px;
    }

    .buttons {
        display: flex;
    }
    
    .dashboard {
        margin-top: 30px;
        display: flex;
        flex-direction: row;
        align-items: center;
        max-width: 100vw;
        max-height: 100vh;
    }

    .form {
        width: 30vw;
        max-width: 500px;
        padding-bottom: 50px;
        height: 80%;

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
        margin-bottom: 30px;
        border-radius: 10px;
    }
    
    #insert {
        width: 150px;
    }

    .section-1 {
        width: 100%;
        height: 80vh;
        background: #fff;
        border-radius: 10px;
        text-align: center;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: start;

        padding-top: 30px;
    }

    button { 
    font-size: 1.1rem;
    margin-top: 1rem;
    padding: 8px;
    border-radius: 5px;
    outline: none;
    border:none;
    width: 65%;
    background-color: #004898;
    color: #fff;
    cursor: pointer;
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

    * {
        margin: 0;
        padding: 0;
        font-family: 'Montserrat', sans-serif;

    }

    body {
        background: #171717;
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
        background-color: rgba(0,0,0,0.5);
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

    /* Style for the close button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    @media screen and (max-width: 600px) {
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
    }
}
</style>



