<?php

$custom = $_POST['custom'];
$facebook = $_POST['facebook'];
$twitter = $_POST['twitter'];
$linkedin = $_POST['linkedin'];
$ownPage = $_POST['customPage'];
$copyright = $_POST['copyright'];
$ownIcon = $_POST['customIcon'];

if ($_POST['action'] == "Edit") {
    include '../database/SQLFooterActions.php';
    $footerActions = new SQLFooterActions();
    ($footerActions->editFooterEntry($custom, $facebook, $twitter, $linkedin, $ownPage, $copyright, $ownIcon)) ? header('Location: ../core/sections.php') : header('Location: ../core/error.php?reason=dberror');
} else {
    header('Location: ../core/error.php?reason=actionnotfound');
    die();
}