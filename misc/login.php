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
        $useractions = new SQLUserActions();
        $success = $useractions->login($_POST["username"], $_POST["password"]);
        if($success){
            $_SESSION['profile'] = $_POST["username"];
            header("Location: ../core/home.php");
            die();
        } else {
            header("Location: ../misc/error.php?reason=usernotfound");
            die();
        }
    } else {
        echo "Combination doesn't match.";
    }
}