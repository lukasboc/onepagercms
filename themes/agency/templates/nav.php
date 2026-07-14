<?php
echo '
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="index.php#page-top"><img alt="Logo" title="' . opcms_esc($title) . '" style=" ' . opcms_esc($logoCSS) . '" src="' . opcms_esc($logo) . '"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ml-auto">';
foreach ($titles as $iValue) {
    echo '<li class="nav-item">
                    <a class="nav-link js-scroll-trigger" href="index.php#' . opcms_esc($iValue) . '">' . opcms_esc($iValue) . '</a>
                </li>';
}
echo '</ul>
        </div>
    </div>
</nav>';
