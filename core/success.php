<?php
include '../database/SQLSuccessActions.php';
$reason = $_GET['reason'] ?? 'none';
$successactions = new SQLSuccessActions();
$message = $successactions->showSuccessMessage($reason);
$headline = $successactions->showSuccessHeadline($reason);
header('refresh:4; url= ' . $_SERVER['HTTP_REFERER']);
?>
<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php' ?>
<body>

<?php include_once 'inc/header.php' ?>
<div class="container">
    <div class="alert alert-success mt-3 items-start">
        <i class="fas fa-check-circle mt-1"></i>
        <div>
            <h1><?php echo $headline ?></h1>
            <?php echo $message ?>
            <div class="divider my-2"></div>
            <p>Redirecting..</p>
        </div>
    </div>
</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
