<?php

class K3eDisableComments {

    const VERSION = '0.1a';

    function __construct() {

        add_action('admin_init', 'k3e_remove_admin_comments');

        function k3e_remove_admin_comments() {
            remove_menu_page('edit-comments.php');
        }

        add_action('wp_before_admin_bar_render', 'k3e_remove_comments');

        function k3e_remove_comments() {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('comments');
        }

        add_action('admin_init', 'k3e_column_init');

        function k3e_column_init() {
            add_filter('manage_posts_columns', 'k3e_manage_columns');
            add_filter('manage_pages_columns', 'k3e_manage_columns');
        }

        function k3e_manage_columns($columns) {
            unset($columns['comments']);
            return $columns;
        }

    }

}
