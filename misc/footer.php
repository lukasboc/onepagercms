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
<body>

<?php include_once "../core/inc/header.php" ?>

<div class="container">
            <h1><?php echo opcms_esc($headline) ?> Footer-Section</h1>

            <form enctype="multipart/form-data" action="../misc/backgroundupload.php" method="post" id="uploadform">
                <?php echo opcms_csrf_field(); ?>
                <div class="mb-4">
                    <img class="max-w-full rounded" src="<?php echo opcms_esc($backgroundimage) ?>"><br>
                    <label class="label" for="image-upload">Background-Image:</label>
                    <input name="background-upload" class="file-input w-full" type="file" <?php echo $disabled ?>>
                </div>
                <div class="mb-4">

                    <input type='submit' class="btn btn-neutral" name='upload'
                           id='image-upload' value='Upload' <?php echo $disabled ?>>
                </div>

            </form>

            <form action="../misc/changefooter.php" method="post" id="changeform">
                <?php echo opcms_csrf_field(); ?>
                <input type="hidden" id="specialid" readonly>
                <h3>Text</h3>
                <div class="mb-4">
                    <label class="label" for="custonText">Custom Text:</label>
                    <input type="text" id="custonText" class="input w-full" name="custom"
                           value="<?php echo $customtext ?>" <?php echo $writeable ?>>
                </div>
                <h3>Icons</h3>
                <div class="mb-4">
                    <label class="label" for="facebook">Facebook:</label>
                    <input type="text" id="facebook" class="input w-full" <?php echo $writeable ?>
                           name="facebook" value="<?php echo $facebook ?>">
                </div>

                <div class="mb-4">
                    <label class="label" for="twitter">Twitter:</label>
                    <input type="text" id="twitter" class="input w-full" <?php echo $writeable ?>
                           name="twitter" value="<?php echo $twitter ?>">
                </div>

                <div class="mb-4">
                    <label class="label" for="linkedin">LinkedIn:</label>
                    <input type="text" id="linkedin" class="input w-full" <?php echo $writeable ?>
                           name="linkedin" value="<?php echo $linkedin ?>">
                </div>

                <label class="label" for="customLink">Custom Link:</label>
                <div class="grid grid-cols-12 gap-2 mb-4">
                    <div class="col-span-12 md:col-span-5">
                        <input type="text" class="input w-full" name="customIcon" placeholder="fas fa-shopping-cart"
                               value="<?php echo $customIcon ?>">
                    </div>
                    <div class="col-span-12 md:col-span-7">
                        <input type="text" class="input w-full" name="customPage"
                               placeholder="http://www.domain-name.tld" value="<?php echo $customPage ?>">
                    </div>
                </div>

                <h3>Copyright</h3>

                <div class="mb-4">
                    <label class="label cursor-pointer justify-start gap-2" for="copyright">
                        <input type="checkbox" class="toggle" id="copyright"
                               name="copyright" <?php if ($copyright == "on") {
                            echo 'checked';
                        } ?> <?php echo $disabled ?>>
                        Show Copyright
                    </label>
                </div>

                <input type="hidden" id="image" required name="image" readonly
                       value="">

                <div class="mb-4">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='<?php echo opcms_esc($headline) ?>'>
                </div>
            </form>

</div>
<?php include_once "../core/inc/footer.php" ?>
</body>
</html>
