<?php

include "Movie.php";

class MovieController {
    private $dbconn;

    public function __construct($dbconn) {
        $this->dbconn = $dbconn;
    }


    public function getMovies() {
        $query = "SELECT * FROM movies";
        $results = mysqli_query($this->dbconn, $query);
        $movies = [];

        if ($results && mysqli_num_rows($results) > 0) {
            while ($row = $results->fetch_assoc()) {
                $movie = new Movie(
                    $row['movieid'],
                    $row['title'],
                    $row['duration'],
                    $row['releaseyear'],
                    $row['poster'],
                    $row['videofile'],
                    $row['rating'],
                    $row['description'],
                    $row['addedby']
                );
                $movies[] = $movie;
            }
        }

        return $movies;
    }

    public function getMovieByID($ID) {
        $query = "SELECT * FROM movies WHERE movieid = '$ID'";
        
        $result = mysqli_query($this->dbconn, $query);
    
        if ($result && mysqli_num_rows($result) > 0) {
            $movieDetails = mysqli_fetch_assoc($result);
    
            $movie = new Movie(
                $movieDetails['movieid'],
                $movieDetails['title'],
                $movieDetails['duration'],
                $movieDetails['releaseyear'],
                $movieDetails['poster'],
                $movieDetails['videofile'],
                $movieDetails['rating'],
                $movieDetails['description'],
                $movieDetails['addedby']
            );
    
            return $movie;
        } else {
            return null;
        }
    }

    public function movieExists($title) {
        $movies = $this->getMovies();
        foreach ($movies as $movie) {
            if ($movie->getTitle() === $title) {
                return true; 
            }
        }
    
        return false;
    }

    public function insertMovie($title, $duration, $rating, $releaseYear, $poster, $videoFile, $description, $addedby) {
        if ($this->movieExists($title)) {
            return false;
        }
    
        $movie = new Movie(null, $title, $duration, $rating, $releaseYear, $poster, $videoFile, $description, $addedby);
    
        $query = "INSERT INTO movies (title, duration, rating, releaseyear, poster, videofile, description, addedby) 
                  VALUES ('$title', '$duration', '$rating', '$releaseYear', '$poster', '$videoFile', '$description', '$addedby')";
    
        if (mysqli_query($this->dbconn, $query)) {
            $movie->setMovieID(mysqli_insert_id($this->dbconn));
            return true;
        } else {
            echo mysqli_error($this->dbconn);
            return false;
        }
    }
    
