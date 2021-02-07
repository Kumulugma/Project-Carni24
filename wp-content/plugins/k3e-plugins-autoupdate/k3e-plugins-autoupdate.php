<?php

/*
  Plugin name: K3e - Plugins Autoupdate
  Plugin URI:
  Description: Automatyczna aktualizacja wtyczek.
  Author: K3e
  Author URI: https://www.k3e.pl/
  Text Domain:
  Domain Path:
  Version: 1.0.3
 */
require_once 'initPluginsAutoupdate.php';
require_once 'plugin-update-checker/plugin-update-checker.php';

add_action('init', 'k3e_plugins_autoupdate_init');

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'http://wp.k3e.pl/?action=get_metadata&slug=k3e-plugins-autoupdate',
	__FILE__, //Full path to the main plugin file or functions.php.
	'k3e-plugins-autoupdate'
);

function k3e_plugins_autoupdate_init() {

    do_action('k3e_plugins_autoupdate_init');

    add_filter('auto_update_plugin', '__return_true');
}

function k3e_plugins_autoupdate_activate() {
    $role = get_role('administrator');
    $role->add_cap(initPluginsAutoupdate::PLUGIN_SLUG . '_access');
}

register_activation_hook(__FILE__, 'k3e_plugins_autoupdate_activate');

function k3e_plugins_autoupdate_deactivate() {
    global $wp_roles;
    foreach (array_keys($wp_roles->roles) as $role) {
        $wp_roles->remove_cap($role, initPluginsAutoupdate::PLUGIN_SLUG . '_access');
    }
}

register_deactivation_hook(__FILE__, 'k3e_plugins_autoupdate_deactivate');

add_filter('plugins_api', 'k3e_plugin_info', 20, 3);
