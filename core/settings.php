<?php
include "../database/SQLSettingActions.php";
include "../database/SQLUserActions.php";
$settingActions = new SQLSettingActions();
$userActions = new SQLUserActions();
$captchaKey = ($settingActions->getSettingValue("recaptcha_key") != null && $settingActions->getSettingValue("recaptcha_key") != "") ? $settingActions->getSettingValue("recaptcha_key") : "";
$logocss = ($settingActions->getSettingValue("logo_css") != null && $settingActions->getSettingValue("logo_css") != "") ? $settingActions->getSettingValue("logo_css") : "";
$logo = ($settingActions->getSettingValue("logo") != null && $settingActions->getSettingValue("logo") != "") ? $settingActions->getSettingValue("logo") : "";
$uploadedLogo = (isset($_GET['logo'])) ? $_GET['logo'] : "";
$primaryColor = ($settingActions->getSettingValue('text-primary') != null && $settingActions->getSettingValue('text-primary') != "") ? $settingActions->getSettingValue('text-primary') : "";
$buttonColor = ($settingActions->getSettingValue('button-color') != null && $settingActions->getSettingValue('button-color') != "") ? $settingActions->getSettingValue('button-color') : "";
$websiteTitle = ($settingActions->getSettingValue('website-title') != null && $settingActions->getSettingValue('website-title') != "") ? $settingActions->getSettingValue('website-title') : "";
?>

<!DOCTYPE html>
<html>
<?php require_once "inc/head.php" ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
    <h1>Settings</h1>
    <h2>Website-Title</h2>
    <form method="post" action="../misc/changewebsitetitle.php">
        <div class="form-group">
            <label for="title">Title:</label>
            <div class="input-group">
                <input type="text" name="title" class="form-control" id="title"
                       value="<?php echo $websiteTitle ?>">
                <div class="input-group-append">
                    <input type='submit' class="btn btn-primary" name='titleform'
                           id='change' value='Update'>
                </div>
            </div>
        </div>
    </form>
    <h2>Colors</h2>
    <form method="post" action="../misc/changeprimarycolor.php">
        <div class="form-group">
            <label for="primaryColor">Primary:</label>
            <div class="input-group">
                <input type="text" name="primaryColor" id="primaryColor" class="form-control"
                       value="<?php echo $primaryColor ?>">
                <div class="input-group-append">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='Update'>
                </div>
            </div>
        </div>
    </form>
    <form method="post" action="../misc/changebuttoncolor.php">
        <div class="form-group">
            <label for="buttonColor">Buttons:</label>
            <div class="input-group">
                <input type="text" name="buttonColor" id="buttonColor" class="form-control"
                       value="<?php echo $buttonColor ?>">
                <div class="input-group-append">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='Update'>
                </div>
            </div>
        </div>
    </form>

    <h2>Logo</h2>
    <form enctype="multipart/form-data" action="../misc/logoupload.php" method="post" id="uploadform">
        <div class="form-group">
            <label>Preview:</label><br>
            <img class="img-fluid mb-2 text-right" style="height: 100px; max-width: 500px;"
                 src="<?php echo ($uploadedLogo != "") ? $uploadedLogo : $logo ?>"><br>
            <label>Upload-Area:</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <input type='submit' class="btn btn-success" id='image-upload' value='Upload'>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="inputGroupFile01"
                           aria-describedby="inputGroupFileAddon01" name="logo">
                    <label class="custom-file-label" for="inputGroupFile01">Choose Logo</label>
                </div>
            </div>
        </div>
    </form>
    <form method="post" action="../misc/changelogo.php">
        <label>Happy with the preview? Safe:</label>
        <input type="hidden" id="action" class="form-control" name="logo" readonly
               value="<?php echo $uploadedLogo ?>">
        <div class="form-group">
            <input type='submit' class="btn btn-primary" name='save'
                   id='save' value='Save'>
        </div>
    </form>
    <form method="post" action="../misc/changelogocss.php">
        <div class="form-group">
            <label for="logocss">Extra CSS:</label>
            <div class="input-group">
                <textarea id="logocss" name="logocss" class="form-control" rows="5"
                ><?php echo $logocss ?></textarea>
                <div class="input-group-append">
                    <input type='submit' class="btn btn-primary" name='captchaKey'
                           id='change' value='Update'>
                </div>
            </div>
        </div>
    </form>
    <h2>reCAPTCHA</h2>
    <form method="post" action="../misc/changecaptcha.php">
        <div class="form-group">
            <label for="recaptcha_key">API Key:</label>
            <div class="input-group">
                <input type="text" id="recaptcha_key" name="recaptcha_key" class="form-control"
                       value="<?php echo $captchaKey ?>">
                <div class="input-group-append">
                    <input type='submit' class="btn btn-primary" name='captchaKey'
                           id='change' value='Update'>
                </div>
            </div>
        </div>
    </form>

    <h2>Users</h2>
    <h3>Current Users:</h3>
    <ul class="list-group list-group-flush w-50 p-3">
        <?php
        $array = $userActions->getAllUsernames();
        for ($i = 0; $i < sizeof($array); $i++) {
            echo "<li class=\"list-group-item\">" . $array[$i]['username'] . "</li>";
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

</div>
<?php include_once "inc/footer.php" ?>

</body>
</html>