<?php
include '../database/SQLSectionActions.php';
$sectionactions = new SQLSectionActions();

$positions = Array();
foreach ($_POST as $key => $value) {
    $positions[$key] = $value;
}
if (sizeof($positions) == 1) {
    header('Location: ../core/error.php?reason=criticalinput');
    die();
}

foreach ($positions as $key => $value) {
    if ($key != "action") {
        if (!$sectionactions->changePosition($key, $value)) {
            header('Location: ../core/error.php?reason=dberror');
            die();
        };
    }
}
header('Location: ../core/success.php?reason=positionchanged');