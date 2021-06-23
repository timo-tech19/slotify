<?php 

    class User {

        private $username;
        private $con;

        public function __construct($con, $username) {
            $this->con = $con;
            $this->username = $username;

            $query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$this->username'");
            $this->user = mysqli_fetch_array($query);
        }

        public function getUsername() {
            return $this->user['username'];
        }

        public function getEmail() {
            return $this->user['email'];
        }

        public function getFullName() {

            $query = mysqli_query($this->con, "SELECT concat(firstName, ' ', lastName) AS fullName FROM users WHERE username='$this->username'");
            $result = mysqli_fetch_array($query);
            return $result['fullName'];
        }


        public function getPlaylists() {
            $username = $this->user['username'];
            $query = mysqli_query($this->con, "SELECT * FROM playlists WHERE owner='$this->username'");
            $playlists = [];

            while($row = mysqli_fetch_array($query)) {
                $playlist = [
                    'id'=> $row['id'],
                    'name'=> $row['name']
                ];

                array_push($playlists, $playlist);
            }
            return $playlists;
        }
    }
?>