<?php

class K3eThemeSupport {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_theme_support');

            function k3e_theme_support() {
                add_submenu_page(
                        'konfiguracja',
                        'Szablon', //page title
                        'Szablon', //menu title
                        'manage_options', //capability,
                        'theme_support', //menu slug
                        'k3e_theme_support_content' //callback function
                );
            }

            function k3e_theme_support_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eThemeSupport::do();
            K3eThemeSupport::save();
        }
    }

    public static function save() {
        if (isset($_POST['ThemeSupport'])) {
            $form = [];
            foreach (K3E::DEFAULT_THEME_SUPPORT as $theme_support => $value) {
                if (isset($_POST['ThemeSupport'][$theme_support])) {
                    $form[$theme_support] = ['status' => $_POST['ThemeSupport'][$theme_support], 'name' => $value['name']];
                } else {
                    $form[$theme_support] = ['status' => 0, 'name' => $value['name']];
                }
            }

            K3eSystem::setSettings(K3E::OPTION_THEME_SUPPORT, serialize($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function do() {
        $support = unserialize(get_option(K3E::OPTION_THEME_SUPPORT));
        if ($support) {
            foreach ($support as $support_name => $support_args) {
                add_theme_support($support_name);
            }
        }
    }

    public static function getThemeSupport() {
        $modules = unserialize(get_option(K3E::OPTION_THEME_SUPPORT));
        if (!$modules) {
            K3eSystem::setSettings(K3E::OPTION_THEME_SUPPORT, serialize(K3E::DEFAULT_THEME_SUPPORT), true);
            $modules = K3E::DEFAULT_THEME_SUPPORT;
        }
        return $modules;
    }

}
