<?php

if (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $id = $_GET['id'];
}
$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$text = $_POST['text'];
(isset($_POST['name'])) ? $name = $_POST['name'] : $name = false;
(isset($_POST['email'])) ? $email = $_POST['email'] : $email = false;
(isset($_POST['message'])) ? $message = $_POST['message'] : $message = false;
(isset($_POST['captcha'])) ? $captcha = $_POST['captcha'] : $captcha = false;

if ($_POST['action'] == "New") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->addNewContactEntry($title, $mutedtitle, $text, $name, $email, $message, $captcha);
} elseif ($_POST['action'] == "Delete") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->deleteContactEntry($id);
} elseif ($_POST['action'] == "Edit") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $success = $sectionactions->editContactEntry($id, $title, $mutedtitle, $text, $name, $email, $message, $captcha);
    if ($success) {
        header("Location: ../core/sections.php");
    } else {
        echo "<h1>Error</h1>Something went wrong. Please try again.";
        header("refresh:2; url= ../core/sections.php");
    }
}