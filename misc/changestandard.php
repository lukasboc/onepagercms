<?php

$id = $_POST['id'];
$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$text = $_POST['text'];
$background = (isset($_POST['delete-background'])) ? "" : $_POST['background-image'];
if (isset($_POST['delete-background'])) {
    if (file_exists($_POST['background-image'])) {
        try {
            unlink($_POST['background-image']);
        } catch (Exception $exception) {
        }
    }
}

if ($_POST['action'] == "New") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->addNewStandardEntry($title, $mutedtitle, $text, $background);
} elseif ($_POST['action'] == "Delete"){
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->deleteStandardEntry($id);
} elseif ($_POST['action'] == "Edit") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    ($sectionactions->editStandardEntry($id, $title, $mutedtitle, $text, $background)) ? header('Location: ../core/success.php?reason=sectionchanged') : header('Location: ../core/error.php?reason=sectionchangefailed');
}
