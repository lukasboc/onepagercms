<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 23.07.19
 * Time: 00:52
 */

class SQLHeaderActions
{
    public function showHeader(): void
    {
        echo "<header class=\"masthead\" style=\"background-image: url('" . $this->getBackground() . "')\">
    <div class=\"container\">
        <div class=\"intro-text\">
            <div class=\"intro-lead-in\">" . $this->getHeaderMutedTitle() . "</div>
            <div class=\"intro-heading text-uppercase\">" . $this->getHeaderTitle() . "</div>";
        if ($this->getBackground() != "" || $this->getBackground() != null) {
            echo "<div class=\"intro-custom\">" . $this->getCustomRow() . "</div>";
        }
        echo "
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
            $seltitle = $db->prepare('SELECT title FROM header WHERE specialid = :specialid;');
            $seltitle->bindValue(':specialid', 0);
            $seltitle->execute();
            $title = $seltitle->fetch();
            return $title['title'];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function editHeaderEntry($mutedTitle, $title, $background, $customrow): ?bool
    {
        include '../database/connect.php';

        try {
            $update = $db->prepare('UPDATE header SET mutedtitle = :mutedtitle, title = :title, background = :background, customrow = :customrow WHERE specialid = :specialid;');
            $update->bindValue(':mutedtitle', $mutedTitle);
            $update->bindValue(':title', $title);
            $update->bindValue(':background', $background);
            $update->bindValue(':customrow', $customrow);
            $update->bindValue(':specialid', 0);
            return ($update->execute()) ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getBackground()
    {
        include '../database/connect.php';

        try {
            $seltitle = $db->prepare('SELECT background FROM header WHERE specialid = :specialid;');
            $seltitle->bindValue(':specialid', 0);
            $seltitle->execute();
            $title = $seltitle->fetch();
            return $title['background'];
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function getCustomRow()
    {
        include '../database/connect.php';

        try {
            $seltitle = $db->prepare('SELECT customrow FROM header WHERE specialid = :specialid;');
            $seltitle->bindValue(':specialid', 0);
            $seltitle->execute();
            $title = $seltitle->fetch();
            return $title['customrow'];
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

}