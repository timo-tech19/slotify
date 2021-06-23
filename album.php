<?php
    include('includes/header.php');
    // include('includes/handlers/includeFiles.php');
    if(isset($_GET['id'])) {

        $album = new Album($con, $_GET['id']);
        $artist = $album->getArtist();

    } else {
        header('Location: index.php');
    }

    $songIds = $album->getSongIds();
    // var_dump($currentUser->getPlaylists());
?>
    <!-- <h1>Album</h1>
    <hr> -->
    <div class="album-container">
        <div class="cover-art">
            <img src="<?php echo $album->getCoverArtPath(); ?>" alt="Cover Arts">
        </div>
        <div class="album-details">
            <h2><?php echo $album->getTitle(); ?></h2>
            <p><?php echo  $artist->getName(); ?></p>
            <p><?php echo  $album->getNumberOfSongs(); ?> songs</p>
        </div>
    </div>
    <div class="songs">
        <h3>Tracks</h3>
        <hr>
        <ul class="tracks">
            <?php 
                $counter = 1;
                foreach ($songIds as $songId) {
                    $albumSong = new Song($con, $songId);
                    $songArtist = $albumSong->getArtist();
            ?>

                    <li class="track" data-song='<?php echo $albumSong->getId(); ?>'>
                        <div class="track-num">
                            <ion-icon name="play-outline" class='play' data-id="<?php echo $albumSong->getId(); ?>"></ion-icon>
                            <span><?php echo $counter; ?></span>
                        </div>
                        <div class="track-details">
                            <h3 class='track-title'><?php echo $albumSong->getTitle(); ?></h3>
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
                                           foreach($playlists as $playlist) {
                                              ?>  
                                                <option value="<?php echo $playlist['id']; ?>">
                                                    <?php echo $playlist['name']; ?>
                                                </option>
                                              <?php
                                           }
                                        ?>
                                    </select>
                                </li>
                                <li class="options-item">Copy song link</li>
                            </ul>
                        </div>
                        <div class="track-duration"><?php echo $albumSong->getDuration(); ?></div>
                    </li> 
                                 
            <?php $counter++;   
                } ?>

        </ul>
    </div>
    <script >
        // const optionsList = document.querySelector('.options-list');
        tempSongIds = <?php echo json_encode($songIds); ?>;
        domReady(() => {;
            const optionsList = document.querySelectorAll('.options-list');

            document.querySelector('.tracks').addEventListener('click', (e) => {
                if(e.target.classList.contains('play'))
                    setTrack(e.target.dataset.id, tempSongIds, true );
            });

            const hideOptions = () => {
                optionsList.forEach((el) => {
                    el.style.display = 'none';
                })
            }
            
            document.querySelectorAll('.toggle-options').forEach((el) => {
                el.addEventListener('click', (e) => {
                    // console.log(el.classList.contains('toggle-options'));
                    el.nextElementSibling.style.display = 'block';
                })
            })
            document.querySelector('.albums').addEventListener('scroll', hideOptions);
            document.body.addEventListener('click', (e) => {
                if(e.target.className !== 'options-list' && !e.target.classList.contains('toggle-options') && !e.target.closest('.options-list')) {
                    hideOptions();
                }
            });

            document.querySelectorAll('.playlist-options').forEach(el => {
                el.addEventListener('change', async (e) => {
                    const songId = e.target.closest('.track').dataset.song;
                    const playlistId = e.target[e.target.selectedIndex].value;

                    var formData = new FormData();
                    formData.append('songId', songId);
                    formData.append('playlistId', playlistId);

                    // Get SONG
                    const response = await fetch('includes/handlers/api/addToPlaylist.php', {
                        method: 'POST',
                        body: formData
                    });

                    const error = await response.text();
                    if(error) {
                        alert(`Error: ${error}`);
                    }

                    hideOptions();
                    e.target.selectedIndex = 0;
                })
            })
                    
        })
    </script>

<?php 
    include('includes/footer.php');
?>