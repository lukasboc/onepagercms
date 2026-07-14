<?php
include "../database/SQLSectionActions.php";
$headline = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$writeable = ($headline == "Delete") ? "readonly" : "";
$disabled = ($headline == "Delete") ? "disabled" : "";

$title = "";
$mutedTitle = "";
$text = "";
$sid = "";
$id = "";
$backgroundimage = (isset($_GET['background-image'])) ? $_GET['background-image'] : "";

if(isset($_GET['id'])){
    $sectionactions = new SQLSectionActions();
    $section = $sectionactions->getSectionByID($_GET['id']);

    $sid = $section->getSuperid();
    $title = $section->getTitle();
    $mutedTitle = $section->getMutedtitle();
    $text = $section->getText();
    if ($section->getBackground() != "") {
        $backgroundimage = $section->getBackground();
    }
}

?>
<!DOCTYPE html>
<html>

<?php include_once "../core/inc/head.php" ?>
<body>

<?php include_once "../core/inc/header.php" ?>

<div class="container">
            <h1><?php echo opcms_esc($headline) ?> Standard-Section</h1>
            <form enctype="multipart/form-data" action="../misc/backgroundupload.php" method="post" id="uploadform">
                <?php echo opcms_csrf_field(); ?>
                <div class="mb-4">
                    <label class="label" for="image-upload">Background:</label>
                    <input type="hidden" id="id" name="id" readonly
                           value="<?php echo opcms_esc($id) ?>">

                    <input type="file" class="file-input w-full" id="image-upload"
                           name="background-image" <?php echo $disabled ?>>
                </div>

                <?php
                if ($backgroundimage != "" && file_exists($backgroundimage)) {
                    echo " <div class=\"mb-4\">";
                    echo "<label class=\"label\">Preview:</label>";
                    echo "<img class=\"max-w-full rounded\" src=\"" . opcms_esc($backgroundimage) . "\">";
                    echo "</div>";
                } ?>
                <div class="mb-4">

                    <input type='submit' class="btn btn-primary" name='upload'
                           id='image-upload' value='Upload' <?php echo $disabled ?>>
                </div>
            </form>

            <form action="../misc/changestandard.php" method="post" id="changeform">
                <?php echo opcms_csrf_field(); ?>
                <input type="hidden" id="id" name="id" readonly value="<?php echo $sid ?>">
                <input type="hidden" value="<?php echo opcms_esc($backgroundimage) ?>"
                       name="background-image">

                <input type="hidden" id="specialid" name="author" readonly>

                <div class="mb-4">
                    <label class="label" for="title">Title:</label>
                    <input type="text" id="title" class="input w-full" required name="title"
                           value="<?php echo $title ?>" <?php echo $writeable ?>
                           >
                </div>

                <div class="mb-4">
                    <label class="label" for="mutedtitle">Muted Title:</label>
                    <input type="text" id="mutedtitle" class="input w-full" required <?php echo $writeable ?>
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                           >
                </div>

                <div class="mb-4">
                    <label class="label" for="text">Text:</label>
                    <textarea rows="4" id="text" class="textarea w-full" required
                              form="changeform" <?php echo $writeable ?>
                              name="text"
                              cols="73"><?php echo $text ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="label cursor-pointer justify-start gap-2" for="delete-background">
                        <input class="checkbox" type="checkbox" id="delete-background" value="yes"
                               name="delete-background">
                        Delete Background-Image
                    </label>
                </div>

                <div class="mb-4">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='<?php echo opcms_esc($headline) ?>'>
                </div>
            </form>
        </div>
<!-- Trumbowyg core (CSS + JS) and jQuery are loaded globally in core/inc/head.php -->
<script src="../plugins/Trumbowyg/dist/plugins/table/trumbowyg.table.min.js"></script>
<script src="../plugins/Trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js"></script>

<script>
    $('textarea').trumbowyg({
        btns: [
            ['viewHTML'],
            ['undo', 'redo'], // Only supported in Blink browsers
            ['formatting'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['insertImage'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['table'],
            ['foreColor'],
            ['fullscreen']
        ],
        autogrow: true,
        changeActiveDropdownIcon: true,
        plugins: {
            table: {
                // Some table plugin options, see details below
            }
        }
    });
</script>

<?php include_once "../core/inc/footer.php" ?>
</body>
</html>
