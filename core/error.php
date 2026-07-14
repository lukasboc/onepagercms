<?php
include '../database/SQLErrorActions.php';
require_once '../system/uploads.php';
$reason = $_GET['reason'] ?? 'none';
$erroractions = new SQLErrorActions();
$message = $erroractions->showErrorMessage($reason);
$headline = $erroractions->showErrorHeadline($reason);
header('refresh:7; url=' . opcms_safe_referer('../core/home.php'));
?>
<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php' ?>
<body>

<?php include_once 'inc/header.php' ?>
<div class="container">
    <div class="alert alert-error mt-3 items-start">
        <i class="fas fa-exclamation-triangle mt-1"></i>
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
