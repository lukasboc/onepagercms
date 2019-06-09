<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 09.05.19
 * Time: 15:32
 */

class User
{
    private $user_username;
    private $user_password;

    public function __construct($username, $password)
    {
        $this->user_username = $username;
        $this->user_password = $password;
    }

    /**
     * @return mixed
     */
    public function getUserUsername()
    {
        return $this->user_username;
    }

    /**
     * @param mixed $user_username
     */
    public function setUserUsername($user_username): void
    {
        $this->user_username = $user_username;
    }

    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->user_password;
    }

    /**
     * @param mixed $user_password
     */
    public function setUserPassword($user_password): void
    {
        $this->user_password = $user_password;
    }
}