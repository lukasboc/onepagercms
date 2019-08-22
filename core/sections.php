<!DOCTYPE html>
<html>
<?php require_once "inc/head.php"; ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
<?php
include "../database/SQLSectionActions.php";
include "../database/SQLHeaderActions.php";
$sectionactions = new SQLSectionActions();
$headeractions = new SQLHeaderActions();
$sections = $sectionactions->getAllSections();
?>
    <h1>Sections</h1>

    <div class="row">
        <div class="col-2 col-sm-2 col-md-2 col-lg-1 sectioncolfix">
            <label>Position</label>
        </div>
        <div class="col-2">
            <label>Type</label>
        </div>
        <div class="col-3 col-sm-5 col-md-5 col-lg-6">
            <label>Title</label>
        </div>
        <div class="col-3">
            <label>Options</label>
        </div>
    </div>

    <form method="post" action="../misc/positions.php" id="positionsform">
        <div class="row pb-3">
            <div class="col-2 col-sm-2 col-md-2 col-lg-1 sectioncolfix">

            </div>
            <div class="col-3 col-sm-2">
                Header
            </div>
            <div class="col-3 col-sm-5 col-md-5 col-lg-6">
                <?php echo $headeractions->getHeaderTitle(); ?>
            </div>
            <div class="col-3">
                <a href="../misc/header.php?action=Edit" class="btn btn-primary" role="button"><i
                            class="far fa-edit"></i></a>
            </div>
        </div>
    <?php
for($i=0; sizeof($sections) > $i; $i++){
    echo'
  <div class="row">
    <div class="col-2 col-sm-2 col-md-2 col-lg-1 sectioncolfix">
     <div class="form-group">
      <select class="form-control" form="positionsform" id="sel1" name="' . $sections[$i]->getSuperid() . '">
        <option selected disabled>'. $sections[$i]->getPosition() . '</option>

      ';
        for($h=1; sizeof($sections)+1 > $h; $h++){
        echo'
         <option value="' . $h . '">' . $h . '</option>
        ';
        }
        echo '
      </select>
      </div>
    </div>
    <div class="col-3 col-sm-2">
      ' . $sections[$i]->getType() . '
    </div>
    <div class="col-3 col-sm-5 col-md-5 col-lg-6">
      ' . $sections[$i]->getTitle() . '
    </div>
    <div class="col-3">
<div class="btn-group">
      <a href="../misc/changesection.php?id=' . $sections[$i]->getSuperid() . '&action=Edit&type=' . $sections[$i]->getType() . '" class="btn btn-primary" role="button"><i class="far fa-edit"></i></a>
      <a href="../misc/changesection.php?id=' . $sections[$i]->getSuperid() . '&action=Delete&type=' . $sections[$i]->getType() . '" class="btn btn-light" role="button"><i class="far fa-trash-alt"></i></a>
</div>
    </div>
  </div>

    
    ';
}
?>
        <div class="row pb-3">
            <div class="col-2 col-sm-2 col-md-2 col-lg-1 sectioncolfix">

            </div>
            <div class="col-3 col-sm-2">
                Footer
            </div>
            <div class="col-3 col-sm-5 col-md-5 col-lg-6">
                -
            </div>
            <div class="col-3">
                <a href="../misc/footer.php?action=Edit" class="btn btn-primary" role="button"><i
                            class="far fa-edit"></i></a>
            </div>
        </div>
        <input type="submit" name="action" value="Save Positions" class="btn btn-warning">
    </form>
    <h1 class="mt-4">New Section</h1>

    <form action="../misc/newsection.php" method="post">
        <div class="form-group">
    <label for="types">Type:</label>
            <select class="form-control col-sm-3" name="type" id="types">
        <option>standard</option>
        <option>icons</option>
        <option>contact</option>
    </select>
    </div>

        <input type="submit" name="action" value="New Section" class="btn btn-success">
    </form>
</div>
<?php include_once "inc/footer.php" ?>
</body>
</html>