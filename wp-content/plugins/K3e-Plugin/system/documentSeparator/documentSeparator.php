<?php

class K3eDocumentSeparator {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_document_separator');

            function k3e_document_separator() {
                add_submenu_page(
                        'konfiguracja',
                        'Separator', //page title
                        'Separator', //menu title
                        'manage_options', //capability,
                        'document_separator', //menu slug
                        'k3e_document_separator_content' //callback function
                );
            }

            function k3e_document_separator_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eDocumentSeparator::save();
        }
    }

    public static function save() {
        if (isset($_POST['DocumentSeparator'])) {
            $form = addslashes($_POST['DocumentSeparator']);

            K3eSystem::setSettings(K3E::OPTION_DOCUMENT_SEPARATOR, ($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function do() {
        add_filter('wp_title', 'customize_title_tag', 10, 3);

        function customize_title_tag($title, $sep, $seplocation) {
            $title = str_replace(':', get_option(K3E::OPTION_DOCUMENT_SEPARATOR), $title);
            return $title;
        }

    }

    public static function getSeparator() {
        $document_separator = (get_option(K3E::OPTION_DOCUMENT_SEPARATOR));
        if (!$document_separator) {
            K3eSystem::setSettings(K3E::OPTION_DOCUMENT_SEPARATOR, (K3E::DEFAULT_DOCUMENT_SEPARATOR), true);
            $document_separator = K3E::DEFAULT_DOCUMENT_SEPARATOR;
        }
        return $document_separator;
    }

}
