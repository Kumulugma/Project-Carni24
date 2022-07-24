<?php

class K3eAdminBarLogo {

    const VERSION = '0.1a';
    const NAME = 'Logo na belce admina';
    
    function __construct() {

        function k3e_admin_bar_remove_logo() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('wp-logo');
        }

        add_action('wp_before_admin_bar_render', 'k3e_admin_bar_remove_logo', 0);
    }

}
