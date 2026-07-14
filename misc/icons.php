<?php
$headline = (isset($_GET['action'])) ? $_GET['action'] : $_POST['action'];
$writeable = ($headline == "Delete") ? "readonly" : "";
$disabled = ($headline == "Delete") ? "disabled" : "";

$title = "";
$mutedTitle = "";
$Text = "";
$sid = "";
$id = "";
$backgroundimage = (isset($_GET['background-image'])) ? $_GET['background-image'] : "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    include "../database/SQLSectionActions.php";
    $actions = new SQLSectionActions();
    $section = $actions->getSectionByID($id);

    $title = $section->getTitle();
    $mutedTitle = $section->getMutedtitle();
    $iconamount = count($section->getIcons());
    $icons = $section->getIcons();
    $iconheadlines = $section->getIconHeadline();
    $icontexts = $section->getIconTexts();
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
            <h1><?php echo opcms_esc($headline) ?> Icons-Section</h1>
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

            <form action="../misc/changeicons.php" method="post" id="changeform">
                <?php echo opcms_csrf_field(); ?>
                <input type="hidden" id="id" name="id" readonly value="<?php echo opcms_esc($id) ?>">
                <input type="hidden" value="<?php echo opcms_esc($backgroundimage) ?>"
                       name="background-image">

                <div class="mb-4">
                    <label class="label" for="title">Title:</label>
                    <input type="text" id="title" class="input w-full" required name="title" value="<?php echo $title ?>"
                    >
                </div>

                <div class="mb-4">
                    <label class="label" for="mutedtitle">Muted Title:</label>
                    <input type="text" id="mutedtitle" class="input w-full" required
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                    >
                </div>

                <div class="mb-4">
                    <label class="label" for="amound-of-sections">Amount of Icons:</label>
                    <select class="select w-full" id="amound-of-sections"
                            name="amound-of-sections" <?php echo $writeable ?>>
                        <?php
                        for($i = 1; $i < 9; $i++){
                            if ($i == $iconamount){
                                $select = "selected";
                            } else{
                                $select = "";
                            }
                            echo '<option ' . $select . ' value="' . $i .  '">' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <?php
                for ($i = 1; $i < 9; $i++){
                    if(isset($icons[$i-1])){
                        $icon = $icons[$i-1];
                        $iconheadline = $iconheadlines[$i-1];
                        $icontext = $icontexts[$i-1];
                    }
                    else {
                        $icon = "";
                        $iconheadline = "";
                        $icontext = "";
                    }
                    echo '
                    <div class="mb-4 iconoption" id="' . $i . '">
                    <h2>Icon ' . $i . '</h2>
                    <input type="text" id="icon-' . $i . '" class="input w-full mb-3"
                           name="icon-' . $i . '" placeholder="Icon" value="' . $icon . '" ' . $writeable . '>';
                    if ($i == 1) echo '
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <span>Example: fas fa-shopping-cart</span>
                    </div> ';
                    echo '
                            <input type="text" id="icon-' . $i . '-headline" class="input w-full mb-3"
                           name="icon-' . $i . '-headline" placeholder="Headline" value="' . $iconheadline . '" ' . $writeable . '>
                    <input type="text" id="icon-' . $i . '-text" class="input w-full"
                           name="icon-' . $i . '-text" placeholder="Text" value="' . $icontext . '" ' . $writeable . '>

                </div>
                    ';
                }
                ?>

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
<!-- jQuery is loaded globally in core/inc/head.php -->
<script>
    $(document).ready(function(){
        $("#amound-of-sections").change(function(){
            $(this).find("option:selected").each(function(){
                var amount = parseInt($(this).attr("value"), 10);
                if (isNaN(amount)) {
                    $(".iconoption").hide();
                    return;
                }
                $(".iconoption").each(function(){
                    var iconIndex = parseInt($(this).attr("id"), 10);
                    if (iconIndex <= amount) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        }).change();
    });
</script>
<?php include_once "../core/inc/footer.php" ?>
</body>
</html>
