<?php

add_action('wp_ajax_newSitemap', 'k3eNewSitemap');
add_action('wp_ajax_nopriv_newSitemap', 'k3eNewSitemap');

function k3eNewSitemap() {
    Sitemap::generate();

    $response = [
        'text' => '<a href="' . get_site_url() . '/sitemap.xml">' . get_site_url() . '/sitemap.xml</a>',
        'date' => '<small>'.date(get_option('date_format'), filemtime(get_home_path() . "sitemap.xml")) . " " . date(get_option('time_format'), filemtime(get_home_path() . "sitemap.xml"))."</small>"
    ];
    wp_send_json($response);

    wp_die();
}
