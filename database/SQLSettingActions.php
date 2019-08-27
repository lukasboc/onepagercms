<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 20.07.19
 * Time: 17:54
 */

class SQLSettingActions
{
    public function getSettingValue($setting)
    {
        include '../database/connect.php';

        try {
            $select = $db->prepare('SELECT value FROM settings WHERE setting = :setting;');
            $select->bindValue(':setting', $setting);
            $select->execute();
            $value = $select->fetch();
            return $value[0];
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function updateSettingValue($setting, $key): bool
    {
        include '../database/connect.php';

        try {
            $update = $db->prepare('UPDATE settings SET value = :value WHERE setting = :setting;');

            $update->bindValue(':value', $key);
            $update->bindValue(':setting', $setting);
            return $update->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

}