<?php
    include('../../config.php');

    if(!isset($_POST['username'])) {
        echo 'User not found';
        exit();
    }

    if(!isset($_POST['currentPassword']) || !isset($_POST['newPassword']) || !isset($_POST['confirmNewPassword'])) {
        echo "All passwords not set";
        exit();
    }

    if($_POST['currentPassword'] == '' || $_POST['newPassword'] == '' || $_POST['confirmNewPassword'] == '') {
        echo 'Please fill in all fields';
        exit();
    }

    $username = $_POST['username'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    $currentPasswordHash = md5($currentPassword);

    $passwordCheck = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$currentPasswordHash'");
    if(mysqli_num_rows($passwordCheck) != 1) {
        echo 'Current Password is incorrect';
        exit();
    }

    if($newPassword != $confirmNewPassword) {
        echo 'New Passwords do not match';
        exit();
    }

    if(preg_match('/[^A-Za-z0-9]/', $newPassword)) {
        echo "Password must container only letters and numbers";
        exit();
    }

    if(strlen($newPassword) > 30 || strlen($newPassword) < 8) {
        echo 'Password must be 8-30 characters long';
        exit();
    }

    $newPasswordHash = md5($newPassword);
    $editPasswordQuery = mysqli_query($con, "UPDATE users SET password='$newPasswordHash' WHERE username='$username'");
    echo 'Password Updated';
?>