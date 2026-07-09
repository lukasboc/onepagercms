<!DOCTYPE html>
<html lang="en">
<?php $opcmsTheme->render('head', $opcmsData); ?>
<body id="page-top">
<?php $opcmsTheme->render('styles', $opcmsData); ?>
<!-- Navigation -->
<?php $sectionactions->showNavigation() ?>

<!-- Header -->
<?php $headeractions->showHeader() ?>

<!-- Sections -->
<?php $sectionactions->showAllSections() ?>

<!-- Footer -->
<?php $footeractions->showFooter() ?>

<!-- Modal 1 -->
<?php $opcmsTheme->render('jsembed', $opcmsData); do_action('opcms_body_end'); ?>
</body>

</html>
