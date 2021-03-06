<?php
include "../database/SQLSettingActions.php";
$settingactions = new SQLSettingActions();
?>
<!DOCTYPE html>
<html>
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="../css/login.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <?php if (file_exists('../img/favicon/opcms48x48.ico')) echo "<link rel=\"shortcut icon\" href=\"../img/favicon/opcms48x48.ico\">"; ?>
</head>
<body>

<div class="container login-container">
    <div class="row">
        <div class="col-1 col-md-3"></div>
        <div class="col login-form-1">
            <div class="row mb-5">
                <div class="col text-center">
                    <img src="../img/logo/logo_black.png"
                         style="<?php echo $settingactions->getSettingValue('logo_css') ?>"
                         style="max-width:300px; max-height: 200px">
                </div>
            </div>
            <h3>Forgot password</h3>
            <div class="row">
                <div class="col text-center">
                    <small class="form-text text-muted">You can reset your password either by entering your username or
                        your email. A new password will be sent to you via email.
                    </small>
                </div>
            </div>
            <form method="post" action="../misc/changeforgottenpassword.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username"/>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email"/>
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btnSubmit" name="resetpassword" value="Reset"/>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <a href="../core/opcms-login.php">Back to login</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-1 col-md-3"></div>
    </div>
</div>
</body>
</html>