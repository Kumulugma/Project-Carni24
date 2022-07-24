<?php

if (!defined('ABSPATH'))
    exit;

/**
 *
 */
class K3eSystem {

    public static function init() {
        add_action('admin_menu', K3E::PLUGIN_SLUG . '_menu');

        function k3e_plugin_menu() {
            add_menu_page(
                    __('Konfiguracja', 'k3e'), //Title
                    __('Konfiguracja', 'k3e'), //Name
                    'manage_options',
                    K3E::PLUGIN_PAGE,
                    K3E::PLUGIN_SLUG . '_content',
                    K3E::PLUGIN_ICON,
                    3
            );

            /* Dostępne pozycje

              2 – Dashboard
              4 – Separator
              5 – Posts
              10 – Media
              15 – Links
              20 – Pages
              25 – Comments
              59 – Separator
              60 – Appearance
              65 – Plugins
              70 – Users
              75 – Tools
              80 – Settings
              99 – Separator

             */

            add_submenu_page(
                    'konfiguracja',
                    'Statystyki', //page title
                    'Statystyki', //menu title
                    'manage_options', //capability,
                    'stats', //menu slug
                    'k3e_stats_content' //callback function
            );

            add_submenu_page(
                    'konfiguracja',
                    'Zaawansowane', //page title
                    'Zaawansowane', //menu title
                    'manage_options', //capability,
                    'k3e_config', //menu slug
                    'k3e_config_content' //callback function
            );
        }

        function k3e_plugin_content() {
            include plugin_dir_path(__FILE__) . '_templates/index.php';
        }

        function k3e_stats_content() {
            include plugin_dir_path(__FILE__) . '_templates/stats.php';
        }

        function k3e_config_content() {
            include plugin_dir_path(__FILE__) . '_templates/config.php';
        }

        K3eSystem::save();
        K3eSystem::load();
    }

    public static function install() {
        K3eSystem::setSettings(K3E::OPTION_PLUGIN_INSTALL_DATE, date('Y-m-d G:i:s'), true);
        K3eSystem::setSettings(K3E::OPTION_PLUGIN_ACTIVATION_DATE, date('Y-m-d G:i:s'));
    }

    public static function uninstall() {
        K3eSystem::setSettings(K3E::OPTION_PLUGIN_UNINSTALL_DATE, date('Y-m-d G:i:s'), true);
        K3eSystem::setSettings(K3E::OPTION_PLUGIN_DEACTIVATION_DATE, date('Y-m-d G:i:s'));
    }
    
    public static function load() {
        $modules = unserialize(get_option(K3E::OPTION_SYSTEM_MODULES));
        if ($modules) {
            foreach ($modules as $module_name => $module_args) {
                if ($module_args['status'] == 1) {
                    include plugin_dir_path(__FILE__) . $module_name . '/' . $module_name . '.php';
                    new $module_args['class']();
                }
            }
        }
    }
    
    public static function save() {

        if (isset($_POST['System'])) {
            $form = [];
            foreach (K3E::DEFAULT_MODULES as $module => $value) {
                if (isset($_POST['System'][$module])) {
                    $form[$module] = ['status' => $_POST['System'][$module], 'name' => $value['name'], 'class' => $value['class']];
                } else {
                    $form[$module] = ['status' => 0, 'name' => $value['name'], 'class' => $value['class']];
                }
            }

            K3eSystem::setSettings(K3E::OPTION_SYSTEM_MODULES, serialize($form));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }

        if (isset($_POST['Config'])) {
            K3eSystem::setSettings(K3E::OPTION_ONEPAGE, serialize($_POST['Config']['onepage']));
            wp_redirect('admin.php?page=' . $_GET['page']);
        }
    }

    public static function setSettings($name, $value, $oneTime = false, $autoload = 'no') {
        if (FALSE === get_option($name)) {
            add_option($name, $value, '', $autoload);
        } elseif (FALSE === $oneTime) {
            update_option($name, $value, '', $autoload);
        }
    }

    public static function getFullConfig() {
        $config = [];

        foreach (K3E::FULL_CONFIG as $item) {
            $config[$item] = get_option($item);
        }
        return $config;
    }

    

    public static function getModules() {
        $checked_modules = unserialize(get_option(K3E::OPTION_SYSTEM_MODULES));
        $default_modules = K3E::DEFAULT_MODULES;
        $modules = [];

        if (!$checked_modules) {
            K3eSystem::setSettings(K3E::OPTION_SYSTEM_MODULES, serialize(K3E::DEFAULT_MODULES), true);
            $modules = K3E::DEFAULT_MODULES;
        } else {
            foreach ($default_modules as $module_name => $module_args) {
                $modules[$module_name] = ['status' => $checked_modules[$module_name]['status'], 'name' => $module_args['name'], 'class' => $module_args['class']];
            }
        }
        return $modules;
    }

    public static function getOnePageOption()
    {
        return unserialize(get_option(K3E::OPTION_ONEPAGE));
    }
    
    public static function getThumbnailsOption()
    {
        return unserialize(get_option(K3E::OPTION_THUMBNAIL_SIZES));
    }
    
    public static function getHiddenMenusOption()
    {
        return unserialize(get_option(K3E::OPTION_HIDE_MENU));
    }
    
    public static function getThemeSupportOption()
    {
        return unserialize(get_option(K3E::OPTION_THEME_SUPPORT));
    }
}
