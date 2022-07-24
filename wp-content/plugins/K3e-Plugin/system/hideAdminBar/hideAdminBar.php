<?php

class K3eHideAdminBar {

    const VERSION = '0.1a';

    function __construct() {
        if (!is_admin()) {
            add_filter('show_admin_bar', '__return_false');
        }
    }

}
