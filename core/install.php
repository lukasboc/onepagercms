<?php
include '../database/SQLSettingActions.php';
$settingactions = new SQLSettingActions();

include '../database/SQLUserActions.php';
$useractions = new SQLUserActions();
if (count($useractions->getAllUsernames()) !== 0) {
    header('Location: ../pages/index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
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