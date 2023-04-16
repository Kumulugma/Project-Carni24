<?php

class K3eLazyLoader implements InterfaceToggler {

    const VERSION = '0.1a';

    function __construct() {

        if (is_admin()) {

            add_action('admin_menu', 'k3e_lazyloader');

            function k3e_lazyloader() {
                add_submenu_page(
                        'konfiguracja',
                        'LazyLoader', //page title
                        'LazyLoader', //menu title
                        'manage_options', //capability,
                        'lazyloader', //menu slug
                        'k3e_lazyloader_content' //callback function
                );
            }

            function k3e_lazyloader_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eLazyLoader::save();
        } else {
            K3eLazyLoader::run();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['LazyLoader'])) {
            if (isset($_POST['LazyLoader']['activate'])) {
                $lazyloaderActivate = ($_POST['LazyLoader']['activate']);
                K3eSystem::setSettings(K3E::OPTION_LAZYLOADER_ACTIVATE, $lazyloaderActivate);
            } else {
                K3eSystem::setSettings(K3E::OPTION_LAZYLOADER_ACTIVATE, 0);
            }
            if (isset($_POST['LazyLoader']['placeholder'])) {
                $lazyloaderPlaceholder = ($_POST['LazyLoader']['placeholder']);
                K3eSystem::setSettings(K3E::OPTION_LAZYLOADER_PLACEHOLDER, $lazyloaderPlaceholder);
            }
            $save = TRUE;
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function run() {
        if (get_option(K3E::OPTION_LAZYLOADER_ACTIVATE)) {

            wp_enqueue_script('Lazy-Load', plugin_dir_url(__FILE__) . "node_modules/lazyload/lazyload.min.js", null, true);
            wp_enqueue_script('K3eLazyLoader', plugin_dir_url(__FILE__) . "_assets/K3eLazyLoader.js", ['Lazy-Load'], null, true);
        }
    }

    public static function getStatus() {
        $lazyloaderActivate = (get_option(K3E::OPTION_LAZYLOADER_ACTIVATE));
        if (!$lazyloaderActivate) {
            K3eSystem::setSettings(K3E::OPTION_LAZYLOADER_ACTIVATE, 0, true);
            $lazyloaderActivate = "";
        }
        return $lazyloaderActivate;
    }

    public static function placeholder() {
        return (get_option(K3E::OPTION_LAZYLOADER_PLACEHOLDER));
    }
}
