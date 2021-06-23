let mouseDown = false;

let tempSongIds;
let userLoggedIn;
let timer;

let _optionsList = document.querySelectorAll('.options-list');
let _deletePlaylistBtn = document.querySelector('.delete-playlist');
let _mainSection = document.querySelector('.albums');
let _allLinks = document.querySelectorAll('a');
let _allImageLinksList = document.querySelectorAll('a img');
let _allImageLinks = [];
_allImageLinksList.forEach((el) => {
    _allImageLinks.push(el.closest('a'));
})
let _userDetails = document.querySelector('.user-details');

//player Dom elements
const _player = document.querySelector('.player');
const _timeStart = document.querySelector('.time-start');
const _timeEnd = document.querySelector('.time-end');
const _progressBar = document.querySelector('.playback .progress');
const _progressBarBg = document.querySelector('.playback .progress-bg');
const _volumePercent = document.querySelector('.volume-percent');
const _volumeProgressBar = document.querySelector('.volume .progress');
const _volumeBarBg = document.querySelector('.volume .progress-bg');
const _next = document.querySelector('.next-btn');
const _prev = document.querySelector('.prev-btn');
const _repeat = document.querySelector('.repeat-btn');
const _songTitle =  document.querySelector('.song-title');
const _songArtist = document.querySelector('.song-artist');
const _songImage = document.querySelector('.song-image img');
const _play = document.querySelector('.play-btn');
const _pause = document.querySelector('.pause-btn');
const _shuffle = document.querySelector('.shuffle-btn');
const _volumeIcons = document.querySelector('.volume-icons');
const _artistPlay = document.querySelector('.artist-play');
const _volumeHigh = document.querySelector('.volume-high');
const _volumeMedium = document.querySelector('.volume-medium');
const _volumeLow = document.querySelector('.volume-low');
const _volumeOff = document.querySelector('.volume-off');
const _volumeMute = document.querySelector('.volume-mute');

// Playlist Dom elements
let _allToggleOptions = document.querySelectorAll('.toggle-options');
let _allPlaylistOptions = document.querySelectorAll('.playlist-options');
let _allRemoveSong = document.querySelectorAll('.remove-song');
let _playlistButton = document.querySelector('.playlist-btn');
let _tracks = document.querySelector('.tracks');
let _songs = document.querySelector('.songs');


// Edit Page
let _email = document.querySelector('.email');
let _emailMessage = document.querySelector('.email-message');
let _currentPassword = document.querySelector('.current-password');
let _newPassword = document.querySelector('.new-password');
let _confirmNewPassword = document.querySelector('.confirm-new-password');
let _passwordMessage = document.querySelector('.password-message');
let _editEmail = document.querySelector('.edit-email');
let _editPassword = document.querySelector('.edit-password');
let _logoutBtn = document.querySelector('.logout-btn');
let _searchInput = document.querySelector('.search-input');

const loadPage = async  (url) => {
    
    let encodedUrl;
    if(url.indexOf('?') === -1) {
        encodedUrl = encodeURI(url + `?ajax=true&userLoggedIn=${userLoggedIn}`);
    } else {
        encodedUrl = encodeURI(url + `&ajax=true&userLoggedIn=${userLoggedIn}`);
    }
    // console.log(encodedUrl);
    const response = await fetch(encodedUrl)
    const body = await response.text();
    document.querySelector('.albums').innerHTML = body;
    history.pushState(null, null, encodedUrl.replace('ajax=true&', ''));
    INIT();
}

const getUrl =  (links) => {
    links.forEach(link => {
        link.addEventListener('click' , (e) => {
            e.preventDefault();
            const url = e.target.closest('a').getAttribute('href');
            loadPage(url);
        })
    }) 
}

getUrl([..._allLinks, ..._allImageLinks]);


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
    _timeStart.textContent = formatTime(audio.currentTime);
    _timeEnd.textContent = formatTime(audio.duration - audio.currentTime);

    const progress = Math.floor((audio.currentTime / audio.duration) * 100);
    _progressBar.style.width = `${progress}%`;
}

const updateVolumeIcon = (progress) => {
    [_volumeHigh, _volumeMedium, _volumeLow, _volumeOff, _volumeMute].forEach(el => el.style.display = 'none');

    if(progress === -1 ) {
        _volumeMute.style.display = 'block';
    } else if(progress < 30) {
        _volumeLow.style.display = 'block';
    } else if(progress < 60) {
        _volumeMedium.style.display = 'block';
    } else if(progress <= 100 ) {
        _volumeHigh.style.display = 'block';
    }
}

