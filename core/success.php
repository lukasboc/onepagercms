<?php
include "../database/SQLSuccessActions.php";
$reason = $_GET['reason'] ?? "none";
$successactions = new SQLSuccessActions();
$message = $successactions->showSuccessMessage($reason);
$headline = $successactions->showSuccessHeadline($reason);
?>
<!DOCTYPE html>
<html>
<?php require_once "inc/head.php" ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
    <h1><?php echo $headline ?></h1>
    <div class="alert alert-success">
        <?php echo $message ?>
    </div>
</div>
<?php include_once "inc/footer.php" ?>
</body>
</html>