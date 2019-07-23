<?php
include "../database/SQLSettingActions.php";
$settingActions = new SQLSettingActions();
$captchaKey = ($settingActions->getSettingValue("recaptcha_key") != null && $settingActions->getSettingValue("recaptcha_key") != "") ? $settingActions->getSettingValue("recaptcha_key") : "";
$logocss = ($settingActions->getSettingValue("logo_css") != null && $settingActions->getSettingValue("logo_css") != "") ? $settingActions->getSettingValue("logo_css") : "";
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
<?php include_once "inc/footer.php" ?>

</body>
</html>