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
            $seltitle = $db->prepare("SELECT receiverMail FROM contact WHERE specialid = :specialid;");
            $seltitle->bindValue(':specialid', $id);
            $seltitle->execute();
            $title = $seltitle->fetch();
            return $title['receiverMail'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

}