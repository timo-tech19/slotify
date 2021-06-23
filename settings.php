<?php
    include('includes/handlers/includeFiles.php');
?>
    <h1><?php echo $currentUser->getFullName(); ?></h1>
    <hr>
    <div class="buttons">
        <a href='edit.php' class='button user-details'>User Details</a>
        <button class='logout-btn'>Logout</button>
    </div>
 