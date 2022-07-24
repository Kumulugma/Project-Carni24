<?php

class K3eRemoveVersion {

    const VERSION = '0.1a';
    const NAME = 'Wersja WP';
    
    function __construct() {

        // remove version from head
        remove_action('wp_head', 'wp_generator');

        // remove version from rss
        add_filter('the_generator', '__return_empty_string');

        // remove version from scripts and styles
        function collectiveray_remove_version_scripts_styles($src) {
            if (strpos($src, 'ver=')) {
                $src = remove_query_arg('ver', $src);
            }
            return $src;
        }

        add_filter('style_loader_src', 'collectiveray_remove_version_scripts_styles', 9999);
        add_filter('script_loader_src', 'collectiveray_remove_version_scripts_styles', 9999);
    }

}
