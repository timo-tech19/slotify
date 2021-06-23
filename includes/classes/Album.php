<?php 

    class Album {

        private $id;
        private $con;
        private $title;
        private $artist;
        private $genre;
        private $coverArtPath;

        public function __construct($con, $id) {
            $this->con = $con;
            $this->id = $id;

            $albumQuery = mysqli_query($this->con, "SELECT * FROM albums where id='$this->id'");
            $album = mysqli_fetch_array($albumQuery);

            $this->title = $album['title'];
            $this->artist = $album['artist'];
            $this->genre = $album['genre'];
            $this->coverArtPath = $album['coverArtPath'];

        }

        public function getTitle() {
            return $this->title;
        }
        

        public function getArtist() {
            return new Artist($this->con, $this->artist);
        }

        public function getCoverArtPath() {
            return $this->coverArtPath;
        }

        public function getNumberOfSongs() {
            $songsQuery = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id'"); 
            return mysqli_num_rows($songsQuery);
        }

        public function getSongIds() {
            $query = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id' ORDER BY albumOrder ASC"); 
            
            $songIds = [];

            while($row = mysqli_fetch_array($query)) {
                array_push($songIds, $row['id']);   
            }
            return $songIds;
        }

    }
?>