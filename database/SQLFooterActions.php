<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 23.07.19
 * Time: 00:52
 */

class SQLFooterActions
{
    public function showFooter()
    {
        echo "
        <footer class=\"footer\">
    <div class=\"container\">
        <div class=\"row align-items-center\">
            <div class=\"col-md-4\">";
        echo ($this->getFooterEntry('copyright')) ? "<span class=\"copyright\">&copy; OnePagerCMS 2021</span>" : "";
        echo "
            </div>
            <div class=\"col-md-4\">
                <ul class=\"list-inline social-buttons\">";
        echo (strlen($this->getFooterEntry("twitter_page")) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"http://twitter.com/" . $this->getFooterEntry("twitter_page") . "\">
                            <i class=\"fab fa-twitter\" ></i>
                        </a>
                    </li>" : "";

        echo (strlen($this->getFooterEntry("facebook_page")) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"http://facebook.com/" . $this->getFooterEntry("facebook_page") . "\">
                            <i class=\"fab fa-facebook-f\" ></i>
                        </a>
                    </li>" : "";

        echo (strlen($this->getFooterEntry("linkedin_page")) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"http://linkedin.com/" . $this->getFooterEntry("linkedin_page") . "\">
                            <i class=\"fab fa-linkedin-in\" ></i>
                        </a>
                    </li>" : "";

        echo (strlen($this->getFooterEntry("custom_icon")) > 0 && strlen($this->getFooterEntry("custom_page")) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"" . $this->getFooterEntry("custom_page") . "\">
                            <i class=\"" . $this->getFooterEntry('custom_icon') . "\" ></i>
                        </a>
                    </li>" : "";

        echo "
                </ul>
            </div>
            <div class=\"col-md-4\">
                <ul class=\"list-inline quicklinks\">
                ";
        include_once "../database/SQLAdditionalPagesActions.php";
        $pagesActions = new SQLAdditionalPagesActions();
        $footerPages = $pagesActions->getAllFooterPages();

        for ($i = 0; $i < sizeof($footerPages); $i++) {
            echo "
            <li class=\"list-inline-item\">
                <a href=\"additionalpage.php?id=" . $footerPages[$i]['id'] . "\">" . $footerPages[$i]['title'] . "</a>
            </li>
            ";
        }
        echo "
                </ul>
            </div>
        </div>
    </div>";
        echo (strlen($this->getFooterEntry("custom")) > 0 && strlen($this->getFooterEntry("custom")) > 0) ? "
                    <div class='row mt-2'>
                        <div class='col text-center'><small>" . $this->getFooterEntry("custom") . "</small>
                            
                        </div>
                    </div>
                    
                    " : "";

        echo "</footer>";
    }

    public function getFooterEntry($var)
    {
        include '../database/connect.php';

        try {
            $selval = $db->prepare("SELECT $var FROM footer WHERE fid =:fid;");
            $selval->bindValue(':fid', 0);
            $selval->execute();
            $val = $selval->fetch();
            return $val[$var];

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function editFooterEntry($custom, $facebook, $twitter, $linked, $own, $copyright, $ownIcon)
    {
        include '../database/connect.php';

        try {
            $update = $db->prepare("UPDATE footer SET custom =:custom, facebook_page=:facebook, twitter_page =:twitter, linkedin_page =:linkedin, custom_page =:own, copyright =:copyright, custom_icon =:own_icon WHERE fid = :fid;");
            $update->bindValue(':fid', 0);
            $update->bindValue(':custom', $custom);
            $update->bindValue(':facebook', $facebook);
            $update->bindValue(':twitter', $twitter);
            $update->bindValue(':linkedin', $linked);
            $update->bindValue(':own', $own);
            $update->bindValue(':copyright', $copyright);
            $update->bindValue(':own_icon', $ownIcon);
            return ($update->execute()) ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

}
