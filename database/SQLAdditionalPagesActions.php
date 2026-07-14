<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 05.08.19
 * Time: 16:41
 */

class SQLAdditionalPagesActions
{
    public function getAllAdditionalPages()
    {
        include '../database/connect.php';

        try {
            $pages = $db->prepare('select * from additionalPages ORDER BY title');
            $pages->execute();
            $all = $pages->fetchAll();
            return $all;
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

    public function getAllFooterPages()
    {
        include '../database/connect.php';

        try {
            $pages = $db->prepare('select * from additionalPages where showInFooter=:showInFooter ORDER BY title');
            $pages->bindValue(':showInFooter', 'on');
            $pages->execute();
            $all = $pages->fetchAll();
            return $all;
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }

    }

    public function getPagesEntry($var, $id)
    {
        include '../database/connect.php';

        // $var is interpolated into the SQL as a column name, so it must be
        // constrained to a known-good whitelist (never a bind parameter target).
        $allowedColumns = array('id', 'title', 'content', 'showInFooter');
        if (!in_array($var, $allowedColumns, true)) {
            return null;
        }

        try {
            $selval = $db->prepare("SELECT $var FROM additionalPages WHERE id =:id;");
            $selval->bindValue(':id', $id);
            $selval->execute();
            $val = $selval->fetch();
            return $val[$var];

        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

    public function editPageEntry($title, $content, $showInFooter, $id)
    {
        include '../database/connect.php';

        try {
            $update = $db->prepare("UPDATE additionalPages SET title =:title, content=:content, showInFooter =:showInFooter WHERE id = :id;");
            $update->bindValue(':id', $id);
            $update->bindValue(':title', $title);
            $update->bindValue(':content', $content);
            $update->bindValue(':showInFooter', $showInFooter);
            return ($update->execute()) ? true : false;
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

    public function deletePageEntry($id)
    {
        include '../database/connect.php';

        try {
            $delete = $db->prepare('DELETE FROM additionalPages WHERE id=:id;');
            $delete->bindValue(':id', $id);
            return ($delete->execute()) ? true : false;
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

    public function newPageEntry($title, $content, $showInFooter)
    {
        include '../database/connect.php';

        try {
            $count = $db->prepare('SELECT * FROM additionalPages ORDER BY id DESC LIMIT 1;');
            $count->execute();
            $number_of_rows = $count->fetch();
            $number = $number_of_rows['id'] + 1;

            $insert = $db->prepare('INSERT INTO additionalPages (id, title, content, showInFooter) VALUES (:id, :title, :content, :showInFooter)');
            $insert->bindValue(':id', $number);
            $insert->bindValue(':title', $title);
            $insert->bindValue(':content', $content);
            $insert->bindValue(':showInFooter', $showInFooter);

            return ($insert->execute()) ? true : false;

        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }


    }
}