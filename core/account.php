<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php';
include '../database/SQLSettingActions.php';
include '../database/SQLUserActions.php';
$settingActions = new SQLSettingActions();
$userActions = new SQLUserActions();
?>
<body>

<?php include_once 'inc/header.php' ?>
<div class="container">
    <h1>Account</h1>
    <h2>Change Password</h2>
    <form method="post" action="../misc/changepassword.php">
                <?php echo opcms_csrf_field(); ?>
        <input type="hidden" name="username" value="<?php echo $userid ?>">
        <div class="mb-4">
            <label class="label" for="oldpassword">Current Password:</label>
            <input type="password" class="input w-full" id="oldpassword" name="oldpassword" required
                   placeholder="Current Password">
        </div>
        <div class="mb-4">
            <label class="label" for="newpassword1">New Password:</label>
            <input type="password" class="input w-full" id="newpassword1" name="newpassword1" required
                   placeholder="New Password">
            <small class="text-sm text-base-content/60">At least 8 characters, one letter and one number
            </small>
        </div>
        <div class="mb-4">
            <label class="label" for="newpassword2">Confirmation:</label>
            <input type="password" class="input w-full" id="newpassword2" name="newpassword2" required
                   placeholder="Confirm Password">
        </div>
        <div class="mb-4 text-center">
            <input class="btn btn-success" type="submit" name="chanepassword" value="Change">
        </div>
    </form>

    <h2>Change E-Mail</h2>
    <form method="post" action="../misc/changeemail.php">
                <?php echo opcms_csrf_field(); ?>
        <input type="hidden" name="username" value="<?php echo $userid ?>">
        <div class="mb-4">
            <label class="label" for="oldemail">Current E-Mail:</label>
            <input type="email" class="input w-full" id="oldemail" name="oldemail" readonly
                   value="<?php echo $userActions->getEmailByUsername($userid) ?>">
        </div>
        <div class="mb-4">
            <label class="label" for="newemail1">New E-Mail:</label>
            <input type="email" class="input w-full" id="newemail1" name="newemailOne" required
                   placeholder="New E-Mail">
        </div>
        <div class="mb-4">
            <label class="label" for="newemail2">Confirmation:</label>
            <input type="email" class="input w-full" id="newemail2" name="newemailTwo" required
                   placeholder="Confirm E-Mail">
        </div>
        <div class="mb-4">
            <label class="label" for="mailpassword">Enter Password:</label>
            <input type="password" class="input w-full" id="mailpassword" name="password" required
                   placeholder="Password">
            <small class="text-sm text-base-content/60">Just to make sure it's really you.
            </small>
        </div>

        <div class="mb-4 text-center">
            <input class="btn btn-success" type="submit" name="chanepassword" value="Change">
        </div>
    </form>


    <h2 class="mt-8">Create new Account</h2>
    <h3>Current Users:</h3>
    <ul class="divide-y divide-base-300 max-w-md mb-4">
        <?php
        $array = $userActions->getAllUsernames();
        for ($i = 0, $iMax = count($array); $i < $iMax; $i++) {
            echo '<li class="py-2">' . $array[$i]['username'] . '</li>';
        }
        ?>
    </ul>

    <h3>New User:</h3>
    <form method="post" action="../misc/adduser.php">
                <?php echo opcms_csrf_field(); ?>
        <div class="mb-4">
            <label class="label" for="username">Username:</label>
            <input type="text" class="input w-full" id="username" placeholder="Username" name="username">
        </div>
        <div class="mb-4">
            <label class="label" for="email">E-Mail:</label>
            <input type="email" class="input w-full" id="email" placeholder="E-Mail" name="email">
            <small class="text-sm text-base-content/60">A generated password and the entered username will be
                send to this adress.
            </small>
        </div>
        <div class="mb-4 text-center">
            <input type='submit' class="btn btn-primary" name='createUser'
                   id='change' value='Create'>
        </div>
    </form>
</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
