<?php
    include('includes/handlers/includeFiles.php');
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
    <div class="songs" data-songs='<?php echo json_encode($songIds); ?>'>
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