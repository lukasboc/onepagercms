<?php
include "../database/SQLHeaderActions.php";
$headline = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$writeable = ($headline == "Delete") ? "readonly" : "";
$disabled = ($headline == "Delete") ? "disabled" : "";

$title = "";
$mutedTitle = "";
$text = "";
$sid = "";
$id = "";
$headeractions = new SQLHeaderActions();

$title = $headeractions->getHeaderTitle();
$mutedTitle = $headeractions->getHeaderMutedtitle();

if (isset($_GET['background-image'])) {
    $backgroundimage = $_GET['background-image'];
} elseif ($headeractions->getBackground() != "") {
    $backgroundimage = $headeractions->getBackground();
} else {
    $backgroundimage = "";
}
$customrow = $headeractions->getCustomRow();

?>
<!DOCTYPE html>
<html>

<?php include_once "../core/inc/head.php" ?>
<body>

<?php include_once "../core/inc/header.php" ?>

<div class="container">
            <h1><?php echo opcms_esc($headline) ?> Header-Section</h1>
            <form enctype="multipart/form-data" action="../misc/backgroundupload.php" method="post" id="uploadform">
                <?php echo opcms_csrf_field(); ?>
                <div class="mb-4">
                    <label class="label" for="image-upload">Background:</label>
                    <input type="hidden" id="id" name="id" readonly
                           value="<?php echo opcms_esc($id) ?>">

                    <input type="file" class="file-input w-full" id="image-upload"
                           name="background-image" <?php echo $disabled ?>>
                </div>
                <div class="mb-4">

                    <input type='submit' class="btn btn-primary" name='upload'
                           id='image-upload' value='Upload' <?php echo $disabled ?>>
                </div>

                <?php
                if ($backgroundimage != "" && file_exists($backgroundimage)) {
                    echo " <div class=\"mb-4\">";
                    echo "<label class=\"label\">Preview:</label>";
                    echo "<img class=\"max-w-full rounded\" src=\"" . opcms_esc($backgroundimage) . "\">";
                    echo "</div>";
                } ?>
            </form>

            <form action="../misc/changeheader.php" method="post" id="changeform">
                <?php echo opcms_csrf_field(); ?>
                <input type="hidden" id="id" name="id" readonly value="<?php echo $sid ?>">

                <input type="hidden" id="specialid" name="author" readonly>
                <input type="hidden" value="<?php echo opcms_esc($backgroundimage) ?>"
                       name="background-image">

                <div class="mb-4">
                    <label class="label" for="mutedtitle">Muted Title:</label>
                    <input type="text" id="mutedtitle" class="input w-full" required <?php echo $writeable ?>
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                    >
                </div>

                <div class="mb-4">
                    <label class="label" for="title">Title:</label>
                    <input type="text" id="title" class="input w-full" required name="title"
                           value="<?php echo $title ?>" <?php echo $writeable ?>
                    >
                </div>

                <div class="mb-4">
                    <label class="label" for="text">Custom Row:</label>
                    <textarea rows="4" id="text" class="textarea w-full"
                              form="changeform" <?php echo $writeable ?>
                              name="customrow"
                              cols="73"><?php echo $customrow ?></textarea>
                </div>

                <input type="hidden" id="image" required name="image" readonly
                       value="">

                <div class="mb-4">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='<?php echo opcms_esc($headline) ?>'>
                </div>
            </form>

</div>
<!-- Trumbowyg core (CSS + JS) and jQuery are loaded globally in core/inc/head.php -->
<script>
    $('textarea').trumbowyg({
        semantic: true
    });
</script>

<?php include_once "../core/inc/footer.php" ?>
</body>
</html>
