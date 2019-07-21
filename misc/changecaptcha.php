<?php
include "../database/SQLSettingActions.php";

$recaptchakey = $_POST['recaptcha_key'] ?? null;
$settingactions = new SQLSettingActions();

if ($recaptchakey == null || strlen($recaptchakey == 0)) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

if ($settingactions->updateCaptchaKey($recaptchakey)) {
    header('Location: ../core/settings.php');
} else {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}