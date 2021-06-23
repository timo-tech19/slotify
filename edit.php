<?php
    if(!isset($_GET['ajax'])) {
        include('includes/header.php');
    } else {
        include('includes/handlers/includeFiles.php');
    }
?>
    <h1>Edit User Details</h1>
    <hr>
    <div class="form-group">
        <h2>Change Email</h2>
        <input type="email" class='form-control email' value='<?php echo $currentUser->getEmail(); ?>' placeholder="New Email...">
        <p class="message email-message"></p>
        <button class='edit-email'>Save</button>
    </div>
    <hr>
    <div class="form-group">
        <h2>Change Password</h2>
        <input type="password" class='form-control current-password' placeholder="Current Password">
        <input type="password" class='form-control new-password' placeholder="New Password">
        <input type="password" class='form-control confirm-new-password' placeholder="Confirm New Password">
        <p class="message password-message"></p>
        <button class='edit-password'>Save</button>
    </div>

    <script>
        const email = document.querySelector('.email');
        const emailMessage = document.querySelector('.email-message');
        const currentPassword = document.querySelector('.current-password');
        const newPassword = document.querySelector('.new-password');
        const confirmNewPassword = document.querySelector('.confirm-new-password');
        const passwordMessage = document.querySelector('.password-message');

        const editEmail = async () => {
            const formData = new FormData();
            formData.append('email', email.value);
            formData.append('username', document.body.dataset.userloggedin);

            const response = await fetch('includes/handlers/api/edit.php',  {
                method: 'POST',
                body: formData
            });

            const message = await response.text();
            // console.log(message);
            emailMessage.textContent = message;
            setTimeout(() => {
                emailMessage.textContent = '';
            }, 2000);
        }

        const editPassword = async () => {
            const formData = new FormData();
            formData.append('username', document.body.dataset.userloggedin);
            formData.append('currentPassword', currentPassword.value);
            formData.append('newPassword', newPassword.value);
            formData.append('confirmNewPassword', confirmNewPassword.value);

            const response = await fetch('includes/handlers/api/editPassword.php',  {
                method: 'POST',
                body: formData
            });

            const message = await response.text();
            // console.log(message);
            passwordMessage.textContent = message;

            if(message === 'Password Updated') {
                currentPassword.value = '';
                newPassword.value = '';
                confirmNewPassword.value = '';
            }
           
            setTimeout(() => {
                passwordMessage.textContent = '';
            }, 2000);
        }

        domReady(() => {
            document.querySelector('.edit-email').addEventListener('click', editEmail);
            document.querySelector('.edit-password').addEventListener('click', editPassword);
        })
    </script>
<?php
    if(!isset($_GET['ajax'])) {
        include('includes/footer.php');
    }
?>
 