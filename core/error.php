<?php
include "../database/SQLErrorActions.php";
$reason = $_GET['reason'] ?? "none";
$erroractions = new SQLErrorActions();
$message = $erroractions->showErrorMessage($reason);
$headline = $erroractions->showErrorHeadline($reason);
header("refresh:7; url= " . $_SERVER['HTTP_REFERER']);
?>
<!DOCTYPE html>
<html>
<?php require_once "inc/head.php" ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
    <div class="alert alert-danger mt-3">
        <h1 class="alert-heading"><?php echo $headline ?></h1>
        <?php echo $message ?>
        <hr>
        <p>Redirecting..</p>
    </div>


</div>
<?php include_once "inc/footer.php" ?>
</body>
</html>