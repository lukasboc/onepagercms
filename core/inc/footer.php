<?php
if (function_exists('do_action')) {
    do_action('opcms_admin_footer');
}
echo '
        </main>
        <footer class="footer footer-center text-sm text-base-content/60 py-3 border-t border-base-300 bg-base-100">
            <div>
                <p><a class="link" href="https://www.buymeacoffee.com/lukasboc" target="_blank">Support OPCMS</a> | Version ' . (defined('OPCMS_VERSION') ? OPCMS_VERSION : '1.2.0') . ' | Thanks for using <a class="link" href="https://onepagercms.de">OPCMS</a>!</p>
            </div>
        </footer>
    </div>
</div>';
