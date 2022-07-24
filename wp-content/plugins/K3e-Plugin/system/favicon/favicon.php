<?php

class K3eFavicon implements InterfaceToggler {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_favicon');

            function k3e_favicon() {
                add_submenu_page(
                        'konfiguracja',
                        'Favicon', //page title
                        'Favicon', //menu title
                        'manage_options', //capability,
                        'favicon', //menu slug
                        'k3e_favicon_content' //callback function
                );
            }

            function k3e_favicon_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eFavicon::save();
        } else {
            K3eFavicon::run();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['Favicon']['salt'])) {
            if ($_POST['Favicon']['activate']) {
                update_option(K3E::OPTION_FAVICON_ACTIVATE, 1);
                $save = TRUE;
            } else {
                update_option(K3E::OPTION_FAVICON_ACTIVATE, 0);
                $save = TRUE;
            }

            if (isset($_FILES['FaviconPackage'])) {
                $target_dir = wp_upload_dir()['basedir'];
                $file_name = $_FILES['FaviconPackage']['name'];
                $file_tmp = $_FILES['FaviconPackage']['tmp_name'];

                move_uploaded_file($file_tmp, $target_dir . '/' . $file_name);

                $zip = new ZipArchive;
                $res = $zip->open($target_dir . '/' . $file_name);
                if ($res === TRUE) {
                    $zip->extractTo($target_dir . '/favicon');
                    $zip->close();
                }
            }
            $save = TRUE;
        }
        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function getStatus() {
        $faviconActivate = (get_option(K3E::OPTION_FAVICON_ACTIVATE));
        if (!$faviconActivate) {
            K3eSystem::setSettings(K3E::OPTION_FAVICON_ACTIVATE, 0, true);
            $faviconActivate = "";
        }
        return $faviconActivate;
    }

    public static function run() {
        if (get_option(K3E::OPTION_FAVICON_ACTIVATE)) {

            add_action('wp_head', 'k3e_favicon_head');

            function k3e_favicon_head() {

                echo '<link rel = "apple-touch-icon" sizes = "180x180" href = "/wp-content/uploads/favicon/apple-touch-icon.png">';
                echo '<link rel = "icon" type = "image/png" sizes = "32x32" href = "/wp-content/uploads/favicon/favicon-32x32.png">';
                echo '<link rel = "icon" type = "image/png" sizes = "16x16" href = "/wp-content/uploads/favicon/favicon-16x16.png">';
                echo '<link rel = "manifest" href = "/wp-content/uploads/favicon/site.webmanifest">';
                echo '<link rel = "mask-icon" href = "/wp-content/uploads/favicon/safari-pinned-tab.svg" color = "#5bbad5">';
                echo '<meta name = "msapplication-TileColor" content = "#da532c">';
                echo '<meta name = "theme-color" content = "#ffffff">';
            }

        }
    }

}
