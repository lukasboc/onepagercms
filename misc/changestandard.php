<?php
include "../database/SQLSectionActions.php";
$sectionactions = new SQLSectionActions();

$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$text = $_POST['text'];

if ($_POST['action'] == "New") {
    $sectionactions->addNewStandardEntry($title, $mutedtitle, $text);
}
