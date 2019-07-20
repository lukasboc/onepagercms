<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 20.07.19
 * Time: 17:54
 */

class SQLSettingActions
{
    public function updateCaptchaKey($key): bool
    {
        include '../database/connect.php';

        try {
            $update = $db->prepare("UPDATE settings SET value = :value WHERE setting = :setting;");

            $update->bindValue(':value', $key);
            $update->bindValue(':setting', 'recaptcha_key');
            return ($update->execute()) ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }

}