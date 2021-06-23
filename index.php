<?php
    if(!isset($_GET['ajax'])) {
        include('includes/header.php');
    } else {
        include('includes/handlers/includeFiles.php');
    }
?>
    <h1>Music You Might Like</h1>
    <hr>
    <div class="albums-container">
        <?php 
            $albums = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

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
<?php
    if(!isset($_GET['ajax'])) {
        include('includes/footer.php');
    }
?>
 