<?php
/**
 * Plugin Name: K3E Migrate
 * Plugin URI: 
 * Description: Wtyczka do eksportu i importu Custom Post Types wraz z polami meta.
 * Version: 1.0.0
 * Author: 
 * Text Domain: k3e-migrate
 */

if (!defined('ABSPATH')) {
    exit;
}

define('K3E_MIGRATE_VERSION', '1.0.0');
define('K3E_MIGRATE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('K3E_MIGRATE_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once K3E_MIGRATE_PLUGIN_DIR . 'includes/class-k3e-migrate-admin.php';
require_once K3E_MIGRATE_PLUGIN_DIR . 'includes/class-k3e-migrate-exporter.php';
require_once K3E_MIGRATE_PLUGIN_DIR . 'includes/class-k3e-migrate-importer.php';

function k3e_migrate_init() {
    if (is_admin()) {
        $admin = new K3E_Migrate_Admin();
        $admin->init();
        
        $exporter = new K3E_Migrate_Exporter();
        $exporter->init();
        
        $importer = new K3E_Migrate_Importer();
        $importer->init();
    }
}
add_action('plugins_loaded', 'k3e_migrate_init');

function k3e_migrate_enqueue_admin_scripts($hook) {
    if (strpos($hook, 'k3e-migrate') === false) {
        return;
    }
    
    wp_enqueue_style('k3e-migrate-admin-styles', K3E_MIGRATE_PLUGIN_URL . 'assets/css/k3e-migrate-admin.css', array(), K3E_MIGRATE_VERSION);
    wp_enqueue_script('k3e-migrate-admin-scripts', K3E_MIGRATE_PLUGIN_URL . 'assets/js/k3e-migrate-admin.js', array('jquery'), K3E_MIGRATE_VERSION, true);
    
    wp_localize_script('k3e-migrate-admin-scripts', 'k3e_migrate_vars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('k3e_migrate_nonce'),
        'strings' => array(
            'confirm_export' => __('Czy na pewno chcesz rozpocząć eksport?', 'k3e-migrate'),
            'confirm_import' => __('Czy na pewno chcesz rozpocząć import?', 'k3e-migrate'),
            'export_complete' => __('Eksport zakończony pomyślnie!', 'k3e-migrate'),
            'import_complete' => __('Import zakończony pomyślnie!', 'k3e-migrate'),
            'error' => __('Wystąpił błąd: ', 'k3e-migrate'),
            'processing' => __('Przetwarzanie... ', 'k3e-migrate'),
            'loading' => __('Ładowanie...', 'k3e-migrate')
        )
    ));
}
add_action('admin_enqueue_scripts', 'k3e_migrate_enqueue_admin_scripts');

register_activation_hook(__FILE__, 'k3e_migrate_activate');
function k3e_migrate_activate() {
    // Activation code if needed
}

register_deactivation_hook(__FILE__, 'k3e_migrate_deactivate');
function k3e_migrate_deactivate() {
    // Deactivation code if needed
}