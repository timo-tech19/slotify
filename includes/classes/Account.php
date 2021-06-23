<?php

class Account {

    private $con;
    public function __construct($con) {
        $this->con = $con;
       $this->errors = [];
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw) {
        // hash password
        $ecryptedPw = md5($pw);
        $profilePic = 'assets/images/users/default.jpg';
        $date = date('Y-m-d');

        return mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$ecryptedPw', '$date', '$profilePic')");
    }

    public function login($un, $pw) {
        $epw = md5($pw);
        
        $query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password ='$epw'");

        if(!mysqli_num_rows($query)) {
            array_push($this->errors, Constants::$loginFailed);
            return false;
        }

        return true;
    }


    public function register($un, $fn, $ln, $em, $pw, $cpw) {
        $this->validateUsername($un);
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmail($em);
        $this->validatePassword($pw, $cpw);

        if(empty($this->errors)) {
            // insert user into DB
            return $this->insertUserDetails($un, $fn, $ln, $em, $pw);
        } else {
            return false;
        }
    }

   
    public function getError($errorMsg) {
        if(!in_array($errorMsg, $this->errors)) {
            $errorMsg = '';
            return $errorMsg;
        }
        return "<span class='error-message'>$errorMsg</span>";
    }

    private function validateUsername($un) {

        if(strlen($un) > 25 || strlen($un) < 5) { 
            array_push($this->errors, Constants::$usernameCharacters);
            return;
        }

        // Check if username exist in DB
        $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");

        // if a record exists
        if(mysqli_num_rows($checkUsernameQuery) != 0) {
            array_push($this->errors, Constants::$usernameTaken);
        }
    }
    private function validateFirstName($fn) {
        if(strlen($fn) > 25 || strlen($fn) < 2) {
            array_push($this->errors, Constants::$firstNameCharacters);
            return;
        }
    }
    private function validateLastName($ln) {
        if(strlen($ln) > 25 || strlen($ln) < 2) {
            array_push($this->errors, Constants::$lastNameCharacters);
            return;
        }
    }
    private function validateEmail($em) {
        // Check for valid email
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errors, Constants::$emailInvalid);
            return;
        }
        // Check if email is not in use
        $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");

        // if a record exists
        if(mysqli_num_rows($checkEmailQuery) != 0) {
            array_push($this->errors, Constants::$emailTaken);
        }
    }
    private function validatePassword($pw, $cpw) {
        // Check if passwords are the same
        if($pw !== $cpw) {
            array_push($this->errors, Constants::$passwordsDoNoMatch);
            return;
        }

        // Check that password contains only letters and numbers
        if(preg_match('/[^A-Za-z0-9]/', $pw)) {
            array_push($this->errors, Constants::$passwordNotAlphanumeric);
            return;
        }

        // Check that password length is between 8-30
        if(strlen($pw) > 30 || strlen($pw) < 8) {
            array_push($this->errors, Constants::$passwordCharacters);
            return;
        }
    }
}