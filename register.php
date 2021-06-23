<?php 
    include("includes/classes/Constants.php");
    include("includes/config.php");
    include("includes/classes/Account.php");
    $account = new Account($con);
    include("includes/handlers/registerHandler.php");
    include("includes/handlers/loginHandler.php");

    // Remember user input
    function getUserInput($name) {
        if(isset($_POST[$name])) {
            echo $_POST[$name];
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slotify | Register</title>
    <link rel="stylesheet" href="assets/css/register.css">

   
</head>
<body>
    <div class="background">
        <div class="content">
            <div class="login-form-container">
                <form action="register.php" method='POST' class="login-form">
                    <h2>Login to your Account</h2>
                    <div>
                        <?php echo $account->getError(Constants::$loginFailed); ?>
                        <label for="login-username">Username</label>
                        <input type="text" name='loginUsername' id="login-username" value="<?php getUserInput('loginUsername') ?>" required>
                    </div>
                    <div>
                        <label for="login-password">Password</label>
                        <input type="password" name='loginPassword' id="login-password" required>
                    </div>
                    <div>
                        <button type="submit" name='loginButton'>Login</button>
                    </div>
                    <p>Don't have an account? <a href="#" class="register-link">Register Here</a></p>
                </form>

                <form action="register.php" method='POST' class="register-form">
                    <h2>Create your FREE Account</h2>
                    <div>
                        <?php echo $account->getError(Constants::$usernameCharacters); ?>
                        <?php echo $account->getError(Constants::$usernameTaken); ?>
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="<?php getUserInput('username') ?>" required>
                    </div>
                    <div>
                        <?php echo $account->getError(Constants::$firstNameCharacters); ?>

                        <label for="firstname">Firstname</label>
                        <input type="text" name="firstname" id="firstname" value="<?php getUserInput('firstname') ?>" required>
                    </div>
                    <div>
                    <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                    
                        <label for="lastname">Lastname</label>
                        <input type="text" name="lastname" id="lastname" value="<?php getUserInput('lastname') ?>" required>
                    </div>
                    <div>
                        <?php echo $account->getError(Constants::$emailInvalid); ?>
                        <?php echo $account->getError(Constants::$emailTaken); ?>
                        <label for="email">Email</label>
                        <input type="email" name='email' id="email" value="<?php getUserInput('email') ?>" required>
                    </div>
                    <div>
                        <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
                        <?php echo $account->getError(Constants::$passwordCharacters); ?>
                        <?php echo $account->getError(Constants::$passwordsDoNoMatch); ?>
                        <label for="password">Password</label>
                        <input type="password" name='password' id="password" required>
                    </div>
                    <div>
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" name='confirmPassword' id="confirm-password" required>
                    </div>
                    <div>
                        <button type="submit" name='registerButton'>Register</button>
                    </div>
                    <p>Already have an account? <a href="#" class='login-link'>Login Here</a></p>
                </form>
            </div>
            <div class="login-text">
                <h1>Get great music, right now</h1>
                <h2>Listen to loads of music for free</h2>
                <ul>
                    <li>Discover music you'll fall in love with</li>
                    <li>Create your own playlist</li>
                    <li>Follow artists to keep up to date</li>
                </ul>
            </div>
        </div>
        
    </div>
    <script src="assets/js/register.js"></script>
    <?php 
        if(isset($_POST['registerButton'])) {
            echo "<script>
                    registerForm.className = 'login-form show';
                    loginForm.className = 'login-form hide';
                </script>";
        } else {
            echo "<script>
                    registerForm.className = 'login-form hide';
                    loginForm.className = 'login-form show';
                </script>";
        }
    ?>
</body>
</html>