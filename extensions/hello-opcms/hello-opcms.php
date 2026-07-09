<?php
/*
 * Sample plugin for OnePagerCMS. Demonstrates the hook API:
 * admin head injection, an admin nav item, an own admin page
 * and a frontend marker.
 */

add_action('opcms_admin_head', function () {
    echo "\n<!-- hello-opcms plugin active -->";
});

add_filter('opcms_admin_nav_items', function ($items) {
    $items[] = array('label' => 'Hello OPCMS', 'href' => '../core/extension.php?page=hello-opcms');
    return $items;
});

add_filter('opcms_admin_pages', function ($pages) {
    $pages['hello-opcms'] = array(
        'title' => 'Hello OPCMS',
        'render' => function () {
            echo '<p>This page is rendered by the <strong>hello-opcms</strong> sample plugin.</p>';
            echo '<p>OnePagerCMS version: ' . htmlspecialchars(OPCMS_VERSION) . '</p>';
        },
    );
    return $pages;
});

add_action('opcms_body_end', function () {
    echo "\n<!-- hello-opcms frontend marker -->";
});
