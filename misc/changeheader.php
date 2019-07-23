<?php

$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];

if ($_POST['action'] == "Edit") {
    include '../database/SQLHeaderActions.php';
    $headerActions = new SQLHeaderActions();
    ($headerActions->editHeaderEntry($mutedtitle, $title)) ? header('Location: ../core/sections.php') : header('Location: ../core/error.php?reason=dberror');
} else {
    header('Location: ../core/error.php?reason=actionnotfound');
    die();
}