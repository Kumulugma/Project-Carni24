<?php
/**
 * Plugin Name: K3e Post Book
 * Plugin URI: https://k3e.pl
 * Description: Kalendarz wpisów w panelu administracyjnym WordPress z możliwością przeglądania wpisów według dat.
 * Version: 1.0.0
 * Author: Kumulugma
 * License: GPL v2 or later
 * Text Domain: k3e-post-book
 */

// Zapobieganie bezpośredniemu dostępowi
if (!defined('ABSPATH')) {
    exit;
}

// Definiowanie stałych wtyczki
define('K3E_POST_BOOK_VERSION', '1.0.0');
define('K3E_POST_BOOK_PLUGIN_URL', plugin_dir_url(__FILE__));
define('K3E_POST_BOOK_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Ładowanie głównej klasy wtyczki
require_once K3E_POST_BOOK_PLUGIN_PATH . 'includes/class-k3e-post-book.php';

/**
 * Inicjalizacja wtyczki
 */
function k3e_post_book_init() {
    new K3e_Post_Book();
}
add_action('plugins_loaded', 'k3e_post_book_init');

/**
 * Funkcja aktywacji wtyczki
 */
function k3e_post_book_activate() {
    // Tutaj można dodać kod wykonywany przy aktywacji
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'k3e_post_book_activate');

/**
 * Funkcja deaktywacji wtyczki
 */
function k3e_post_book_deactivate() {
    // Tutaj można dodać kod wykonywany przy deaktywacji
    flush_rewrite_rules();
}