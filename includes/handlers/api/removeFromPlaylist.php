<?php
    include('../../config.php');

    if(isset($_POST['songId']) && isset($_POST['playlistId'])) {

        $songId = $_POST['songId'];
        $playlistId = $_POST['playlistId'];

        $query = mysqli_query($con, "DELETE FROM playlistSongs WHERE playlistId='$playlistId' AND songId='$songId'");

    } else {
        echo 'Playlist or Song not recieved';
    }
?>