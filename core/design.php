<?php
include "../database/SQLSettingActions.php";
$settingActions = new SQLSettingActions();
$primaryColor = ($settingActions->getSettingValue('text-primary') != null && $settingActions->getSettingValue('text-primary') != "") ? $settingActions->getSettingValue('text-primary') : "";
$buttonColor = ($settingActions->getSettingValue('button-color') != null && $settingActions->getSettingValue('button-color') != "") ? $settingActions->getSettingValue('button-color') : "";
$customcss = ($settingActions->getSettingValue("custom-css") != null && $settingActions->getSettingValue("custom-css") != "") ? $settingActions->getSettingValue("custom-css") : "";
$navbackgroundcolor = ($settingActions->getSettingValue("navigation-color") != null && $settingActions->getSettingValue("navigation-color") != "") ? $settingActions->getSettingValue("navigation-color") : "";
?>

<!DOCTYPE html>
<html>
<?php require_once "../core/inc/head.php" ?>

<body>
<?php include_once "../core/inc/header.php" ?>
<div class="container">
    <h1>Design</h1>
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

    <form method="post" action="../misc/changenavbackgroundcolor.php">
        <div class="form-group">
            <label for="buttonColor">Navigation Background:</label>
            <div class="input-group">
                <input type="text" name="navigation-color" id="buttonColor" class="form-control"
                       value="<?php echo $navbackgroundcolor ?>">
                <div class="input-group-append">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='Update'>
                </div>
            </div>
        </div>
    </form>

    <h2>Custom CSS</h2>
    <form method="post" action="../misc/changecustomcss.php">
        <div class="form-group row">
            <label for="customcss" class="col-sm-2 col-form-label">CSS:</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="customcss" name="customcss"
                          placeholder=".class { ... }"
                          rows="10"><?php echo $customcss ?></textarea>
            </div>
        </div>
        <div class="form-group text-center">
            <input class="btn btn-success" type="submit" name="changecustomcss" value="Save">
        </div>
    </form>


</div>


<?php include_once "inc/footer.php" ?>
</body>
</html>