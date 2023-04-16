<?php

/*
  Plugin name: K3e - Plugin
  Plugin URI: https://www.k3e.pl/
  Description: Przystawka K3e rozszerzająca podstawowe funkcjonalności systemu Wordpress.
  Author: K3e
  Author URI: https://www.k3e.pl/
  Text Domain:
  Domain Path:
  Version: 0.2.17
 */
require_once 'updater/K3eUpdater.php';
require_once 'K3E.php';
require_once 'interfaces/InterfaceToggler.php';
require_once 'system/K3eSystem.php';
require_once 'modules/K3eModules.php';
add_action('init', 'k3e_plugin_init');

Puc_v4_Factory::buildUpdateChecker(
        'http://plugins.k3e.pl/?action=get_metadata&slug=k3e-plugin',
        __FILE__, //Full path to the main plugin file or functions.php.
        'k3e-plugin'
);

function k3e_plugin_init() {
    do_action('k3e_plugin_init');
    if (current_user_can('manage_options')) {
        K3eUpdater::init();
    }
    K3eSystem::init();
    K3eModules::init();
}

function k3e_plugin_activate() {
    K3eSystem::install();
}

register_activation_hook(__FILE__, 'k3e_plugin_activate');

function k3e_plugin_deactivate() {
    K3eSystem::uninstall();
}

register_deactivation_hook(__FILE__, 'k3e_plugin_deactivate');
