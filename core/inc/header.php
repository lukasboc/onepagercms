<?php
session_start();
if (!isset($_SESSION['profile'])) {
    die('Please <a href="../core/opcms-login.php">sign in</a> first.');
} else {
    $userid = $_SESSION["profile"];
}
$opcmsPluginNavItems = '';
if (function_exists('apply_filters')) {
    foreach (apply_filters('opcms_admin_nav_items', array()) as $opcmsNavItem) {
        if (!isset($opcmsNavItem['label'], $opcmsNavItem['href'])) {
            continue;
        }
        $opcmsPluginNavItems .= '
            <li><a href="' . htmlspecialchars($opcmsNavItem['href']) . '"><i class="fas fa-plug w-4"></i>' . htmlspecialchars($opcmsNavItem['label']) . '</a></li>';
    }
}
$opcmsCurrentPage = basename($_SERVER['PHP_SELF']);
function opcms_nav_item($file, $icon, $label)
{
    global $opcmsCurrentPage;
    $active = $opcmsCurrentPage === $file ? ' class="menu-active"' : '';
    return '
            <li><a' . $active . ' href="../core/' . $file . '"><i class="fas ' . $icon . ' w-4"></i>' . $label . '</a></li>';
}

echo '
<div class="drawer lg:drawer-open">
    <input id="opcms-drawer" type="checkbox" class="drawer-toggle">
    <div class="drawer-side">
        <label for="opcms-drawer" aria-label="Close sidebar" class="drawer-overlay"></label>
        <aside class="bg-base-100 border-r border-base-300 w-64 min-h-full flex flex-col">
            <div class="px-4 py-5">
                <a href="../core/home.php">
                    <img class="opcms-logo h-12 w-auto" src="../img/logo/logo_black.png" alt="OnePager CMS">
                </a>
            </div>
            <ul class="menu w-full px-2 gap-1 flex-1">'
    . opcms_nav_item('home.php', 'fa-home', 'Overview')
    . opcms_nav_item('sections.php', 'fa-layer-group', 'Sections') . '
            <li>
                <details' . (in_array($opcmsCurrentPage, array('settings.php', 'design.php')) ? ' open' : '') . '>
                    <summary><i class="fas fa-paint-brush w-4"></i>Customize</summary>
                    <ul>
                        <li><a' . ($opcmsCurrentPage === 'settings.php' ? ' class="menu-active"' : '') . ' href="../core/settings.php">Settings</a></li>
                        <li><a' . ($opcmsCurrentPage === 'design.php' ? ' class="menu-active"' : '') . ' href="../core/design.php">Design</a></li>
                    </ul>
                </details>
            </li>'
    . opcms_nav_item('preview.php', 'fa-eye', 'Preview')
    . opcms_nav_item('additionalPages.php', 'fa-file-alt', 'Additional Pages')
    . opcms_nav_item('extensions.php', 'fa-puzzle-piece', 'Extensions')
    . $opcmsPluginNavItems
    . opcms_nav_item('account.php', 'fa-user', 'Account')
    . opcms_nav_item('faq.php', 'fa-question-circle', 'FAQ') . '
            </ul>
        </aside>
    </div>
    <div class="drawer-content flex flex-col min-h-screen bg-base-200">
        <div class="navbar bg-base-100 border-b border-base-300 px-4 gap-2">
            <label for="opcms-drawer" aria-label="Open sidebar" class="btn btn-ghost btn-square lg:hidden">
                <i class="fas fa-bars"></i>
            </label>
            <div class="flex-1"></div>
            <label class="swap swap-rotate btn btn-ghost btn-circle" title="Toggle dark mode">
                <input type="checkbox" id="opcms-theme-toggle" aria-label="Toggle dark mode">
                <i class="fas fa-sun swap-on"></i>
                <i class="fas fa-moon swap-off"></i>
            </label>
            <span class="text-sm text-base-content/60 hidden sm:inline">Signed in as: ' . $userid . '</span>
            <a href="../core/logout.php" class="btn btn-ghost btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <script>(function () {
            var toggle = document.getElementById("opcms-theme-toggle");
            toggle.checked = document.documentElement.getAttribute("data-theme") === "dark";
            toggle.addEventListener("change", function () {
                var theme = toggle.checked ? "dark" : "light";
                document.documentElement.setAttribute("data-theme", theme);
                localStorage.setItem("opcms-admin-theme", theme);
            });
        })();</script>
        <main class="flex-1">';
?>
