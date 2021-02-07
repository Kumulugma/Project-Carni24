<?php

require_once 'plugin-update-checker/plugin-update-checker.php';
if (!defined('ABSPATH'))
    exit;

/**
 *
 */
class K3E {

    public static function init() {
        
    }
    
    public static function install() {
        K3E::setSettings('k3e_system_install_date', date('Y-m-d G:i:s'), true);
        K3E::setSettings('k3e_system_activate_date', date('Y-m-d G:i:s'));
        K3E::setSettings('k3e_system_modules', serialize(initSystem::DEFAULT_MODULES), true);
    }
    
    public static function renderView($path) {
        include plugin_dir_path(__FILE__).$path.".php";
    }
    
    public static function setSettings($name, $value, $oneTime = false ,$autoload = 'no')
    {
        if(FALSE === get_option($name)) {
            add_option($name, $value, '', $autoload);
        } elseif(FALSE === $oneTime) {
            update_option($name, $value, '', $autoload);
        }
    }

}
