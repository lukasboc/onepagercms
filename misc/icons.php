<?php
if(isset($_GET['action'])){
    $headline = $_GET['action'];
} else {
    $headline = $_POST['action'];
}

$title = "";
$mutedTitle = "";
$Text = "";
$sid = "";
$id = "";
$backgroundimage = "";

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
                    <label for="image-upload">Bild hochladen:</label>
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

            <form action="../misc/changestandard.php" method="post" id="changeform">
                <div class="form-group">
                    <input type="hidden" id="id" class="form-control" name="id" readonly value="">
                </div>

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" class="form-control" required name="title"
                    >
                </div>

                <div class="form-group">
                    <label for="mutedtitle"">Muted Title:</label>
                    <input type="text" id=mutedtitle" class="form-control" required
                           name="mutedtitle"
                    >
                </div>

                <div class="form-group">
                    <label for="description">Amount of Icons:</label>
                    <select id="amound-of-sections" name="amound-of-sections">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="icon-1">Icon 1:</label>
                    <input type="text" id=icon-1" class="form-control"
                           name="icon-1">
                    <label for="icon-1-headline">Icon 1 Headline:</label>
                    <input type="text" id=icon-1-headline" class="form-control"
                           name="icon-1-headline">
                    <label for="icon-1-text">Icon 1 Text:</label>
                    <input type="text" id=icon-1-text" class="form-control"
                           name="icon-1-text">

                </div>

                <div class="form-group">
                    <label for="icon-2">Icon 2:</label>
                    <input type="text" id=icon-2" class="form-control"
                           name="icon-2">
                    <label for="icon-2-headline">Icon 2 Headline:</label>
                    <input type="text" id=icon-1-headline" class="form-control"
                           name="icon-2-headline">
                    <label for="icon-2-text">Icon 2 Text:</label>
                    <input type="text" id=icon-1-text" class="form-control"
                           name="icon-2-text">

                </div>

                <div class="form-group">
                    <label for="icon-3">Icon 3:</label>
                    <input type="text" id=icon-3" class="form-control"
                           name="icon-3">
                    <label for="icon-3-headline">Icon 3 Headline:</label>
                    <input type="text" id=icon-3-headline" class="form-control"
                           name="icon-3-headline">
                    <label for="icon-3-text">Icon 3 Text:</label>
                    <input type="text" id=icon-3-text" class="form-control"
                           name="icon-3-text">

                </div>

                <div class="form-group">
                    <label for="icon-4">Icon 4:</label>
                    <input type="text" id=icon-4" class="form-control"
                           name="icon-4">
                    <label for="icon-4-headline">Icon 4 Headline:</label>
                    <input type="text" id=icon-1-headline" class="form-control"
                           name="icon-4-headline">
                    <label for="icon-4-text">Icon 4 Text:</label>
                    <input type="text" id=icon-4-text" class="form-control"
                           name="icon-4-text">

                </div>

                <div class="form-group">
                    <label for="icon-5">Icon 5:</label>
                    <input type="text" id=icon-5" class="form-control"
                           name="icon-5">
                    <label for="icon-5-headline">Icon 5 Headline:</label>
                    <input type="text" id=icon-5-headline" class="form-control"
                           name="icon-5-headline">
                    <label for="icon-5-text">Icon 5 Text:</label>
                    <input type="text" id=icon-5-text" class="form-control"
                           name="icon-5-text">

                </div>

                <div class="form-group">
                    <label for="icon-6">Icon 6:</label>
                    <input type="text" id=icon-6" class="form-control"
                           name="icon-6">
                    <label for="icon-6-headline">Icon 6 Headline:</label>
                    <input type="text" id=icon-6-headline" class="form-control"
                           name="icon-6-headline">
                    <label for="icon-6-text">Icon 6 Text:</label>
                    <input type="text" id=icon-6-text" class="form-control"
                           name="icon-6-text">

                </div>

                <div class="form-group">
                    <label for="icon-7">Icon 7:</label>
                    <input type="text" id=icon-7" class="form-control"
                           name="icon-7">
                    <label for="icon-7-headline">Icon 7 Headline:</label>
                    <input type="text" id=icon-1-headline" class="form-control"
                           name="icon-7-headline">
                    <label for="icon-7-text">Icon 7 Text:</label>
                    <input type="text" id=icon-7-text" class="form-control"
                           name="icon-7-text">

                </div>

                <div class="form-group">
                    <label for="icon-8">Icon 8:</label>
                    <input type="text" id=icon-8" class="form-control"
                           name="icon-8">
                    <label for="icon-8-headline">Icon 8 Headline:</label>
                    <input type="text" id=icon-8-headline" class="form-control"
                           name="icon-8-headline">
                    <label for="icon-8-text">Icon 8 Text:</label>
                    <input type="text" id=icon-8-text" class="form-control"
                           name="icon-8-text">

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