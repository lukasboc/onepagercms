<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 25.08.19
 * Time: 17:59
 */

class SQLContactActions
{
    public function getReceiverMail($id)
    {
        include '../database/connect.php';

        try {
            $seltitle = $db->prepare('SELECT receiverMail FROM contact WHERE specialid = :specialid;');
            $seltitle->bindValue(':specialid', $id);
            $seltitle->execute();
            $title = $seltitle->fetch();
            return $title['receiverMail'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getContactConfig($id): ?array
    {
        include '../database/connect.php';

        try {
            $select = $db->prepare('SELECT name, email, message, receiverMail FROM contact WHERE specialid = :specialid;');
            $select->bindValue(':specialid', $id);
            $select->execute();
            $config = $select->fetch();
            return ($config === false) ? null : $config;

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
            return null;
        }
    }

}