<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 08.06.19
 * Time: 21:54
 */

include "../database/ISectionActions.php";
include "../database/Standard.php";
include "../database/Icons.php";
include "../database/Contact.php";
include "../database/SQLSettingActions.php";

class SQLSectionActions implements ISectionActions
{

    public function getAllSections()
    {
        $secation_array = array();

        include '../database/connect.php';

        $prepare = $db->prepare('select * from sections order by position');
        $prepare->execute();
        $r = $prepare->fetchAll();

        foreach ($r as $iValue) {

            if ($iValue[1] === 'standard') {
                $selection = $this->getEntryFromStandardTable($iValue[2]);
                $superid = $this->getSuperIDForStandardEntry($selection[0]['specialid']);
                $standard = new Standard($selection[0][0], $iValue[3], $selection[0][2], $selection[0][3], $selection[0][4], $selection[0][5], $selection[0][6], $superid[0][0], $selection[0]['background']);
                $secation_array[] = $standard;
            }

            if ($iValue[1] === 'icons') {
                $selection = $this->getEntryFromIconsTable($iValue[2]);

                $iconArray = array(
                    $selection[0][6],
                    $selection[0][7],
                    $selection[0][8],
                    $selection[0][9],
                    $selection[0][10],
                    $selection[0][11],
                    $selection[0][12],
                    $selection[0][13],
                );
                $iconHeadlineArray = array(
                    $selection[0][14],
                    $selection[0][15],
                    $selection[0][16],
                    $selection[0][17],
                    $selection[0][18],
                    $selection[0][19],
                    $selection[0][20],
                    $selection[0][21],
                );

                $iconTextArray = array(
                    $selection[0][22],
                    $selection[0][23],
                    $selection[0][24],
                    $selection[0][25],
                    $selection[0][26],
                    $selection[0][27],
                    $selection[0][28],
                    $selection[0][29],
                );
                $superid = $this->getSuperIDForIconsEntry($selection[0]['specialid']);

                $icons = new Icons($selection[0][0], $iValue[3], $selection[0][2], $selection[0][3], $selection[0][4], $selection[0][5], $iconArray, $iconHeadlineArray, $iconTextArray, $superid[0]['id'], $selection[0]['background']);
                $secation_array[] = $icons;

            }

            if ($iValue[1] === 'contact') {
                $prepstandard = $db->prepare('select * from contact where specialid=:specialid');
                $prepstandard->bindValue(':specialid', $iValue[2]);
                $prepstandard->execute();
                $selection = $prepstandard->fetchAll();

                $superid = $this->getSuperIDForContactEntry($selection[0]['specialid']);

                $contact = new Contact($selection[0][0], $iValue[3], $selection[0][2], $selection[0][3], $selection[0][4], $selection[0][5], $selection[0][6], $selection[0][7], $selection[0][8], $selection[0][9], $selection[0][10], $superid[0]['id'], $selection[0]['background'], $selection[0]['receiverMail']);
                $secation_array[] = $contact;
            }
        }
        return $secation_array;
    }

    private function getEntryFromStandardTable($sid)
    {
        include '../database/connect.php';

        $prepstandard = $db->prepare('select * from standard where specialid=:specialid');
        $prepstandard->bindValue(':specialid', $sid);
        $prepstandard->execute();
        $selection = $prepstandard->fetchAll();
        return $selection;
    }

    private function getSuperIDForStandardEntry($sid)
    {
        include '../database/connect.php';

        $prepstandard = $db->prepare('select id from sections where specialid=:specialid AND type=:type');
        $prepstandard->bindValue(':specialid', $sid);
        $prepstandard->bindValue(':type', 'standard');
        $prepstandard->execute();
        $selection = $prepstandard->fetchAll();
        return $selection;
    }

    private function getSuperIDForIconsEntry($sid)
    {
        include '../database/connect.php';

        $prepstandard = $db->prepare('select id from sections where specialid=:specialid AND type=:type');
        $prepstandard->bindValue(':specialid', $sid);
        $prepstandard->bindValue(':type', 'icons');
        $prepstandard->execute();
        $selection = $prepstandard->fetchAll();
        return $selection;
    }

