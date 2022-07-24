<?php

class K3eRegisterMenu {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_register_menu');

            function k3e_register_menu() {
                add_submenu_page(
                        'konfiguracja',
                        'Zarejestruj menu', //page title
                        'Zarejestruj menu', //menu title
                        'manage_options', //capability,
                        'register_menu', //menu slug
                        'k3e_register_menu_content' //callback function
                );
            }

            function k3e_register_menu_content() {
                wp_enqueue_script('K3eRegisterManu', plugin_dir_url(__FILE__) . '_assets/K3eRegisterMenu.js', false, '1.0', 'all');
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eRegisterMenu::do();
            K3eRegisterMenu::save();
        }
    }

    public static function save() {
        if (isset($_POST['RegisterMenu'])) {
            $form = [];
            foreach ($_POST['RegisterMenu'] as $register_menu) {
                if (!empty($register_menu['slug'])) {
                    $form[$register_menu['slug']] = ['name' => $register_menu['name'], 'slug' => $register_menu['slug']];
                }
            }

            K3eSystem::setSettings(K3E::OPTION_REGISTER_MENU, serialize($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function do() {
        $register_menu = unserialize(get_option(K3E::OPTION_REGISTER_MENU));
        if ($register_menu) {
            foreach ($register_menu as $menu) {
                register_nav_menus(array($menu['slug'] => $menu['name']));
            }
        }
    }

    public static function getLoadMenus() {
        $register_menu = unserialize(get_option(K3E::OPTION_REGISTER_MENU));
        if (!$register_menu) {
            K3eSystem::setSettings(K3E::OPTION_REGISTER_MENU, serialize(K3E::DEFAULT_MENUS), true);
            $register_menu = K3E::DEFAULT_MENUS;
        }
        return $register_menu;
    }

}
