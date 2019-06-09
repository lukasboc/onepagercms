<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 08.06.19
 * Time: 18:26
 */

if (isset($_POST['login'])) {
    if ($_POST["username"] != "" AND $_POST["password"] != "") {
        require "../database/SQLUserActions.php";
        $useractions = new SQLUserActions();
        $success = $useractions->login($_POST["username"], $_POST["password"]);
        if($success){
            header("Location: ../core/home.php");
        }
    } else {
        echo "Combination doesn't match.";
    }
}