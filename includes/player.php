<?php 
    $songsQuery = mysqli_query($con, "SELECT id  FROM songs ORDER BY RAND() LIMIT 10");
    $songIds = [];
    while($songId = mysqli_fetch_array($songsQuery)) {
        array_push($songIds, $songId);
    }

    $songIdsJson = json_encode($songIds);
?>
<footer class='player' data-songs='<?php echo $songIdsJson; ?>'>
    <div class="song">
        <div class="song-image">
            <img src="#" alt="Music cover arts">
        </div>
        <div class="song-details">
            <h3 class="song-title"></h3>
            <a class="song-artist"></a>
        </div>
    </div>
    <div class="playback">
        <div class="controls">
            <ion-icon name="shuffle-outline" class='shuffle-btn'></ion-icon>
            <ion-icon name="play-skip-back-outline" class='prev-btn'></ion-icon>
            <span>
                <ion-icon name="play-outline" class='play-btn show'></ion-icon>
                <ion-icon name="pause-outline" class='pause-btn hide'></ion-icon>
            </span>
            <ion-icon name="play-skip-forward-outline" class="next-btn"></ion-icon>
            <ion-icon name="repeat-outline" class='repeat-btn'></ion-icon>
        </div>
        <div class="progress-bar">
            <span class="time-start">0:00</span>
            <div class="progress-bg">
                <div class="progress"></div>
            </div>
            <span class="time-end">0:00</span>
        </div>
    </div>
    <div class="volume">
        <div class="volume-icons">
            <ion-icon name="volume-high-outline" class='volume-high'></ion-icon>
            <ion-icon name="volume-medium-outline" class='volume-medium'></ion-icon>
            <ion-icon name="volume-low-outline" class='volume-low'></ion-icon>
            <ion-icon name="volume-off-outline" class='volume-off'></ion-icon>
            <ion-icon name="volume-mute-outline" class='volume-mute'></ion-icon>
        </div>
        <div class="progress-bar">
            <div class="progress-bg">
                <div class="progress"></div>
            </div>
            <span class="volume-percent">0%</span>
        </div>
    </div>
</footer>