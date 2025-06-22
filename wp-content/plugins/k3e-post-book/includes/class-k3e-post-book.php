<?php
/**
 * Główna klasa wtyczki K3e Post Book
 */

if (!defined('ABSPATH')) {
    exit;
}

class K3e_Post_Book {
    
    /**
     * Konstruktor klasy
     */
    public function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Ładowanie zależności
     */
    private function load_dependencies() {
        require_once K3E_POST_BOOK_PLUGIN_PATH . 'includes/class-k3e-admin.php';
        require_once K3E_POST_BOOK_PLUGIN_PATH . 'includes/class-k3e-calendar.php';
    }
    
    /**
     * Inicjalizacja hooków WordPress
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Inicjalizacja wtyczki
     */
    public function init() {
        // Ładowanie tłumaczeń
        load_plugin_textdomain('k3e-post-book', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Inicjalizacja klasy admin tylko w panelu administracyjnym
        if (is_admin()) {
            new K3e_Admin();
        }
    }
    
    /**
     * Ładowanie zasobów CSS i JS dla panelu administracyjnego
     */
    public function enqueue_admin_assets($hook_suffix) {
        // Ładowanie tylko na stronie dashboard
        if ($hook_suffix !== 'index.php') {
            return;
        }
        
        wp_enqueue_style(
            'k3e-post-book-admin',
            K3E_POST_BOOK_PLUGIN_URL . 'assets/css/admin-style.css',
            array(),
            K3E_POST_BOOK_VERSION
        );
        
        wp_enqueue_script(
            'k3e-post-book-admin',
            K3E_POST_BOOK_PLUGIN_URL . 'assets/js/admin-script.js',
            array('jquery'),
            K3E_POST_BOOK_VERSION,
            true
        );
        
        // Przekazywanie danych do JavaScript
        wp_localize_script('k3e-post-book-admin', 'k3ePostBook', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('k3e_post_book_nonce'),
            'strings' => array(
                'loading' => __('Ładowanie...', 'k3e-post-book'),
                'error' => __('Wystąpił błąd podczas ładowania danych.', 'k3e-post-book')
            )
        ));
    }
}