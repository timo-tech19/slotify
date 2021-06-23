<?php
    include('../../config.php');

    if(isset($_POST['id'])) {
        $id = $_POST['id'];

        mysqli_query($con, "DELETE FROM playlists WHERE id='$id'");
        mysqli_query($con, "DELETE FROM playlistsongs WHERE playlistId='$id'");
    } else {
        echo "Playlist id not found";
    }

?>