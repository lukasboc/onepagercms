<!DOCTYPE html>
<html>
<?php require_once "../core/inc/head.php";
include_once "../database/SQLAdditionalPagesActions.php";
$pagesActions = new SQLAdditionalPagesActions();
$allPages = $pagesActions->getAllAdditionalPages();
?>

<body>
<?php
include_once "../core/inc/header.php" ?>
<div class="container">

    <h1>Additional Pages</h1>
    <div class="row mb-3">
        <div class="col text-center">
            <a class="btn btn-success" href="../misc/additionalpage.php?action=New" role="button">New Page</a>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            <label>Title</label>
        </div>
        <div class="col">
            <label>Content</label>
        </div>
        <div class="col-3">
            <label>Options</label>
        </div>
    </div>
    <?php
    for ($i = 0; sizeof($allPages) > $i; $i++) {
        echo '
  <div class="row">
    <div class="col-2">
    ' . $allPages[$i][1] . '
    </div>
    <div class="col">
      ' . mb_strimwidth(strip_tags($allPages[$i][2]), 0, 80, '...') . '
    </div>
    <div class="col-3">
<div class="btn-group">
      <a href="../misc/additionalpage.php?id=' . $allPages[$i][0] . '&action=Edit" class="btn btn-primary" role="button">Edit</a>
            <a href="../pages/additionalpage.php?id=' . $allPages[$i][0] . '" class="btn btn-info" role="button">Show</a>
      <a href="../misc/additionalpage.php?id=' . $allPages[$i][0] . '&action=Delete" class="btn btn-light" role="button">Delete</a>
</div>
    </div>
  </div>

    
    ';
    } ?>
</div>
</body>
</html>