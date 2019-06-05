<?php
/**
 * Created by PhpStorm.
 * User: lukasbock
 * Date: 05.06.19
 * Time: 23:00
 */
include_once "Section.php";
include_once "Icons.php";

class SectionActions
{
    private $sectionarray = Array();

    public function __construct()
    {
        $icons = Array();
        array_push($icons, "fa-shopping-cart");
        array_push($icons, "fa-laptop");
        array_push($icons, "fa-lock");

        $iconHeadlines = Array();
        array_push($iconHeadlines, "fa-shopping-cart");
        array_push($iconHeadlines, "fa-laptop");
        array_push($iconHeadlines, "fa-lock");

        $iconTexts = Array();
        array_push($iconTexts, "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.");
        array_push($iconTexts, "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.");
        array_push($iconTexts, "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.");


        $sectionone = new Section(0, 0, "standard", "Testsection", "Muted Title below Title", "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr", "05.06.2019");
        $sectiontwo = new Icons(1, 1, "icons", "Iconsection", "Muted Title below Title", "05.06.2019", $icons, $iconHeadlines, $iconTexts);
        $sectionthree = new Section(2, 2, "standard", "Testsection3", "Muted Title below Title", "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr", "06.06.2019");

        array_push($this->sectionarray, $sectionone);
        array_push($this->sectionarray, $sectiontwo);
        array_push($this->sectionarray, $sectionthree);
    }

    public function getAllSections()
    {
        if (sizeof($this->sectionarray) > 0) {
            for ($i = 0; $i < sizeof($this->sectionarray); $i++) {
                if ($i % 2 == 0) {
                    $bgcolor = "";
                } else {
                    $bgcolor = "bg-light ";
                }
                if ($this->sectionarray[$i]->getType() == "standard") {
                    echo '
                  <section class="' . $bgcolor . 'page-section" id="services">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase">' . $this->sectionarray[$i]->getTitle() . '</h2>
          <h3 class="section-subheading text-muted">' . $this->sectionarray[$i]->getMutedTitle() . '</h3>
        </div>
      </div>
      <div class="row text-center">' . $this->sectionarray[$i]->getText() . '

      </div>
    </div>
  </section>

                
                
                
                ';
                } elseif ($this->sectionarray[$i]->getType() == "icons") {
                    echo '
                      <section class="' . $bgcolor . 'page-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase">' . $this->sectionarray[$i]->getTitle() . '</h2>
          <h3 class="section-subheading text-muted">' . $this->sectionarray[$i]->getMutedTitle() . '</h3>
        </div>
      </div>
      <div class="row text-center">';
                    for ($h = 0; $h < sizeof($this->sectionarray[$i]->getIcons()); $h++) {
                        echo '
        <div class="col-md-4">
          <span class="fa-stack fa-4x">
            <i class="fas fa-circle fa-stack-2x text-primary"></i>
            <i class="fas ' . $this->sectionarray[$i]->getIcons()[$h] . ' fa-stack-1x fa-inverse"></i>
          </span>
          <h4 class="service-heading">' . $this->sectionarray[$i]->getIconHeadline()[$h] . '</h4>
          <p class="text-muted">'. $this->sectionarray[$i]->getIconTexts()[$h] . '</p>
        </div>
                    ';
                    }
                    echo '      </div>
    </div>
  </section>';
                }
            }
        } else {
            echo "There are no sections configured.";
        }
    }
}