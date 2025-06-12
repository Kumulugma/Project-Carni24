<?php
/**
 * Carni24 WordPress Theme - Functions
 * Nowa wersja z systemem assets - ZAKTUALIZOWANA
 * 
 * @package Carni24
 * @version 3.0.0
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

// ===== STAŁE MOTYWU ===== //
define('CARNI24_VERSION', '3.0.0');
define('CARNI24_THEME_PATH', get_template_directory());
define('CARNI24_THEME_URL', get_template_directory_uri());
define('CARNI24_ASSETS_URL', CARNI24_THEME_URL . '/assets');

// ===== PODSTAWOWA KONFIGURACJA MOTYWU ===== //
function carni24_setup_theme_support() {
    // Title tag
    add_theme_support('title-tag');
    
    // Post thumbnails
    add_theme_support('post-thumbnails');
    
    // Automatic feed links
    add_theme_support('automatic-feed-links');
    
    // HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Custom header
    add_theme_support('custom-header', array(
        'default-image' => '',
        'width'         => 1200,
        'height'        => 300,
        'flex-height'   => true,
        'flex-width'    => true,
    ));
    
    // Custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));
    
    // Navigation menus
    register_nav_menus(array(
        'main-menu' => __('Main Navigation', 'carni24'),
        'footer-menu' => __('Footer Navigation', 'carni24')
    ));
}
add_action('after_setup_theme', 'carni24_setup_theme_support');

// ===== SYSTEM ŁADOWANIA ASSETS ===== //

/**
 * Enqueue frontend assets with conditional loading
 */
function carni24_enqueue_frontend_assets() {
    if (is_admin()) return;
    
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
    
    // ===== GŁÓWNE PLIKI MOTYWU ===== //
    
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
        array('bootstrap'),
        CARNI24_VERSION,
        true
    );
    
    // ===== CONDITIONAL ASSETS - STRONY SPECYFICZNE ===== //
    
    // Homepage
    if (is_front_page()) {
        wp_enqueue_style(
            'carni24-homepage',
            CARNI24_ASSETS_URL . '/css/pages/homepage.css',
            array('carni24-style'),
            CARNI24_VERSION
        );
        
        wp_enqueue_script(
            'carni24-homepage-js',
            CARNI24_ASSETS_URL . '/js/pages/homepage.js',
            array('carni24-main'),
            CARNI24_VERSION,
            true
        );
        
        // NOWE: Front page specific JavaScript
        wp_enqueue_script(
            'carni24-front-page',
            CARNI24_ASSETS_URL . '/js/pages/front-page.js',
            array('jquery', 'bootstrap'),
            CARNI24_VERSION,
            true
        );
    }
    
    // Blog homepage (when showing posts on front)
    if (is_home() && !is_front_page()) {
        wp_enqueue_style(
            'carni24-homepage',
            CARNI24_ASSETS_URL . '/css/pages/homepage.css',
            array('carni24-style'),
            CARNI24_VERSION
        );
    }
    
    // Single posts and species
    if (is_single() || is_singular('species')) {
        wp_enqueue_style(
            'carni24-article',
            CARNI24_ASSETS_URL . '/css/pages/article.css',
            array('carni24-style'),
            CARNI24_VERSION
        );
    }
    
    // Search page
    if (is_search()) {
        wp_enqueue_style(
            'carni24-search',
            CARNI24_ASSETS_URL . '/css/pages/search.css',
            array('carni24-style'),
            CARNI24_VERSION
        );
    }
    
    // Category pages
    if (is_page_template('page-species-category.php') || is_category() || is_tax()) {
        wp_enqueue_style(
            'carni24-category',
            CARNI24_ASSETS_URL . '/css/pages/category.css',
            array('carni24-style'),
            CARNI24_VERSION
        );
    }
    
    // Gallery page (specific page ID)
    if (is_page(242)) {
        wp_enqueue_style(
            'carni24-gallery',
            CARNI24_ASSETS_URL . '/css/pages/gallery.css',
            array('carni24-style'),
            CARNI24_VERSION
        );
        
        wp_enqueue_script(
            'carni24-gallery-js',
            CARNI24_ASSETS_URL . '/js/pages/gallery.js',
            array('carni24-main'),
            CARNI24_VERSION,
            true
        );
    }
    
    // Static pages
    if (is_page() && !is_front_page() && !is_page(242)) {
        wp_enqueue_style(
            'carni24-static',
            CARNI24_ASSETS_URL . '/css/pages/static.css',
            array('carni24-style'),
            CARNI24_VERSION
        );
    }
    
    // ===== JAVASCRIPT LOCALIZATION ===== //
    wp_localize_script('carni24-main', 'carni24_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_nonce'),
        'theme_url' => CARNI24_THEME_URL,
        'is_front_page' => is_front_page(),
        'is_mobile' => wp_is_mobile()
    ));
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_frontend_assets');

