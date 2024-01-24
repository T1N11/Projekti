<?php

class DataBaseConnection {
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "moviesite";
    private $conn;
    
    public function startConnection() {
        $this->conn = mysqli_connect($this->server, $this->username, $this->password, $this->database);

        if(!$this->conn) {
            die("Error: " . mysqli_connect_error());
        } else {
            return $this->conn;
        }
    }  

    public function getConnection() {
        return $this->conn;
    }
    public function emailExists($email) {
        $query = "SELECT COUNT(*) as count FROM users WHERE email = '$email'";
        $result = mysqli_query($this->conn, $query);

        if($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['count'] > 0;
        } else {
            echo "Error while checking the existence of email: " . mysqli_error($this->conn);
            return false;
        }
    }

    public function validateUserData($username, $email, $password) {
        $nameRegex = '/^[a-zA-Z\s]+$/';
        $emailRegex = '/^[a-zA-Z._-]+@[a-z]+\.[a-z]{2,3}$/';
    
        $errorMessage = "";
    
        if (!preg_match($nameRegex, $username)) {
            $errorMessage .= 'Please enter a valid username!<br>';
        }
    
        if (!preg_match($emailRegex, $email)) {
            $errorMessage .= 'Please enter a valid email Address<br>';
        } 
    
        if (strlen($password) < 6) {
            $errorMessage .= 'Password must be longer than 6!<br>';
        }
    
        if ($this->emailExists($email)) {
            $errorMessage .= 'Email already exists!<br>';
            echo "<h3 style='color: red; text-align: center;'> Email already exists!</h3>";
        }
        
        return ($errorMessage === "") ? true : $errorMessage;
    }
        
    public function authenticateUser($email, $password) {
        $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $results = mysqli_query($this->conn, $query);

        return mysqli_num_rows($results) > 0;
    }

    public function insertData($username, $email, $password, $confirmPassword) {
        $validation = $this->validateUserData($username, $email, $password);
        
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($validation === true) {
            if(mysqli_query($this->conn, $query)) {
               return true;
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($this->conn);
            }
        } else { 
            return false;
        }
    }

    //Movie-Section:


    public function movieExists($title) {
        $query = "SELECT COUNT(*) as count FROM movies WHERE title = '$title'";
        $result = mysqli_query($this->conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['count'] > 0;
        } else {
            echo "Error while checking the existence of movie: " . mysqli_error($this->conn);
            return false;
        }
    }

    public function insertMovie($title, $duration, $rating, $releaseyear, $poster, $videofile, $description, $addedby) {
        $query = "INSERT INTO movies (title, duration, rating, releaseyear, poster, videofile, description, addedby) VALUES ('$title', '$duration', '$rating', '$releaseyear', '$poster', '$videofile', '$description', '$addedby')";

        
        if ($this->movieExists($title)) {
            // Movie already exists, return false or handle accordingly
            return false;
        } else if(mysqli_query($this->conn, $query)) {
            return true;
        } else {
            echo mysqli_error($this->conn);
            return false;
        }
    }

    function get_MovieData() {
        $query = "SELECT * FROM movies";
        $results = mysqli_query($this->conn, $query);
        $movies = [];

        if($results && mysqli_num_rows($results) > 0) {
            while ($row = $results->fetch_assoc()){
                $movies[] = $row;
            }
        }

        return $movies;
    }
    
    function get_MovieByID($ID) {
        $query = "SELECT * FROM movies where movieid = '$ID'";
        $result = mysqli_query($this->conn, $query);
        $movies = [];

        if ($result && mysqli_num_rows($result) > 0) {
            $movieDetails = mysqli_fetch_assoc($result);
            return $movieDetails;
        } else {
            return null; 
        }

        return $movies;
    }
    function totalMovies() {
        $query = "SELECT COUNT(*) as count FROM movies";
        $results = mysqli_query($this->conn, $query);


        if($results) {
            $row = mysqli_fetch_assoc($results);
            return $row['count'];
        } else { 
            echo "Error while getting the total of Movies: ". mysqli_error($this->conn);
            return 0;
        }
    }

    function get_ByPage($start, $numberOf) {
        $query = "SELECT * FROM movies LIMIT $start, $numberOf";
        $results = mysqli_query($this->conn, $query);
        $movies = [];

        if ($results && mysqli_num_rows($results) > 0) {
            while($row = $results->fetch_assoc()) {
                $movies[] = $row;
            }
        }
        return $movies;
    }
    public function is_Admin($email) {
        $query = "SELECT AccountType FROM users WHERE email = '$email'";
        $result = mysqli_query($this->conn, $query);
    
        if (!$result) {
            echo "Error: " . $query . "<br>" . mysqli_error($this->conn);
            return 'user'; 
        }
    
        $row = mysqli_fetch_assoc($result);
    
        if ($row && $row['AccountType'] == 1) {
            return 'admin';
        }
    
        return 'user';
    }
    public function updateMovie($movieid, $title, $duration, $releaseyear) {
        $query = "UPDATE movies SET title='$title', duration='$duration', releaseyear='$releaseyear' WHERE movieid='$movieid'";
        
        $complete = mysqli_query($this->conn, $query);
        
        return $complete;
    }

