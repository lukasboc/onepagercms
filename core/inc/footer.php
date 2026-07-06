<?php
if (function_exists('do_action')) {
    do_action('opcms_admin_footer');
}
echo '
<footer class="footer">
    <div class="footer-backend text-center py-3">
        <a href="https://www.buymeacoffee.com/lukasboc" target="_blank">Support OPCMS</a> | Version 1.1.0 | Thanks for using <a href="https://onepagercms.de">OPCMS</a>!
    </div>
</footer>';