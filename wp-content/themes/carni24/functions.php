<?php
// wp-content/themes/carni24/functions.php
// Główny plik funkcji motywu - oczyszczony

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

// === STAŁE MOTYWU ===
define('CARNI24_VERSION', '2.1.0');
define('CARNI24_THEME_PATH', get_template_directory());
define('CARNI24_THEME_URL', get_template_directory_uri());

// === PODSTAWOWA KONFIGURACJA MOTYWU ===

// Wsparcie dla funkcji WordPress
function carni24_setup_theme_support() {
    // Dodaj obsługę title-tag
    add_theme_support('title-tag');
    
    // Dodaj obsługę miniaturek
    add_theme_support('post-thumbnails');
    
    // Dodaj obsługę automatic feed links
    add_theme_support('automatic-feed-links');
    
    // Dodaj obsługę HTML5
    add_theme_support('html5', array(
        'search-form',
        'gallery',
        'caption',
        'script',
        'style'
    ));
    
    // Dodaj obsługę custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Dodaj obsługę custom header
    add_theme_support('custom-header', array(
        'default-image' => '',
        'width'         => 1200,
        'height'        => 300,
        'flex-height'   => true,
        'flex-width'    => true,
    ));
    
    // Dodaj obsługę custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));
}
add_action('after_setup_theme', 'carni24_setup_theme_support');

// === ŁADOWANIE PLIKÓW CSS I JS ===

