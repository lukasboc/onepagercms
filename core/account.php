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
    <div class></div>
    <form method="post" action="../misc/changepassword.php">
        <input type="hidden" name="username" value="<?php echo $userid ?>">
        <div class="form-group row">
            <label for="oldpassword" class="col-sm-2 col-form-label">Current Password:</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="oldpassword" name="oldpassword" required
                       placeholder="Current Password">
            </div>
        </div>
        <div class="form-group row">
            <label for="newpassword1" class="col-sm-2 col-form-label">New Password:</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="newpassword1" name="newpassword1" required
                       placeholder="New Password">
                <small id="passwordHelp" class="form-text text-muted">At least 8 characters, one letter and one number
                </small>

            </div>
        </div>
        <div class="form-group row">
            <label for="newpassword2" class="col-sm-2 col-form-label">Confirmation:</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="newpassword2" name="newpassword2" required
                       placeholder="Confirm Password">
            </div>
        </div>
        <div class="form-group text-center">
            <input class="btn btn-success" type="submit" name="chanepassword" value="Change">
        </div>
    </form>

    <h2>Change E-Mail</h2>
    <form method="post" action="../misc/changeemail.php">
        <input type="hidden" name="username" value="<?php echo $userid ?>">
        <div class="form-group row">
            <label for="oldemail" class="col-sm-2 col-form-label">Current E-Mail:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="oldemail" name="oldemail" readonly
                       value="<?php echo $userActions->getEmailByUsername($userid) ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="newemail1" class="col-sm-2 col-form-label">New E-Mail:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="newemail1" name="newemailOne" required
                       placeholder="New E-Mail">
            </div>
        </div>
        <div class="form-group row">
            <label for="newemail2" class="col-sm-2 col-form-label">Confirmation:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="newemail2" name="newemailTwo" required
                       placeholder="Confirm E-Mail">
            </div>
        </div>
        <div class="form-group row">
            <label for="mailpassword" class="col-sm-2 col-form-label">Enter Password:</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="mailpassword" name="password" required
                       placeholder="Password">
                <small id="passwordHelp" class="form-text text-muted">Just to make sure it's really you.
                </small>

            </div>
        </div>

        <div class="form-group text-center">
            <input class="btn btn-success" type="submit" name="chanepassword" value="Change">
        </div>
    </form>


    <h2 class="mt-5">Create new Account</h2>
    <h3>Current Users:</h3>
    <ul class="list-group list-group-flush w-50 p-3">
        <?php
        $array = $userActions->getAllUsernames();
        for ($i = 0, $iMax = count($array); $i < $iMax; $i++) {
            echo '<li class="list-group-item">' . $array[$i]['username'] . '</li>';
        }
        ?>
    </ul>

    <h3>New User:</h3>
    <form method="post" action="../misc/adduser.php">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Username:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="username" placeholder="Username" name="username">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">E-Mail:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" placeholder="E-Mail" name="email">
                <small id="emailHelp" class="form-text text-muted">A generated password and the entered username will be
                    send to this adress.
                </small>
            </div>
        </div>
        <div class="form-group text-center">
            <input type='submit' class="btn btn-primary" name='createUser'
                   id='change' value='Create'>
        </div>
    </form>
</div>
</body>
</html>