    private function getSuperIDForContactEntry($sid)
    {
        include '../database/connect.php';

        $prepstandard = $db->prepare('select id from sections where specialid=:specialid AND type=:type');
        $prepstandard->bindValue(':specialid', $sid);
        $prepstandard->bindValue(':type', 'contact');
        $prepstandard->execute();
        $selection = $prepstandard->fetchAll();
        return $selection;
    }

    private function getSpecialIdForIconsEntry($id)
    {
        include '../database/connect.php';

        $select = $db->prepare('select specialid from sections where id=:id AND type=:type');
        $select->bindValue(':id', $id);
        $select->bindValue(':type', 'icons');
        $select->execute();
        $selection = $select->fetchAll();
        return $selection;

    }

    private function getSpecialIdForContactEntry($id)
    {
        include '../database/connect.php';

        $select = $db->prepare('select specialid from sections where id=:id AND type=:type');
        $select->bindValue(':id', $id);
        $select->bindValue(':type', 'contact');
        $select->execute();
        $selection = $select->fetchAll();
        return $selection;

    }

    private function getSpecialIdForStandardEntry($id)
    {
        include '../database/connect.php';

        $select = $db->prepare('select specialid from sections where id=:id AND type=:type');
        $select->bindValue(':id', $id);
        $select->bindValue(':type', 'standard');
        $select->execute();
        $selection = $select->fetchAll();
        return $selection;

    }


    private function getEntryFromIconsTable($sid)
    {
        include '../database/connect.php';

        $prepstandard = $db->prepare('select * from icons where specialid=:specialid');
        $prepstandard->bindValue(':specialid', $sid);
        $prepstandard->execute();
        $selection = $prepstandard->fetchAll();
        return $selection;

    }

    public function showAllSections()
    {
        $sectionarray = $this->getAllSections();
        if (sizeof($sectionarray) > 0) {
            foreach ($sectionarray as $i => $iValue) {
                if ($i % 2 == 0) {
                    $bgcolor = '';
                } else {
                    $bgcolor = 'bg-light ';
                }
                if ($iValue->getType() === 'standard') {
                    $this->showStandardSection($i, $bgcolor, $sectionarray);
                } elseif ($iValue->getType() === 'icons') {
                    $this->showIconsSection($i, $bgcolor, $sectionarray);
                } elseif ($iValue->getType() === 'contact') {
                    $this->showContactSection($i, $bgcolor, $sectionarray);
                }
            }
        } else {
            echo 'There are no sections configured.';
        }

    }

    private function showStandardSection($i, $bgcolor, $sectionarray): void
    {
        echo '
                  <section class="' . $bgcolor . 'page-section" id="' . $sectionarray[$i]->getTitle() . '" style="background-image: url(' . $sectionarray[$i]->getBackground() . ')">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase">' . $sectionarray[$i]->getTitle() . '</h2>
          <h3 class="section-subheading text-muted">' . $sectionarray[$i]->getMutedTitle() . '</h3>
        </div>
      </div>
      <div class="row" style="display:contents">' . $sectionarray[$i]->getText() . '

      </div>
    </div>
  </section>
                ';
    }

    private function showIconsSection($i, $bgcolor, $sectionarray): void
    {
        echo '
                  <section class="' . $bgcolor . 'page-section" id="' . $sectionarray[$i]->getTitle() . '" style="background-image: url(' . $sectionarray[$i]->getBackground() . ')">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase" >' . $sectionarray[$i]->getTitle() . '</h2>
          <h3 class="section-subheading text-muted">' . $sectionarray[$i]->getMutedTitle() . '</h3>
        </div>
      </div>
      <div class="row text-center">';
        for ($h = 0; $h < count(array_filter($sectionarray[$i]->getIcons())); $h++) {
            echo '
        <div class="col-md-4">
          <span class="fa-stack fa-4x">
            <i class="fas fa-circle fa-stack-2x text-primary"></i>
            <i class="' . $sectionarray[$i]->getIcons()[$h] . ' fa-stack-1x fa-inverse"></i>
          </span>
          <h4 class="service-heading">' . $sectionarray[$i]->getIconHeadline()[$h] . '</h4>
          <p class="text-muted">' . $sectionarray[$i]->getIconTexts()[$h] . '</p>
        </div>
                    ';
        }
        echo '      </div>
    </div>
  </section>';
    }

