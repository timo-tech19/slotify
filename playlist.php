<?php
    include('includes/header.php');
    // include('includes/handlers/includeFiles.php');
    if(isset($_GET['id'])) {

        $playlist = new Playlist($con, $_GET['id']);
        $owner = $playlist->getOwner();

    } else {
        header('Location: index.php');
    }

    $songIds = $playlist->getSongIds();

?>
    <!-- <h1>Album</h1>
    <hr> -->
    <div class="album-container">
        <div class="cover-art">
            <img src="assets/images/music-cover.jpg" alt="Cover Arts">
        </div>
        <div class="album-details">
            <h2><?php echo $playlist->getName(); ?></h2>
            <p><?php echo  $playlist->getOwner(); ?></p>
            <p><?php echo  $playlist->getNumSongs(); ?> songs</p>
            <button class='delete-playlist' data-playlistId='<?php echo $playlist->getId(); ?>'>Delete Playlist</button>
        </div>
    </div>
    <div class="songs">
        <h3>Tracks</h3>
        <hr>
        <ul class="tracks">
            <?php 
                $counter = 1;
                foreach ($songIds as $songId) {
                    $playlistSong = new Song($con, $songId);
                    $songArtist = $playlistSong->getArtist();
            ?>

                    <li class="track" data-song='<?php echo $playlistSong->getId(); ?>'>
                        <div class="track-num">
                            <ion-icon name="play-outline" class='play' data-id="<?php echo $playlistSong->getId(); ?>"></ion-icon>
                            <span><?php echo $counter; ?></span>
                        </div>
                        <div class="track-details">
                            <h3 class='track-title'><?php echo $playlistSong->getTitle(); ?></h3>
                            <p class='track-artist'><?php echo $songArtist->getName(); ?></p>
                        </div>
                        <div class="track-options">
                            <ion-icon name="ellipsis-horizontal" class='toggle-options'></ion-icon>
                            <ul class="options-list">
                                <li class="options-item">
                                    <select name="options" class="playlist-options">
                                        <option value="">Add to playlist</option>
                                        <?php
                                            $playlists = $currentUser->getPlaylists();
                                           foreach($playlists as $pl) {
                                              ?>  
                                                <option value="<?php echo $pl['id']; ?>">
                                                    <?php echo $pl['name']; ?>
                                                </option>
                                              <?php
                                           }
                                        ?>
                                    </select>
                                </li>
                                <li class="options-item remove-song" data-playlist="<?php echo $playlist->getId(); ?>">Remove From Playlist</li>
                            </ul>
                        </div>
                        <div class="track-duration"><?php echo $playlistSong->getDuration(); ?></div>
                    </li> 
                                 
            <?php $counter++;   
                } ?>

        </ul>
    </div>
    <script >
        tempSongIds = <?php echo json_encode($songIds); ?>;
        domReady(() => {;
            document.querySelector('.tracks').addEventListener('click', (e) => {
                if(e.target.classList.contains('play'))
                    setTrack(e.target.dataset.id, tempSongIds, true );
            });

            document.querySelectorAll('.remove-song').forEach(el => {
                el.addEventListener('click', async (e) => {
                    const songId = e.target.closest('.track').dataset.song;
                    const playlistId = e.target.dataset.playlist;

                    var formData = new FormData();
                    formData.append('songId', songId);
                    formData.append('playlistId', playlistId);

                    // Get SONG
                    const response = await fetch('includes/handlers/api/removeFromPlaylist.php', {
                        method: 'POST',
                        body: formData
                    });

                    const error = await response.text();
                    if(error) {
                        alert(`Error: ${error}`);
                    }

                    // hideOptions();
                    e.target.closest('.track').remove();
                })
            })
        })
    </script>

<?php 
    include('includes/footer.php');
?>