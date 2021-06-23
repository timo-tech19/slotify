<aside class="navbar">
    <div class="logo">
        <h1> <a href="index.php">Slot<span>i</span>fy</a></h1>
    </div>
    <hr>
    <a class='search' href="search.php">
        <span>SEARCH</span>
        <ion-icon name="search-sharp"></ion-icon>
    </a>
    <hr>
    <ul class='nav'>
        <li>
            <a href="index.php" class="browse">Browse</a>
        </li>
        <li>
            <a href="playlists.php" class="your-music">Your Music</a>
        </li>
        <li>
            <a href="settings.php" class='profile'><?php echo $currentUser->getFullName(); ?></a>
        </li>
    </ul>
</aside>