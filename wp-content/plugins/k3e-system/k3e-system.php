<?php

/*
  Plugin name: K3e - System
  Plugin URI:
  Description: Przystawka K3e rozszerzająca podstawowe funkcjonalności systemu Wordpress.
  Author: K3e
  Author URI: https://www.k3e.pl/
  Text Domain:
  Domain Path:
  Version: 1.1.6
 */
require_once 'initSystem.php';
require_once 'system/K3E.php';
add_action('init', 'k3e_system_init');

Puc_v4_Factory::buildUpdateChecker(
        'http://wp.k3e.pl/?action=get_metadata&slug=k3e-system',
        __FILE__, //Full path to the main plugin file or functions.php.
        'k3e-system'
);

function k3e_system_init() {
    do_action('k3e_system_init');
    K3E::init();
    
    if (is_admin()) {
        initSystem::init();
        initSystem::save();
        initSystem::run();
    }
    
}

function k3e_system_activate() {
    $role = get_role('administrator');
    $role->add_cap(initSystem::PLUGIN_SLUG . '_access');
    
    K3E::install();
}

register_activation_hook(__FILE__, 'k3e_system_activate');

function k3e_system_deactivate() {
    global $wp_roles;
    foreach (array_keys($wp_roles->roles) as $role) {
        $wp_roles->remove_cap($role, initSystem::PLUGIN_SLUG . '_access');
    }
}

register_deactivation_hook(__FILE__, 'k3e_system_deactivate');