/**
 * Enqueue admin assets
 */
function carni24_enqueue_admin_assets($hook) {
    // Only on theme options page
    if ($hook !== 'appearance_page_carni24-theme-options') {
        return;
    }
    
    wp_enqueue_media();
    
    wp_enqueue_style(
        'carni24-admin-style',
        CARNI24_ASSETS_URL . '/admin/css/theme-options.css',
        array(),
        CARNI24_VERSION
    );
    
    wp_enqueue_script(
        'carni24-admin-script',
        CARNI24_ASSETS_URL . '/admin/js/theme-options.js',
        array('jquery', 'wp-util'),
        CARNI24_VERSION,
        true
    );
    
    wp_localize_script('carni24-admin-script', 'carni24_admin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_admin_nonce'),
        'strings' => array(
            'save_success' => __('Settings saved successfully!', 'carni24'),
            'save_error' => __('Error saving settings. Please try again.', 'carni24'),
        )
    ));
}
add_action('admin_enqueue_scripts', 'carni24_enqueue_admin_assets');

// ===== WIDGET AREAS ===== //
function carni24_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget 1', 'carni24'),
        'id'            => 'footer-1',
        'description'   => __('First footer widget area', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget 2', 'carni24'),
        'id'            => 'footer-2',
        'description'   => __('Second footer widget area', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget 3', 'carni24'),
        'id'            => 'footer-3',
        'description'   => __('Third footer widget area', 'carni24'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="widget-title">',
        'after_title'   => '</h5>',
    ));
}
add_action('widgets_init', 'carni24_widgets_init');

// ===== ŁADOWANIE MODUŁÓW ===== //

// 1. Core functions
require_once CARNI24_THEME_PATH . '/includes/image-sizes.php';
require_once CARNI24_THEME_PATH . '/includes/polish-numbers.php';
require_once CARNI24_THEME_PATH . '/includes/disable-comments.php';
require_once CARNI24_THEME_PATH . '/includes/breadcrumbs.php';
require_once CARNI24_THEME_PATH . '/includes/seo.php';

// 2. Post types and taxonomies
if (file_exists(CARNI24_THEME_PATH . '/post-types/species.php')) {
    require_once CARNI24_THEME_PATH . '/post-types/species.php';
}

// 3. Meta boxes and custom fields
if (file_exists(CARNI24_THEME_PATH . '/includes/meta-boxes.php')) {
    require_once CARNI24_THEME_PATH . '/includes/meta-boxes.php';
}

// 4. Theme options
if (file_exists(CARNI24_THEME_PATH . '/includes/theme-options.php')) {
    require_once CARNI24_THEME_PATH . '/includes/theme-options.php';
}

// 5. Admin functionality
if (is_admin()) {
    require_once CARNI24_THEME_PATH . '/includes/admin.php';
}

// 6. Custom Post Types
if (file_exists(CARNI24_THEME_PATH . '/post-types/guides.php')) {
    require_once CARNI24_THEME_PATH . '/post-types/guides.php';
}

// ===== HELPER FUNCTIONS ===== //

/**
 * Get theme option with fallback
 */
if (!function_exists('carni24_get_option')) {
    function carni24_get_option($option_name, $default = '') {
        $options = get_option('carni24_theme_options', array());
        return isset($options[$option_name]) ? $options[$option_name] : $default;
    }
}

/**
 * Calculate reading time for articles
 */
if (!function_exists('carni24_calculate_reading_time')) {
    function carni24_calculate_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 słów na minutę
        return max(1, $reading_time);
    }
}

/**
 * Set post views count
 */
if (!function_exists('carni24_set_post_views')) {
    function carni24_set_post_views($postID) {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count == '') {
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }
}

/**
 * Track post views automatically
 */
function carni24_track_post_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;    
    }
    carni24_set_post_views($post_id);
}
add_action('wp_head', 'carni24_track_post_views');

/**
 * Estimate reading time
 */
if (!function_exists('carni24_estimate_reading_time')) {
    function carni24_estimate_reading_time($content) {
        $word_count = str_word_count(wp_strip_all_tags($content));
        $reading_time = ceil($word_count / 200); // 200 words per minute
        return $reading_time;
    }
}

/**
 * Get post thumbnail with fallback
 */
