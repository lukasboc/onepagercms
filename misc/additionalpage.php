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
<body>

<?php include_once "../core/inc/header.php" ?>

<div class="container">
            <h1><?php echo opcms_esc($headline) ?> Additional Page</h1>

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

            <form action="../misc/changeadditionalpage.php" method="post" id="changeform">
                <?php echo opcms_csrf_field(); ?>
                <input type="hidden" id="id" readonly name="id" value="<?php echo opcms_esc($id) ?>">
                <div class="mb-4">
                    <label class="label" for="custonText">Title:</label>
                    <input type="text" id="custonText" class="input w-full" name="title" required
                           value="<?php echo $title ?>" <?php echo $writeable ?>>
                </div>
                <div class="mb-4">
                    <label class="label" for="content">Content:</label>
                    <textarea rows="15" id="text" class="textarea w-full font-mono" required
                              form="changeform" <?php echo $writeable ?>
                              name="content"
                              cols="73"><?php echo $content ?></textarea>
                    <small class="text-sm text-base-content/60">You can enter HTML Code here. (The active theme's
                        styling will be applied)
                    </small>

                </div>

                <div class="mb-4">
                    <label class="label cursor-pointer justify-start gap-2" for="showInFooter">
                        <input type="checkbox" class="toggle" id="showInFooter"
                               name="showInFooter" <?php if ($showInFooter == "on") {
                            echo 'checked';
                        } ?> <?php echo $disabled ?>>
                        Show in Footer
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
