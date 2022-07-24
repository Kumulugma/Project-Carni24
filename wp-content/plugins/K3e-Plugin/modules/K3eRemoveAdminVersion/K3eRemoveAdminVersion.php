<?php

class K3eRemoveAdminVersion {

    const VERSION = '0.1a';
    const NAME = 'Wersja - panel administracyjny';

    function __construct() {

        if (is_admin()) {

            function k3e_admin_version() {
                remove_filter('update_footer', 'core_update_footer');
            }

            add_action('admin_menu', 'k3e_admin_version');
        }
    }

}
