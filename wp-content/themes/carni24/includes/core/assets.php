<?php

/**
 * Carni24 Assets Management
 * System ładowania CSS/JS z conditional loading
 * 
 * @package Carni24
 * @subpackage Core
 */
// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ładuje główne assets frontend
 */
function carni24_enqueue_frontend_assets() {
    if (is_admin())
        return;

    // ===== EXTERNAL LIBRARIES ===== //
    // Bootstrap CSS
    wp_enqueue_style(
            'bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
            array(),
            '5.3.0'
    );

    // Bootstrap Icons
    wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css',
            array(),
            '1.11.0'
    );

    // Bootstrap JS
    wp_enqueue_script(
            'bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
            array(),
            '5.3.0',
            true
    );

    // ===== THEME ASSETS ===== //
    // Main CSS - zawsze ładowany
    wp_enqueue_style(
            'carni24-style',
            CARNI24_ASSETS_URL . '/css/style.css',
            array('bootstrap'),
            CARNI24_VERSION
    );

    // Main JS - zawsze ładowany
    wp_enqueue_script(
            'carni24-main',
            CARNI24_ASSETS_URL . '/js/main.js',
            array('jquery', 'bootstrap'),
            CARNI24_VERSION,
            true
    );

    // ===== CONDITIONAL ASSETS ===== //
    // Homepage assets
    if (is_front_page()) {
        wp_enqueue_style(
                'carni24-homepage',
                CARNI24_ASSETS_URL . '/css/pages/homepage.css',
                array('carni24-style'),
                CARNI24_VERSION
        );

        wp_enqueue_script(
                'carni24-homepage',
                CARNI24_ASSETS_URL . '/js/pages/homepage.js',
                array('carni24-main'),
                CARNI24_VERSION,
                true
        );
    }

    // Single post/page assets
    if (is_singular()) {
        wp_enqueue_style(
                'carni24-single',
                CARNI24_ASSETS_URL . '/css/pages/single.css',
                array('carni24-style'),
                CARNI24_VERSION
        );
    }

    // Custom Post Types assets
    if (is_singular('species') || is_post_type_archive('species')) {
        wp_enqueue_style(
                'carni24-species',
                CARNI24_ASSETS_URL . '/css/pages/species.css',
                array('carni24-style'),
                CARNI24_VERSION
        );

        wp_enqueue_script(
                'carni24-species',
                CARNI24_ASSETS_URL . '/js/pages/species.js',
                array('carni24-main'),
                CARNI24_VERSION,
                true
        );
    }

    // Archive pages assets
    if (is_archive() || is_home()) {
        wp_enqueue_style(
                'carni24-archive',
                CARNI24_ASSETS_URL . '/css/pages/archive.css',
                array('carni24-style'),
                CARNI24_VERSION
        );
    }

    if (is_post_type_archive('species') || 
        is_page_template('page-blog.php') || 
        is_archive() || 
        is_category() || 
        is_tag() || 
        is_author() || 
        is_date()) {
            // CSS dla kontrolek
    wp_enqueue_style(
        'carni24-controls',
        get_template_directory_uri() . '/assets/css/components/controls.css',
        array(),
        '1.2.0'
    );
    
    // CSS dla kart
    wp_enqueue_style(
        'carni24-cards',
        get_template_directory_uri() . '/assets/css/components/cards.css',
        array(),
        '1.2.0'
    );
    
    // CSS dla paginacji
    wp_enqueue_style(
        'carni24-pagination',
        get_template_directory_uri() . '/assets/css/components/pagination.css',
        array(),
        '1.2.0'
    );
    
    // JavaScript dla kontrolek - NAPRAWIONY
    wp_enqueue_script(
        'carni24-controls-js',
        get_template_directory_uri() . '/assets/js/controls.js',
        array('jquery'),
        '1.2.0',
        true
    );
    }
    
    if (is_singular()) {
        wp_enqueue_style(
            'carni24-single-templates',
            get_template_directory_uri() . '/assets/css/pages/single-templates.css',
            array(),
            '1.0.0'
        );
    }

    // Search results assets
    if (is_search()) {
        wp_enqueue_style(
                'carni24-search',
                CARNI24_ASSETS_URL . '/css/pages/search.css',
                array('carni24-style'),
                CARNI24_VERSION
        );
    }

    // ===== LOCALIZE SCRIPTS ===== //

    wp_localize_script('carni24-main', 'carni24_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_frontend_nonce'),
        'home_url' => home_url(),
        'is_mobile' => wp_is_mobile(),
        'strings' => array(
            'loading' => __('Ładowanie...', 'carni24'),
            'error' => __('Wystąpił błąd. Spróbuj ponownie.', 'carni24'),
            'no_results' => __('Brak wyników.', 'carni24'),
        )
    ));
}

add_action('wp_enqueue_scripts', 'carni24_enqueue_frontend_assets');

/**
 * Preload critical assets dla lepszej wydajności
 */
function carni24_preload_assets() {
    // Preload głównego CSS
    echo '<link rel="preload" href="' . CARNI24_ASSETS_URL . '/css/style.css" as="style">' . "\n";

    // Preload głównych fontów
    echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}

add_action('wp_head', 'carni24_preload_assets', 1);

/**
 * Defer non-critical JavaScript
 */
function carni24_defer_scripts($tag, $handle, $src) {
    $defer_scripts = array(
        'carni24-homepage',
        'carni24-species',
    );

    if (in_array($handle, $defer_scripts)) {
        return str_replace('<script ', '<script defer ', $tag);
    }

    return $tag;
}

add_filter('script_loader_tag', 'carni24_defer_scripts', 10, 3);
