<?php

class K3eRemoveMenus {

    const VERSION = '0.1a';
    const NAME = 'Menu w panelu administracyjnym';

    function __construct() {

        if (is_admin()) {

            function remove_menus() {
                $menus = unserialize(get_option(K3E::OPTION_HIDE_MENU));
                if ($menus) {
                    foreach ($menus as $menu) {
                        remove_menu_page($menu);
                    }
                }
            }

            add_action('admin_menu', 'remove_menus');

            add_action('admin_menu', 'k3e_hide_menu');

            function k3e_hide_menu() {
                add_submenu_page(
                        'konfiguracja',
                        'Ukryj menu', //page title
                        'Ukryj menu', //menu title
                        'manage_options', //capability,
                        'hide_menu', //menu slug
                        'k3e_hide_menu_content' //callback function
                );
            }

            function k3e_hide_menu_content() {

                wp_enqueue_script('K3eRemoveMenus', plugin_dir_url(__FILE__) . '_assets/K3eRemoveMenus.js', false, '1.0', 'all');
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eRemoveMenus::save();
        }
    }

    public static function save() {
        if (isset($_POST['HideMenu'])) {
            $form = [];
            foreach ($_POST['HideMenu'] as $value) {
                if (!empty($value)) {
                    $form[] = $value;
                }
            }

            K3eSystem::setSettings(K3E::OPTION_HIDE_MENU, serialize($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function hide_menus() {
        $modules = unserialize(get_option(K3E::OPTION_HIDE_MENU));
        if (!$modules) {
            $modules = [];
        }
        return $modules;
    }

}
