<?php
echo "
        <footer class=\"footer\">
    <div class=\"container\">
        <div class=\"row align-items-center\">
            <div class=\"col-md-4\">";
echo ($copyright) ? "<span class=\"copyright\">&copy; OnePagerCMS 2019-".date("Y")."</span>" : "";
echo "
            </div>
            <div class=\"col-md-4\">
                <ul class=\"list-inline social-buttons\">";
echo (strlen($twitterPage) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"http://twitter.com/" . $twitterPage . "\">
                            <i class=\"fab fa-twitter\" ></i>
                        </a>
                    </li>" : "";

echo (strlen($facebookPage) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"http://facebook.com/" . $facebookPage . "\">
                            <i class=\"fab fa-facebook-f\" ></i>
                        </a>
                    </li>" : "";

echo (strlen($linkedinPage) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"http://linkedin.com/" . $linkedinPage . "\">
                            <i class=\"fab fa-linkedin-in\" ></i>
                        </a>
                    </li>" : "";

echo (strlen($customIcon) > 0 && strlen($customPage) > 0) ? "
                    <li class=\"list-inline-item\">
                        <a target='_blank' href=\"" . $customPage . "\">
                            <i class=\"" . $customIcon . "\" ></i>
                        </a>
                    </li>" : "";

echo "
                </ul>
            </div>
            <div class=\"col-md-4\">
                <ul class=\"list-inline quicklinks\">
                ";
for ($i = 0; $i < count($footerPages); $i++) {
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
echo (strlen($custom) > 0 && strlen($custom) > 0) ? "
                    <div class='row mt-2'>
                        <div class='col text-center'><small>" . $custom . "</small>
                            
                        </div>
                    </div>
                    
                    " : "";

echo "</footer>";
