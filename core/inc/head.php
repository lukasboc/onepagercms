<?php
require_once '../system/bootstrap.php';
echo '
<head>
    <title>OP-CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <script>(function(){var t=localStorage.getItem("opcms-admin-theme")||(window.matchMedia&&matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light");document.documentElement.setAttribute("data-theme",t);})();</script>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../plugins/Trumbowyg/dist/ui/trumbowyg.min.css">';
if (file_exists('../img/favicon/opcms48x48.ico')) echo "<link rel=\"shortcut icon\" href=\"../img/favicon/opcms48x48.ico\">";
do_action('opcms_admin_head');
echo '
</head>
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../plugins/Trumbowyg/dist/trumbowyg.min.js"></script>
';
?>
