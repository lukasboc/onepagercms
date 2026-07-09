<?php
include '../database/SQLSettingActions.php';
include '../database/SQLUserActions.php';
$settingActions = new SQLSettingActions();
$userActions = new SQLUserActions();
$captchaKey = ($settingActions->getSettingValue('recaptcha_key') !== null && $settingActions->getSettingValue('recaptcha_key') !== '') ? $settingActions->getSettingValue('recaptcha_key') : '';
$logocss = ($settingActions->getSettingValue('logo_css') !== null && $settingActions->getSettingValue('logo_css') !== '') ? $settingActions->getSettingValue('logo_css') : '';
$logo = ($settingActions->getSettingValue('logo') !== null && $settingActions->getSettingValue('logo') !== '') ? $settingActions->getSettingValue('logo') : '';
$uploadedLogo = $_GET['logo'] ?? '';
$websiteTitle = ($settingActions->getSettingValue('website-title') !== null && $settingActions->getSettingValue('website-title') !== '') ? $settingActions->getSettingValue('website-title') : '';
$googleAnalytics = ($settingActions->getSettingValue('google-analytics') !== null && $settingActions->getSettingValue('google-analytics') !== '') ? $settingActions->getSettingValue('google-analytics') : '';
$metadescription = ($settingActions->getSettingValue('page-description') !== null && $settingActions->getSettingValue('page-description') !== '') ? $settingActions->getSettingValue('page-description') : '';
?>

<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php' ?>
<body>

<?php include_once 'inc/header.php' ?>
<div class="container">
    <h1>Settings</h1>
    <h2>Website-Title</h2>
    <form method="post" action="../misc/changewebsitetitle.php">
        <div class="mb-4">
            <label class="label" for="title">Title:</label>
            <div class="join w-full">
                <input type="text" name="title" class="input join-item w-full" id="title"
                       value="<?php echo $websiteTitle ?>">
                <input type='submit' class="btn btn-primary join-item" name='titleform'
                       id='change' value='Save'>
            </div>
        </div>
    </form>

    <h2>Meta Description</h2>
    <form method="post" action="../misc/changemetadescription.php">
        <div class="mb-4">
            <label class="label" for="metadescription">Description:</label>
            <div class="mb-3">
                <textarea name="metadescription" id="metadescription" rows="4"
                          class="textarea w-full"><?php echo $metadescription ?></textarea>
            </div>
            <div>
                <input type='submit' class="btn btn-primary" name='captchaKey'
                       id='change' value='Save'>
            </div>

        </div>
    </form>

    <h2>Logo</h2>
    <form enctype="multipart/form-data" action="../misc/logoupload.php" method="post" id="uploadform">
        <div class="mb-4">
            <label class="label">Preview:</label><br>
            <img class="max-w-full mb-2 rounded"
                 style="height: 100px; max-width: 500px; background-image: url('../img/core/logobg.png')"
                 src="<?php echo ($uploadedLogo !== '') ? $uploadedLogo : $logo ?>"><br>
            <label class="label">Upload-Area:</label>
            <div class="join w-full">
                <input type='submit' class="btn btn-primary join-item" id='image-upload' value='Upload'>
                <input type="file" class="file-input join-item w-full" id="inputGroupFile01" name="logo">
            </div>
        </div>
    </form>
    <form method="post" action="../misc/changelogo.php">
        <label class="label">Happy with the preview? Safe:</label>
        <input type="hidden" id="action" name="logo" readonly
               value="<?php echo $uploadedLogo ?>">
        <div class="mb-4">
            <input type='submit' class="btn btn-primary" name='save'
                   id='save' value='Save'>
        </div>
    </form>
    <h2>Logo CSS</h2>
    <form method="post" action="../misc/changelogocss.php">
        <div class="mb-4">
            <label class="label" for="logocss">CSS:</label>
            <div class="mb-3">
                <textarea id="logocss" name="logocss" class="textarea w-full font-mono" rows="5"
                ><?php echo $logocss ?></textarea>
            </div>
            <div>
                    <input type='submit' class="btn btn-primary" name='captchaKey'
                           id='change' value='Save'>
                </div>
            </div>
    </form>

    <h2>Favicon</h2>
    <form enctype="multipart/form-data" action="../misc/faviconupload180x180.php" method="post" id="uploadform">
        <div class="mb-4">
            <label class="label">Apple-touch-icon 180x180 (PNG):</label>
            <div class="join w-full">
                <input type='submit' class="btn btn-primary join-item" id='image-upload' value='Save'>
                <input type="file" class="file-input join-item w-full" name="180x180png">
            </div>
        </div>
    </form>
    <form enctype="multipart/form-data" action="../misc/faviconupload32x32.php" method="post" id="uploadform">
        <div class="mb-4">
            <label class="label">Image/png 32x32 (PNG):</label>
            <div class="join w-full">
                <input type='submit' class="btn btn-primary join-item" id='image-upload' value='Save'>
                <input type="file" class="file-input join-item w-full" name="32x32png">
            </div>
        </div>
    </form>
    <form enctype="multipart/form-data" action="../misc/faviconupload16x16.php" method="post" id="uploadform">
        <div class="mb-4">
            <label class="label">Image/png 16x16 (PNG):</label>
            <div class="join w-full">
                <input type='submit' class="btn btn-primary join-item" id='image-upload' value='Save'>
                <input type="file" class="file-input join-item w-full" name="16x16png">
            </div>
        </div>
    </form>
    <form enctype="multipart/form-data" action="../misc/faviconupload48x48.php" method="post" id="uploadform">
        <div class="mb-4">
            <label class="label">Shortcut Icon 48x48 (ICO):</label>
            <div class="join w-full">
                <input type='submit' class="btn btn-primary join-item" id='image-upload' value='Save'>
                <input type="file" class="file-input join-item w-full" name="48x48ico">
            </div>
        </div>
    </form>

    <h2>reCAPTCHA</h2>
    <form method="post" action="../misc/changecaptcha.php">
        <div class="mb-4">
            <label class="label" for="recaptcha_key">API Key:</label>
            <div class="join w-full">
                <input type="text" id="recaptcha_key" name="recaptcha_key" class="input join-item w-full"
                       value="<?php echo $captchaKey ?>">
                <input type='submit' class="btn btn-primary join-item" name='captchaKey'
                       id='change' value='Save'>
            </div>
        </div>
    </form>

    <h2>Google Analytics</h2>
    <form method="post" action="../misc/changegoogleanalytics.php">
        <div class="mb-4">
            <label class="label" for="gAnalyics">Tracking-Code:</label>
            <div class="mb-3">
                <textarea name="googleAnalytics" id="gAnalyics" rows="4" class="textarea w-full font-mono" placeholder='<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=X-XXXXXXX-X"></script>
    ...
    gtag("config", "XX-XXXXXXXXX-X");
</script>'><?php echo $googleAnalytics ?></textarea>
            </div>
            <div>
                <input type='submit' class="btn btn-primary" name='captchaKey'
                       id='change' value='Save'>
            </div>

        </div>
    </form>

</div>

<?php include_once 'inc/footer.php' ?>

</body>
</html>
