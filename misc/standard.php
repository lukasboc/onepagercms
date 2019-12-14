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

<?php include_once "../core/inc/header.php" ?>

<div class="container" id="backendFormContainer">
            <h1><?php echo "$headline" ?> Standard-Section</h1>
            <form enctype="multipart/form-data" action="../misc/backgroundupload.php" method="post" id="uploadform">
                <div class="form-group">
                    <label for="image-upload">Background:</label>
                    <input type="hidden" id="id" class="form-control" name="id" readonly
                           value="<?php echo $id ?>">

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image-upload"
                                   aria-describedby="inputGroupFileAddon01"
                                   name="background-image" <?php echo $disabled ?>>
                            <label class="custom-file-label" for="image-upload">Choose file</label>
                        </div>
                </div>
                </div>

                <?php
                if ($backgroundimage != "" && file_exists($backgroundimage)) {
                    echo " <div class=\"form-group\">";
                    echo "<label>Preview:</label>";
                    echo "<img class=\"img-fluid\" src=\"" . $backgroundimage . "\">";
                    echo "</div>";
                } ?>
                <div class="form-group">

                    <input type='submit' class="btn btn-primary" name='upload'
                           id='image-upload' value='Upload' <?php echo $disabled ?>>
                </div>
            </form>

            <form action="../misc/changestandard.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" name="id" readonly value="<?php echo $sid ?>">
                </div>
                <input type="hidden" class="form-control" value="<?php echo $backgroundimage ?>"
                       name="background-image">

                <div class="form-group">
                    <input type="hidden" id="specialid" class="form-control" name="author" readonly
                           >
                </div>

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" class="form-control" required name="title"
                           value="<?php echo $title ?>" <?php echo $writeable ?>
                           >
                </div>

                <div class="form-group">
                    <label for="mutedtitle">Muted Title:</label>
                    <input type="text" id=mutedtitle" class="form-control" required <?php echo $writeable ?>
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                           >
                </div>

                <div class="form-group">
                    <label for="text">Text:</label>
                    <textarea rows="4" id="text" id="wysiwyg" class="form-control" required
                              form="changeform" <?php echo $writeable ?>
                              name="text"
                              cols="73"><?php echo $text ?></textarea>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="delete-background" value="yes"
                               name="delete-background">
                        <label class="form-check-label" for="delete-background">
                            Delete Background-Image
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='<?php echo $headline ?>'>
                </div>
            </form>
        </div>
<!-- Import jQuery -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../plugins/vendor/jquery/jquery.slim.js"><\/script>')</script>

<!-- Import Trumbowyg -->
<script src="../plugins/Trumbowyg/dist/trumbowyg.min.js"></script>
<!-- Import Trumbowyg plugins... -->
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

</body>
</html>