    private function showContactSection($i, $bgcolor, $sectionarray)
    {
        $settingsActions = new SQLSettingActions();
        $namefield = '';
        $emailfield = '';
        $messagefield = '';
        $captchafield = '';

        if ($sectionarray[$i]->getName()) {
            $namefield = '
                            <div class="form-group">
                                <input class="form-control" id="name" name="Name" type="text" placeholder="Your Name *" required="required" data-validation-required-message="Please enter your name.">
                                <p class="help-block text-danger"></p>
                            </div>
                            ';
        }
        if ($sectionarray[$i]->getEmail()) {
            $emailfield = '
                            <div class="form-group">
                                <input class="form-control" id="email" name="Mail" type="email" placeholder="Your Email *" required="required" data-validation-required-message="Please enter your email address.">
                                <p class="help-block text-danger"></p>
                            </div>
                            ';
        }
        if ($sectionarray[$i]->getMessage()) {
            $messagefield = '
                           <div class="form-group">
                                <textarea class="form-control" name="Message" rows="8" id="message" placeholder="Your Message *" required="required" data-validation-required-message="Please enter a message."></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                            ';
        }
        if ($sectionarray[$i]->getCaptcha()) {
            $captchafield = '
                           <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                           <div class="g-recaptcha" data-sitekey="' . $settingsActions->getSettingValue('recaptcha_key') . '"></div>';
        }
        echo '
                  <section class="' . $bgcolor . 'page-section" id="' . $sectionarray[$i]->getTitle() . '" style="background-image: url(' . $sectionarray[$i]->getBackground() . ')">
                        <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading text-uppercase">' . $sectionarray[$i]->getTitle() . '</h2>
                <h3 class="section-subheading text-muted">' . $sectionarray[$i]->getMutedTitle() . '</h3>
                <div class="text-center mb-5">' . $sectionarray[$i]->getText() . '

      </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <form id="contactForm" name="sentMessage" action="../misc/contactform.php" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            ' . $namefield . $emailfield . '
                        </div>
                        <div class="col-md-6">
                            ' . $messagefield . '
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="contactId" value="' . $sectionarray[$i]->getId() . '">
                        <div class="col-lg-12 text-center">
                            <div class="text-center mb-1" style="width:304px; margin: 0 auto">' . $captchafield . '</div>
                            <button id="sendMessageButton" class="btn btn-primary btn-xl text-uppercase" type="submit">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </section>';
    }

    public function showNavigation()
    {
        $settingActions = new SQLSettingActions();
        $titles = $this->getAllTitles();
        $logo = $settingActions->getSettingValue('logo');
        $logoCSS = $settingActions->getSettingValue('logo_css');

        echo '
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="index.php#page-top"><img style=" ' . $logoCSS . '" src="' . $logo . '"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ml-auto">';
        foreach ($titles as $iValue) {
            echo '<li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="index.php#' . $iValue . '">' . $iValue . '</a>
                </li>';
        }
        echo '</ul>
        </div>
    </div>
</nav>';
    }


    public function getAllTitles(): array
    {
        $titleArray = Array();
        $allSections = $this->getAllSections();
        foreach ($allSections as $iValue) {
            $titleArray[] = $iValue->getTitle();
        }
        return $titleArray;
    }

