<?php
echo '
                  <section class="' . $bgcolor . 'page-section" id="' . $section->getTitle() . '" style="background-image: url(' . $section->getBackground() . ')">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase">' . $section->getTitle() . '</h2>
          <h3 class="section-subheading text-muted">' . $section->getMutedTitle() . '</h3>
        </div>
      </div>
      <div class="row" style="display:contents">' . $section->getText() . '

      </div>
    </div>
  </section>
                ';
