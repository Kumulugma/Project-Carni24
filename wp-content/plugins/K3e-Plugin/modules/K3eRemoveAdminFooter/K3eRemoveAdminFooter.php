<?php

class K3eRemoveAdminFooter {

    const VERSION = '0.1a';
    const NAME = 'Stopka - panel administracyjny';

    function __construct() {

        if (is_admin()) {

            function k3e_footer_admin() {
                echo '';
            }

            add_filter('admin_footer_text', 'k3e_footer_admin');
        }
    }

}
