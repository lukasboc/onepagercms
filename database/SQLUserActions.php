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
        include '../database/connect.php';

        $select = $db->prepare("SELECT `username`, `password`
                                   FROM users
                                   WHERE `username` = :username");

        $select->bindValue(':username', $username);
        $select->execute();
        $nachricht = $select->fetch();

        if (password_verify($password . secret, $nachricht["password"])) {
            return true;
        } else {
            return false;
        }
    }

    public function register($username, $password, $email): bool
    {
        include '../database/connect.php';
        $hash = password_hash($password . secret, PASSWORD_BCRYPT, array('cost' => 15));

        $count = $db->prepare("SELECT * FROM users ORDER BY uid DESC LIMIT 1;");
        $count->execute();
        $number_of_rows = $count->fetch();
        $number = $number_of_rows["uid"] + 1;


        $insert = $db->prepare("INSERT INTO users 
         (`uid`, `username`, `password`, `email`) VALUES (:uid, :username, :password, :email)");


        $insert->bindValue(':uid', $number);
        $insert->bindValue(':username', $username);
        $insert->bindValue(':password', $hash);
        $insert->bindValue(':email', $email);

        if ($insert->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function getAllUsernames()
    {
        include '../database/connect.php';
        try {
            $selusrn = $db->prepare("SELECT username FROM users;");
            $selusrn->execute();
            $usernames = $selusrn->fetchAll();
            return $usernames;

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function checkForSpecificUsername($username)
    {
        include '../database/connect.php';
        try {
            $selusrn = $db->prepare("SELECT count(username) FROM users WHERE username = :username;");
            $selusrn->bindValue(':username', $username);
            $selusrn->execute();
            $count = $selusrn->fetch();
            return $count;

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }

    public function checkForSpecificEmail($email)
    {
        include '../database/connect.php';
        try {
            $selusrn = $db->prepare("SELECT count(email) FROM users WHERE email = :email;");
            $selusrn->bindValue(':email', $email);
            $selusrn->execute();
            $count = $selusrn->fetch();
            return $count;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getEmailByUsername($username)
    {
        include '../database/connect.php';
        try {
            $selusrn = $db->prepare("SELECT email FROM users WHERE username = :username;");
            $selusrn->bindValue(':username', $username);
            $selusrn->execute();
            $usnm = $selusrn->fetch();
            return $usnm[0];
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getUsernameByEmail($email)
    {
        include '../database/connect.php';
        try {
            $selem = $db->prepare("SELECT username FROM users WHERE email = :email;");
            $selem->bindValue(':email', $email);
            $selem->execute();
            $em = $selem->fetch();
            return $em[0];
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function changePassword($username, $newpassword)
    {
        include '../database/connect.php';

        try {
            $hash = password_hash($newpassword . secret, PASSWORD_BCRYPT, array('cost' => 15));

            $update = $db->prepare("UPDATE users SET  password =:password  WHERE username = :username;");
            $update->bindValue(':username', $username);
            $update->bindValue(':password', $hash);
            return ($update->execute()) ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function changeEmail($username, $password, $newMail)
    {
        try {
            if (!$this->login($username, $password)) {
                return false;
            }
            include '../database/connect.php';
            $update = $db->prepare("UPDATE users SET email =:email  WHERE username = :username;");
            $update->bindValue(':username', $username);
            $update->bindValue(':email', $newMail);
            return ($update->execute()) ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

}