<?php
include "../database/SQLSettingActions.php";
$primaryColor = (isset($_POST['navigationtext-color']) && strlen($_POST['navigationtext-color']) > 0) ? $_POST['navigationtext-color'] : "";
if ($primaryColor != "") {
    $settingActions = new SQLSettingActions();
    $settingActions->updateSettingValue('navigationtext-color', $primaryColor) ? header("Location: ../core/design.php") : header('Location: ../core/error.php?reason=dberror');
} else header('Location: ../core/error.php?reason=criticalinput');