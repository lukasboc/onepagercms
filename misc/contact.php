<?php
if (isset($_GET['action'])) {
    $headline = $_GET['action'];
} else {
    $headline = $_POST['action'];

}
$title = "";
$mutedTitle = "";
$text = "";
$sid = "";
$id = "";
$backgroundimage = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    include "../database/SQLSectionActions.php";
    $actions = new SQLSectionActions();
    $section = $actions->getSectionByID($id);

    $title = $section->getTitle();
    $mutedTitle = $section->getMutedtitle();
    $text = $section->getText();
    $name = $section->getName();
    $email = $section->getEmail();
    $message = $section->getMessage();
    $captcha = $section->getCaptcha();

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
            <h1><?php echo "$headline" ?> Icons-Section</h1>

            <form enctype="multipart/form-data" action="../misc/backgroundupload.php" method="post" id="uploadform">
                <div class="form-group">
                    <img class="img-fluid" src="<?php echo $backgroundimage ?>"><br>
                    <label for="image-upload">Background-Image:</label>
                    <input type="hidden" id="id" class="form-control" name="id" readonly
                           value="<?php echo $id ?>">
                    <input type="hidden" id="action" class="form-control" name="action" readonly
                           value="<?php echo $sid ?>">
                    <input name="background-upload" class="form-control-file" type="file"/>
                </div>
                <div class="form-group">

                    <input type='submit' class="btn btn-secondary" name='upload'
                           id='image-upload' value='Upload'>
                </div>

            </form>

            <form action="../misc/changecontact.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" name="id" readonly value="<?php echo $id ?>">
                </div>

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" class="form-control" required name="title"
                           value="<?php echo $title ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="mutedtitle"">Muted Title:</label>
                    <input type="text" id=mutedtitle" class="form-control" required
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="text">Text:</label>
                    <textarea rows="4" id="text" class="form-control" required form="changeform"
                              name="text"
                              cols="73"><?php echo $text ?></textarea>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="name" name="name" <?php if ($name == 'on') {
                        echo 'checked';
                    } ?>>
                    <label class="custom-control-label" for="name">Name Field</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="email"
                           name="email" <?php if ($email == 'on') {
                        echo 'checked';
                    } ?>>
                    <label class="custom-control-label" for="email">E-Mail Field</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="message"
                           name="message" <?php if ($message == 'on') {
                        echo 'checked';
                    } ?>>
                    <label class="custom-control-label" for="message">Message Field</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="captcha"
                           name="captcha" <?php if ($captcha == 'on') {
                        echo 'checked';
                    } ?>>
                    <label class="custom-control-label" for="captcha">Captcha</label>
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