<?php
    include('includes/handlers/includeFiles.php');
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
 