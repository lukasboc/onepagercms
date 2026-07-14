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
        if (function_exists('opcms_theme') && opcms_theme()->hasTemplate('header')) {
            opcms_theme()->render('header', array(
                'background' => $this->getBackground(),
                'mutedtitle' => $this->getHeaderMutedTitle(),
                'title' => $this->getHeaderTitle(),
                'customrow' => $this->getCustomRow(),
            ));
            return;
        }

        echo "<header class=\"masthead\" style=\"background-image: url('" . $this->getBackground() . "')\">
    <div class=\"container\">
        <div class=\"intro-text\">
            <div class=\"intro-lead-in\">" . $this->getHeaderMutedTitle() . "</div>
            <h1><div class=\"intro-heading text-uppercase\">" . $this->getHeaderTitle() . "</div></h1>";
        if ($this->getCustomRow() !== null && $this->getCustomRow() !== "") {
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
            error_log('OPCMS DB error: ' . $exception->getMessage());
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
            error_log('OPCMS DB error: ' . $exception->getMessage());
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
            error_log('OPCMS DB error: ' . $exception->getMessage());
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
            error_log('OPCMS DB error: ' . $exception->getMessage());
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
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

}