const updateVolumeProgressBar = (audio) => {
    const progress = audio.volume * 100;
    _volumePercent.textContent = `${Math.floor(progress)}%`
    _volumeProgressBar.style.width = `${progress}%`;
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
            _timeEnd.textContent = formatTime(this.duration);
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

// Logout functions
const logout = async () => {
    await fetch('includes/handlers/api/logout.php');
    location.reload();
}

const hideOptions = () => {
    _optionsList.forEach((el) => {
        el.style.display = 'none';
    })
}

const editEmail = async () => {
    const formData = new FormData();
    formData.append('email', _email.value);
    formData.append('username', document.body.dataset.userloggedin);

    const response = await fetch('includes/handlers/api/editEmail.php',  {
        method: 'POST',
        body: formData
    });

    const message = await response.text();
    // console.log(message);
    _emailMessage.textContent = message;
    setTimeout(() => {
        _emailMessage.textContent = '';
    }, 2000);
}


const editPassword = async () => {
    const formData = new FormData();
    formData.append('username', document.body.dataset.userloggedin);
    formData.append('currentPassword', _currentPassword.value);
    formData.append('newPassword', _newPassword.value);
    formData.append('confirmNewPassword', _confirmNewPassword.value);

    const response = await fetch('includes/handlers/api/editPassword.php',  {
        method: 'POST',
        body: formData
    });

    const message = await response.text();
    // console.log(message);
    _passwordMessage.textContent = message;

    if(message === 'Password Updated') {
        _currentPassword.value = '';
        _newPassword.value = '';
        _confirmNewPassword.value = '';
    }
   
    setTimeout(() => {
        _passwordMessage.textContent = '';
    }, 2000);
}

const search = (e) => {
    clearTimeout(timer);

    timer = setTimeout(() => {
        loadPage('search.php?term=' + e.target.value);
    }, 2000)
}


function INIT() {
    _optionsList = document.querySelectorAll('.options-list');
    _deletePlaylistBtn = document.querySelector('.delete-playlist');
    _mainSection = document.querySelector('.albums');
    _allLinks = document.querySelectorAll('a');
    _allImageLinksList = document.querySelectorAll('a img');
    _allImageLinks = [];
    _allImageLinksList.forEach((el) => {
        _allImageLinks.push(el.closest('a'));
    });
    _userDetails = document.querySelector('.user-details');


    // Playlist Dom elements
    _allToggleOptions = document.querySelectorAll('.toggle-options');
    _allPlaylistOptions = document.querySelectorAll('.playlist-options');
    _allRemoveSong = document.querySelectorAll('.remove-song');
    _playlistButton = document.querySelector('.playlist-btn');
    _tracks = document.querySelector('.tracks');
    _songs = document.querySelector('.songs');


    // Edit Page
    _email = document.querySelector('.email');
    _emailMessage = document.querySelector('.email-message');
    _currentPassword = document.querySelector('.current-password');
    _newPassword = document.querySelector('.new-password');
    _confirmNewPassword = document.querySelector('.confirm-new-password');
    _passwordMessage = document.querySelector('.password-message');
    _editEmail = document.querySelector('.edit-email');
    _editPassword = document.querySelector('.edit-password');
    _logoutBtn = document.querySelector('.logout-btn');
    _searchInput = document.querySelector('.search-input');

    _userDetails ? getUrl([..._allImageLinks, _userDetails]): getUrl([..._allImageLinks]);
    
    if(_logoutBtn)  {
        _logoutBtn.addEventListener('click', logout);
    } 

    // create Playlist
    if(_playlistButton) {
        _playlistButton.addEventListener('click', createPlaylist); 
    } 

    userLoggedIn = document.body.dataset.userloggedin

    if(_deletePlaylistBtn) {
        _deletePlaylistBtn.addEventListener('click', () => deletePlaylist(_deletePlaylistBtn.dataset.playlistid));
    }

    _allToggleOptions.forEach((el) => {
        el.addEventListener('click', (e) => {
            // console.log(el.classList.contains('toggle-options'));
            el.nextElementSibling.style.display = 'block';
        })
    })

    _mainSection.addEventListener('scroll', hideOptions);
    document.body.addEventListener('click', (e) => {
        if(e.target.className !== 'options-list' && !e.target.classList.contains('toggle-options') && !e.target.closest('.options-list')) {
            hideOptions();
        }
    });

    _allPlaylistOptions.forEach(el => {
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

    if(_songs) {
        tempSongIds = JSON.parse(_songs.dataset.songs);
    }
    

    if(_tracks) {
        _tracks.addEventListener('click', (e) => {
            if(e.target.classList.contains('play'))
                setTrack(e.target.dataset.id, tempSongIds, true );
        });
    }

    _allRemoveSong.forEach(el => {
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


    // Album codes 
    _allToggleOptions.forEach((el) => {
        el.addEventListener('click', (e) => {
            // console.log(el.classList.contains('toggle-options'));
            el.nextElementSibling.style.display = 'block';
        })
    })

    _mainSection.addEventListener('scroll', hideOptions);

    document.body.addEventListener('click', (e) => {
        if(e.target.className !== 'options-list' && !e.target.classList.contains('toggle-options') && !e.target.closest('.options-list')) {
            hideOptions();
        }
    });

    if(_artistPlay) {
        _artistPlay.addEventListener('click', () => playFirstSong(tempSongIds));
    }

    
    if(_editEmail) {
        _editEmail.addEventListener('click', editEmail);
        _editPassword.addEventListener('click', editPassword);
    }

    if(_searchInput) {
        const val = _searchInput.value;
        _searchInput.addEventListener('focus', function() {
            _searchInput.value = val;
        })
        _searchInput.focus();
        _searchInput.addEventListener('keydown', search)
    }
}