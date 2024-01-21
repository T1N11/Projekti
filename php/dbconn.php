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

    public function insertMovie($title, $duration, $rating, $releaseyear, $poster, $videofile, $description) {
        $query = "INSERT INTO movies (title, duration, rating, releaseyear, poster, videofile, description) VALUES ('$title', '$duration', '$rating', '$releaseyear', '$poster', '$videofile', '$description')";

        
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
    public function closeConnection() {
        mysqli_close($this->conn);
    }
}

?>