    public function getSectionByID($id)
    {
        include '../database/connect.php';

        $prepstandard = $db->prepare('select * from sections where id=:id');
        $prepstandard->bindValue(':id', $id);
        $prepstandard->execute();
        $selection = $prepstandard->fetch();

        if ($selection['type'] === 'standard') {
            $getfromspecial = $db->prepare('select * from standard where specialid=:specialid');
            $getfromspecial->bindValue(':specialid', $selection['specialid']);
            $getfromspecial->execute();
            $section = $getfromspecial->fetch();

            $standard = new Standard($section['specialid'], $section['position'], 'standard', $section['title'], $section['mutedtitle'], $section['text'], $section['date'], $id, $section['background']);

            return $standard;
        }

        if ($selection['type'] === 'icons') {
            $getfromspecial = $db->prepare('select * from icons where specialid=:specialid');
            $getfromspecial->bindValue(':specialid', $selection['specialid']);
            $getfromspecial->execute();
            $section = $getfromspecial->fetch();
            $iconarray = Array();
            $iconheadlinearray = Array();
            $icontextarray = Array();
            for ($i = 6; $i < 14; $i++) {
                if ($section[$i] != null) {
                    $iconarray[] = $section[$i];
                    $iconheadlinearray[] = $section[$i + 8];
                    $icontextarray[] = $section[$i + 16];
                }
            }
            $icons = new Icons($section['specialid'], $section['position'], 'icons', $section['title'], $section['mutedtitle'], $section['date'], $iconarray, $iconheadlinearray, $icontextarray, $id, $section['background']);

            return $icons;
        }

        if ($selection['type'] === 'contact') {
            include '../database/connect.php';

            $prepstandard = $db->prepare('select * from sections where id=:id');
            $prepstandard->bindValue(':id', $id);
            $prepstandard->execute();
            $selection = $prepstandard->fetch();

            if ($selection['type'] === 'contact') {
                $getfromspecial = $db->prepare('select * from contact where specialid=:specialid');
                $getfromspecial->bindValue(':specialid', $selection['specialid']);
                $getfromspecial->execute();
                $section = $getfromspecial->fetch();

                $contact = new Contact($section['specialid'], $section['position'], 'contact', $section['title'], $section['mutedtitle'], $section['text'], $section['date'], $section['name'], $section['email'], $section['message'], $section['captcha'], $id, $section['background'], $section['receiverMail']);

                return $contact;

            }

        }
    }

    public function getSectionBySID($sid)
    {
        include '../database/connect.php';

        $getfromspecial = $db->prepare('select * from standard where specialid=:specialid');
        $getfromspecial->bindValue(':specialid', $sid);
        $getfromspecial->execute();
        $section = $getfromspecial->fetch();
        return $section;
    }

    public function addNewStandardEntry($title, $mutedtitle, $text, $background): void
    {
        include '../database/connect.php';

        $count = $db->prepare('SELECT * FROM standard ORDER BY specialid DESC LIMIT 1;');
        $count->execute();
        $number_of_rows = $count->fetch();
        $number = $number_of_rows['specialid'] + 1;

        $pos = $db->prepare('SELECT * FROM sections ORDER BY position DESC LIMIT 1;');
        $pos->execute();
        $max_pos = $pos->fetch();
        $position = $max_pos['position'] + 1;


        $insert = $db->prepare('INSERT INTO standard (`specialid`, `position`, `sectiontype`, `title`, `mutedtitle`, `text`, `date`, `background`) VALUES (:sid, :position, :sectiontype, :title, :mutedtitle, :text, :date, :background)');

        $insert->bindValue(':sid', $number);
        $insert->bindValue(':position', $position);
        $insert->bindValue(':sectiontype', 'standard');
        $insert->bindValue(':title', $title);
        $insert->bindValue(':mutedtitle', $mutedtitle);
        $insert->bindValue(':text', $text);
        $insert->bindValue(':date', date("m.d.Y"));
        $insert->bindValue(':background', $background);


        if ($insert->execute()) {
            $ncount = $db->prepare('SELECT * FROM sections ORDER BY id DESC LIMIT 1;');
            $ncount->execute();
            $tnumber_of_rows = $ncount->fetch();
            $gnumber = $tnumber_of_rows["id"] + 1;

            $maxpos = $db->prepare('SELECT * FROM sections ORDER BY position DESC LIMIT 1;');
            $maxpos->execute();
            $maximumposition = $maxpos->fetch();
            $newposition = $maximumposition["position"] + 1;

            $section = $db->prepare('INSERT INTO sections (`id`, `type`, `specialid`, `position`) VALUES (:id, :type, :specialid, :position)');

            $section->bindValue(':id', $gnumber);
            $section->bindValue(':type', 'standard');
            $section->bindValue(':specialid', $number);
            $section->bindValue(':position', $newposition);

            if ($section->execute()) {
                header('Location: ../core/sections.php');

            } else {
                echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            }
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
        }

    }

