<!DOCTYPE html>
<html>
<?php require_once "inc/head.php"; ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
<?php
include "../database/SQLSectionActions.php";
$sectionactions = new SQLSectionActions();
$sections = $sectionactions->getAllSections();
?>
    <h1>Sections</h1>

    <div class="row">
        <div class="col-1">
            <label>Position</label>
        </div>
        <div class="col-2">
            <label>Type</label>
        </div>
        <div class="col">
            <label>Title</label>
        </div>
        <div class="col-3">
            <label>Options</label>
        </div>
    </div>

    <form method="post" action="../misc/positions.php">
    <?php
for($i=0; sizeof($sections) > $i; $i++){
    echo'
  <div class="row">
    <div class="col-1">
     <div class="form-group">
      <select class="form-control" id="sel1">
        <option selected disabled>'. $sections[$i]->getPosition() . '</option>

      ';
        for($h=1; sizeof($sections)+1 > $h; $h++){
        echo'
         <option>' . $h . '</option>
        ';
        }
        echo '
      </select>
      </div>
    </div>
    <div class="col-2">
      ' . $sections[$i]->getType() . '
    </div>
    <div class="col">
      ' . $sections[$i]->getTitle() . '
    </div>
    <div class="col-3">
<div class="btn-group">
      <a href="../misc/changesection.php?id=' . $sections[$i]->getSuperid() . '&action=Edit&type=' . $sections[$i]->getType() . '" class="btn btn-primary" role="button">Edit</a>
      <a href="../misc/changesection.php?id=' . $sections[$i]->getSuperid() . '&action=Delete&type=' . $sections[$i]->getType() . '" class="btn btn-light" role="button">Delete</a>
</div>
    </div>
  </div>

    
    ';
}
?>
        <input type="submit" name="action" value="Save positions" class="btn btn-warning">
    </form>
    <h1 class="mt-4">New Section</h1>

    <form action="../misc/newsection.php" method="post">
        <div class="form-group">
    <label for="types">Type:</label>
    <select class="form-control col-1" name="type" id="types">
        <option>standard</option>
        <option>icons</option>
        <option>contact</option>
    </select>
    </div>
    <input type="submit" name="action" value="New Section" class="btn btn-success">

    </form>
</div>
</body>
</html>