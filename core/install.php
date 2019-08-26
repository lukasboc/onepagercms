<?php
include "../database/SQLSettingActions.php";
$settingactions = new SQLSettingActions();

include "../database/SQLUserActions.php";
$useractions = new SQLUserActions();
if (sizeof($useractions->getAllUsernames()) != 0) {
    header("Location: ../misc/error.php?reason=installationdone");
}

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
        <div class="col-1 col-md-3"></div>
        <div class="col login-form-1">
            <div class="row mb-5">
                <div class="col text-center">
                    <img src="../img/logo/logo_black.png"
                         style="<?php echo $settingactions->getSettingValue('logo_css') ?>"
                         style="max-width:300px; max-height: 200px">
                </div>
            </div>
            <h3>First Installation</h3>
            <div class="row">
                <div class="col text-center">
                    <small class="form-text text-muted">Please enter your prefered username and your email adress. A
                        generated password will be sent to the entered email adress.
                    </small>
                </div>
            </div>
            <form method="post" action="../misc/firstuserdata.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Username"/>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email"/>
                </div>
                <div class="form-group text-center">
                    <input type="submit" class="btnSubmit" name="installation" value="Let's go!"/>
                </div>
            </form>
        </div>
        <div class="col-1 col-md-3"></div>
    </div>
</div>
</body>
</html>