    public function addNewIconsEntry($title, $mutedtitle, $iconarray, $iconheadlinearray, $icontextarray, $background): void
    {
        include '../database/connect.php';

        $count = $db->prepare('SELECT * FROM icons ORDER BY specialid DESC LIMIT 1;');
        $count->execute();
        $number_of_rows = $count->fetch();
        $number = $number_of_rows['specialid'] + 1;

        $pos = $db->prepare('SELECT * FROM sections ORDER BY position DESC LIMIT 1;');
        $pos->execute();
        $max_pos = $pos->fetch();
        $position = $max_pos['position'] + 1;


        $insert = $db->prepare('INSERT INTO icons (`specialid`, `position`, `sectiontype`, `title`, `mutedtitle`,  `date`, `iconone`, `icontwo`, `iconthree`, `iconfour`, `iconfive`, `iconsix`, `iconseven`, `iconeight`, `iconheadlineone`, `iconheadlinetwo`, `iconheadlinethree`, `iconheadlinefour`, `iconheadlinefive`, `iconheadlinesix`, `iconheadlineseven`, `iconheadlineeight`, `icontextone`, `icontexttwo`, `icontextthree`, `icontextfour`, `icontextfive`, `icontextsix`, `icontextseven`, `icontexteight`, `background`) VALUES (:sid, :position, :sectiontype, :title, :mutedtitle, :date, :iconone, :icontwo, :iconthree, :iconfour, :iconfive, :iconsix, :iconseven, :iconeight, :iconheadlineone, :iconheadlinetwo, :iconheadlinethree, :iconheadlinefour, :iconheadlinefive, :iconheadlinesix, :iconheadlineseven, :iconheadlineeight, :icontextone, :icontexttwo, :icontextthree, :icontextfour, :icontextfive, :icontextsix, :icontextseven, :icontexteight, :background)');

        $insert->bindValue(':sid', $number);
        $insert->bindValue(':position', $position);
        $insert->bindValue(':sectiontype', 'icons');
        $insert->bindValue(':title', $title);
        $insert->bindValue(':mutedtitle', $mutedtitle);
        $insert->bindValue(':date', date('m.d.Y'));
        $insert->bindValue(':iconone', $iconarray[0]);
        $insert->bindValue(':icontwo', $iconarray[1]);
        $insert->bindValue(':iconthree', $iconarray[2]);
        $insert->bindValue(':iconfour', $iconarray[3]);
        $insert->bindValue(':iconfive', $iconarray[4]);
        $insert->bindValue(':iconsix', $iconarray[5]);
        $insert->bindValue(':iconseven', $iconarray[6]);
        $insert->bindValue(':iconeight', $iconarray[7]);

        $insert->bindValue(':iconheadlineone', $iconheadlinearray[0]);
        $insert->bindValue(':iconheadlinetwo', $iconheadlinearray[1]);
        $insert->bindValue(':iconheadlinethree', $iconheadlinearray[2]);
        $insert->bindValue(':iconheadlinefour', $iconheadlinearray[3]);
        $insert->bindValue(':iconheadlinefive', $iconheadlinearray[4]);
        $insert->bindValue(':iconheadlinesix', $iconheadlinearray[5]);
        $insert->bindValue(':iconheadlineseven', $iconheadlinearray[6]);
        $insert->bindValue(':iconheadlineeight', $iconheadlinearray[7]);

        $insert->bindValue(':icontextone', $icontextarray[0]);
        $insert->bindValue(':icontexttwo', $icontextarray[1]);
        $insert->bindValue(':icontextthree', $icontextarray[2]);
        $insert->bindValue(':icontextfour', $icontextarray[3]);
        $insert->bindValue(':icontextfive', $icontextarray[4]);
        $insert->bindValue(':icontextsix', $icontextarray[5]);
        $insert->bindValue(':icontextseven', $icontextarray[6]);
        $insert->bindValue(':icontexteight', $icontextarray[7]);

        $insert->bindValue(':background', $background);

        if ($insert->execute()) {
            $ncount = $db->prepare('SELECT * FROM sections ORDER BY id DESC LIMIT 1;');
            $ncount->execute();
            $tnumber_of_rows = $ncount->fetch();
            $gnumber = $tnumber_of_rows['id'] + 1;

            $maxpos = $db->prepare('SELECT * FROM sections ORDER BY position DESC LIMIT 1;');
            $maxpos->execute();
            $maximumposition = $maxpos->fetch();
            $newposition = $maximumposition['position'] + 1;

            $section = $db->prepare('INSERT INTO sections (`id`, `type`, `specialid`, `position`) VALUES (:id, :type, :specialid, :position)');

            $section->bindValue(':id', $gnumber);
            $section->bindValue(':type', 'icons');
            $section->bindValue(':specialid', $number);
            $section->bindValue(':position', $newposition);

            if ($section->execute()) {
                header('Location: ../core/sections.php');

            } else {
                echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            }
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
        }
    }

