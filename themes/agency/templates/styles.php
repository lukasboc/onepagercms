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

    <?php echo apply_filters('opcms_custom_css', $settingactions->getSettingValue('custom-css')) ?>
</style>
