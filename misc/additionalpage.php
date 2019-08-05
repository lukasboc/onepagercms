<?php
include "../database/SQLAdditionalPagesActions.php";
$headline = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$writeable = ($headline == "Delete") ? "readonly" : "";
$disabled = ($headline == "Delete") ? "disabled" : "";
$id = $_GET['id'] ?? null;

$title = "";
$content = "";
$showInFooter = "";

$backgroundimage = "";

$pagesActions = new SQLAdditionalPagesActions();

if ($id != null) {
    $title = $pagesActions->getPagesEntry('title', $id);
    $content = $pagesActions->getPagesEntry('content', $id);
    $showInFooter = $pagesActions->getPagesEntry('showInFooter', $id);
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
            <h1><?php echo "$headline" ?> Additional Page</h1>

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

            <form action="../misc/changeadditionalpage.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" readonly name="id" value="<?php echo $id ?>">
                </div>
                <div class="form-group">
                    <label for="custonText">Title:</label>
                    <input type="text" id="custonText" class="form-control" name="title" required
                           value="<?php echo $title ?>" <?php echo $writeable ?>>
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea rows="15" id="text" class="form-control" required
                              form="changeform" <?php echo $writeable ?>
                              name="content"
                              cols="73"><?php echo $content ?></textarea>
                    <small id="emailHelp" class="form-text text-muted">You can enter HTML Code here. (Bootstrap will be
                        applied)
                    </small>

                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="showInFooter"
                           name="showInFooter" <?php if ($showInFooter == "on") {
                        echo 'checked';
                    } ?> <?php echo $disabled ?>>
                    <label class="custom-control-label" for="showInFooter">Show in Footer</label>
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