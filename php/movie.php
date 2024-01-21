<?php

    public class Movie {
        private $title;
        private $duration;
        private $rating;
        private $description;
        
        function __construct($title, $duration, $rating, $description) {
            $this->title = $title;
            $this->duration = $duration;
            $this->rating = $rating;
            $this->description = $description;
        }

        
    }
?>