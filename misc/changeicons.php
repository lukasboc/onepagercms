<?php

$id = $_POST['id'];
$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$amound_of_secitons = $_POST['amound-of-sections'];

$iconone = $_POST['icon-1'];
$icononeheadline = $_POST['icon-1-headline'];
$icononetext = $_POST['icon-1-text'];

$icontwo = $_POST['icon-2'];
$icontwoheadline = $_POST['icon-2-headline'];
$icontwotext = $_POST['icon-2-text'];

$iconone = $_POST['icon-3'];
$icononeheadline = $_POST['icon-3-headline'];
$icononetext = $_POST['icon-3-text'];

$iconthree = $_POST['icon-3'];
$iconthreeheadline = $_POST['icon-3-headline'];
$iconthreetext = $_POST['icon-3-text'];

$iconfour = $_POST['icon-4'];
$iconfourheadline = $_POST['icon-4-headline'];
$iconfourtext = $_POST['icon-4-text'];

$iconfive = $_POST['icon-5'];
$iconfiveheadline = $_POST['icon-5-headline'];
$iconfivetext = $_POST['icon-5-text'];

$iconsix = $_POST['icon-6'];
$iconsixheadline = $_POST['icon-6-headline'];
$iconsixtext = $_POST['icon-6-text'];

$iconseven = $_POST['icon-7'];
$iconsevenheadline = $_POST['icon-7-headline'];
$iconseventext = $_POST['icon-7-text'];

$iconeight = $_POST['icon-8'];
$iconeightheadline = $_POST['icon-8-headline'];
$iconeighttext = $_POST['icon-8-text'];

$iconarray = [$iconone, $icontwo, $iconthree, $iconfour, $iconfive, $iconsix, $iconseven, $iconeight];
$iconheadlinearray = [$icononeheadline, $icontwoheadline, $iconthreeheadline, $iconfourheadline, $iconfiveheadline, $iconsixheadline, $iconsevenheadline, $iconeightheadline];
$icontextarray = [$icononetext, $icontwotext, $iconthreetext, $iconfourtext, $iconfivetext, $iconsixtext, $iconseventext, $iconeighttext];

if ($_POST['action'] == "New") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->addNewIconsEntry($title, $mutedtitle, $iconarray, $iconheadlinearray, $icontextarray );
} elseif ($_POST['action'] == "Delete"){
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->deleteIconsEntry($id);
} elseif ($_POST['action'] == "Edit"){
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $success = $sectionactions->editIconsEntry($id, $title, $mutedtitle, $iconarray, $iconheadlinearray, $icontextarray);
    if($success){
        header("Location: ../core/sections.php");
    } else {
        echo "<h1>Error</h1>Something went wrong. Please try again.";
        header("refresh:2; url= ../core/sections.php");
    }
}