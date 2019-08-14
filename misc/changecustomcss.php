<?php
include "../database/SQLSettingActions.php";

$customcss = $_POST['custom-css'] ?? null;
$settingactions = new SQLSettingActions();

if ($logocss == null || strlen($logocss === 0)) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

if ($settingactions->updateSettingValue('custom-css', $customcss)) {
    header('Location: ../core/success.php?reason=customcsschanged');
} else {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}