<?php

class MovieController {
    private $dbconn;

    public function __construct($dbconn) {
        $this->dbconn = $dbconn;
    }

    public function getAllMovies() {
        return $this->dbconn->get_MovieData();
    }

    public function getMovieByID($ID) {
        return $this->dbconn->get_MovieByID($ID);
    }

    public function insertMovie($title, $duration, $rating, $releaseyear, $poster, $videofile, $description) {
        return $this->dbconn->insertMovie($title, $duration, $rating, $releaseyear, $poster, $videofile, $description);
    }

    public function updateMovie($movieid, $title, $duration, $releaseyear, $userid) {
        return $this->dbconn->updateMovie($movieid, $title, $duration, $releaseyear, $userid);
    }

    public function deleteMovie($movieid) { 
        return $this->dbconn->deleteMovie($movieid);
    }
    public function totalMovies(){ 
        return $this->dbconn->totalMovies();
    }

    public function getByPage($starts, $numberOf) {
        return $this->dbconn->get_ByPage($starts, $numberOf);
    }
    public function addToWatchList($email, $movieId) {
        return $this->dbconn->add_ToWatchList($email, $movieId);
    }
    public function loadWatchList($email) {
        return $this->dbconn->load_WatchList($email);
    }
    public function inWatchList($email, $movieID) {
        return $this->dbconn->in_WatchList($email, $movieID);
    }
    public function removeFromWatchList($email, $movieID) {
        return $this->dbconn->remove_FromWatchList($email, $movieID);

    }

    public function search($text) {
        $database = $this->dbconn->getConnection();
        $query = "SELECT movieid FROM movies WHERE title LIKE ?";
        $text = urldecode($text);
        $stmt = $database->prepare($query);
        if (!$stmt) {
            die('Error in query preparation: ' . $database->error);
        }
    
        $text = '%' . $text . '%'; 
        $stmt->bind_param('s', $text);
        
        $success = $stmt->execute();
        if (!$success) {
            die('Error in query execution: ' . $stmt->error);
        }
    
        $result = $stmt->get_result();
        
        $hits = [];
        
        while ($row_result = $result->fetch_assoc()) {
            $hits[] = $row_result;
        }
        
        $stmt->close();
        
        return $hits;
    }
    
}
?>
