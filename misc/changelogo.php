<?php
include "../database/SQLSettingActions.php";

$logo = $_POST['logo'] ?? null;
$settingactions = new SQLSettingActions();

if ($logo != null) {
    $settingactions->updateSettingValue("logo", $logo) ? header("Location: ../core/settings.php") : header('Location: ../core/error.php?reason=dberror');
}