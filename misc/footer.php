<?php
include "../database/SQLFooterActions.php";
$headline = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$writeable = ($headline == "Delete") ? "readonly" : "";
$disabled = ($headline == "Delete") ? "disabled" : "";

$customtext = "";
$facebook = "";
$twitter = "";
$linkedin = "";
$customPage = "";
$copyright = "";

$backgroundimage = "";

$footeractions = new SQLFooterActions();

$customtext = $footeractions->getFooterEntry('custom');
$facebook = $footeractions->getFooterEntry('facebook_page');
$twitter = $footeractions->getFooterEntry('twitter_page');
$linkedin = $footeractions->getFooterEntry('linkedin_page');
$customPage = $footeractions->getFooterEntry('custom_page');
$copyright = $footeractions->getFooterEntry('copyright');
$customIcon = $footeractions->getFooterEntry('custom_icon');

?>
<!DOCTYPE html>
<html>

<?php include_once "../core/inc/head.php" ?>

<?php include_once "../core/inc/header.php" ?>

<div class="container">
    <div class="row">
        <div class="col">

        </div>
        <div class="col-6">
            <h1><?php echo "$headline" ?>Footer-Section</h1>

            <form enctype="multipart/form-data" action="../misc/backgroundupload.php" method="post" id="uploadform">
                <div class="form-group">
                    <img class="img-fluid" src="<?php echo $backgroundimage ?>"><br>
                    <label for="image-upload">Background-Image:</label>
                    <input name="background-upload" class="form-control-file" type="file" <?php echo $disabled ?>>
                </div>
                <div class="form-group">

                    <input type='submit' class="btn btn-secondary" name='upload'
                           id='image-upload' value='Upload' <?php echo $disabled ?>>
                </div>

            </form>

            <form action="../misc/changefooter.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="specialid" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="custonText">Custom Text:</label>
                    <input type="text" id="custonText" class="form-control" name="custom"
                           value="<?php echo $customtext ?>" <?php echo $writeable ?>>
                </div>

                <div class="form-group">
                    <label for="facebook">Facebook:</label>
                    <input type="text" id="facebook" class="form-control" <?php echo $writeable ?>
                           name="facebook" value="<?php echo $facebook ?>">
                </div>

                <div class="form-group">
                    <label for="twitter">Twitter:</label>
                    <input type="text" id="twitter" class="form-control" <?php echo $writeable ?>
                           name="twitter" value="<?php echo $twitter ?>">
                </div>

                <div class="form-group">
                    <label for="linkedin"">LinkedIn:</label>
                    <input type="text" id="linkedin" class="form-control" <?php echo $writeable ?>
                           name="linkedin" value="<?php echo $linkedin ?>">
                </div>

                <label for="customLink">Custom Link:</label>
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <input type="text" class="form-control" name="customIcon" placeholder="fas fa-shopping-cart"
                               value="<?php echo $customIcon ?>">
                    </div>
                    <div class="form-group col-md">
                        <input type="text" class="form-control" name="customPage"
                               placeholder="http://www.domain-name.tld" value="<?php echo $customPage ?>">
                    </div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="copyright"
                           name="copyright" <?php if ($copyright == "on") {
                        echo 'checked';
                    } ?> <?php echo $disabled ?>>
                    <label class="custom-control-label" for="copyright">Show Copyright</label>
                </div>

                <div class="form-group">
                    <input type="hidden" id="image" class="form-control" required name="image" readonly
                           value="">
                </div>

                <div class="form-group">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='<?php echo $headline ?>'>
                </div>
            </form>

        </div>
        <div class="col"></div>
    </div>
</div>
</body>
</html>