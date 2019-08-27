<!DOCTYPE html>
<html lang="en">
<?php
include '../database/SQLUserActions.php';
$useractions = new SQLUserActions();
if (count($useractions->getAllUsernames()) === 0) {
    header('Location: ../core/install.php');
}


include '../database/SQLSectionActions.php';
include '../database/SQLHeaderActions.php';
include_once '../database/SQLSettingActions.php';
include_once '../database/SQLFooterActions.php';
$sectionactions = new SQLSectionActions();
$headeractions = new SQLHeaderActions();
$footeractions = new SQLFooterActions();
$settingactions = new SQLSettingActions();
require_once 'inc/head.php';
?>
<body id="page-top">
<style>
    .text-primary {
        color: <?php echo $settingactions->getSettingValue('text-primary') ?> !important
    }

    a {
        color: <?php echo $settingactions->getSettingValue('text-primary') ?>
    }

    a:hover {
        color: <?php echo $settingactions->getSettingValue('text-primary') ?>
    }

    ::selection {
        background: <?php echo $settingactions->getSettingValue('text-primary') ?>;
    }

    #mainNav .navbar-nav .nav-item .nav-link.active, #mainNav .navbar-nav .nav-item .nav-link:hover {
        color: <?php echo $settingactions->getSettingValue('text-primary') ?>;
    }

    #mainNav .navbar-toggler {
        background-color: <?php echo $settingactions->getSettingValue('text-primary') ?>;
    }

    ul.social-buttons li a:active, ul.social-buttons li a:focus, ul.social-buttons li a:hover {
        background-color: <?php echo $settingactions->getSettingValue('text-primary') ?>
    }

    .btn-primary {
        background-color: <?php echo $settingactions->getSettingValue('button-color') ?>;
        border-color: <?php echo $settingactions->getSettingValue('button-color') ?>;
    }

    .btn-primary:active, .btn-primary:focus, .btn-primary:hover {
        background-color: <?php echo $settingactions->getSettingValue('button-color') ?> !important;
        border-color: <?php echo $settingactions->getSettingValue('button-color') ?> !important;
    }

    #mainNav {
        background-color: <?php echo $settingactions->getSettingValue('navigation-color') ?> !important;
    }

    #mainNav .navbar-nav .nav-item .nav-link {
        color: <?php echo $settingactions->getSettingValue('navigationtext-color') ?> !important;
    }

    #mainNav .navbar-nav .nav-item .nav-link:hover {
        color: <?php echo $settingactions->getSettingValue('text-primary') ?> !important;
    }

    #mainNav .navbar-nav .nav-item .nav-link.active, #mainNav .navbar-nav .nav-item .nav-link:hover {
        color: <?php echo $settingactions->getSettingValue('text-primary') ?>;
    }

    <?php echo $settingactions->getSettingValue('custom-css') ?>
</style>
<!-- Navigation -->
<?php $sectionactions->showNavigation() ?>

<!-- Header -->
<?php $headeractions->showHeader() ?>

<!-- Sections -->
<?php $sectionactions->showAllSections() ?>

<!-- Footer -->
<?php $footeractions->showFooter() ?>

<!-- Modal 1 -->
<?php require_once 'inc/jsembed.php' ?>
</body>

</html>
