<?php include('includes/header.php');
// include('includes/handlers/includeFiles.php');

    if(isset($_GET['id'])) {

        $artist = new Artist($con, $_GET['id']);
        // $artist = $album->getArtist();

    } else {
        header('Location: index.php');
    }

?>
    <h1><?php echo $artist->getName(); ?></h1>
    <button class='artist-play orange-btn'>Play</button>
    <hr>
    <div class="songs">
        <h3>Tracks</h3>
        <hr>
        <ul class="tracks">
            <?php 
                $songIds = $artist->getSongIds();
                $counter = 1;
                foreach ($songIds as $songId) {
                    $albumSong = new Song($con, $songId);
                    $songArtist = $albumSong->getArtist();

                    if($counter > 5) break;
            ?>

                    <li class="track">
                        <div class="track-num">
                            <ion-icon name="play-outline" class='play' data-id="<?php echo $albumSong->getId(); ?>"></ion-icon>
                            <span><?php echo $counter; ?></span>
                        </div>
                        <div class="track-details">
                            <h3 class='track-title'><?php echo $albumSong->getTitle(); ?></h3>
                            <p class='track-artist'><?php echo $songArtist->getName(); ?></p>
                        </div>
                        <div class="track-options"><ion-icon name="ellipsis-horizontal"></ion-icon></div>
                        <div class="track-duration"><?php echo $albumSong->getDuration(); ?></div>
                    </li> 
                                 
            <?php $counter++;   
                } ?>

        </ul>
    </div>
    <script>
        tempSongIds = <?php echo json_encode($songIds); ?>;
        domReady(() => {
            document.querySelector('.tracks').addEventListener('click', (e) => {
                console.log(e.target);
                if(e.target.classList.contains('play'));
                setTrack(e.target.dataset.id, tempSongIds, true );
            });

            document.querySelector('.artist-play').addEventListener('click', () => playFirstSong(tempSongIds));
        }) 
    </script>

        <div class="artist-albums">
            <h3>Albums</h3>
            <hr>
            <div class="albums-container">
                <?php 
                    $artistId = $_GET['id'];
                    $albums = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId' ORDER BY RAND()");

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

<?php include('includes/footer.php'); ?>