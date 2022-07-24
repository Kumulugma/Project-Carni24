<?php
// The shortcode function
function k3e_placeholder_shortcode() {
    wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . "/_assets/bootstrap/dist/js/bootstrap.bundle.min.js", ['jquery']);
    wp_enqueue_style('bootstrap', plugin_dir_url(__FILE__) . "/_assets/bootstrap/dist/css/bootstrap.min.css");
    wp_enqueue_style('cover', plugin_dir_url(__FILE__) . "/_assets/cover.css");

    
    ob_start();
    include plugin_dir_path(__FILE__) . '_templates/placeholder.php';
    $string = ob_get_clean();
    return $string;
}

// Register shortcode
add_shortcode('K3E_PLACEHOLDER', 'k3e_placeholder_shortcode');
