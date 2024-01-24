<?php

class Movie {
    private $movieID;
    private $username;
    private $email;
    private $duration;
    private $releaseYear;
    private $poster;
    private $videoFile;
    private $rating;
    private $description;

    public function __construct($mi, $un, $e, $d, $ry, $p, $vf, $r, $desc) {
        $this->movieID = $mi;
        $this->username = $un;
        $this->email = $e;
        $this->duration = $d;
        $this->releaseYear = $ry;
        $this->poster = $p;
        $this->videoFile = $vf;
        $this->rating = $r;
        $this->description = $desc;
    }

    public function getMovieID() {
        return $this->movieID;
    }

    public function setMovieID($movieID) {
        $this->movieID = $movieID;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function setDuration($duration) {
        $this->duration = $duration;
    }

    public function getReleaseYear() {
        return $this->releaseYear;
    }

    public function setReleaseYear($releaseYear) {
        $this->releaseYear = $releaseYear;
    }

    public function getPoster() {
        return $this->poster;
    }

    public function setPoster($poster) {
        $this->poster = $poster;
    }

    public function getVideoFile() {
        return $this->videoFile;
    }

    public function setVideoFile($videoFile) {
        $this->videoFile = $videoFile;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}

?>