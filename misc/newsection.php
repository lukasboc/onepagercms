<?php
require __DIR__ . '/inc/auth.php';
require_once '../system/bootstrap.php';
$type = $_POST['type'];

if($type == "standard") {
    header('Location: standard.php?action=New');
} elseif ($type == "icons"){
    header('Location: icons.php?action=New');
} elseif ($type == "contact"){
    header('Location: ../misc/contact.php?action=New');
} elseif (function_exists('opcms_get_section_type') && ($opcmsSectionTypeConfig = opcms_get_section_type($type)) !== null) {
    $opcmsSeparator = (strpos($opcmsSectionTypeConfig['form_url'], '?') === false) ? '?' : '&';
    header('Location: ' . $opcmsSectionTypeConfig['form_url'] . $opcmsSeparator . 'action=New');
} else {
    header('Location: ../core/sections.php');
}

?>