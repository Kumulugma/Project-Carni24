<?php

class K3eJQueryDisable {

    const VERSION = '0.1a';

    function __construct() {

        add_filter('wp_enqueue_scripts', 'change_default_jquery', PHP_INT_MAX);

        function change_default_jquery() {
            wp_dequeue_script('jquery');
            wp_deregister_script('jquery');
        }

    }

}
