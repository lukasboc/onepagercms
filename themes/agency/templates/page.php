<!DOCTYPE html>
<html lang="de">

<?php $opcmsTheme->render('head', $opcmsData); ?>
<body id="page-top">
<style>
    .text-primary {
        color: <?php echo $settingactions->getSettingValue("text-primary") ?> !important
    }

    a {
        color: <?php echo $settingactions->getSettingValue("text-primary") ?>
    }

    a:hover {
        color: <?php echo $settingactions->getSettingValue("text-primary") ?>
    }

    ::selection {
        background: <?php echo $settingactions->getSettingValue("text-primary") ?>;
    }

    #mainNav .navbar-nav .nav-item .nav-link.active, #mainNav .navbar-nav .nav-item .nav-link:hover {
        color: <?php echo $settingactions->getSettingValue("text-primary") ?>;
    }

    #mainNav .navbar-toggler {
        background-color: <?php echo $settingactions->getSettingValue("text-primary") ?>;
    }

    ul.social-buttons li a:active, ul.social-buttons li a:focus, ul.social-buttons li a:hover {
        background-color: <?php echo $settingactions->getSettingValue("text-primary") ?>
    }

    .btn-primary {
        background-color: <?php echo $settingactions->getSettingValue("button-color") ?>;
        border-color: <?php echo $settingactions->getSettingValue("button-color") ?>;
    }

    .btn-primary:active, .btn-primary:focus, .btn-primary:hover {
        background-color: <?php echo $settingactions->getSettingValue("button-color") ?> !important;
        border-color: <?php echo $settingactions->getSettingValue("button-color") ?> !important;
    }
</style>
<!-- Navigation -->
<?php $sectionactions->showNavigation() ?>
<div class="container" style="margin-top:120px">
    <h1><?php echo $title ?></h1>

    <p>
        <?php echo $content ?>
    </p>
</div>
<!-- Footer -->
<?php $footeractions->showFooter() ?>
<!-- Modal 1 -->
<?php $opcmsTheme->render('jsembed', $opcmsData); do_action('opcms_body_end'); ?>
</body>
</html>
