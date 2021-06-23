<?php 

    class Song {

        private $id;
        private $con;
        private $song;
        private $title;
        private $artistId;
        private $albumId;
        private $genre;
        private $duration;
        private $path;

        public function __construct($con, $id) {
            $this->con = $con;
            $this->id = $id;

            $query = mysqli_query($this->con, "SELECT * FROM songs where id='$this->id'");
            $song = mysqli_fetch_array($query);

            $this->title = $song['title'];
            $this->artistId = $song['artist'];
            $this->artistId = $song['artist'];
            $this->albumId = $song['album'];
            $this->genre = $song['genre'];
            $this->duration = $song['duration'];
            $this->path = $song['path'];
        }

        public function getTitle() {
            return $this->title;
        }

        public function getId() {
            return $this->id;
        }

        public function getArtist() {
            return new Artist($this->con, $this->artistId);
        }

        public function getAlbum() {
            return new Album($this->con, $this->albumId);
        }

        
        public function getGenre() {
            return $this->genre;
        }

        public function getPath() {
            return $this->path;
        }

        public function getDuration() {
            return $this->duration;
        }

    }
?>