    public function updateMovie($movieid, $title, $duration, $releaseyear, $rating, $userid) {
        $moviesArray = $this->getMovies();
        foreach ($moviesArray as $movie) {
            if($movie->getMovieID() == $movieid) {
                $movie->setTitle($title);
                $movie->setReleaseYear($releaseyear);
                $movie->setDuration($duration);
                $movie->setRating($rating);
                $query2 = "UPDATE movies SET title='{$movie->getTitle()}', 
                duration='{$movie->getDuration()}', 
                releaseyear='{$movie->getReleaseYear()}', 
                rating='{$movie->getRating()}', 
                addedby='$userid' 
                WHERE movieid='{$movie->getMovieID()}'";            }
        }



        $query1 = "SELECT username FROM users WHERE userid = '$userid'";
        $result = mysqli_query($this->dbconn, $query1);

        if (!$result) {
            echo "Error fetching username: " . mysqli_error($this->dbconn);
            return false;
        }

        $row = mysqli_fetch_assoc($result);
        $complete = mysqli_query($this->dbconn, $query2);

        if ($complete) {
            return true;
        }

        return false;
    }


    public function deleteMovie($movieid) {
        $query = "DELETE from movies where movieid = '$movieid'";
        $query2 = "DELETE from watchlist where movieid = '$movieid'";

        $watch = mysqli_query($this->dbconn, $query2);
        

        if($watch) {
            $complete = mysqli_query($this->dbconn, $query);
            return $complete;
        }
        return $watch;

    }

    function totalMovies() {
        $query = "SELECT COUNT(*) as count FROM movies";
        $results = mysqli_query($this->dbconn, $query);


        if($results) {
            $row = mysqli_fetch_assoc($results);
            return $row['count'];
        } else { 
            echo "Error while getting the total of Movies: ". mysqli_error($this->dbconn);
            return 0;
        }
    }

    

    public function getByPage($start, $numberOf) {
        $query = "SELECT * FROM movies LIMIT $start, $numberOf";
        $results = mysqli_query($this->dbconn, $query);
        $movies = [];
    
        if ($results && mysqli_num_rows($results) > 0) {
            while ($row = $results->fetch_assoc()) {
                $movie = new Movie(
                    $row['movieid'],
                    $row['title'],
                    $row['duration'],
                    $row['releaseyear'],
                    $row['poster'],
                    $row['videofile'],
                    $row['rating'],
                    $row['description'],
                    $row['addedby']
                );
                $movies[] = $movie;
            }
        }
    
        return $movies;
    }

    public function getUserIdByEmail($email) {
        $query_user = "SELECT userid FROM users WHERE email = '$email'";
        $result_user = mysqli_query($this->dbconn , $query_user);

        if (!$result_user) {
            echo "Error fetching user ID: " . mysqli_error($this->dbconn);
            return false;
        }

        $row_user = mysqli_fetch_assoc($result_user);
        return $row_user ? $row_user['userid'] : false;
    }

    public function getWatchlistCount($userid) {
        $query_watchlist_count = "SELECT COUNT(*) as count FROM watchlist WHERE userid = '$userid'";
        $result_watchlist_count = mysqli_query($this->dbconn, $query_watchlist_count);

        if (!$result_watchlist_count) {
            echo "Error fetching watchlist count: " . mysqli_error($this->dbconn);
            return false;
        }

        $row_watchlist_count = mysqli_fetch_assoc($result_watchlist_count);
        return $row_watchlist_count ? $row_watchlist_count['count'] : false;
    }

    public function addToWatchList($email, $movieId) {
        $userid = $this->getUserIdByEmail($email);

        if ($userid === false) {
            return false; 
        }

        $watchlist_count = $this->getWatchlistCount($userid);

        if ($watchlist_count === false) {
            return false; 
        }

        if ($watchlist_count >= 12) {
            echo "<h3 style='background-color: red; text-align:center;'>You can only add a maximum of 12 movies to your watchlist!<h3>";
            return false;
        }

        $query = "INSERT INTO watchlist(userid, movieid) VALUES ('$userid', '$movieId')";
        $complete = mysqli_query($this->dbconn, $query);

        if (!$complete) {
            echo "<h3 style='background-color: red; text-align:center;'>Movie Already in WatchList!<h3>";
            return false;
        }

        return true;
    }

    public function loadWatchList($email) {
        $userid = $this->getUserIdByEmail($email);

        if ($userid === false) {
            return []; 
        }

        $query_watchlist = "SELECT m.* FROM movies m
                            JOIN watchlist w ON m.movieid = w.movieid
                            WHERE w.userid = '$userid'";
        $result_watchlist = mysqli_query($this->dbconn, $query_watchlist);

        if (!$result_watchlist) {
            echo "Error fetching watchlist: " . mysqli_error($this->dbconn);
            return [];
        }

        $watchlist = [];
        while ($row_watchlist = mysqli_fetch_assoc($result_watchlist)) {
            $watchlist[] = $row_watchlist;
        }

        return $watchlist;
    }
    public function inWatchList($email, $movieID) {
        $query_user = "SELECT userid FROM users WHERE email = '$email'";
        $result_user = mysqli_query($this->dbconn, $query_user);
        
        if (!$result_user) {
            echo "Error fetching user ID: " . mysqli_error($this->dbconn);
            return [];
        }

        $row_user = mysqli_fetch_assoc($result_user);
        $userid = $row_user['userid'];

        $query_watchlist = "SELECT * FROM watchlist w WHERE w.userid = '$userid' and w.movieid = '$movieID'";
        $result_watchlist = mysqli_query($this->dbconn, $query_watchlist);

        
        return mysqli_num_rows($result_watchlist) > 0;

    
    }
    public function removeFromWatchList($email, $movieID) {
        $query_user = "SELECT userid FROM users WHERE email = '$email'";
        $result_user = mysqli_query($this->dbconn, $query_user);
        
        if (!$result_user) {
            echo "Error fetching user ID: " . mysqli_error($this->dbconn);
            return [];
        }

        $row_user = mysqli_fetch_assoc($result_user);
        $userid = $row_user['userid'];

        $query_watchlist = "DELETE FROM watchlist WHERE userid = '$userid' AND movieid = '$movieID'";
        $result = mysqli_query($this->dbconn, $query_watchlist);

        return $result;
    }

    public function search($text) {

        $query = "SELECT movieid FROM movies WHERE title LIKE ?";
        $text = urldecode($text);

        $stmt = $this->dbconn->prepare($query);
        if (!$stmt) {
            die('Error in query preparation: ' . $this->dbconn->error);
        }

        $text = '%' . $text . '%';
        $stmt->bind_param('s', $text);

        $success = $stmt->execute();
        if (!$success) {
            die('Error in query execution: ' . $stmt->error);
        }

        $result = $stmt->get_result();

       
        $hits = $result->fetch_all(MYSQLI_ASSOC); 


        $stmt->close();

        return $hits;
    }
    
}
?>
