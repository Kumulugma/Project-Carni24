<?php

class initSystem {

    const PLUGIN_NAME = "K3e - System";
    const PLUGIN_SLUG = "k3e_system";
    const PLUGIN_BOOTSTRAP = 'k3e-system';
    const PLUGIN_DIR = 'k3e-system';
    const PLUGIN_URL = ABSPATH . 'wp-content/plugins/' . PLUGIN_DIR . '/';
    const PLUGIN_PAGE = 'konfiguracja';
    const PLUGIN_ICON = 'dashicons-schedule';
    const DEFAULT_MODULES = [
        'autoupdate' => true,
        'thumbnails' => true,
        'hide_admin_bar' => true,
        'hide_emojis' => true
    ];

    public static function init() {
        
    }

    public static function run() {

        add_action('admin_menu', initSystem::PLUGIN_SLUG . '_menu');

        function k3e_system_menu() {
            add_menu_page(
                    __('Konfiguracja', 'k3e'), //Title
                    __('Konfiguracja', 'k3e'), //Name
                    'manage_options',
                    initSystem::PLUGIN_PAGE,
                    initSystem::PLUGIN_SLUG . '_content',
                    initSystem::PLUGIN_ICON,
                    3
            );

            /* Dostępne pozycje

              2 – Dashboard
              4 – Separator
              5 – Posts
              10 – Media
              15 – Links
              20 – Pages
              25 – Comments
              59 – Separator
              60 – Appearance
              65 – Plugins
              70 – Users
              75 – Tools
              80 – Settings
              99 – Separator

             */
        }

        function k3e_system_content() {
            K3E::renderView('templates/index');
        }

    }

    public static function save() {
        if (isset($_POST['Form'])) {
            $form = [];
            foreach (initSystem::DEFAULT_MODULES as $module => $value) {
                if (isset($_POST['Form'][$module])) {
                    $form[$module] = $_POST['Form'][$module];
                } else {
                    $form[$module] = '0';
                }
            }
            K3E::setSettings('k3e_system_modules', serialize($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function modules() {
        $modules = unserialize(get_option('k3e_system_modules'));
        if (!$modules) {
            K3E::setSettings('k3e_system_modules', serialize(initSystem::DEFAULT_MODULES), true);
            $modules = initSystem::DEFAULT_MODULES;
        }
        return $modules;
    }

}
