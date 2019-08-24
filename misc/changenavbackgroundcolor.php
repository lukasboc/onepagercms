<?php
include "../database/SQLSettingActions.php";
$primaryColor = (isset($_POST['navigation-color']) && strlen($_POST['navigation-color']) > 0) ? $_POST['navigation-color'] : "";
if ($primaryColor != "") {
    $settingActions = new SQLSettingActions();
    $settingActions->updateSettingValue('navigation-color', $primaryColor) ? header("Location: ../core/design.php") : header('Location: ../core/error.php?reason=dberror');
} else header('Location: ../core/error.php?reason=criticalinput');
