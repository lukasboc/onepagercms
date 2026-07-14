<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 08.06.19
 * Time: 18:26
 */

session_start();
if (isset($_POST['login'])) {
    if ($_POST["username"] != "" AND $_POST["password"] != "") {
        require "../database/SQLUserActions.php";
        require_once "../database/SQLSpamProtectionActions.php";

        // Throttle failed logins per IP to blunt brute-force attempts. A distinct
        // key namespace keeps this counter separate from contact-form throttling.
        $spamprotection = new SQLSpamProtectionActions();
        $throttleKey = 'login:' . ($_SERVER['REMOTE_ADDR'] ?? '');
        if ($spamprotection->isRateLimited($throttleKey)) {
            header("Location: ../misc/error.php?reason=contactratelimited");
            die();
        }

        $useractions = new SQLUserActions();
        $success = $useractions->login($_POST["username"], $_POST["password"]);
        if($success){
            // Prevent session fixation: issue a fresh session id on privilege change.
            session_regenerate_id(true);
            $_SESSION['profile'] = $_POST["username"];
            header("Location: ../core/home.php");
            die();
        } else {
            $spamprotection->recordSubmission($throttleKey);
            header("Location: ../misc/error.php?reason=usernotfound");
            die();
        }
    } else {
        echo "Combination doesn't match.";
    }
}
