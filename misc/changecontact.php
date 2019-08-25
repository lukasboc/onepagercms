<?php

if (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $id = $_GET['id'];
}
$title = $_POST['title'];
$mutedtitle = $_POST['mutedtitle'];
$text = $_POST['text'];
$receiverMail = $_POST['receiverMail'];
$background = (isset($_POST['delete-background'])) ? "" : $_POST['background-image'];
if (isset($_POST['delete-background'])) {
    if (file_exists($_POST['background-image'])) {
        try {
            unlink($_POST['background-image']);
        } catch (Exception $exception) {
        }
    }
}

(isset($_POST['name'])) ? $name = $_POST['name'] : $name = false;
(isset($_POST['email'])) ? $email = $_POST['email'] : $email = false;
(isset($_POST['message'])) ? $message = $_POST['message'] : $message = false;
(isset($_POST['captcha'])) ? $captcha = $_POST['captcha'] : $captcha = false;

if ($_POST['action'] == "New") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->addNewContactEntry($title, $mutedtitle, $text, $name, $email, $message, $captcha, $background, $receiverMail);
} elseif ($_POST['action'] == "Delete") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    $sectionactions->deleteContactEntry($id);
} elseif ($_POST['action'] == "Edit") {
    include '../database/SQLSectionActions.php';
    $sectionactions = new SQLSectionActions();
    ($sectionactions->editContactEntry($id, $title, $mutedtitle, $text, $name, $email, $message, $captcha, $background, $receiverMail)) ? header('Location: ../core/success.php?reason=sectionchanged') : header('Location: ../core/error.php?reason=sectionchangefailed');;
}