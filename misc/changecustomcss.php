<?php
include "../database/SQLSettingActions.php";

$customcss = $_POST['customcss'] ?? "";
$settingactions = new SQLSettingActions();

if ($settingactions->updateSettingValue('custom-css', $customcss)) {
    header('Location: ../core/success.php?reason=customcsschanged');
} else {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}