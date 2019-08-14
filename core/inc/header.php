<?php
session_start();
if (!isset($_SESSION['profile'])) {
    die('Please <a href="../pages/opcms-login.php">sign in</a> first.');
} else {
    $userid = $_SESSION["profile"];
}
echo '
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand btn disabled" href="#">OnePagerCMS</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../core/home.php">Overview<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/sections.php">Sections</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/settings.php">Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/design.php">Design</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/preview.php">Preview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/additionalPages.php">Additional Pages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/account.php">Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/faq.php">FAQ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../core/logout.php">Logout</a>
            </li>
        </ul>
            <span class="navbar-text">
        Signed in as: ' . $userid . '
    </span>
    </div>
</nav>


';
?>