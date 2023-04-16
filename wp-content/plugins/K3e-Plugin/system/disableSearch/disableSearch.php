<?php

class K3eDisableSearch {

    const VERSION = '0.1a';

    function __construct() {

        add_action('template_redirect', 'k3e_front_remove_search');

        function k3e_front_remove_search() {
            $homepage_id = get_option('page_on_front');
            if (is_search()) {
                wp_redirect(get_permalink($homepage_id));
            }
        }

    }

}
