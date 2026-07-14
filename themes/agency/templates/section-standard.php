<?php
echo '
                  <section class="' . $bgcolor . 'page-section" id="' . opcms_esc($section->getTitle()) . '" style="background-image: url(' . opcms_esc($section->getBackground()) . ')">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase">' . opcms_esc($section->getTitle()) . '</h2>
          <h3 class="section-subheading text-muted">' . opcms_esc($section->getMutedTitle()) . '</h3>
        </div>
      </div>
      <div class="row" style="display:contents">' . $section->getText() . '

      </div>
    </div>
  </section>
                ';
