<?php
include "../database/SQLSettingActions.php";
$primaryColor = (isset($_POST['primaryColor']) && strlen($_POST['primaryColor']) > 0) ? $_POST['primaryColor'] : "";
if ($primaryColor != "") {
    $settingActions = new SQLSettingActions();
    $settingActions->updateSettingValue('text-primary', $primaryColor) ? header("Location: ../core/settings.php") : header('Location: ../core/error.php?reason=dberror');
} else header('Location: ../core/error.php?reason=criticalinput');
