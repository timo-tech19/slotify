<?php 
    include('includes/handlers/includeFiles.php');
    // session_destroy();

    // Check session data and redirect if it does not exist
    if(isset($_SESSION['userLoggedIn'])) {
        $userLoggedIn = $_SESSION['userLoggedIn'];
    } else {
        header('Location: register.php');
    }
    $currentUser = new User($con, $userLoggedIn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slotify | Home</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <script type="module" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@5.0.0/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body data-userloggedin="<?php echo $userLoggedIn ?>">
    <!-- <h1>Welcome to Slotify <?php echo $userLoggedIn ?></h1> -->
    <main class='container'>
        <?php include('includes/navbar.php') ?>

        <section class='albums'>