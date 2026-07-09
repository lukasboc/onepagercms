<?php
require_once '../system/bootstrap.php';
$opcmsAdminPages = apply_filters('opcms_admin_pages', array());
$opcmsRequestedPage = isset($_GET['page']) ? $_GET['page'] : '';
?>
<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php' ?>
<body>
<?php include_once 'inc/header.php' ?>
<div class="container">
    <?php
    if (isset($opcmsAdminPages[$opcmsRequestedPage]) && is_callable($opcmsAdminPages[$opcmsRequestedPage]['render'])) {
        if (!empty($opcmsAdminPages[$opcmsRequestedPage]['title'])) {
            echo '<h1>' . htmlspecialchars($opcmsAdminPages[$opcmsRequestedPage]['title']) . '</h1>';
        }
        call_user_func($opcmsAdminPages[$opcmsRequestedPage]['render']);
    } else {
        echo '<div class="alert alert-error mt-3 items-start"><i class="fas fa-exclamation-triangle mt-1"></i><div><h1>Unknown page</h1>The requested extension page is not registered. Maybe the plugin providing it was deactivated.</div></div>';
    }
    ?>
</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
