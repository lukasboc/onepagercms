<?php
require_once '../system/bootstrap.php';
if($_GET['type'] == 'standard'){
    $id = $_GET['id'];
    if($_GET['action'] == 'Edit'){
    header('Location: ../misc/standard.php?id=' . $id . '&action=Edit');
    } elseif ($_GET['action'] == 'Delete'){
        header('Location: ../misc/standard.php?id=' . $id . '&action=Delete');
    }
}

elseif ($_GET['type'] == 'icons'){
    $id = $_GET['id'];
    if($_GET['action'] == 'Edit'){
        header('Location: ../misc/icons.php?id=' . $id . '&action=Edit');
    } elseif ($_GET['action'] == 'Delete'){
        header('Location: ../misc/icons.php?id=' . $id . '&action=Delete');
    }
}

    elseif ($_GET['type'] == 'contact') {
        $id = $_GET['id'];
        if ($_GET['action'] == 'Edit') {
            header('Location: ../misc/contact.php?id=' . $id . '&action=Edit');
        } elseif ($_GET['action'] == 'Delete') {
            header('Location: ../misc/contact.php?id=' . $id . '&action=Delete');
        }

}

    elseif (function_exists('opcms_get_section_type') && ($opcmsSectionTypeConfig = opcms_get_section_type($_GET['type'])) !== null) {
        $id = $_GET['id'];
        if ($_GET['action'] == 'Edit' || $_GET['action'] == 'Delete') {
            $opcmsSeparator = (strpos($opcmsSectionTypeConfig['form_url'], '?') === false) ? '?' : '&';
            header('Location: ' . $opcmsSectionTypeConfig['form_url'] . $opcmsSeparator . 'action=' . $_GET['action'] . '&id=' . urlencode($id));
        } else {
            header('Location: ../core/sections.php');
        }
}