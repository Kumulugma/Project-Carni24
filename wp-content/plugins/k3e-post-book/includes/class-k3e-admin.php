<?php
/**
 * Klasa obsługująca panel administracyjny
 */

if (!defined('ABSPATH')) {
    exit;
}

class K3e_Admin {
    
    /**
     * Konstruktor klasy
     */
    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
        add_action('wp_ajax_k3e_get_calendar_data', array($this, 'ajax_get_calendar_data'));
    }
    
    /**
     * Dodawanie widget do dashboard
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'k3e_post_book_calendar',
            __('Kalendarz Wpisów', 'k3e-post-book'),
            array($this, 'display_dashboard_widget')
        );
    }
    
    /**
     * Wyświetlanie widget dashboard
     */
    public function display_dashboard_widget() {
        $calendar = new K3e_Calendar();
        $current_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $current_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        // Sprawdzanie poprawności danych
        $current_month = max(1, min(12, $current_month));
        $current_year = max(2000, min(2099, $current_year));
        
        include K3E_POST_BOOK_PLUGIN_PATH . 'templates/calendar-metabox.php';
    }
    
    /**
     * AJAX handler do pobierania danych kalendarza
     */
    public function ajax_get_calendar_data() {
        // Sprawdzanie nonce
        if (!wp_verify_nonce($_POST['nonce'], 'k3e_post_book_nonce')) {
            wp_die(__('Błąd bezpieczeństwa.', 'k3e-post-book'));
        }
        
        $month = isset($_POST['month']) ? intval($_POST['month']) : date('n');
        $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
        
        // Sprawdzanie poprawności danych
        $month = max(1, min(12, $month));
        $year = max(2000, min(2099, $year));
        
        $calendar = new K3e_Calendar();
        $calendar_html = $calendar->generate_calendar($month, $year);
        
        wp_send_json_success(array(
            'calendar' => $calendar_html,
            'month' => $month,
            'year' => $year
        ));
    }
}