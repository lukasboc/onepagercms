<?php
include "../database/SQLSettingActions.php";

$googleanalytics = $_POST['googleAnalytics'] ?? null;
$settingactions = new SQLSettingActions();

if ($settingactions->updateSettingValue('google-analytics', $googleanalytics)) {
    header('Location: ../core/settings.php');
} else {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}