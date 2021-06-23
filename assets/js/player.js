let currentIndex = 0;
let repeat = false;
let currentPlaylist;
let shufflePlaylist;
let shuffle = false;

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

    if(currentIndex === (currentPlaylist.length - 1)) {
        currentIndex = 0;
    } else {
        currentIndex++;
    }
    

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
    _songTitle.textContent = song.title;

    // Get Artist
    const artistData = new FormData();
    artistData.append('artistId', song.artist);
    const artistResponse = await fetch('includes/handlers/api/getArtist.php', {
        method: 'POST',
        body: artistData
    });
    const artist = await artistResponse.json();
    _songArtist.textContent = artist.name;
    _songArtist.setAttribute('href', 'artist.php?id=' + artist.id);
    // Get Album
    const albumData = new FormData();
    albumData.append('albumId', song.album);
    const albumResponse = await fetch('includes/handlers/api/getAlbum.php', {
        method: 'POST',
        body: albumData
    });
    const album = await albumResponse.json();
    _songImage.setAttribute('src', album.coverArtPath);

    if(play) {
        audioElement.play();
        _play.className = 'play-btn hide';
        _pause.className = 'pause-btn show';
    };
}

const setShuffle = () => {
    shuffle = !shuffle;
    if(shuffle) {
        // randomize playlist
        _shuffle.className = _shuffle.className + ' active';
        shufflePlayList = shuffleArray(shufflePlaylist);
        currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
    } else {
        // reset to regular playlist
        const classNameArr = _shuffle.className.split(' ');
        classNameArr.pop();
        _shuffle.className = classNameArr.join(' ');

        currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
    }


}

let newPlaylist = JSON.parse(_player.dataset.songs);
newPlaylist = newPlaylist.map(el => el.id);
const audioElement = new Audio();

setTrack(newPlaylist[0], newPlaylist , false);

updateVolumeProgressBar(audioElement.audio);

const showPlayIcon = () => {
    _play.className = 'play-btn show';
    _pause.className = 'pause-btn hide';
}

const showPauseIcon = () => {
    _play.className = 'play-btn hide';
    _pause.className = 'pause-btn show';
}

['mousedown', 'touchstart', 'mousemove', 'touchmove'].forEach((event) => {
    _player.addEventListener(event,(e) => {
        e.preventDefault();
    });
})

const toggleRepeat = (repeat) => {
    if(repeat) {
        _repeat.className = _repeat.className + ' active';
    } else {
        const classNameArr = _repeat.className.split(' ');
        classNameArr.pop();
        _repeat.className = classNameArr.join(' ');
    }
}

const toggleMute = () => {
    audioElement.audio.muted = !audioElement.audio.muted;
    updateVolumeIcon(-1);
}



const calcTimeFromOffset = (mouseEvent, progressBar) => {
    const percentage = (mouseEvent.offsetX / progressBar.offsetWidth) * 100;
    const seconds = audioElement.audio.duration * (percentage / 100);
    audioElement.setTime(seconds);
}

_play.addEventListener('click',async () => {
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

_pause.addEventListener('click', () => {
    showPlayIcon();
    audioElement.pause();
});

_progressBarBg.addEventListener('mousedown', () => mouseDown = true);
_progressBarBg.addEventListener('mousemove', function (e) {
    if(mouseDown) {
        // Calculate time depending on x-offset position;
        calcTimeFromOffset(e, this);
    }
})

_progressBarBg.addEventListener('mouseup', function (e) { 
    calcTimeFromOffset(e, this);
    mouseDown = false;
});

_volumeBarBg.addEventListener('mousedown', () => mouseDown = true);
_volumeBarBg.addEventListener('mousemove', function (e) {
    if(mouseDown) {
        // Calculate volume depending on x-offset position;
        const percentage = e.offsetX / this.offsetWidth;
        if(percentage >= 0 && percentage <= 1) {
            audioElement.setVolume(percentage);
        }
    }
})

_volumeBarBg.addEventListener('mouseup', function (e) { 
    const percentage = e.offsetX / this.offsetWidth;
    if(percentage >= 0 && percentage <= 1) {
        audioElement.setVolume(percentage);
    }
    mouseDown = false;
});


document.addEventListener('mouseup', () => {
    mouseDown = false;
})

_next.addEventListener('click', () => nextSong());
_prev.addEventListener('click', () => prevSong());
_repeat.addEventListener('click', () => {
    repeat = !repeat;
    toggleRepeat(repeat);
});

_shuffle.addEventListener('click', setShuffle);

_volumeIcons.addEventListener('click', (e) => {
    
    if(!e.target.classList.contains('volume-mute')) {
        toggleMute();
    } else {
        audioElement.audio.muted = false;
    }
})
