<?php

class Movie {
    private $movieID;
    private $title;
    private $duration;
    private $releaseYear;
    private $poster;
    private $videoFile;
    private $rating;
    private $description;
    private $addedby;

    public function __construct($movieID, $title, $duration, $releaseYear, $poster, $videoFile, $rating, $description, $addedby) {
        $this->movieID = $movieID;
        $this->title = $title;
        $this->duration = $duration;
        $this->releaseYear = $releaseYear;
        $this->poster = $poster;
        $this->videoFile = $videoFile;
        $this->rating = $rating;
        $this->description = $description;
        $this->addedby = $addedby;
    }
    public function getMovieID() {
        return $this->movieID;
    }
    

    public function setMovieID($movieID) {
        $this->movieID = $movieID;
    }

    public function getTitle() {
        return $this->title;
    }
    public function setTitle($title) {
        $this->title = $title;
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

    public function getAddedBy() {
        return $this->addedby;
    }
    public function set_AddedBy($addedby) {
        $this->addedby = $addedby;
    }
}

?>