    public function addNewContactEntry($title, $mutedtitle, $text, $name, $email, $message, $captcha, $background, $receiverMail): void
    {
        include '../database/connect.php';

        $count = $db->prepare('SELECT * FROM contact ORDER BY specialid DESC LIMIT 1;');
        $count->execute();
        $number_of_rows = $count->fetch();
        $number = $number_of_rows['specialid'] + 1;

        $pos = $db->prepare('SELECT * FROM sections ORDER BY position DESC LIMIT 1;');
        $pos->execute();
        $max_pos = $pos->fetch();
        $position = $max_pos['position'] + 1;

        $insert = $db->prepare('INSERT INTO contact (`specialid`, `position`, `sectiontype`, `title`, `mutedtitle`, `text`, `date`, `name`, `email`, `message`, `captcha`, `background`, `receiverMail`) VALUES (:sid, :position, :sectiontype, :title, :mutedtitle, :text, :date, :name, :email, :message, :captcha, :background, :receiverMail)');

        $insert->bindValue(':sid', $number);
        $insert->bindValue(':position', $position);
        $insert->bindValue(':sectiontype', 'contact');
        $insert->bindValue(':title', $title);
        $insert->bindValue(':mutedtitle', $mutedtitle);
        $insert->bindValue(':text', $text);
        $insert->bindValue(':date', date('m.d.Y'));
        $insert->bindValue(':name', $name);
        $insert->bindValue(':email', $email);
        $insert->bindValue(':message', $message);
        $insert->bindValue(':captcha', $captcha);
        $insert->bindValue(':background', $background);
        $insert->bindValue(':receiverMail', $receiverMail);

        if ($insert->execute()) {
            $ncount = $db->prepare('SELECT * FROM sections ORDER BY id DESC LIMIT 1;');
            $ncount->execute();
            $tnumber_of_rows = $ncount->fetch();
            $gnumber = $tnumber_of_rows['id'] + 1;

            $maxpos = $db->prepare('SELECT * FROM sections ORDER BY position DESC LIMIT 1;');
            $maxpos->execute();
            $maximumposition = $maxpos->fetch();
            $newposition = $maximumposition['position'] + 1;

            $section = $db->prepare('INSERT INTO sections (`id`, `type`, `specialid`, `position`) VALUES (:id, :type, :specialid, :position)');

            $section->bindValue(':id', $gnumber);
            $section->bindValue(':type', 'contact');
            $section->bindValue(':specialid', $number);
            $section->bindValue(':position', $newposition);

            if ($section->execute()) {
                header('Location: ../core/sections.php');
            } else {
                echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            }
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
        }

    }

//Edit

