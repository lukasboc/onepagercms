<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 21.07.19
 * Time: 01:11
 */

class SQLSuccessActions
{
    public function showSuccessMessage($reason)
    {
        include '../database/connect.php';

        try {
            $selmsg = $db->prepare('SELECT message FROM success WHERE reason =:reason;');
            $selmsg->bindValue(':reason', $reason);
            $selmsg->execute();
            $message = $selmsg->fetch();
            return $message['message'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }

    public function showSuccessHeadline($reason)
    {
        include '../database/connect.php';

        try {
            $selmsg = $db->prepare('SELECT headline FROM success WHERE reason =:reason;');
            $selmsg->bindValue(':reason', $reason);
            $selmsg->execute();
            $message = $selmsg->fetch();
            return $message['headline'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }

    }


}