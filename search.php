<?php 
include('includes/handlers/includeFiles.php');

    if(isset($_GET['term'])) {
        $searchTerm = urldecode($_GET['term']);
    } else {
        $searchTerm = "";
    }

    $query = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$searchTerm%' LIMIT 10");
    $songIds = [];
    while($row = mysqli_fetch_array($query)) {
        array_push($songIds, $row['id']);
    }
?>
    <div class="search-box">
        <h2>Search for artists, albums and songs</h2>
        <input type="text" value='<?php echo $searchTerm; ?>' class='search-input' placeholder="Type here...">
    </div>

<?php if($searchTerm == '') exit(); ?>

    <div class="songs" data-songs='<?php echo json_encode($songIds); ?>'>
        <h3>Tracks</h3>
        <hr>
        <ul class="tracks">
            <?php
                if(mysqli_num_rows($query) == 0) {
                    ?>
                        <span>No songs matching <?php echo $searchTerm; ?></span>
                    <?php
                }

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
    
    <div class="artists">
        <h3>Artists</h3>
        <hr>
        <ul class='artist-list'>
            <?php 
                $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$searchTerm%'");
                $artistIds = [];
                while($row = mysqli_fetch_array($artistsQuery)) {
                    array_push($artistIds, $row['id']);
                }

                if(mysqli_num_rows($artistsQuery) == 0) {
                    ?>
                        <span>No artists matching <?php echo $searchTerm; ?></span>
                    <?php
                }

                foreach($artistIds as $artistId) {
                    $artist = new Artist($con, $artistId);
                    ?>
                    <li class='artist-item'>
                        <a href="artist.php?id=<?php echo $artist->getId(); ?>">
                            <?php echo $artist->getName(); ?>
                        </a>
                    </li>
                    <?php
                }
            ?>
        </ul>
    </div>
    
    <div class="artists">
        <h3>Albums</h3>
        <hr>
        <div class="albums-list">
            <?php 
                $albums = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '%$searchTerm%'");

                if(mysqli_num_rows($albums) == 0) {
                    ?>
                        <span>No albums matching <?php echo $searchTerm; ?></span>
                    <?php
                }

                while($album = mysqli_fetch_array($albums)) {
                    ?>
                    <a href="album.php?id=<?php echo $album['id'] ;?>" class="album">
                        <span class="album-image">
                            <img src="<?php echo $album['coverArtPath']; ?>" alt="Cover arts <?php echo $album['title']; ?>">
                        </span>
                        <span class='album-title'><?php echo $album['title'] ; ?> </span>
                    </a>
                
                <?php }
            ?>
        </div>
    </div>
    