<?php

class K3eLoginLogo {

    const VERSION = '0.1a';
    const NAME = 'Logo na stronie logowania';

    function __construct() {

        function k3e_custom_login() {
            ?>
            <style>
                /* Body style */
                body {
                    background: linear-gradient(0deg, #484848 1%, #777777 100%) fixed;
                }
                /* Logo style */
                .login h1 a {
                    background: url('<?= WP_PLUGIN_URL ?>/<?=K3E::PLUGIN_DIR?>/images/logo.png') 50% 50% no-repeat !important;
                }
                #language-switcher a, #backtoblog a, #nav a{ color: #fff !important; }
            </style>
            <?php

        }

        add_action('login_head', 'k3e_custom_login');
    }

}
