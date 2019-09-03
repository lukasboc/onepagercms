<head>
    <?php echo $settingactions->getSettingValue('google-analytics'); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $settingactions->getSettingValue('page-description'); ?>">
    <title><?php
        echo $settingactions->getSettingValue('website-title');
        ?></title>

    <!-- Bootstrap core CSS -->
    <link href="../plugins/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="../plugins/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet'
          type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/agency.min.css" rel="stylesheet">

    <?php if (file_exists('../img/favicon/favicon180x180.png')) echo "<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"../img/favicon/favicon180x180.png\">"; ?>
    <?php if (file_exists('../img/favicon/favicon32x32.png')) echo "<link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"../img/favicon/favicon32x32.png\">"; ?>
    <?php if (file_exists('../img/favicon/favicon16x16.png')) echo "<link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"../img/favicon/favicon16x16.png\">"; ?>
    <?php if (file_exists('../img/favicon/favicon48x48.ico')) echo "<link rel=\"shortcut icon\" href=\"../img/favicon/favicon48x48.ico\">"; ?>
    <meta name="theme-color" content="#ffffff">
</head>