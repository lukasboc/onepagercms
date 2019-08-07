<?php
include "../database/SQLErrorActions.php";
$reason = $_GET['reason'] ?? "none";
$erroractions = new SQLErrorActions();
$message = $erroractions->showErrorMessage($reason);
$headline = $erroractions->showErrorHeadline($reason);
?>
<!DOCTYPE html>
<html>
<?php require_once "inc/head.php" ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
    <h1><?php echo $headline ?></h1>
    <div class="alert alert-danger">
        <?php echo $message ?>
    </div>


</div>
<?php include_once "inc/footer.php" ?>
</body>
</html>