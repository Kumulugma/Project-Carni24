<?php

add_action('wp_ajax_newRobotsTXT', 'k3eNewRobotsTXT');
add_action('wp_ajax_nopriv_newRobotsTXT', 'k3eNewRobotsTXT');

function k3eNewRobotsTXT() {
    Robots::generate();

    $response = [
        'text' => '<a href="' . get_site_url() . '/robots.txt">' . get_site_url() . '/robots.txt</a>',
        'date' => '<small>'.date(get_option('date_format'), filemtime(get_home_path() . "robots.txt")) . " " . date(get_option('time_format'), filemtime(get_home_path() . "robots.txt"))."</small>"
    ];
    wp_send_json($response);

    wp_die();
}
