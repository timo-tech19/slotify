<?php 
    $songsQuery = mysqli_query($con, "SELECT id  FROM songs ORDER BY RAND() LIMIT 10");
    $songIds = [];
    while($songId = mysqli_fetch_array($songsQuery)) {
        array_push($songIds, $songId);
    }

    $songIdsJson = json_encode($songIds);
?>

<script>
    let currentIndex = 0;
    let repeat = false;
    let currentPlaylist;
    let shufflePlaylist;
    let shuffle = false;
    let shuffleBtn;

    const prevSong = () => {

        if(audioElement.audio.currentTime > 3 || currentIndex === 0) {
            audioElement.setTime(0);
            return;
        }
        
        currentIndex--;
        setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
    }

    const nextSong = () => {

        if(repeat) {
            audioElement.setTime(0);
            audioElement.play();
            return;
        }
        // console.log(currentIndex);
        if(currentIndex === (currentPlaylist.length - 1)) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }
        
        // console.log(currentIndex);
        const songToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(songToPlay, currentPlaylist, true);
    }

    const setTrack = async (currentSongId, newPlaylist, play) => {

        if(newPlaylist !== currentPlaylist) {
            currentPlaylist = newPlaylist;
            shufflePlaylist = shuffleArray([...currentPlaylist]);
        }
 
        currentIndex =  shuffle ? shufflePlaylist.indexOf(currentSongId) : currentPlaylist.indexOf(currentSongId);

        var formData = new FormData();
        formData.append('songId', currentSongId);
        audioElement.pause();

        // Get SONG
        const response = await fetch('includes/handlers/api/getSongs.php', {
            method: 'POST',
            body: formData
        });
        const song = await response.json();
        audioElement.setTrack(song);
        document.querySelector('.song-title').textContent = song.title;

        // Get Artist
        const artistData = new FormData();
        artistData.append('artistId', song.artist);
        const artistResponse = await fetch('includes/handlers/api/getArtist.php', {
            method: 'POST',
            body: artistData
        });
        const artist = await artistResponse.json();
        const songArtistEl = document.querySelector('.song-artist');
        songArtistEl.textContent = artist.name;
        songArtistEl.setAttribute('href', 'artist.php?id=' + artist.id);
        // Get Album
        const albumData = new FormData();
        albumData.append('albumId', song.album);
        const albumResponse = await fetch('includes/handlers/api/getAlbum.php', {
            method: 'POST',
            body: albumData
        });
        const album = await albumResponse.json();
        document.querySelector('.song-image img').setAttribute('src', album.coverArtPath);

        if(play) {
            audioElement.play();
            document.querySelector('.play-btn').className = 'play-btn hide';
            document.querySelector('.pause-btn').className = 'pause-btn show';
        };
    }

    const setShuffle = () => {
        shuffle = !shuffle;
        if(shuffle) {
            // randomize playlist
            shuffleBtn.className = shuffleBtn.className + ' active';
            shufflePlayList = shuffleArray(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        } else {
            // reset to regular playlist
            const classNameArr = shuffleBtn.className.split(' ');
            classNameArr.pop();
            shuffleBtn.className = classNameArr.join(' ');

            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        }

        // console.log(currentIndex);
    }

    let newPlaylist = <?php echo $songIdsJson; ?>;
    newPlaylist = newPlaylist.map(el => el.id);
    const audioElement = new Audio();
    // console.log(audioElement);
    setTrack(newPlaylist[0], newPlaylist , false);


    domReady(() => {
        updateVolumeProgressBar(audioElement.audio);
       
        const player = document.querySelector('.player');
        const playButton = document.querySelector('.play-btn');
        const pauseButton = document.querySelector('.pause-btn');
        const progressBar = document.querySelector('.playback .progress-bg');
        const volumeBar = document.querySelector('.volume .progress-bg');
        const nextBtn = document.querySelector('.next-btn');
        const prevBtn = document.querySelector('.prev-btn');
        const repeatBtn = document.querySelector('.repeat-btn');
        shuffleBtn = document.querySelector('.shuffle-btn');
        const volumeIcons = document.querySelector('.volume-icons');

        const showPlayIcon = () => {
            playButton.className = 'play-btn show';
            pauseButton.className = 'pause-btn hide';
        }

        const showPauseIcon = () => {
            playButton.className = 'play-btn hide';
            pauseButton.className = 'pause-btn show';
        }

        ['mousedown', 'touchstart', 'mousemove', 'touchmove'].forEach((event) => {
            player.addEventListener(event,(e) => {
                e.preventDefault();
            });
        })

        const toggleRepeat = (repeat) => {
            if(repeat) {
                repeatBtn.className = repeatBtn.className + ' active';
            } else {
                const classNameArr = repeatBtn.className.split(' ');
                classNameArr.pop();
                repeatBtn.className = classNameArr.join(' ');
            }
        }

        const toggleMute = () => {
            audioElement.audio.muted = !audioElement.audio.muted;
            updateVolumeIcon(-1);
        }

        playButton.addEventListener('click',async () => {
            showPauseIcon();
            audioElement.play();

            if(audioElement.audio.currentTime === 0) {
                const updateData = new FormData();
                updateData.append('songId', audioElement.currentlyPlaying);
                await fetch('includes/handlers/api/updatePlays.php', {
                    method: 'POST',
                    body: updateData
                });
            }
        });

        pauseButton.addEventListener('click', () => {
            showPlayIcon();
            audioElement.pause();
        });

        const calcTimeFromOffset = (mouseEvent, progressBar) => {
            const percentage = (mouseEvent.offsetX / progressBar.offsetWidth) * 100;
            const seconds = audioElement.audio.duration * (percentage / 100);
            audioElement.setTime(seconds);
        }

        progressBar.addEventListener('mousedown', () => mouseDown = true);
        progressBar.addEventListener('mousemove', function (e) {
            if(mouseDown) {
                // Calculate time depending on x-offset position;
                calcTimeFromOffset(e, this);
            }
        })

        progressBar.addEventListener('mouseup', function (e) { 
            calcTimeFromOffset(e, this);
            mouseDown = false;
        });

        volumeBar.addEventListener('mousedown', () => mouseDown = true);
        volumeBar.addEventListener('mousemove', function (e) {
            if(mouseDown) {
                // Calculate volume depending on x-offset position;
                const percentage = e.offsetX / this.offsetWidth;
                if(percentage >= 0 && percentage <= 1) {
                    audioElement.setVolume(percentage);
                }
            }
        })

        volumeBar.addEventListener('mouseup', function (e) { 
            const percentage = e.offsetX / this.offsetWidth;
            if(percentage >= 0 && percentage <= 1) {
                audioElement.setVolume(percentage);
            }
            mouseDown = false;
        });


        document.addEventListener('mouseup', () => {
            mouseDown = false;
        })

        nextBtn.addEventListener('click', () => nextSong());
        prevBtn.addEventListener('click', () => prevSong());
        repeatBtn.addEventListener('click', () => {
            repeat = !repeat;
            toggleRepeat(repeat);
        });

        shuffleBtn.addEventListener('click', setShuffle);

        volumeIcons.addEventListener('click', (e) => {
            
            if(!e.target.classList.contains('volume-mute')) {
                toggleMute();
            } else {
                audioElement.audio.muted = false;
            }
        })
    });

</script>


<footer class='player'>
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