<?php
session_start();
if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}
include_once '../database/SQLSettingActions.php';

// Drops all cached marketplace responses so the next page load fetches fresh data.
$settingActions = new SQLSettingActions();
$settingActions->deleteSettingsByPrefix('marketplace-cache-');

header('Location: ../core/extensions.php?tab=marketplace');
