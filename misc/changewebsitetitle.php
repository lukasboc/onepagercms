<?php
include "../database/SQLSettingActions.php";

$title = $_POST['title'] ?? null;
$settingactions = new SQLSettingActions();

if ($title != null) {
    $settingactions->updateSettingValue("website-title", $title) ? header("Location: ../core/settings.php") : header('Location: ../core/error.php?reason=dberror');
} else {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}