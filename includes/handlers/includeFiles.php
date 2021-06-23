<?php

        if(isset($_GET['ajax'])) {
                include('includes/config.php');
                include('includes/classes/User.php');
                include('includes/classes/Artist.php');
                include('includes/classes/Album.php');
                include('includes/classes/Song.php');
                include('includes/classes/Playlist.php');

                $userLoggedIn = $_SESSION['userLoggedIn'];
                $currentUser = new User($con, $userLoggedIn);
        }
        else {
                include("includes/header.php");
                include("includes/footer.php");

                $url = $_SERVER['REQUEST_URI'];
                echo "<script>loadPage('$url')</script>";
                exit();
        }

?>