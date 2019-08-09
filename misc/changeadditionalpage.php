<?php

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
$showInFooter = $_POST['showInFooter'];

if ($_POST['action'] == "Edit") {
    include '../database/SQLAdditionalPagesActions.php';
    $pagesActions = new SQLAdditionalPagesActions();
    ($pagesActions->editPageEntry($title, $content, $showInFooter, $id)) ? header('Location: ../core/additionalPages.php') : header('Location: ../core/error.php?reason=dberror');
} elseif ($_POST['action'] == "Delete") {
    include '../database/SQLAdditionalPagesActions.php';
    $pagesActions = new SQLAdditionalPagesActions();
    ($pagesActions->deletePageEntry($id)) ? header('Location: ../core/additionalPages.php') : header('Location: ../core/error.php?reason=dberror');
} elseif ($_POST['action'] == "New") {
    include '../database/SQLAdditionalPagesActions.php';
    //Kommentar
    $pagesActions = new SQLAdditionalPagesActions();
    ($pagesActions->newPageEntry($title, $content, $showInFooter)) ? header('Location: ../core/additionalPages.php') : header('Location: ../core/error.php?reason=dberror');
} else {
    header('Location: ../core/error.php?reason=actionnotfound');
    die();
}