    public function editStandardEntry($id, $title, $mutedtitle, $text, $background): ?bool
    {
        include '../database/connect.php';
        try {
            $sid = $this->getSpecialIdForStandardEntry($id)[0]['specialid'];
            $update = $db->prepare('UPDATE standard SET title = :title, mutedtitle = :mutedtitle, text = :text, date = :date, background = :background WHERE specialid = :sid;');

            $update->bindValue(':sid', $sid);
            $update->bindValue(':title', $title);
            $update->bindValue(':mutedtitle', $mutedtitle);
            $update->bindValue(':text', $text);
            $update->bindValue(':date', date('Y-m-d H:i:s'));
            $update->bindValue(':background', $background);

            return ($update->execute()) ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function editIconsEntry($id, $title, $mutedtitle, $iconarray, $iconheadlinearray, $icontextarray, $background): ?bool
    {
        include '../database/connect.php';

        $selection = $this->getSpecialIdForIconsEntry($id);
        $sid = $selection[0]['specialid'];

        try {
            $update = $db->prepare('UPDATE icons SET title = :title, mutedtitle = :mutedtitle, date = :date, iconone = :iconone, icontwo = :icontwo, iconthree = :iconthree, iconfour = :iconfour, iconfive = :iconfive, iconsix = :iconsix, iconseven = :iconseven, iconeight = :iconeight, iconheadlineone = :iconheadlineone, iconheadlinetwo = :iconheadlinetwo, iconheadlinethree = :iconheadlinethree, iconheadlinefour = :iconheadlinefour, iconheadlinefive = :iconheadlinefive, iconheadlinesix = :iconheadlinesix, iconheadlineseven = :iconheadlineseven, iconheadlineeight = :iconheadlineeight, icontextone = :icontextone, icontexttwo = :icontexttwo, icontextthree = :icontextthree, icontextfour = :icontextfour, icontextfive = :icontextfive, icontextsix = :icontextsix, icontextseven = :icontextseven, icontexteight = :icontexteight, background = :background  WHERE specialid = :sid;');

            $update->bindValue(':sid', $sid);
            $update->bindValue(':title', $title);
            $update->bindValue(':mutedtitle', $mutedtitle);
            $update->bindValue(':date', date('Y-m-d H:i:s'));

            $update->bindValue(':iconone', $iconarray[0]);
            $update->bindValue(':icontwo', $iconarray[1]);
            $update->bindValue(':iconthree', $iconarray[2]);
            $update->bindValue(':iconfour', $iconarray[3]);
            $update->bindValue(':iconfive', $iconarray[4]);
            $update->bindValue(':iconsix', $iconarray[5]);
            $update->bindValue(':iconseven', $iconarray[6]);
            $update->bindValue(':iconeight', $iconarray[7]);

            $update->bindValue(':iconheadlineone', $iconheadlinearray[0]);
            $update->bindValue(':iconheadlinetwo', $iconheadlinearray[1]);
            $update->bindValue(':iconheadlinethree', $iconheadlinearray[2]);
            $update->bindValue(':iconheadlinefour', $iconheadlinearray[3]);
            $update->bindValue(':iconheadlinefive', $iconheadlinearray[4]);
            $update->bindValue(':iconheadlinesix', $iconheadlinearray[5]);
            $update->bindValue(':iconheadlineseven', $iconheadlinearray[6]);
            $update->bindValue(':iconheadlineeight', $iconheadlinearray[7]);

            $update->bindValue(':icontextone', $icontextarray[0]);
            $update->bindValue(':icontexttwo', $icontextarray[1]);
            $update->bindValue(':icontextthree', $icontextarray[2]);
            $update->bindValue(':icontextfour', $icontextarray[3]);
            $update->bindValue(':icontextfive', $icontextarray[4]);
            $update->bindValue(':icontextsix', $icontextarray[5]);
            $update->bindValue(':icontextseven', $icontextarray[6]);
            $update->bindValue(':icontexteight', $icontextarray[7]);

            $update->bindValue(':background', $background);

            return ($update->execute()) ? true : false;

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    public function editContactEntry($id, $title, $mutedtitle, $text, $name, $email, $message, $captcha, $background, $receiverMail): ?bool
    {
        include '../database/connect.php';

        $selection = $this->getSpecialIdForContactEntry($id);
        $sid = $selection[0]['specialid'];

        try {
            $update = $db->prepare('UPDATE contact SET title = :title, mutedtitle = :mutedtitle, text = :text, date = :date, name = :name, email = :email, message = :message, captcha = :captcha, background = :background, receiverMail = :receiverMail WHERE specialid = :sid;');

            $update->bindValue(':sid', $sid);
            $update->bindValue(':title', $title);
            $update->bindValue(':mutedtitle', $mutedtitle);
            $update->bindValue(':text', $text);
            $update->bindValue(':date', date('Y-m-d H:i:s'));
            $update->bindValue(':name', $name);
            $update->bindValue(':email', $email);
            $update->bindValue(':message', $message);
            $update->bindValue(':captcha', $captcha);
            $update->bindValue(':background', $background);
            $update->bindValue(':receiverMail', $receiverMail);

            return ($update->execute()) ? true : false;

        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }

    // Delete
    public function deleteStandardEntry($id): void
    {
        include '../database/connect.php';

        $selection = $this->getSpecialIdForStandardEntry($id);
        $sid = $selection[0]['specialid'];

        $select = $db->prepare('DELETE FROM sections
                                   WHERE `id`=:id');

        $select->bindValue(':id', $id);

        if ($select->execute()) {
            header('Location: ../core/sections.php');
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            header('refresh:3;url=../core/sections.php');
        }

        $delete = $db->prepare('DELETE FROM standard
                                   WHERE `specialid`=:sid');

        $delete->bindValue(':sid', $sid);

        if ($delete->execute()) {
            header('Location: ../core/sections.php');
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            header('refresh:3;url=../core/sections.php');
        }

    }

    public function deleteIconsEntry($id): void
    {
        include '../database/connect.php';

        $selection = $this->getSpecialIdForIconsEntry($id);
        $sid = $selection[0]['specialid'];

        $select = $db->prepare('DELETE FROM sections
                                   WHERE `id`=:id');

        $select->bindValue(':id', $id);

        if ($select->execute()) {
            header('Location: ../core/sections.php');
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            header('refresh:3;url=../core/sections.php');
        }

        $delete = $db->prepare('DELETE FROM icons
                                   WHERE `specialid`=:sid');

        $delete->bindValue(':sid', $sid);

        if ($delete->execute()) {
            header('Location: ../core/sections.php');
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            header('refresh:3;url=../core/sections.php');
        }

    }

    public function deleteContactEntry($id): ?bool
    {
        include '../database/connect.php';

        $selection = $this->getSpecialIdForContactEntry($id);
        $sid = $selection[0]['specialid'];

        $select = $db->prepare('DELETE FROM sections
                                   WHERE `id`=:id');

        $select->bindValue(':id', $id);

        if ($select->execute()) {
            header('Location: ../core/sections.php');
        } else {
            echo '<h1>Oh oh!</h1><p>Something went wrong.</p>';
            header('refresh:3;url=../core/sections.php');
        }

        $delete = $db->prepare('DELETE FROM contact
                                   WHERE `specialid`=:sid');

        $delete->bindValue(':sid', $sid);

        if ($delete->execute()) {
            return true;
        }

        return false;

    }

    public function changePosition($id, $position): ?bool
    {
        include '../database/connect.php';
        try {
            $update = $db->prepare('UPDATE sections SET position = :position WHERE id = :id;');

            $update->bindValue(':id', $id);
            $update->bindValue(':position', $position);
            return $update->execute() ? true : false;
        } catch (Exception $exception) {
            echo 'Something went wrong: ' . $exception->getMessage();
        }
    }
}