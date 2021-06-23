<?php

    function sanitizeFormUsername($inputText) {
        $inputText =  strip_tags($inputText);
        $inputText = str_replace(' ', '', $inputText);
        return  $inputText;
    }

    function sanitizeFormPassword($inputText) {
        $inputText =  strip_tags($inputText);
        return  $inputText;
    }

    function sanitizeFormString($inputText) {
        $inputText =  strip_tags($inputText);
        $inputText = str_replace(' ', '', $inputText);
        $inputText = ucfirst(strtolower($inputText));
        return  $inputText;
    }


    if(isset($_POST['registerButton'])) {
        // Login Button was pressed
        $username = sanitizeFormUsername($_POST['username']);
        $firstname =  sanitizeFormString($_POST['firstname']);
        $lastname =  sanitizeFormString($_POST['lastname']);
        $email =  sanitizeFormPassword($_POST['email']);
        $password =  sanitizeFormPassword($_POST['password']);
        $confirmPassword =  sanitizeFormPassword($_POST['confirmPassword']);
        // echo "$username";

        $IsRegistered = $account->register($username, $firstname, $lastname, $email, $password, $confirmPassword);
        
        if($IsRegistered) {
            $_SESSION['userLoggedIn'] = $username;
            header('Location: index.php');
        }
    }

?>