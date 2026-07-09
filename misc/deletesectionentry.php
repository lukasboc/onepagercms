<?php
session_start();
if (!isset($_SESSION['profile'])) {
    header('Location: ../core/opcms-login.php');
    die();
}
require_once '../system/bootstrap.php';
include_once '../database/SQLSectionActions.php';

// Removes an orphaned sections-registry row whose plugin is no longer active.
// Built-in and currently registered types must go through their own flow.
$sectionActions = new SQLSectionActions();
$row = isset($_GET['id']) ? $sectionActions->getSectionRow($_GET['id']) : null;

if ($row !== null
        && !in_array($row['type'], array('standard', 'icons', 'contact'), true)
        && (!function_exists('opcms_get_section_type') || opcms_get_section_type($row['type']) === null)) {
    $sectionActions->deleteSectionEntry($row['id']);
}

header('Location: ../core/sections.php');