if (!function_exists('carni24_get_post_thumbnail')) {
    function carni24_get_post_thumbnail($post_id = null, $size = 'medium', $fallback = true) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        if (has_post_thumbnail($post_id)) {
            return get_the_post_thumbnail_url($post_id, $size);
        }
        
        if ($fallback) {
            return CARNI24_ASSETS_URL . '/images/placeholder.jpg';
        }
        
        return false;
    }
}

/**
 * Customizer - Social Media Settings
 */
function carni24_customize_register_social($wp_customize) {
    // Social Media Section
    $wp_customize->add_section('carni24_social_media', array(
        'title' => 'Social Media',
        'priority' => 35,
        'description' => 'Ustawienia linków do mediów społecznościowych'
    ));

    // Facebook
    $wp_customize->add_setting('carni24_facebook', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('carni24_facebook', array(
        'label' => 'Facebook URL',
        'section' => 'carni24_social_media',
        'type' => 'url'
    ));

    // Instagram
    $wp_customize->add_setting('carni24_instagram', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('carni24_instagram', array(
        'label' => 'Instagram URL',
        'section' => 'carni24_social_media',
        'type' => 'url'
    ));

    // YouTube
    $wp_customize->add_setting('carni24_youtube', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('carni24_youtube', array(
        'label' => 'YouTube URL',
        'section' => 'carni24_social_media',
        'type' => 'url'
    ));

    // Twitter
    $wp_customize->add_setting('carni24_twitter', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
    $wp_customize->add_control('carni24_twitter', array(
        'label' => 'Twitter URL',
        'section' => 'carni24_social_media',
        'type' => 'url'
    ));
}
add_action('customize_register', 'carni24_customize_register_social');

// ===== SECURITY & OPTIMIZATION ===== //

/**
 * Remove unnecessary WordPress features
 */
function carni24_cleanup() {
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove Windows Live Writer link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'carni24_cleanup');

/**
 * Add custom body classes
 */
function carni24_body_classes($classes) {
    // Add page slug class
    if (is_page()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    
    // Add front page class
    if (is_front_page()) {
        $classes[] = 'front-page';
    }
    
    // Add mobile class
    if (wp_is_mobile()) {
        $classes[] = 'mobile-device';
    }
    
    return $classes;
}
add_filter('body_class', 'carni24_body_classes');

/**
 * Custom excerpt length
 */
function carni24_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'carni24_excerpt_length');

/**
 * Custom excerpt more
 */
function carni24_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'carni24_excerpt_more');


// ===== DODAJ TE FUNKCJE DO FUNCTIONS.PHP ===== //

/**
 * Calculate reading time for articles
 */
if (!function_exists('carni24_calculate_reading_time')) {
    function carni24_calculate_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 słów na minutę
        return max(1, $reading_time);
    }
}

/**
 * Get custom excerpt with fallback
 */
if (!function_exists('carni24_get_custom_excerpt')) {
    function carni24_get_custom_excerpt($post_id = null, $fallback_words = 20) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        // Sprawdź czy istnieje custom excerpt
        $custom_excerpt = get_post_meta($post_id, '_custom_excerpt', true);
        
        if (!empty($custom_excerpt)) {
            return $custom_excerpt;
        }
        
        // Fallback do WordPress excerpt
        $excerpt = get_the_excerpt($post_id);
        if (!empty($excerpt)) {
            return $excerpt;
        }
        
        // Ostatni fallback - fragment treści bez HTML
        $content = get_post_field('post_content', $post_id);
        $content = wp_strip_all_tags($content);
        return wp_trim_words($content, $fallback_words, '...');
    }
}

/**
 * Set post views count
 */
if (!function_exists('carni24_set_post_views')) {
    function carni24_set_post_views($postID) {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if($count == '') {
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }
}

/**
 * Track post views automatically
 */
if (!function_exists('carni24_track_post_views')) {
    function carni24_track_post_views($post_id) {
        if (!is_single()) return;
        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;    
        }
        carni24_set_post_views($post_id);
    }
}
add_action('wp_head', 'carni24_track_post_views');

/**
 * Add image sizes for the theme
 */
if (!function_exists('carni24_add_image_sizes')) {
    function carni24_add_image_sizes() {
        // Blog thumbnail
        add_image_size('blog_thumb', 400, 250, true);
        
        // Manifest thumbnail
        add_image_size('manifest_thumb', 350, 233, true);
        
        // Widget thumbnail
        add_image_size('widget_thumb', 80, 80, true);
        
        // Homepage card
        add_image_size('homepage_card', 400, 300, true);
    }
}
add_action('after_setup_theme', 'carni24_add_image_sizes');