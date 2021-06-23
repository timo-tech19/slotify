<?php
    include('includes/header.php');
?>

<h1>Playlists</h1>
<button class="orange-btn playlist-btn">New Playlist</button>
<hr/>

<div class="playlists">
    <?php 
        $user = new User($con, $userLoggedIn);
        
        $playlists = mysqli_query($con, "SELECT * FROM playlists WHERE owner='$userLoggedIn'");

        while($playlist = mysqli_fetch_array($playlists)) {
            ?>
            <a href="playlist.php?id=<?php echo $playlist['id'] ?>" class="playlist">
                <span class="playlist-image">
                    <img src="assets/images/music-cover.jpg" alt="Playlist">
                </span>
                <span class='playlist-title'><?php echo $playlist['name'] ; ?> </span>
            </a>
            
        <?php }
    ?>
</div>


<script>
    const playlistButton = document.querySelector('.playlist-btn');
    playlistButton.addEventListener('click', createPlaylist);
</script>

<?php 
    include('includes/footer.php');
?>