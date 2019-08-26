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

<?php include_once "../core/inc/header.php" ?>

<div class="container">
    <div class="row">
        <div class="col">

        </div>
        <div class="col-6">
            <h1><?php echo "$headline" ?> Header-Section</h1>
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
                <div class="form-group">

                    <input type='submit' class="btn btn-primary" name='upload'
                           id='image-upload' value='Upload' <?php echo $disabled ?>>
                </div>

                <?php
                if ($backgroundimage != "" && file_exists($backgroundimage)) {
                    echo " <div class=\"form-group\">";
                    echo "<label>Preview:</label>";
                    echo "<img class=\"img-fluid\" src=\"" . $backgroundimage . "\">";
                    echo "</div>";
                } ?>
            </form>

            <form action="../misc/changeheader.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" name="id" readonly value="<?php echo $sid ?>">
                </div>

                <div class="form-group">
                    <input type="hidden" id="specialid" class="form-control" name="author" readonly
                    >
                    <input type="hidden" class="form-control" value="<?php echo $backgroundimage ?>"
                           name="background-image">
                </div>

                <div class="form-group">
                    <label for="mutedtitle"">Muted Title:</label>
                    <input type="text" id=mutedtitle" class="form-control" required <?php echo $writeable ?>
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" class="form-control" required name="title"
                           value="<?php echo $title ?>" <?php echo $writeable ?>
                    >
                </div>

                <div class="form-group">
                    <label for="text">Custom Row:</label>
                    <textarea rows="4" id="text" class="form-control"
                              form="changeform" <?php echo $writeable ?>
                              name="customrow"
                              cols="73"><?php echo $customrow ?></textarea>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../plugins/vendor/jquery/jquery.min.js"><\/script>')</script>
<script src="../plugins/Trumbowyg/dist/trumbowyg.min.js"></script>

<script>
    $('textarea').trumbowyg({
        semantic: true
    });
</script>

</body>
</html>