    public function deleteMovie($movieid) {
        $query = "DELETE from movies where movieid = '$movieid'";

        $complete = mysqli_query($this->conn, $query);

        return $complete;
    }

    public function add_ToWatchList($email, $movieId) {
        $query_user = "SELECT userid FROM users WHERE email = '$email'";
        $result_user = mysqli_query($this->conn, $query_user);
    
        if (!$result_user) {
            echo "Error fetching user ID: " . mysqli_error($this->conn);
            return false;
        }
    
        $row_user = mysqli_fetch_assoc($result_user);
        $userid = $row_user['userid'];
    
        $query_watchlist_count = "SELECT COUNT(*) as count FROM watchlist WHERE userid = '$userid'";
        $result_watchlist_count = mysqli_query($this->conn, $query_watchlist_count);
    
        if (!$result_watchlist_count) {
            echo "Error fetching watchlist count: " . mysqli_error($this->conn);
            return false;
        }
    
        $row_watchlist_count = mysqli_fetch_assoc($result_watchlist_count);
        $watchlist_count = $row_watchlist_count['count'];
    
        if ($watchlist_count >= 12) {
            echo "<h3 style='background-color: red; text-align:center;'>You can only add a maximum of 12 movies to your watchlist!<h3>";
            return false;
        }
    
        $query = "INSERT INTO watchlist(userid, movieid) VALUES ('$userid', '$movieId')";
        $complete = mysqli_query($this->conn, $query);
    
        if (!$complete) {
            echo "<h3 style='background-color: red; text-align:center;'>Movie Already in WatchList!<h3>";
            return false;
        }
    
        return true;
    }
    

    public function load_WatchList($email) {
        $query_user = "SELECT userid FROM users WHERE email = '$email'";
        $result_user = mysqli_query($this->conn, $query_user);
    
        if (!$result_user) {
            echo "Error fetching user ID: " . mysqli_error($this->conn);
            return [];
        }
    
        $row_user = mysqli_fetch_assoc($result_user);
        $userid = $row_user['userid'];
    
        $query_watchlist = "SELECT m.* FROM movies m
                            JOIN watchlist w ON m.movieid = w.movieid
                            WHERE w.userid = '$userid'";
        $result_watchlist = mysqli_query($this->conn, $query_watchlist);
    
        if (!$result_watchlist) {
            echo "Error fetching watchlist: " . mysqli_error($this->conn);
            return [];
        }
    
        $watchlist = [];
        while ($row_watchlist = mysqli_fetch_assoc($result_watchlist)) {
            $watchlist[] = $row_watchlist;
        }
    
        return $watchlist;
    }
    
    public function in_WatchList($email, $movieID) {
        $query_user = "SELECT userid FROM users WHERE email = '$email'";
        $result_user = mysqli_query($this->conn, $query_user);
        
        if (!$result_user) {
            echo "Error fetching user ID: " . mysqli_error($this->conn);
            return [];
        }

        $row_user = mysqli_fetch_assoc($result_user);
        $userid = $row_user['userid'];

        $query_watchlist = "SELECT * FROM watchlist w WHERE w.userid = '$userid' and w.movieid = '$movieID'";
        $result_watchlist = mysqli_query($this->conn, $query_watchlist);

        
        return mysqli_num_rows($result_watchlist) > 0;

       
    }

    public function remove_FromWatchList($email, $movieID) {
        $query_user = "SELECT userid FROM users WHERE email = '$email'";
        $result_user = mysqli_query($this->conn, $query_user);
        
        if (!$result_user) {
            echo "Error fetching user ID: " . mysqli_error($this->conn);
            return [];
        }

        $row_user = mysqli_fetch_assoc($result_user);
        $userid = $row_user['userid'];

        $query_watchlist = "DELETE FROM watchlist WHERE userid = '$userid' AND movieid = '$movieID'";
        $result = mysqli_query($this->conn, $query_watchlist);

        return $result;
    }

    public function add_Message($first, $last, $email, $message) {
        $query = "INSERT INTO messages (name, surname, email, message) VALUES ('$first', '$last','$email', '$message')";
        $execute = mysqli_query($this->conn, $query);
    
        return $execute;
    }

    public function get_UserID($email) {
        $query_user = "SELECT username FROM users WHERE email = '$email'";
        $result = mysqli_query($this->conn, $query_user);
        $row_user = mysqli_fetch_assoc($result);
        $username = $row_user['username'];
        return $username;
    }

    public function get_Messages() {
        $query = "SELECT * FROM messages";
        $result = mysqli_query($this->conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
        }
        return $messages;
    }

    public function load_Landing($dataid, $tableName) {
        $query = "SELECT * FROM $tableName WHERE dataid = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $dataid); 
        $stmt->execute();
        

        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $data = $row['data'];
        } else {

            $data = "Default landing page data";
        }
        $stmt->close();
        
        return $data;
    }
    
    public function closeConnection() {
        mysqli_close($this->conn);
        
    }
}

?>


