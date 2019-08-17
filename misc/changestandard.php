<?php

$id = $_POST['id'];
$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$text = $_POST['text'];
$background = $_POST['background-image'];

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
