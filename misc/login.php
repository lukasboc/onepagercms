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
        } else {
            echo "
            <!DOCTYPE html>
            <html>";
            require_once "../core/inc/head.php";
            echo "
                <body>
                    <?php include_once \"inc/header.php\" ?>
                    <div class=\"container\">
                        <h1>Wrong Combination</h1>
                        <div class=\"alert alert-danger\">
                            The combination you entered was not correct. Please try again.
                        </div>
                    </div>
                </body>
            </html>
            
            ";
            header("refresh:3;url=../pages/opcms-login.php");
        }
    } else {
        echo "Combination doesn't match.";
    }
}