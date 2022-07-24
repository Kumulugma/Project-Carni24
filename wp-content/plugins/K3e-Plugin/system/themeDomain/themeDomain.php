<?php

class K3eThemeDomain {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_theme_domain');

            function k3e_theme_domain() {
                add_submenu_page(
                        'konfiguracja',
                        'Domena', //page title
                        'Domena', //menu title
                        'manage_options', //capability,
                        'theme_domain', //menu slug
                        'k3e_theme_domain_content' //callback function
                );
            }

            function k3e_theme_domain_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eThemeDomain::do();
            K3eThemeDomain::save();
        }
    }

    public static function save() {
        if (isset($_POST['ThemeDomain'])) {
            $form = addslashes($_POST['ThemeDomain']);

            K3eSystem::setSettings(K3E::OPTION_THEME_DOMAIN, ($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function do() {
        $theme_domain = get_option(K3E::OPTION_THEME_DOMAIN);
        load_theme_textdomain($theme_domain, get_template_directory() . '/languages');
    }

    public static function getThemeDomain() {
        $theme_domain = (get_option(K3E::OPTION_THEME_DOMAIN));
        if (!$theme_domain) {
            K3eSystem::setSettings(K3E::OPTION_THEME_DOMAIN, (K3E::DEFAULT_THEME_DOMAIN), true);
            $theme_domain = K3E::DEFAULT_THEME_DOMAIN;
        }
        return $theme_domain;
    }

}
