<?php

$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$text = $_POST['text'];

if ($_POST['action'] == "New") {
    $sectionactions = new SQLSectionActions();
    $sectionactions->addNewStandardEntry($title, $mutedtitle, $text);
}
