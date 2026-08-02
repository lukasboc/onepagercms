<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 21.07.19
 * Time: 01:11
 */

class SQLErrorActions
{
    /**
     * Lazily inserts error messages added after 1.2.0. The shipped
     * SQLiteDatabase.db and DBs created by older versions don't contain them,
     * and update.php must stay optional for 1.2.1 (no required migration).
     */
    public function ensureMessages(): void
    {
        include '../database/connect.php';

        try {
            $messages = array(
                array(17, 'csrf', 'Security check failed', 'Your session could not be verified (invalid or missing security token). Please reload the page and try again.'),
            );

            // guard by reason, not only id: upgraded installs may already have
            // the row from update.php
            $insert = $db->prepare('INSERT INTO error (`id`, `reason`, `headline`, `message`)
                SELECT :id, :reason, :headline, :message
                WHERE NOT EXISTS (SELECT 1 FROM error WHERE reason = :reason OR id = :id)');
            foreach ($messages as $row) {
                $insert->bindValue(':id', $row[0]);
                $insert->bindValue(':reason', $row[1]);
                $insert->bindValue(':headline', $row[2]);
                $insert->bindValue(':message', $row[3]);
                $insert->execute();
            }
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

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
            error_log('OPCMS DB error: ' . $exception->getMessage());
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
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }

    }


}