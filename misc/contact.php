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
    $receiverMail = $section->getReceiverMail();
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
            <h1><?php echo opcms_esc($headline) ?> Contact-Section</h1>
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

            <form action="../misc/changecontact.php" method="post" id="changeform">
                <?php echo opcms_csrf_field(); ?>
                <input type="hidden" id="id" name="id" readonly value="<?php echo opcms_esc($id) ?>">
                <input type="hidden" value="<?php echo opcms_esc($backgroundimage) ?>"
                       name="background-image">

                <div class="mb-4">
                    <label class="label" for="title">Title:</label>
                    <input type="text" id="title" class="input w-full" required name="title"
                           value="<?php echo $title ?>" <?php echo $writeable ?>
                    >
                </div>

                <div class="mb-4">
                    <label class="label" for="mutedtitle">Muted Title:</label>
                    <input type="text" id="mutedtitle" class="input w-full"
                           name="mutedtitle" value="<?php echo $mutedTitle ?>" <?php echo $writeable ?>>
                </div>

                <div class="mb-4">
                    <label class="label" for="text">Text:</label>
                    <textarea rows="4" id="text" class="textarea w-full" form="changeform"
                              name="text"
                              cols="73" <?php echo $writeable ?>><?php echo $text ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="label" for="receiverMail">Receiver E-Mail:</label>
                    <input type="email" id="receiverMail" class="input w-full" required
                           name="receiverMail" value="<?php echo $receiverMail ?>" <?php echo $writeable ?>>
                </div>

                <div class="mb-3">
                    <label class="label cursor-pointer justify-start gap-2" for="name">
                        <input type="checkbox" class="toggle" id="name" name="name" <?php if ($name == 'on') {
                            echo 'checked';
                        } ?> <?php echo $disabled ?>>
                        Name Field
                    </label>
                </div>

                <div class="mb-3">
                    <label class="label cursor-pointer justify-start gap-2" for="email">
                        <input type="checkbox" class="toggle" id="email"
                               name="email" <?php if ($email == 'on') {
                            echo 'checked';
                        } ?> <?php echo $disabled ?>>
                        E-Mail Field
                    </label>
                </div>

                <div class="mb-3">
                    <label class="label cursor-pointer justify-start gap-2" for="message">
                        <input type="checkbox" class="toggle" id="message"
                               name="message" <?php if ($message == 'on') {
                            echo 'checked';
                        } ?> <?php echo $disabled ?>>
                        Message Field
                    </label>
                </div>

                <div class="mb-4">
                    <label class="label cursor-pointer justify-start gap-2" for="delete-background">
                        <input class="checkbox" type="checkbox" id="delete-background" name="delete-background">
                        Delete Background-Image
                    </label>
                </div>
                <div class="mb-4">
                    <input type='submit' class="btn btn-primary" name='action'
                           id='change' value='<?php echo opcms_esc($headline) ?>'>
                </div>
            </form>

        </div>

<?php include_once "../core/inc/footer.php" ?>
</body>
</html>
