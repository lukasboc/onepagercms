<?php
include '../database/SQLSettingActions.php';

$metadescription = $_POST['metadescription'] ?? null;
$settingactions = new SQLSettingActions();

if ($metadescription !== null) {
    $settingactions->updateSettingValue('page-description', $metadescription) ? header('Location: ../core/settings.php') : header('Location: ../core/error.php?reason=dberror');
} else {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}