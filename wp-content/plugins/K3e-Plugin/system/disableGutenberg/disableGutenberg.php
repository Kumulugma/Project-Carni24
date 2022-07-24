<?php

class K3eDisableGutenberg {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_disable_gutenberg');

            function k3e_disable_gutenberg() {
                add_submenu_page(
                        'konfiguracja',
                        'Gutenberg', //page title
                        'Gutenberg', //menu title
                        'manage_options', //capability,
                        'disable_gutenberg', //menu slug
                        'k3e_disable_gutenberg_content' //callback function
                );
            }

            function k3e_disable_gutenberg_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eDisableGutenberg::save();
            K3eDisableGutenberg::do();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['DisableGutenberg']['salt'])) {
            $form = [];
            foreach ($_POST['DisableGutenberg'] as $key => $DisableGutenberg) {
                if ($key != 'salt') {
                    $form[] = sanitize_text_field($DisableGutenberg);
                }
            }

            update_option(K3E::OPTION_DISABLE_GUTENBERG_POSTS, serialize($form));
            $save = TRUE;
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function do() {
        if (get_option(K3E::OPTION_DISABLE_GUTENBERG_POSTS) != 'a:0:{}') {
            add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);

            function prefix_disable_gutenberg($current_status, $post_type) {
                // Use your post type key instead of 'product'
                $posts = unserialize(get_option(K3E::OPTION_DISABLE_GUTENBERG_POSTS));
                foreach ($posts as $post) {
                    if ($post_type === $post) {
                        return false;
                    }
                }
                return $current_status;
            }

        }
    }

    public static function getDisabled() {
        $option = unserialize(get_option(K3E::OPTION_DISABLE_GUTENBERG_POSTS));
        $disableGutengerg = is_array($option) ? $option : [];
        return $disableGutengerg;
    }

}
