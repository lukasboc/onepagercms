<?php
$headline = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$writeable = ($headline == "Delete") ? "readonly" : "";
$disabled = ($headline == "Delete") ? "disabled" : "";

$title = "";
$mutedTitle = "";
$receiverMail = "";
$text = "";
$sid = "";
$id = "";
$backgroundimage = (isset($_GET['background-image'])) ? $_GET['background-image'] : "";

$name = "";
$email = "";
$message = "";
$captcha = "";

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
    $receiverMail = $section->getReceiverMail();
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
            <h1><?php echo "$headline" ?> Contact-Section</h1>
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

            <form action="../misc/changecontact.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" name="id" readonly value="<?php echo $id ?>">
                </div>
                <input type="hidden" class="form-control" value="<?php echo $backgroundimage ?>"
                       name="background-image">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" class="form-control" required name="title"
                           value="<?php echo $title ?>" <?php echo $writeable ?>
                    >
                </div>

                <div class="form-group">
                    <label for="mutedtitle">Muted Title:</label>
                    <input type="text" id="mutedtitle" class="form-control"
                           name="mutedtitle" value="<?php echo $mutedTitle ?>" <?php echo $writeable ?>>
                </div>

                <div class="form-group">
                    <label for="text">Text:</label>
                    <textarea rows="4" id="text" class="form-control" form="changeform"
                              name="text"
                              cols="73" <?php echo $writeable ?>><?php echo $text ?></textarea>
                </div>

                <div class="form-group">
                    <label for="receiverMail">Receiver E-Mail:</label>
                    <input type="email" id=receiverMail" class="form-control" required
                           name="receiverMail" value="<?php echo $receiverMail ?>" <?php echo $writeable ?>>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="name" name="name" <?php if ($name == 'on') {
                        echo 'checked';
                    } ?> <?php echo $disabled ?>>
                    <label class="custom-control-label" for="name">Name Field</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="email"
                           name="email" <?php if ($email == 'on') {
                        echo 'checked';
                    } ?> <?php echo $disabled ?>>
                    <label class="custom-control-label" for="email">E-Mail Field</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="message"
                           name="message" <?php if ($message == 'on') {
                        echo 'checked';
                    } ?> <?php echo $disabled ?>>
                    <label class="custom-control-label" for="message">Message Field</label>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="captcha"
                           name="captcha" <?php if ($captcha == 'on') {
                        echo 'checked';
                    } ?> <?php echo $disabled ?>>
                    <label class="custom-control-label" for="captcha">Captcha</label>
                    <a href="../core/faq.php" data-toggle="tooltip"
                       title="API-Key has to be set. For more help read FAQ." class="text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </a>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="delete-background" name="delete-background">
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

</body>
</html>