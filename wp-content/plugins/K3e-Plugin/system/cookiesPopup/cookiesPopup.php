<?php

class K3eCookiesPopup implements InterfaceToggler {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_cookies_popup');

            function k3e_cookies_popup() {
                add_submenu_page(
                        'konfiguracja',
                        'Cookies', //page title
                        'Cookies', //menu title
                        'manage_options', //capability,
                        'cookies_popup', //menu slug
                        'k3e_cookies_popup_content' //callback function
                );
            }

            function k3e_cookies_popup_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eCookiesPopup::save();
        } else {
            K3eCookiesPopup::run();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['CookiesPopup']['salt'])) {
            if ($_POST['CookiesPopup']['activate']) {
                update_option(K3E::OPTION_COOKIES_POPUP_ACTIVATE, 1);
                $save = TRUE;
            } else {
                update_option(K3E::OPTION_COOKIES_POPUP_ACTIVATE, 0);
                $save = TRUE;
            }
            if ($_POST['CookiesPopup']['content']) {
                $form = sanitize_text_field($_POST['CookiesPopup']['content']);
                update_option(K3E::OPTION_COOKIES_POPUP_CONTENT, ($form));
                $save = TRUE;
            }
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function getStatus() {
        $googleAnalyticsActivate = (get_option(K3E::OPTION_COOKIES_POPUP_ACTIVATE));
        if (!$googleAnalyticsActivate) {
            K3eSystem::setSettings(K3E::OPTION_COOKIES_POPUP_ACTIVATE, 0, true);
            $googleAnalyticsActivate = "";
        }
        return $googleAnalyticsActivate;
    }

    public static function getContent() {
        return (get_option(K3E::OPTION_COOKIES_POPUP_CONTENT));
    }

    public static function run() {
        if (!is_admin()) {
            wp_enqueue_script('K3eCookiesPopup', plugin_dir_url(__FILE__) . "_assets/K3eCookiesPopup.js", ['jquery']);
            wp_enqueue_style('K3eCookiesPopup', plugin_dir_url(__FILE__) . "_assets/K3eCookiesPopup.css");

            add_action('wp_footer', 'k3e_action_cookies_popup');

            function k3e_action_cookies_popup() {
                $content = get_option(K3E::OPTION_COOKIES_POPUP_CONTENT);

                echo '<div id="cookie-consent" style="display: none;">';
                echo '<span>' . $content . '</span>';
                echo '<div class="mt-2 d-flex align-items-center justify-content-center g-2">';
                echo '<button class="allow-button" onclick="acceptCookieConsent();">' . __('Rozumiem', 'k3e') . '</button>';
                echo '</div>';
                echo '</div>';
            }

        }
    }

}
