<?php
echo '
                  <section class="' . $bgcolor . 'page-section" id="' . opcms_esc($section->getTitle()) . '" style="background-image: url(' . opcms_esc($section->getBackground()) . ')">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading text-uppercase" >' . opcms_esc($section->getTitle()) . '</h2>
          <h3 class="section-subheading text-muted">' . opcms_esc($section->getMutedTitle()) . '</h3>
        </div>
      </div>
      <div class="row text-center">';
for ($h = 0; $h < count(array_filter($section->getIcons())); $h++) {
    echo '
        <div class="col-md-4">
          <span class="fa-stack fa-4x">
            <i class="fas fa-circle fa-stack-2x text-primary"></i>
            <i class="' . opcms_esc($section->getIcons()[$h]) . ' fa-stack-1x fa-inverse"></i>
          </span>
          <h4 class="service-heading">' . opcms_esc($section->getIconHeadline()[$h]) . '</h4>
          <p class="text-muted">' . opcms_esc($section->getIconTexts()[$h]) . '</p>
        </div>
                    ';
}
echo '      </div>
    </div>
  </section>';
