<?php
include "../database/SQLSettingActions.php";
$settingActions = new SQLSettingActions();
$captchaKey = ($settingActions->getSettingValue("recaptcha_key") != null && $settingActions->getSettingValue("recaptcha_key") != "") ? $settingActions->getSettingValue("recaptcha_key") : "";
$logocss = ($settingActions->getSettingValue("logo_css") != null && $settingActions->getSettingValue("logo_css") != "") ? $settingActions->getSettingValue("logo_css") : "";
$logo = ($settingActions->getSettingValue("logo") != null && $settingActions->getSettingValue("logo") != "") ? $settingActions->getSettingValue("logo") : "";

?>

<!DOCTYPE html>
<html>
<?php require_once "inc/head.php" ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
    <h1>Settings</h1>
    <h2>reCAPTCHA</h2>
    <form method="post" action="../misc/changecaptcha.php">
        <div class="form-group">
            <label>API Key:</label>
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
    <h2>Logo</h2>
    <form enctype="multipart/form-data" action="../misc/logoupload.php" method="post" id="uploadform">
        <div class="form-group">
            <img class="img-fluid mb-2 text-right" style="height: 100px" src="<?php echo $logo ?>"><br>
            <div class="input-group">
                <div class="input-group-prepend">
                    <input type='submit' class="btn btn-success" name='upload'
                           id='image-upload' value='Upload'>
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
        <input type="hidden" id="action" class="form-control" name="logo" readonly
               value="<?php echo $logo ?>">
        <div class="form-group">
            <input type='submit' class="btn btn-primary" name='save'
                   id='save' value='Save'>
        </div>
    </form>
    <form method="post" action="../misc/changelogocss.php">
        <div class="form-group">
            <label>Extra CSS:</label>
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

</div>

</div>
<?php include_once "inc/footer.php" ?>

</body>
</html>