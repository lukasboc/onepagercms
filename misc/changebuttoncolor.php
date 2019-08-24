<?php
include "../database/SQLSettingActions.php";
$buttonColor = (isset($_POST['buttonColor']) && strlen($_POST['buttonColor']) > 0) ? $_POST['buttonColor'] : "";
if ($buttonColor != "") {
    $settingActions = new SQLSettingActions();
    $settingActions->updateSettingValue('button-color', $buttonColor) ? header("Location: ../core/design.php") : header('Location: ../core/error.php?reason=dberror');
} else header('Location: ../core/error.php?reason=criticalinput');
