<?php

add_action('wp_ajax_activeTypes', 'k3eActiveTypes');
add_action('wp_ajax_nopriv_activeTypes', 'k3eActiveTypes');

function k3eActiveTypes() {

    $result = Post_types::save();

    $response = [
        $result
    ];
    wp_send_json($response);

    wp_die();
}
