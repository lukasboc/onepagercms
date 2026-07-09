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
<?php require_once "../core/inc/head.php" ?>
<body class="min-h-screen bg-base-200">
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
</body>
</html>
