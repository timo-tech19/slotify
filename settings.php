<?php
    if(!isset($_GET['ajax'])) {
        include('includes/header.php');
    } else {
        include('includes/handlers/includeFiles.php');
    }
?>
    <h1><?php echo $currentUser->getFullName(); ?></h1>
    <hr>
    <div class="buttons">
        <a href='edit.php' class='button'>User Details</a>
        <button class='logout-btn'>Logout</button>
    </div>

    <script>
        const logout = async () => {
            await fetch('includes/handlers/api/logout.php');
            location.reload();
        }

        domReady(() => {
            document.querySelector('.logout-btn').addEventListener('click', logout);
        })
    </script>
<?php
    if(!isset($_GET['ajax'])) {
        include('includes/footer.php');
    }
?>
 