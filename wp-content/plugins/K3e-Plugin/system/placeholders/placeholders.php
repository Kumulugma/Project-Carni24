<?php

class K3ePlaceholder implements InterfaceToggler {

    const VERSION = '0.1a';

    function __construct() {

        if (is_admin()) {

            add_action('admin_menu', 'k3e_placeholder');

            function k3e_placeholder() {
                add_submenu_page(
                        'konfiguracja',
                        'Zaślepka', //page title
                        'Zaślepka', //menu title
                        'manage_options', //capability,
                        'placeholder', //menu slug
                        'k3e_placeholder_content' //callback function
                );
            }

            function k3e_placeholder_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3ePlaceholder::save();
        } else {
            K3ePlaceholder::run();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['Placeholder'])) {
            if (isset($_POST['Placeholder']['name'])) {
                $placeholderName = html_entity_decode($_POST['Placeholder']['name']);
                K3eSystem::setSettings(K3E::OPTION_PLACEHOLDER_NAME, serialize($placeholderName));
            }
            if (isset($_POST['Placeholder']['amount'])) {
                $placeholderAmount = intval($_POST['Placeholder']['amount']);
                K3eSystem::setSettings(K3E::OPTION_PLACEHOLDER_AMOUNT, serialize($placeholderAmount));
            }
            if (isset($_POST['Placeholder']['activate'])) {
                $placeholderActivate = ($_POST['Placeholder']['activate']);
                K3eSystem::setSettings(K3E::OPTION_PLACEHOLDER_ACTIVATE, $placeholderActivate);
            } else {
                K3eSystem::setSettings(K3E::OPTION_PLACEHOLDER_ACTIVATE, 0);
            }
            $save = TRUE;
        }

        if (isset($_POST['Placeholders'])) {
            $form = [];
            foreach ($_POST['Placeholders'] as $placeholders => $value) {
                $form[$placeholders] = ['headling' => html_entity_decode($value['headling']), 'content' => html_entity_decode($value['content']), 'active' => intval($value['active'])];
            }

            K3eSystem::setSettings(K3E::OPTION_PLACEHOLDERS, serialize($form));
            $save = TRUE;
        }
        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function run() {
        if (get_option(K3E::OPTION_PLACEHOLDER_ACTIVATE)) {

            add_filter('theme_page_templates', 'k3e_add_page_template_to_dropdown');

            function k3e_add_page_template_to_dropdown($templates) {
                $templates[plugin_dir_path(__FILE__) . '_templates/page-placeholder.php'] = __('Zaślepka', 'k3e');

                return $templates;
            }

            add_filter('template_include', 'k3e_change_page_template', 99);

            function k3e_change_page_template($template) {
                if (is_page()) {
                    $meta = get_post_meta(get_the_ID());

                    if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {
                        $template = $meta['_wp_page_template'][0];
                    }
                }

                return $template;
            }

            require_once 'shortcodes/K3E_PLACEHOLDER.php';
        }
    }

    public static function getPlaceholders() {
        $placeholders = unserialize(get_option(K3E::OPTION_PLACEHOLDERS));
        if (!$placeholders) {
            K3eSystem::setSettings(K3E::OPTION_PLACEHOLDERS, serialize([0 => ['headling' => '', 'content' => '', 'active' => 0]]), true);
            $placeholders = [0 => ['headling' => '', 'content' => '', 'active' => 0]];
        }
        return $placeholders;
    }

    public static function getAmount() {
        $placeholdersAmount = unserialize(get_option(K3E::OPTION_PLACEHOLDER_AMOUNT));
        if (!$placeholdersAmount) {
            K3eSystem::setSettings(K3E::OPTION_PLACEHOLDER_AMOUNT, serialize(""), true);
            $placeholdersAmount = "";
        }
        return $placeholdersAmount;
    }

    public static function getName() {
        $placeholderName = unserialize(get_option(K3E::OPTION_PLACEHOLDER_NAME));
        if (!$placeholderName) {
            K3eSystem::setSettings(K3E::OPTION_PLACEHOLDER_NAME, serialize(""), true);
            $placeholderName = "";
        }
        return $placeholderName;
    }

    public static function getStatus() {
        $placeholderActivate = (get_option(K3E::OPTION_PLACEHOLDER_ACTIVATE));
        if (!$placeholderActivate) {
            K3eSystem::setSettings(K3E::OPTION_PLACEHOLDER_ACTIVATE, 0, true);
            $placeholderActivate = "";
        }
        return $placeholderActivate;
    }

}
