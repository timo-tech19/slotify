let mouseDown = false;

let volumeOff;
let volumeLow;
let volumeHigh;
let volumeMute;

let tempSongIds;
let userLoggedIn;
let deletePlaylistBtn;

// const loadPage = (url) => {
//     
//     let encodedUrl;
//     if(url.indexOf('?') === -1) {
//         encodedUrl = encodeURI(url + `?ajax=true&userLoggedIn=${userLoggedIn}`);
//     } else {
//         encodedUrl = encodeURI(url + `&ajax=true&userLoggedIn=${userLoggedIn}`);
//     }
//     // console.log(encodedUrl);
//     fetch(encodedUrl)
//         .then(function(response) {
//             return response.text();
//         })
//         .then(function(body) {
//             document.querySelector('.albums').innerHTML = body;
//             history.pushState();
//             loadEvents();
//         });
// }

// const getUrl =  (links) => {
//     links.forEach(link => {
//         link.addEventListener('click' , (e) => {
//             e.preventDefault();
//             const url = e.target.closest('a').getAttribute('href');
//             loadPage(url);
//         })
//     }) 
// }

// const loadEvents = () => domReady(() => {
//     const logo = document.querySelector('.logo h1');
//     const albumLinks = document.querySelectorAll('.album');
//     const browse = document.querySelector('.browse');
//     const playlists = document.querySelector('.your-music');
//     const songArtist = document.querySelector('.song-artist');

//     getUrl([logo, browse, songArtist, playlists, ...albumLinks]);

// })

// loadEvents();

const createPlaylist = async () => {
    const playlistName = prompt('Please enter playlist name: ');

    if(playlistName) {
        console.log(playlistName, userLoggedIn);
        var formData = new FormData();
        formData.append('name', playlistName);
        formData.append('username', userLoggedIn);

        // Create Playlist
        const response = await fetch('includes/handlers/api/createPlaylist.php', {
            method: 'POST',
            body: formData
        });

        const error = await response.text();

        if(error) {
            alert(error);
        }
    }
}

const deletePlaylist = async (playlistId) => {
    const cfrm = confirm('Are your sure you want to delete this playlist? ');

    if(cfrm) {
        var formData = new FormData();
        formData.append('id', playlistId);

        // Delete Playlist
        const response = await fetch('includes/handlers/api/deletePlaylist.php', {
            method: 'POST',
            body: formData
        });

        history.pushState(null, null, 'http://localhost/slotify/playlists.php');

        const error = await response.text();

        if(error) {
            alert(error);
        }
    }
}

const formatTime = (time) => {
    const timeInSeconds = Math.round(time);
    const minutes = Math.floor(timeInSeconds / 60);
    const seconds = timeInSeconds- (minutes * 60);

    const extraZero = seconds < 10 ? '0': '';

    return `${minutes}:${extraZero + seconds}`;
}

const updateTimeProgressBar = (audio) => {
    document.querySelector('.time-start').textContent = formatTime(audio.currentTime);
    document.querySelector('.time-end').textContent = formatTime(audio.duration - audio.currentTime);

    const progress = Math.floor((audio.currentTime / audio.duration) * 100);
    document.querySelector('.playback .progress').style.width = `${progress}%`;
}

const updateVolumeIcon = (progress) => {
    [volumeHigh, volumeMedium, volumeLow, volumeOff, volumeMute].forEach(el => el.style.display = 'none');

    if(progress === -1 ) {
        volumeMute.style.display = 'block';
    } else if(progress < 30) {
        volumeLow.style.display = 'block';
    } else if(progress < 60) {
        volumeMedium.style.display = 'block';
    } else if(progress <= 100 ) {
        volumeHigh.style.display = 'block';
    }
}

const updateVolumeProgressBar = (audio) => {
    // const volumeIcons = document.querySelector('.volume ion-icon');
    const progress = audio.volume * 100;
    document.querySelector('.volume-percent').textContent = `${Math.floor(progress)}%`
    document.querySelector('.volume .progress').style.width = `${progress}%`;
    if(!audio.muted) {
        updateVolumeIcon(progress);
    }
}

const playFirstSong = (playlist) => {
    setTrack(playlist[0], playlist, true);
}

class Audio {
    constructor () {
        this.audio = document.createElement('audio');
        this.currentlyPlaying;

        
        this.audio.addEventListener('canplay', function() {
            // set duration on UI
            document.querySelector('.time-end').textContent = formatTime(this.duration);
        });

        this.audio.addEventListener('timeupdate', function() {
            if(this.duration) updateTimeProgressBar(this);
        });

        this.audio.addEventListener('volumechange', function() {
            updateVolumeProgressBar(this);
        })

        this.audio.addEventListener('ended', nextSong);
    }

    setTrack = (song) => {
        this.currentlyPlaying = song
        this.audio.src = song.path;
    }

    play = () => {
        this.audio.play();
    }

    pause = () => {
        this.audio.pause();
    }

    setTime = (seconds) => {
        this.audio.currentTime = seconds;
    }

    setVolume = (volume) => {
        this.audio.volume = volume;
    }

}

function domReady(fn) {
    if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

domReady(() => {
    volumeHigh = document.querySelector('.volume-high');
    volumeMedium = document.querySelector('.volume-medium');
    volumeLow = document.querySelector('.volume-low');
    volumeOff = document.querySelector('.volume-off');
    volumeMute = document.querySelector('.volume-mute');

    userLoggedIn = document.body.dataset.userloggedin
    deletePlaylistBtn = document.querySelector('.delete-playlist');

    deletePlaylistBtn.addEventListener('click', () => deletePlaylist(deletePlaylistBtn.dataset.playlistid));

        
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


function shuffleArray(array) {
    var currentIndex = array.length,  randomIndex;
  
    // While there remain elements to shuffle...
    while (0 !== currentIndex) {
  
      // Pick a remaining element...
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex--;
  
      // And swap it with the current element.
      [array[currentIndex], array[randomIndex]] = [
        array[randomIndex], array[currentIndex]];
    }
  
    return array;
  }