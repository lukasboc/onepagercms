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
$backgroundimage = "";

if (isset($_GET['id'])) {
    $sectionactions = new SQLSectionActions();
    $section = $sectionactions->getSectionByID($_GET['id']);

    $sid = $section->getSuperid();
    $title = $section->getTitle();
    $mutedTitle = $section->getMutedtitle();
    $text = $section->getText();
}

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
            <h1><?php echo "$headline" ?> Header-Section</h1>

            <form enctype="multipart/form-data" action="../misc/backgroundupload.php" method="post" id="uploadform">
                <div class="form-group">
                    <img class="img-fluid" src="<?php echo $backgroundimage ?>"><br>
                    <label for="image-upload">Bild hochladen:</label>
                    <input type="hidden" id="id" class="form-control" name="id" readonly
                           value="<?php echo $id ?>">
                    <input type="hidden" id="action" class="form-control" name="action" readonly
                           value="<?php echo $sid ?>">
                    <input name="background-upload" class="form-control-file" type="file" <?php echo $disabled ?>>
                </div>
                <div class="form-group">

                    <input type='submit' class="btn btn-secondary" name='upload'
                           id='image-upload' value='Upload' <?php echo $disabled ?>>
                </div>

            </form>

            <form action="../misc/changestandard.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" name="id" readonly value="<?php echo $sid ?>">
                </div>

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
                    <label for="mutedtitle"">Muted Title:</label>
                    <input type="text" id=mutedtitle" class="form-control" required <?php echo $writeable ?>
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="text">Text:</label>
                    <textarea rows="4" id="text" class="form-control" required
                              form="changeform" <?php echo $writeable ?>
                              name="text"
                              cols="73"><?php echo $text ?></textarea>
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