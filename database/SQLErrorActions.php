<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 21.07.19
 * Time: 01:11
 */

class SQLErrorActions
{
    public function showErrorMessage($reason)
    {
        include '../database/connect.php';

        try {
            $selmsg = $db->prepare('SELECT message FROM error WHERE reason =:reason;');
            $selmsg->bindValue(':reason', $reason);
            $selmsg->execute();
            $message = $selmsg->fetch();
            return $message['message'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }

    public function showErrorHeadline($reason)
    {
        include '../database/connect.php';

        try {
            $selmsg = $db->prepare('SELECT headline FROM error WHERE reason =:reason;');
            $selmsg->bindValue(':reason', $reason);
            $selmsg->execute();
            $message = $selmsg->fetch();
            return $message['headline'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }


}