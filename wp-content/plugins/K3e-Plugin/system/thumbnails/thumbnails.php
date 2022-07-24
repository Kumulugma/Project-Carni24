<?php

class K3eThumbnails {

    const VERSION = '0.1b';

    function __construct() {

        if (is_admin()) {

            add_action('admin_menu', 'k3e_thumbnails');

            function k3e_thumbnails() {
                add_submenu_page(
                        'konfiguracja',
                        'Miniaturki', //page title
                        'Miniaturki', //menu title
                        'manage_options', //capability,
                        'thumbnails', //menu slug
                        'k3e_thumbnails_content' //callback function
                );
            }

            function k3e_thumbnails_content() {
                wp_enqueue_script('K3eThumbnails', plugin_dir_url(__FILE__) . '_assets/K3eThumbnails.js', false, '1.0', 'all');
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eThumbnails::do();
            K3eThumbnails::save();
        }
        K3eThumbnails::do();
    }

    public static function save() {
        if (isset($_POST['Thumbnail'])) {
            $form = [];
            foreach ($_POST['Thumbnail'] as $thumbnail) {
                if (!empty($thumbnail['name'])) {
                    $form[$thumbnail['name']] = ['name' => $thumbnail['name'], 'width' => $thumbnail['width'], 'height' => $thumbnail['height'], 'crop' => $thumbnail['crop']];
                }
            }

            K3eSystem::setSettings(K3E::OPTION_THUMBNAIL_SIZES, serialize($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function do() {
        $thumbnails = unserialize(get_option(K3E::OPTION_THUMBNAIL_SIZES));
        if ($thumbnails) {
            foreach ($thumbnails as $thumbnail) {
                add_image_size($thumbnail['name'], $thumbnail['width'], $thumbnail['height'], $thumbnail['crop']);
            }
        }
    }

    public static function getThumbnails() {
        $thumbnails = unserialize(get_option(K3E::OPTION_THUMBNAIL_SIZES));
        if (!$thumbnails) {
            K3eSystem::setSettings(K3E::OPTION_THUMBNAIL_SIZES, serialize(K3E::DEFAULT_THUMBNAILS), true);
            $thumbnails = K3E::DEFAULT_THUMBNAILS;
        }
        return $thumbnails;
    }

}
