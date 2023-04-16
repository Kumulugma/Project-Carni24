<?php

class K3ePreloader implements InterfaceToggler {

    const VERSION = '0.1b';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_preloader');

            function k3e_preloader() {
                add_submenu_page(
                        'konfiguracja',
                        'Preloader', //page title
                        'Preloader', //menu title
                        'manage_options', //capability,
                        'preloader', //menu slug
                        'k3e_preloader_content' //callback function
                );
            }

            function k3e_preloader_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3ePreloader::save();
        } else {
            K3ePreloader::run();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['Preloader'])) {
            if (isset($_POST['Preloader']['activate'])) {
                $preloaderActivate = ($_POST['Preloader']['activate']);
                K3eSystem::setSettings(K3E::OPTION_PRELOADER_ACTIVATE, $preloaderActivate);
            } else {
                K3eSystem::setSettings(K3E::OPTION_PRELOADER_ACTIVATE, 0);
            }
            $save = TRUE;
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function run() {
        if (get_option(K3E::OPTION_PRELOADER_ACTIVATE)) {
            wp_enqueue_script('K3ePreloader', plugin_dir_url(__FILE__) . "_assets/K3ePreloader.js", ['jquery']);
            wp_enqueue_style('K3ePreloader', plugin_dir_url(__FILE__) . "_assets/K3ePreloader.css");

            // Add code after opening body tag.
            add_action('wp_body_open', 'k3e_preloader_body_open_code');

            function k3e_preloader_body_open_code() {
                echo '<div class="preloader-wrapper">';
                echo '<div class="preloader" class="d-flex justify-content-center">';
                echo '<div class="spinner-grow" style="width: 5rem; height: 5rem;" role="status">';
                echo '<span class="visually-hidden"></span>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

        }
    }

    public static function getStatus() {
        $preloaderActivate = (get_option(K3E::OPTION_PRELOADER_ACTIVATE));
        if (!$preloaderActivate) {
            K3eSystem::setSettings(K3E::OPTION_PRELOADER_ACTIVATE, 0, true);
            $preloaderActivate = "";
        }
        return $preloaderActivate;
    }

}
