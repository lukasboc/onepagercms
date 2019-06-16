<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 08.06.19
 * Time: 18:28
 */

include "User.php";

class UserActions
{
    public function __construct()
    {
        $this->userArray = Array();
        $userone = new User("test", "test");
        array_push($this->userArray, $userone);
    }

    public function login($username, $password): bool
    {
        $h = 0;
        for ($i = 0; $i < sizeof($this->userArray); $i++) {
            if ($this->userArray[$i]->getUserPassword() == $password && $this->userArray[$i]->getUserUsername() == $username) {
                $h++;
            }
        }
        if ($h > 0) {
            session_start();
            $_SESSION['profile'] = $username;
            return true;
        } else {
            throw new Exception("Wrong combination!");
        }
    }

    public function register($username, $password, $confirmationpassword, $isAuthor): bool
    {
        $h = 0;
        for ($i = 0; $i < sizeof($this->userArray); $i++) {
            if ($this->userArray[$i]->getUserUsername() == $username) {
                $h++;
            }
        }
        if ($h == 0) {
            if ($password == $confirmationpassword && strlen($password) > 5) {
                $newuser = new User($username, $password, $isAuthor);
                array_push($this->userArray, $newuser);
                return true;
            } else {
                throw new Exception("Password doesn't fit!");
            }
        } else {
            throw new Exception("Username already taken!");
        }
    }

}