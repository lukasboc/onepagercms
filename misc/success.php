<?php
include "../database/SQLSuccessActions.php";
$reason = $_GET['reason'] ?? "none";
$successactions = new SQLSuccessActions();
$message = $successactions->showSuccessMessage($reason);
$headline = $successactions->showSuccessHeadline($reason);
header("refresh:4; url= " . $_SERVER['HTTP_REFERER']);
?>
<!DOCTYPE html>
<html>
<?php require_once "../core/inc/head.php" ?>
<body>

<div class="container">
    <div class="alert alert-success mt-3">
        <h1 class="alert-heading"><?php echo $headline ?></h1>
        <?php echo $message ?>
        <hr>
        <p>Redirecting..</p>
    </div>
</div>
</body>
</html>