<?php
// wp-content/themes/carni24/functions.php
// Główny plik funkcji motywu - uporządkowany z includes

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

// === STAŁE MOTYWU ===
define('CARNI24_VERSION', '2.0.0');
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
        // Usunięto 'comment-form' i 'comment-list' - komentarze wyłączone
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
    
    // Wyłącz block editor (Gutenberg) jeśli potrzebne
    // add_theme_support('disable-custom-font-sizes');
    // add_theme_support('editor-color-palette', array());
}
add_action('after_setup_theme', 'carni24_setup_theme_support');

// === ŁADOWANIE PLIKÓW CSS I JS ===

// Assets dla frontendu
function carni24_enqueue_frontend_assets() {
    if (!is_admin()) {
        // Bootstrap CSS
        wp_enqueue_style(
            'bootstrap', 
            'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css', 
            array(), 
            '5.0.0-beta1'
        );
        
        // Bootstrap JS
        wp_enqueue_script(
            'bootstrap', 
            'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js', 
            array(), 
            '5.0.0-beta1', 
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
        if (is_single()) {
            wp_enqueue_style('carni24-article', CARNI24_THEME_URL . '/assets/css/article.css', array(), CARNI24_VERSION);
        }
        
        if (is_search()) {
            wp_enqueue_style('carni24-search', CARNI24_THEME_URL . '/assets/css/search.css', array(), CARNI24_VERSION);
        }
        
        if (is_category()) {
            wp_enqueue_style('carni24-category', CARNI24_THEME_URL . '/assets/css/category.css', array(), CARNI24_VERSION);
            wp_enqueue_script('carni24-category-js', CARNI24_THEME_URL . '/assets/js/category.js', array('jquery'), CARNI24_VERSION, true);
        }
        
        if (is_tag()) {
            wp_enqueue_style('carni24-tag', CARNI24_THEME_URL . '/assets/css/tag.css', array(), CARNI24_VERSION);
        }
        
        if (is_front_page()) {
            wp_enqueue_style('carni24-homepage', CARNI24_THEME_URL . '/assets/css/homepage.css', array(), CARNI24_VERSION);
            wp_enqueue_script('carni24-homepage-js', CARNI24_THEME_URL . '/assets/js/homepage.js', array('jquery'), CARNI24_VERSION, true);
        }
        
        if (is_404()) {
            wp_enqueue_script('anime-js', CARNI24_THEME_URL . '/node_modules/animejs/lib/anime.min.js', array(), CARNI24_VERSION, true);
            wp_enqueue_script('carni24-404', CARNI24_THEME_URL . '/assets/js/404.js', array('anime-js'), CARNI24_VERSION, true);
            wp_enqueue_style('carni24-404', CARNI24_THEME_URL . '/assets/css/404.css', array(), CARNI24_VERSION);
        }
        
        // Specjalne style dla galerii (strona ID 242)
        if (is_page(242)) {
            wp_enqueue_script('jquery-slim', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', array(), '3.5.1', true);
            wp_enqueue_style('carni24-gallery', CARNI24_THEME_URL . '/assets/css/gallery.css', array(), CARNI24_VERSION);
        }
    }
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_frontend_assets');

// Assets dla panelu administracyjnego
function carni24_enqueue_admin_assets($hook) {
    // Ogranicz do stron ustawień motywu (opcjonalnie)
    $allowed_pages = array(
        'appearance_page_carni24-theme-options',
        'appearance_page_carni24-thumbnails',
        'post.php',
        'post-new.php'
    );
    
    if (in_array($hook, $allowed_pages) || strpos($hook, 'carni24') !== false) {
        // CSS dla panelu admin
        wp_enqueue_style(
            'carni24-admin-style', 
            CARNI24_THEME_URL . '/assets/admin/css/admin-theme-options.css', 
            array(), 
            CARNI24_VERSION
        );
        
        // JS dla panelu admin
        wp_enqueue_script(
            'carni24-admin-script', 
            CARNI24_THEME_URL . '/assets/admin/js/admin-theme-options.js', 
            array('jquery'), 
            CARNI24_VERSION, 
            true
        );
        
        // Media uploader dla meta-boxes
        wp_enqueue_media();
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

// 7. FUNKCJE TEKSTOWE I FORMATOWANIE
require_once CARNI24_THEME_PATH . '/includes/titleSeparator.php';        // Separator tytułów
require_once CARNI24_THEME_PATH . '/includes/readMore.php';              // Read more
require_once CARNI24_THEME_PATH . '/includes/menuAClass.php';            // Klasy menu
require_once CARNI24_THEME_PATH . '/includes/specID.php';                // Spec ID

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

// === DEBUG I DEVELOPMENT ===

// Funkcja debug (tylko dla administratorów)
function carni24_debug_info() {
    if (current_user_can('manage_options') && isset($_GET['carni24_debug'])) {
        echo '<div style="background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;">';
        echo '<strong>Carni24 Debug Info:</strong><br>';
        echo 'Wersja motywu: ' . CARNI24_VERSION . '<br>';
        echo 'Ścieżka motywu: ' . CARNI24_THEME_PATH . '<br>';
        echo 'URL motywu: ' . CARNI24_THEME_URL . '<br>';
        echo 'Liczba gatunków: ' . wp_count_posts('species')->publish . '<br>';
        echo 'Liczba wpisów: ' . wp_count_posts('post')->publish . '<br>';
        echo 'Liczba zdjęć: ' . gallery_count() . '<br>';
        echo '</div>';
    }
}
add_action('wp_footer', 'carni24_debug_info');

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

// === COMPATIBILITY I BACKWARDS SUPPORT ===

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
        echo '<div class="notice notice-error"><p>';
        echo '<strong>Motyw Carni24:</strong> Wymaga WordPress 5.0 lub nowszego. ';
        echo 'Aktualna wersja: ' . $wp_version;
        echo '</p></div>';
    });
}

// === CLEANUP NA KONIEC ===

// Wyczyść zbędne cache przy aktywacji motywu
function carni24_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Ustaw domyślne opcje motywu
    if (!get_option('carni24_theme_version')) {
        add_option('carni24_theme_version', CARNI24_VERSION);
        add_option('carni24_setup_complete', false);
    }
}
add_action('after_switch_theme', 'carni24_theme_activation');

// Cleanup przy deaktywacji
function carni24_theme_deactivation() {
    flush_rewrite_rules();
}
add_action('switch_theme', 'carni24_theme_deactivation');

// === INFORMACJE O MOTYWIE ===
/*
 * Motyw: Carni24 v2.0.0
 * Opis: Motyw WordPress dla strony o roślinach mięsożernych
 * Autor: [Twoje Imię]
 * Wymagania: PHP 7.4+, WordPress 5.0+
 * 
 * Funkcje:
 * - Responsive design (Bootstrap 5)
 * - Custom Post Type: Species (Gatunki)
 * - SEO zoptymalizowany
 * - Wyłączone komentarze
 * - Odmiana liczebników PL
 * - Konfigurowalne miniaturki
 * - Panel ustawień motywu
 * - Lazy loading obrazów
 * - WebP support (opcjonalnie)
 */

// Dedykowane pola meta zamiast excerpt
require_once get_template_directory() . '/includes/custom-meta-fields.php';

// Funkcja do szacowania czasu czytania (jeśli nie istnieje)
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

// Dodaj Bootstrap Icons CDN jeśli nie jest już załadowany
function carni24_enqueue_bootstrap_icons() {
    if (!wp_style_is('bootstrap-icons', 'enqueued')) {
        wp_enqueue_style(
            'bootstrap-icons',
            'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css',
            array(),
            '1.11.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_bootstrap_icons');

// Dodaj Bootstrap JavaScript jeśli nie jest już załadowany
function carni24_enqueue_bootstrap_js() {
    if (!wp_script_is('bootstrap', 'enqueued')) {
        wp_enqueue_script(
            'bootstrap',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
            array(),
            '5.3.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_bootstrap_js');

// Dodaj style dla nowych kolorów przycisków
function carni24_enqueue_button_styles() {
    wp_add_inline_style('carni24-style', '
        /* Jasny przycisk - zielony tekst */
        .btn-light,
        .btn-outline-light {
            color: #28a745 !important;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .btn-light:hover,
        .btn-outline-light:hover {
            color: #1e7e34 !important;
            background-color: #e2e6ea;
            border-color: #dae0e5;
        }
        
        /* Zielony przycisk - jasny tekst */
        .btn-success,
        .btn-outline-success {
            color: #ffffff !important;
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .btn-success:hover,
        .btn-outline-success:hover {
            color: #ffffff !important;
            background-color: #218838;
            border-color: #1e7e34;
        }
        
        .btn-outline-success {
            color: #28a745 !important;
            background-color: transparent;
        }
        
        .btn-outline-success:hover {
            color: #ffffff !important;
            background-color: #28a745;
        }
        
        /* Specjalne przypadki dla hero i kart */
        .hero-slide .btn-light {
            color: #28a745 !important;
            background-color: rgba(248, 249, 250, 0.95);
            backdrop-filter: blur(2px);
        }
        
        .hero-slide .btn-success {
            color: #ffffff !important;
            background-color: rgba(40, 167, 69, 0.95);
            backdrop-filter: blur(2px);
        }
        
        .post-card .btn-light,
        .archive-post-card .btn-light {
            color: #28a745 !important;
            font-weight: 500;
        }
        
        .post-card .btn-light:hover,
        .archive-post-card .btn-light:hover {
            color: #1e7e34 !important;
            transform: translateX(3px);
        }
    ');
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_button_styles');

// Dodaj meta box dla ustawień wyróżnienia postów
function carni24_add_featured_meta_box() {
    add_meta_box(
        'carni24_featured_settings',
        'Ustawienia wyróżnienia',
        'carni24_featured_meta_box_callback',
        array('post', 'species'),
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'carni24_add_featured_meta_box');

function carni24_featured_meta_box_callback($post) {
    wp_nonce_field('carni24_featured_meta', 'carni24_featured_nonce');
    
    $featured_post = get_post_meta($post->ID, '_featured_post', true);
    $hero_featured = get_post_meta($post->ID, '_hero_featured', true);
    ?>
    
    <p>
        <label>
            <input type="checkbox" name="featured_post" value="1" <?= checked(1, $featured_post, false) ?>>
            Wyróżniony wpis (featured posts)
        </label>
    </p>
    
    <p>
        <label>
            <input type="checkbox" name="hero_featured" value="1" <?= checked(1, $hero_featured, false) ?>>
            Pokaż w hero sliderze
        </label>
    </p>
    
    <p class="description">
        Zaznacz aby wpis był wyróżniony na stronie głównej lub w sliderze.
    </p>
    <?php
}

// Zapisz meta boxy wyróżnienia
function carni24_save_featured_meta($post_id) {
    if (!isset($_POST['carni24_featured_nonce']) || !wp_verify_nonce($_POST['carni24_featured_nonce'], 'carni24_featured_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Zapisz featured post
    if (isset($_POST['featured_post'])) {
        update_post_meta($post_id, '_featured_post', 1);
    } else {
        delete_post_meta($post_id, '_featured_post');
    }
    
    // Zapisz hero featured
    if (isset($_POST['hero_featured'])) {
        update_post_meta($post_id, '_hero_featured', 1);
    } else {
        delete_post_meta($post_id, '_hero_featured');
    }
}
add_action('save_post', 'carni24_save_featured_meta');

// Dodaj kolumny w admin dla wyróżnienia
function carni24_add_featured_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        
        if ($key === 'title') {
            $new_columns['featured_status'] = '<i class="dashicons dashicons-star-filled" title="Wyróżnione"></i>';
        }
    }
    
    return $new_columns;
}
add_filter('manage_posts_columns', 'carni24_add_featured_columns');
add_filter('manage_species_posts_columns', 'carni24_add_featured_columns');

function carni24_show_featured_columns($column, $post_id) {
    if ($column === 'featured_status') {
        $featured_post = get_post_meta($post_id, '_featured_post', true);
        $hero_featured = get_post_meta($post_id, '_hero_featured', true);
        
        $status = array();
        
        if ($featured_post) {
            $status[] = '<span style="color: #f39c12;" title="Wyróżniony wpis">★</span>';
        }
        
        if ($hero_featured) {
            $status[] = '<span style="color: #e74c3c;" title="W hero sliderze">♦</span>';
        }
        
        if (empty($status)) {
            echo '<span style="color: #95a5a6;">—</span>';
        } else {
            echo implode(' ', $status);
        }
    }
}
add_action('manage_posts_custom_column', 'carni24_show_featured_columns', 10, 2);
add_action('manage_species_posts_custom_column', 'carni24_show_featured_columns', 10, 2);

// AJAX endpoint dla live search (opcjonalnie)
function carni24_ajax_search() {
    if (!isset($_GET['query']) || empty($_GET['query'])) {
        wp_die('No query provided');
    }
    
    $query = sanitize_text_field($_GET['query']);
    
    $search_query = new WP_Query(array(
        'post_type' => array('post', 'species'),
        'posts_per_page' => 5,
        's' => $query,
        'post_status' => 'publish'
    ));
    
    $results = array();
    
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            $results[] = array(
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'excerpt' => carni24_get_card_description(get_the_ID(), 15),
                'date' => get_the_date(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
            );
        }
    }
    
    wp_reset_postdata();
    
    wp_send_json_success($results);
}
add_action('wp_ajax_carni24_search', 'carni24_ajax_search');
add_action('wp_ajax_nopriv_carni24_search', 'carni24_ajax_search');

// Dodaj AJAX URL do JavaScript
function carni24_ajax_scripts() {
    wp_localize_script('jquery', 'carni24_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_search_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'carni24_ajax_scripts');

// Dodaj CSS dla search overlay do head
function carni24_search_overlay_styles() {
    ?>
    <style>
    /* Podstawowe style dla wyszukiwarki w overlay */
    .search-trigger-btn {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 20px;
        transition: all 0.3s ease;
        white-space: nowrap;
        color: #28a745 !important;
    }
    
    .search-trigger-btn:hover {
        color: #1e7e34 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    
    .search-modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    
    .search-overlay-input {
        border: 2px solid #e9ecef;
        border-radius: 25px 0 0 25px;
        padding: 15px 20px;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    
    .search-overlay-input:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    }
    
    .search-overlay-submit {
        border-radius: 0 25px 25px 0;
        padding: 15px 20px;
        font-size: 18px;
        background: #28a745;
        border-color: #28a745;
        color: #ffffff !important;
    }
    
    .search-overlay-submit:hover {
        background: #218838;
        border-color: #1e7e34;
        color: #ffffff !important;
    }
    
    .popular-search-tag {
        text-decoration: none;
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 20px;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
        display: inline-block;
        color: #28a745 !important;
        background: #f8f9fa;
    }
    
    .popular-search-tag:hover {
        background: #28a745 !important;
        color: #ffffff !important;
        border-color: #28a745;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
    }
    
    @media (max-width: 768px) {
        .search-trigger-btn {
            font-size: 13px;
            padding: 6px 10px;
        }
        
        .search-overlay-input,
        .search-overlay-submit {
            font-size: 16px;
            padding: 12px 15px;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'carni24_search_overlay_styles');

// Ulepszony widget dla latest posts z nowymi polami meta
class Carni24_Latest_Posts_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_latest_posts',
            'Carni24 - Najnowsze wpisy',
            array('description' => 'Wyświetla najnowsze wpisy z custom opisami')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : 'Najnowsze wpisy';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_thumbnails = !empty($instance['show_thumbnails']);
        $show_excerpts = !empty($instance['show_excerpts']);
        
        echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        
        $latest_posts = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        if ($latest_posts->have_posts()) :
        ?>
            <div class="carni24-latest-posts">
                <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                    <article class="latest-post-item">
                        <?php if ($show_thumbnails && has_post_thumbnail()) : ?>
                            <div class="latest-post-thumbnail">
                                <a href="<?= get_permalink() ?>">
                                    <?= get_the_post_thumbnail(get_the_ID(), 'widget_thumb', array(
                                        'loading' => 'lazy'
                                    )) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="latest-post-content">
                            <h4 class="latest-post-title">
                                <a href="<?= get_permalink() ?>">
                                    <?= get_the_title() ?>
                                </a>
                            </h4>
                            
                            <div class="latest-post-meta">
                                <time datetime="<?= get_the_date('c') ?>">
                                    <?= get_the_date() ?>
                                </time>
                            </div>
                            
                            <?php if ($show_excerpts) : ?>
                                <div class="latest-post-excerpt">
                                    <?= wp_trim_words(carni24_get_card_description(get_the_ID()), 15, '...') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <style>
            .carni24-latest-posts .latest-post-item {
                display: flex;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid #eee;
            }
            
            .carni24-latest-posts .latest-post-item:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }
            
            .latest-post-thumbnail {
                flex-shrink: 0;
                margin-right: 15px;
            }
            
            .latest-post-thumbnail img {
                width: 60px;
                height: 60px;
                object-fit: cover;
                border-radius: 8px;
            }
            
            .latest-post-content {
                flex-grow: 1;
            }
            
            .latest-post-title {
                font-size: 14px;
                font-weight: 600;
                line-height: 1.3;
                margin-bottom: 5px;
            }
            
            .latest-post-title a {
                color: #212529;
                text-decoration: none;
            }
            
            .latest-post-title a:hover {
                color: #28a745;
            }
            
            .latest-post-meta {
                font-size: 12px;
                color: #6c757d;
                margin-bottom: 8px;
            }
            
            .latest-post-excerpt {
                font-size: 12px;
                color: #6c757d;
                line-height: 1.4;
            }
            </style>
        <?php
        endif;
        
        wp_reset_postdata();
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_thumbnails = !empty($instance['show_thumbnails']);
        $show_excerpts = !empty($instance['show_excerpts']);
        ?>
        <p>
            <label for="<?= $this->get_field_id('title') ?>">Tytuł:</label>
            <input class="widefat" id="<?= $this->get_field_id('title') ?>" name="<?= $this->get_field_name('title') ?>" type="text" value="<?= esc_attr($title) ?>">
        </p>
        
        <p>
            <label for="<?= $this->get_field_id('number') ?>">Liczba wpisów:</label>
            <input class="tiny-text" id="<?= $this->get_field_id('number') ?>" name="<?= $this->get_field_name('number') ?>" type="number" step="1" min="1" value="<?= $number ?>" size="3">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?= checked($show_thumbnails) ?> id="<?= $this->get_field_id('show_thumbnails') ?>" name="<?= $this->get_field_name('show_thumbnails') ?>">
            <label for="<?= $this->get_field_id('show_thumbnails') ?>">Pokaż miniaturki</label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?= checked($show_excerpts) ?> id="<?= $this->get_field_id('show_excerpts') ?>" name="<?= $this->get_field_name('show_excerpts') ?>">
            <label for="<?= $this->get_field_id('show_excerpts') ?>">Pokaż opisy</label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        $instance['show_thumbnails'] = !empty($new_instance['show_thumbnails']);
        $instance['show_excerpts'] = !empty($new_instance['show_excerpts']);
        
        return $instance;
    }
}

// Zarejestruj widget
function carni24_register_widgets() {
    register_widget('Carni24_Latest_Posts_Widget');
}
add_action('widgets_init', 'carni24_register_widgets');

// Funkcja pomocnicza do generowania breadcrumbs z lepszą integracją
function carni24_breadcrumbs() {
    if (is_front_page()) return;
    
    echo '<nav aria-label="breadcrumb">';
    echo '<ol class="breadcrumb">';
    echo '<li class="breadcrumb-item"><a href="' . home_url() . '"><i class="bi bi-house-door"></i> Start</a></li>';
    
    if (is_category() || is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            echo '<li class="breadcrumb-item"><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
        }
    }
    
    if (is_single()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_category()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title('', false) . '</li>';
    } elseif (is_page()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_search()) {
        echo '<li class="breadcrumb-item active" aria-current="page">Wyniki wyszukiwania: "' . get_search_query() . '"</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

// Dodaj breadcrumbs CSS
function carni24_breadcrumbs_styles() {
    wp_add_inline_style('carni24-style', '
        .breadcrumb {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 25px;
            padding: 12px 20px;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .breadcrumb-item a {
            color: #28a745;
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: #1e7e34;
            text-decoration: underline;
        }
        
        .breadcrumb-item.active {
            color: #6c757d;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
            color: #6c757d;
        }
    ');
}
add_action('wp_enqueue_scripts', 'carni24_breadcrumbs_styles');

?>