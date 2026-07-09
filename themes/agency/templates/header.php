<?php
echo "<header class=\"masthead\" style=\"background-image: url('" . $background . "')\">
    <div class=\"container\">
        <div class=\"intro-text\">
            <div class=\"intro-lead-in\">" . $mutedtitle . "</div>
            <h1><div class=\"intro-heading text-uppercase\">" . $title . "</div></h1>";
if ($customrow !== null && $customrow !== "") {
    echo "<div class=\"intro-custom\">" . $customrow . "</div>";
}
echo "
        </div>
    </div>
</header>";