// Assets dla frontendu
function carni24_enqueue_frontend_assets() {
    if (!is_admin()) {
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
        
        // Główny arkusz stylów motywu
        wp_enqueue_style(
            'carni24-style', 
            CARNI24_THEME_URL . '/assets/css/style.css', 
            array('bootstrap'), 
            CARNI24_VERSION
        );
        
        // Conditional styles dla różnych typów stron
        if (is_single() || is_singular('species')) {
            wp_enqueue_style(
                'carni24-article', 
                CARNI24_THEME_URL . '/assets/css/article.css', 
                array('carni24-style'), 
                CARNI24_VERSION
            );
        }

        if (is_search()) {
            wp_enqueue_style(
                'carni24-search', 
                CARNI24_THEME_URL . '/assets/css/search.css', 
                array('carni24-style'), 
                CARNI24_VERSION
            );
        }

        if (is_page_template('page-species-category.php') || is_category()) {
            wp_enqueue_style(
                'carni24-category', 
                CARNI24_THEME_URL . '/assets/css/category.css', 
                array('carni24-style'), 
                CARNI24_VERSION
            );
        }

        if (is_page(242)) { // Strona galerii
            wp_enqueue_style(
                'carni24-gallery', 
                CARNI24_THEME_URL . '/assets/css/gallery.css', 
                array('carni24-style'), 
                CARNI24_VERSION
            );
        }

        // Scene styles dla hero sections
        if (!is_front_page()) {
            wp_enqueue_style(
                'carni24-scene', 
                CARNI24_THEME_URL . '/css/scene.css', 
                array('carni24-style'), 
                CARNI24_VERSION
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_frontend_assets');

// Assets dla admin
function carni24_enqueue_admin_assets($hook) {
    // Tylko na stronie theme options
    if ($hook == 'appearance_page_carni24-theme-options') {
        wp_enqueue_media();
        
        wp_enqueue_style(
            'carni24-admin-style', 
            CARNI24_THEME_URL . '/assets/admin/css/admin-theme-options.css', 
            array(), 
            CARNI24_VERSION
        );
        
        wp_enqueue_script(
            'carni24-admin-script', 
            CARNI24_THEME_URL . '/assets/admin/js/admin-theme-options.js', 
            array('jquery'), 
            CARNI24_VERSION, 
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'carni24_enqueue_admin_assets');

// === ŁADOWANIE MODUŁÓW MOTYWU ===

// 1. PODSTAWOWE FUNKCJE
require_once CARNI24_THEME_PATH . '/includes/image-sizes.php';           // Rozmiary miniaturek
require_once CARNI24_THEME_PATH . '/includes/polish-numbers.php';        // Odmiana liczebników
require_once CARNI24_THEME_PATH . '/includes/disable-comments.php';      // Wyłączenie komentarzy

// 2. TYPY POSTÓW I TAXONOMIE
require_once CARNI24_THEME_PATH . '/post-types/species.php';             // CPT Gatunki

// 3. META BOXY I CUSTOM FIELDS
require_once CARNI24_THEME_PATH . '/includes/meta-boxes.php';            // Podstawowe meta boxy
require_once CARNI24_THEME_PATH . '/includes/extended-meta-boxes.php';   // Rozszerzone pola meta

// 4. FUNKCJE POMOCNICZE
require_once CARNI24_THEME_PATH . '/includes/pagination.php';            // Paginacja
require_once CARNI24_THEME_PATH . '/includes/breadcrumbs.php';           // Breadcrumbs
require_once CARNI24_THEME_PATH . '/includes/galleryCount.php';          // Licznik galerii

// 5. SEO I OPTYMALIZACJA
require_once CARNI24_THEME_PATH . '/includes/seo.php';                   // Funkcje SEO
require_once CARNI24_THEME_PATH . '/includes/sitemap.php';               // Generator mapy strony

// 6. PANEL ADMINISTRACYJNY
require_once CARNI24_THEME_PATH . '/includes/theme-options.php';         // Ustawienia motywu
require_once CARNI24_THEME_PATH . '/includes/thumbnail-settings.php';    // Ustawienia miniaturek
require_once CARNI24_THEME_PATH . '/includes/admin.php';                 // Funkcje administracyjne

// === FUNKCJE POMOCNICZE MOTYWU ===

// Helper function dla opcji motywu
if (!function_exists('carni24_get_option')) {
    function carni24_get_option($option_name, $default = '') {
        return get_option('carni24_' . $option_name, $default);
    }
}

// Sprawdź czy funkcja istnieje przed definicją
if (!function_exists('carni24_setup_menus')) {
    function carni24_setup_menus() {
        register_nav_menus(array(
            'primary' => __('Menu główne', 'carni24'),
            'footer-content' => __('Stopka - Menu treści', 'carni24'),
            'footer-info' => __('Stopka - Menu informacji', 'carni24'),
        ));
    }
    add_action('init', 'carni24_setup_menus');
}

// Rejestracja sidebars/widget areas
function carni24_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar główny', 'carni24'),
        'id'            => 'sidebar-1',
        'description'   => __('Główny sidebar strony', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Stopka Widget 1', 'carni24'),
        'id'            => 'footer-1',
        'description'   => __('Pierwszy widget w stopce', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ));
    
    register_sidebar(array(
        'name'          => __('Stopka Widget 2', 'carni24'),
        'id'            => 'footer-2',
        'description'   => __('Drugi widget w stopce', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'carni24_widgets_init');

// === FUNKCJE POMOCNICZE ===

// Funkcja do szacowania czasu czytania
if (!function_exists('carni24_estimate_reading_time')) {
    function carni24_estimate_reading_time($content) {
        $word_count = str_word_count(wp_strip_all_tags($content));
        $reading_time = ceil($word_count / 200); // 200 słów na minutę
        return $reading_time;
    }
}

// Dodaj hook do dodawania overlay wyszukiwarki na każdej stronie
function carni24_add_search_overlay() {
    get_template_part('template-parts/search-overlay');
}
add_action('wp_footer', 'carni24_add_search_overlay');

// === SECURITY I PERFORMANCE ===

// Usuń niepotrzebne head tags
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

// Wyłącz emoji scripts (dla performance)
function carni24_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'carni24_disable_emojis');

// === HOOKS I FILTRY ===

// Dodaj klasy body dla różnych typów stron
function carni24_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'homepage';
    }
    
    if (is_singular('species')) {
        $classes[] = 'single-species';
    }
    
    if (is_post_type_archive('species')) {
        $classes[] = 'archive-species';
    }
    
    return $classes;
}
add_filter('body_class', 'carni24_body_classes');

// Modyfikuj excerpt length
function carni24_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'carni24_excerpt_length');

// Modyfikuj excerpt more
function carni24_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'carni24_excerpt_more');

// === DEBUG I DEVELOPMENT (tylko dla administratorów) ===

// Funkcja debug
function carni24_debug_info() {
    if (current_user_can('manage_options') && isset($_GET['carni24_debug'])) {
        echo '<div style="background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc; font-size: 12px;">';
        echo '<strong>Carni24 Debug Info:</strong><br>';
        echo 'Wersja motywu: ' . CARNI24_VERSION . '<br>';
        echo 'Ścieżka motywu: ' . CARNI24_THEME_PATH . '<br>';
        echo 'URL motywu: ' . CARNI24_THEME_URL . '<br>';
        echo 'Liczba gatunków: ' . wp_count_posts('species')->publish . '<br>';
        echo 'Liczba wpisów: ' . wp_count_posts('post')->publish . '<br>';
        if (function_exists('gallery_count')) {
            echo 'Liczba zdjęć: ' . gallery_count() . '<br>';
        }
        echo '</div>';
    }
}
add_action('wp_footer', 'carni24_debug_info');

// === COMPATIBILITY ===

// Sprawdź wersję PHP
if (version_compare(PHP_VERSION, '7.4', '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        echo '<strong>Motyw Carni24:</strong> Wymaga PHP 7.4 lub nowszego. ';
        echo 'Aktualna wersja: ' . PHP_VERSION;
        echo '</p></div>';
    });
}

// Sprawdź wersję WordPress
global $wp_version;
if (version_compare($wp_version, '5.0', '<')) {
    add_action('admin_notices', function() {
        global $wp_version;
        echo '<div class="notice notice-error"><p>';
        echo '<strong>Motyw Carni24:</strong> Wymaga WordPress 5.0 lub nowszego. ';
        echo 'Aktualna wersja: ' . $wp_version;
        echo '</p></div>';
    });
}

?>