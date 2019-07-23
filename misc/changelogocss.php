<?php
include "../database/SQLSettingActions.php";

$logocss = $_POST['logocss'] ?? null;
$settingactions = new SQLSettingActions();

if ($logocss == null || strlen($logocss === 0)) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

if ($settingactions->updateSettingValue('logo_css', $logocss)) {
    header('Location: ../core/settings.php');
} else {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}