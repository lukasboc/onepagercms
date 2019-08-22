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
</head>
<body>

<div class="container login-container">
    <div class="row">
        <div class="col-3"></div>
        <div class="col login-form-1">
            <div class="row mb-5">
                <div class="col text-center">
                    <img src="../img/logo/logo_black.png"
                         style="<?php echo $settingactions->getSettingValue('logo_css') ?>"
                         style="max-width:300px; max-height: 200px">
                </div>
            </div>
            <h3>Sign in</h3>
            <form method="post" action="../misc/login.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required />
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btnSubmit" name="login" value="Sign in"/>

                </div>
                <div class="row">
                    <div class="col text-center">
                        <a href="../misc/resetpassword.php">Forgot your password?</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-3"></div>
    </div>
</div>
</body>
</html>