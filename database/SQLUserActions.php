<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 09.06.19
 * Time: 22:54
 */

define('secret', 'TmPaG6äUAUtuB5BA?');

class SQLUserActions
{
    public function login($username, $password) : bool
    {
        require_once '../database/connect.php';

        $select = $db->prepare("SELECT `username`, `password`
                                   FROM users
                                   WHERE `username` = :username");

        $select->bindValue(':username', $username);
        $select->execute();
        $nachricht = $select->fetch();


        if (password_verify($password . secret, $nachricht["password"])) {
            session_start();
            $_SESSION['profile'] = $username;
            return true;
        } else {
            return false;
        }
    }

    public function register($username, $password, $confirmationpassword, $isAuthor) :bool
    {
        require_once "../database/connect.php";

        $hash = password_hash($password . secret, PASSWORD_BCRYPT, array('cost' => 15));

        $count = $db->prepare("SELECT * FROM users ORDER BY uid DESC LIMIT 1;");
        $count->execute();
        $number_of_rows = $count->fetch();
        $number = $number_of_rows["uid"] + 1;


        $insert = $db->prepare("INSERT INTO users 
         (`uid`, `username`, `password`) VALUES (:uid, :username, :password)");


        $insert->bindValue(':uid', $number);
        $insert->bindValue(':username', $username);
        $insert->bindValue(':password', $hash);

        if ($insert->execute()) {
            return true;
        } else {
            return false;
        }

    }

}