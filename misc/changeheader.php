<?php

$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$background = (isset($_POST['delete-background'])) ? "" : $_POST['background-image'];
if (isset($_POST['delete-background'])) {
    if (file_exists($_POST['background-image'])) {
        try {
            unlink($_POST['background-image']);
        } catch (Exception $exception) {
        }
    }
}
$customrow = $_POST['customrow'];


if ($_POST['action'] == "Edit") {
    include '../database/SQLHeaderActions.php';
    $headerActions = new SQLHeaderActions();
    ($headerActions->editHeaderEntry($mutedtitle, $title, $background, $customrow)) ? header('Location: ../core/sections.php') : header('Location: ../core/error.php?reason=dberror');
} else {
    header('Location: ../core/error.php?reason=actionnotfound');
    die();
}