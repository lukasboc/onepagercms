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
    $iconamount = sizeof($section->getIcons());
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

<?php include_once "../core/inc/header.php" ?>

<div class="container">
    <div class="row">
        <div class="col">

        </div>
        <div class="col-6">
            <h1><?php echo "$headline" ?> Icons-Section</h1>
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

            <form action="../misc/changeicons.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" name="id" readonly value="<?php echo $id ?>">
                </div>
                <input type="hidden" class="form-control" value="<?php echo $backgroundimage ?>"
                       name="background-image">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" class="form-control" required name="title" value="<?php echo $title ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="mutedtitle">Muted Title:</label>
                    <input type="text" id=mutedtitle" class="form-control" required
                           name="mutedtitle" value="<?php echo $mutedTitle ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="description">Amount of Icons:</label>
                    <select class="form-control" id="amound-of-sections"
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
                    <div class="form-group iconoption" id="' . $i . '">
                    <h2>Icon ' . $i . '</h2>
                    <input type="text" id=icon-' . $i . '" class="form-control mb-3"
                           name="icon-' . $i . '" placeholder="Icon" value="' . $icon . '" ' . $writeable . '>';
                    if ($i == 1) echo '
                    <div class="alert alert-info" role="alert">
                        Example: fas fa-shopping-cart
                    </div> ';
                    echo '
                            <input type="text" id=icon-' . $i . '-headline" class="form-control mb-3"
                           name="icon-' . $i . '-headline" placeholder="Headline" value="' . $iconheadline . '" ' . $writeable . '>
                    <input type="text" id=icon-1-text" class="form-control"
                           name="icon-' . $i . '-text" placeholder="Text" value="' . $icontext . '" ' . $writeable . '>

                </div>
                    ';
                }
                ?>

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
        <div class="col"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("select").change(function(){
            $(this).find("option:selected").each(function(){
                if($(this).attr("value")=="1"){
                    $(".iconoption").not("#1").hide();
                    $("#1").show();
                }else if($(this).attr("value")=="2"){
                    $(".iconoption").not("#2").hide();
                    $("#1").show();
                    $("#2").show();
                }else if($(this).attr("value")=="3"){
                    $(".iconoption").not("#3").hide();
                    $("#1").show();
                    $("#2").show();
                    $("#3").show();
                }else if($(this).attr("value")=="4"){
                    $(".iconoption").not("#4").hide();
                    $("#1").show();
                    $("#2").show();
                    $("#3").show();
                    $("#4").show();
                }else if($(this).attr("value")=="5"){
                    $(".iconoption").not("#5").hide();
                    $("#1").show();
                    $("#2").show();
                    $("#3").show();
                    $("#4").show();
                    $("#5").show();
                }else if($(this).attr("value")=="6"){
                    $(".iconoption").not("#6").hide();
                    $("#1").show();
                    $("#2").show();
                    $("#3").show();
                    $("#4").show();
                    $("#5").show();
                    $("#6").show();

                }else if($(this).attr("value")=="7"){
                    $(".iconoption").not("#7").hide();
                    $("#1").show();
                    $("#2").show();
                    $("#3").show();
                    $("#4").show();
                    $("#5").show();
                    $("#6").show();
                    $("#7").show();

                }else if($(this).attr("value")=="8"){
                    $(".iconoption").not("#8").hide();
                    $("#1").show();
                    $("#2").show();
                    $("#3").show();
                    $("#4").show();
                    $("#5").show();
                    $("#6").show();
                    $("#7").show();
                    $("#8").show();
                } else{
                    $(".iconoption").hide();
                }
            });
        }).change();
    });
</script>
</body>
</html>