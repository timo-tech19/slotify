<?php 

    class Playlist {

        private $playlist;
        private $con;
        private $id;
        private $name;
        private $owner;

        public function __construct($con, $playlist) {
            
            $this->con = $con;

            if(!is_array($playlist)) {
                $query = mysqli_query($this->con, "SELECT * FROM playlists WHERE id='$playlist'");
                $playlist = mysqli_fetch_array($query);
            }


            $this->playlist = $playlist;
            $this->name = $playlist['name'];
            $this->owner = $playlist['owner'];
            $this->id = $playlist['id'];

        }

        public function getName() {
            return $this->name;
        }

        public function getId() {
            return $this->id;
        }

        public function getOwner() {
            return $this->owner;
        }

        public function getNumSongs() {
            $songQuery = mysqli_query($this->con, "SELECT songId FROM playlistsongs WHERE playlistId='$this->id'");

            return mysqli_num_rows($songQuery);
        }

        public function getSongIds() {
            $query = mysqli_query($this->con, "SELECT songId FROM playlistsongs WHERE playlistId='$this->id' ORDER BY playlistOrder ASC"); 
            
            $songIds = [];

            while($row = mysqli_fetch_array($query)) {
                array_push($songIds, $row['songId']);   
            }
            return $songIds;
        }

    }
?>