<?php

class K3eAutoupdate {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {
            add_filter('auto_update_plugin', '__return_true');
        }
    }

}
