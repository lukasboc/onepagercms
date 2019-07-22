<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 23.07.19
 * Time: 00:52
 */

class SQLHeaderActions
{
    public function showHeader()
    {
        echo "<header class=\"masthead\">
    <div class=\"container\">
        <div class=\"intro-text\">
            <div class=\"intro-lead-in\">" . $this->getHeaderMutedTitle() . "</div>
            <div class=\"intro-heading text-uppercase\">" . $this->getHeaderTitle() . "</div>
        </div>
    </div>
</header>";
    }

    public function getHeaderMutedTitle()
    {

        include '../database/connect.php';

        try {
            $seltitle = $db->prepare("SELECT mutedtitle FROM header WHERE specialid = :specialid;");
            $seltitle->bindValue(':specialid', 0);
            $seltitle->execute();
            $mtitle = $seltitle->fetch();
            return $mtitle['mutedtitle'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getHeaderTitle()
    {
        include '../database/connect.php';

        try {
            $seltitle = $db->prepare("SELECT title FROM header WHERE specialid = :specialid;");
            $seltitle->bindValue(':specialid', 0);
            $seltitle->execute();
            $title = $seltitle->fetch();
            return $title['title'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }


    }


}