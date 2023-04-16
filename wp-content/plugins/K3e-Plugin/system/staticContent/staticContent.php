<?php

class K3eStaticContent {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_static_content');

            function k3e_static_content() {

                add_menu_page(
                        __('Treść statyczna', 'k3e'), //Title
                        __('Treść statyczna', 'k3e'), //Name
                        'manage_options',
                        'static_content',
                        'k3e_static_content_content',
                        'dashicons-analytics',
                        4
                );
            }

            add_action('admin_menu', 'k3e_static_content_config');

            function k3e_static_content_config() {
                add_submenu_page(
                        'konfiguracja',
                        'Treść', //page title
                        'Treść', //menu title
                        'manage_options', //capability,
                        'static_content_config', //menu slug
                        'k3e_static_content_content_config' //callback function
                );
            }

            function k3e_static_content_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            function k3e_static_content_content_config() {
                include plugin_dir_path(__FILE__) . '_templates/config.php';
            }

            K3eStaticContent::do();
            K3eStaticContent::save();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['StaticForm'])) {
            $staticForm = ($_POST['StaticForm']);
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORM_TYPE, ($staticForm));
            $save = TRUE;
        }

        if (isset($_POST['StaticFormId'])) {
            $staticFormId = ($_POST['StaticFormId']);
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORM_ID, ($staticFormId));
            $save = TRUE;
        }

        if (isset($_POST['StaticFormsAmount'])) {
            $staticFormsAmount = ($_POST['StaticFormsAmount']);
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORM_AMOUNT, ($staticFormsAmount));
            $save = TRUE;
        }

        if (isset($_POST['StaticForms'])) {
            $staticForms = [];
            foreach ($_POST['StaticForms'] as $form) {
                $staticForms[] = ['label' => $form['label'], 'name' => $form['name'], 'type' => $form['type']];
            }
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORMS, serialize($staticForms));
            $save = TRUE;
        }

        if (isset($_POST['StaticContent'])) {
            $StaticContent = [];
            foreach ($_POST['StaticContent'] as $k => $form) {
                $StaticContent[] = [$k => $form];
            }
            K3eSystem::setSettings(K3E::OPTION_STATIC_CONTENT, serialize($StaticContent));
            $save = TRUE;
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function do() {
        add_action('admin_menu', 'change_media_label');

        function change_media_label() {
            if (K3eStaticContent::getStaticFormType() == 1) {
                global $menu;
                $menu[array_key_last($menu)][2] = K3eStaticContent::getStaticFormPage();
            }
        }

        if (is_admin()) {

            function k3e_remove_pageparent_meta_box() {
                if (isset($_GET['post']) && $_GET['post'] == get_option('page_on_front')) {
                    remove_meta_box('pageparentdiv', 'page', 'side');
                    remove_meta_box('postimagediv', 'page', 'side');
                }
            }

            add_action('do_meta_boxes', 'k3e_remove_pageparent_meta_box');

            function disable_block_editor_for_static($use_block_editor, $post) {

                $home_page_id = get_option('page_on_front');
                $excluded_ids = array($home_page_id);
                if (in_array($post->ID, $excluded_ids)) {
                    return false;
                }
                return $use_block_editor;
            }

            add_filter('use_block_editor_for_post', 'disable_block_editor_for_static', 10, 2);
        }
    }

    public static function getStaticFormID() {
        $static_form_ID = (get_option(K3E::OPTION_STATIC_FORM_ID));
        if (!$static_form_ID) {
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORM_ID, 0, true);
            $static_form_ID = 0;
        }
        return $static_form_ID;
    }

    public static function getStaticFormType() {
        $static_form_type = (get_option(K3E::OPTION_STATIC_FORM_TYPE));
        if (!$static_form_type) {
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORM_TYPE, 0, true);
            $static_form_type = "";
        }
        return $static_form_type;
    }

    public static function getStaticFormPage() {
        $static_form_link = (get_option(K3E::OPTION_STATIC_FORM_ID));
        if ($static_form_link) {
            $static_form_link = "/wp-admin/post.php?post=" . $static_form_link . "&action=edit";
        } else {
            $static_form_link = '/wp-admin/admin.php?page=static_content_config';
        }
        return $static_form_link;
    }

    public static function getStaticFormsAmount() {
        $static_forms_amount = (get_option(K3E::OPTION_STATIC_FORM_AMOUNT));
        if (!$static_forms_amount) {
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORM_AMOUNT, 0, true);
            $static_forms_amount = "";
        }
        return $static_forms_amount;
    }

    public static function getStaticForms() {
        $static_forms = unserialize(get_option(K3E::OPTION_STATIC_FORMS));
        if (!$static_forms) {
            K3eSystem::setSettings(K3E::OPTION_STATIC_FORMS, serialize([]), true);
            $static_forms = "";
        }
        return $static_forms;
    }

    public static function getStaticContent() {
        $static_content = unserialize(get_option(K3E::OPTION_STATIC_CONTENT));
        if (!$static_content) {
            K3eSystem::setSettings(K3E::OPTION_STATIC_CONTENT, serialize([]), true);
            $static_content = [];
        }
        return $static_content;
    }

}
