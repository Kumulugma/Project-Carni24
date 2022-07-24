<?php

class K3eGoogleAnalytics implements InterfaceToggler {

    const VERSION = '0.1a';

    function __construct() {
        if (is_admin()) {

            add_action('admin_menu', 'k3e_g_analytics');

            function k3e_g_analytics() {
                add_submenu_page(
                        'konfiguracja',
                        'Google Analytics', //page title
                        'Google Analytics', //menu title
                        'manage_options', //capability,
                        'g_analytics', //menu slug
                        'k3e_g_analytics_content' //callback function
                );
            }

            function k3e_g_analytics_content() {
                include plugin_dir_path(__FILE__) . '_templates/index.php';
            }

            K3eGoogleAnalytics::save();
        } else {
            K3eGoogleAnalytics::run();
        }
    }

    public static function save() {
        $save = FALSE;

        if (isset($_POST['GoogleAnalytics']['salt'])) {
            if ($_POST['GoogleAnalytics']['activate']) {
                update_option(K3E::OPTION_GOOGLE_ANALYTICS_ACTIVATE, 1);
                $save = TRUE;
            } else {
                update_option(K3E::OPTION_GOOGLE_ANALYTICS_ACTIVATE, 0);
                $save = TRUE;
            }
            if ($_POST['GoogleAnalytics']['code']) {
                $form = sanitize_text_field($_POST['GoogleAnalytics']['code']);
                update_option(K3E::OPTION_GOOGLE_ANALYTICS_CODE, ($form));
                $save = TRUE;
            }
        }

        if ($save) {
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function run() {
        if (!is_admin()) {

            add_action('wp_head', 'k3e_google_analytics_head');

            function k3e_google_analytics_head() {

                $code = get_option(K3E::OPTION_GOOGLE_ANALYTICS_CODE);

                echo "<!-- Google Tag Manager -->";
                echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':";
                echo "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],";
                echo "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=";
                echo "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);";
                echo "})(window,document,'script','dataLayer','" . $code . "');</script>";
                echo "<!-- End Google Tag Manager -->";
            }

            // Add code after opening body tag.
            add_action('wp_body_open', 'k3e_google_analytics_body');

            function k3e_google_analytics_body() {
                $code = get_option(K3E::OPTION_GOOGLE_ANALYTICS_CODE);

                echo '<!-- Google Tag Manager (noscript) -->';
                echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $code . '" ';
                echo 'height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
                echo '<!-- End Google Tag Manager (noscript) -->';
            }

        }
    }

    public static function getStatus() {
        $googleAnalyticsActivate = (get_option(K3E::OPTION_GOOGLE_ANALYTICS_ACTIVATE));
        if (!$googleAnalyticsActivate) {
            K3eSystem::setSettings(K3E::OPTION_GOOGLE_ANALYTICS_ACTIVATE, 0, true);
            $googleAnalyticsActivate = "";
        }
        return $googleAnalyticsActivate;
    }

    public static function getCode() {
        return (get_option(K3E::OPTION_GOOGLE_ANALYTICS_CODE));
    }

}
