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

/**
 * Add to wp-content/themes/carni24/functions.php
 * Conditional loading of CSS for CPT templates
 */

// Enqueue conditional CSS based on post type
function carni24_conditional_styles() {
    // Load single CPT styles for species and guides
    if (is_singular('species') || is_singular('guides')) {
        wp_enqueue_style(
            'carni24-single-cpt',
            get_template_directory_uri() . '/assets/css/pages/single-cpt.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/pages/single-cpt.css')
        );
    }
    
    // Load archive styles for species and guides
    if (is_post_type_archive('species') || is_post_type_archive('guides') || 
        is_tax('guide_category') || is_tax('guide_tag')) {
        wp_enqueue_style(
            'carni24-archive-cpt',
            get_template_directory_uri() . '/assets/css/pages/archive-cpt.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/pages/archive-cpt.css')
        );
    }
    
    // Load lightbox for species galleries
    if (is_singular('species')) {
        wp_enqueue_script(
            'lightbox2-js',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js',
            array('jquery'),
            '2.11.4',
            true
        );
        
        wp_enqueue_style(
            'lightbox2-css',
            'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css',
            array(),
            '2.11.4'
        );
    }
}
add_action('wp_enqueue_scripts', 'carni24_conditional_styles');

// Add body classes for CPT templates
function carni24_cpt_body_classes($classes) {
    if (is_singular('species')) {
        $classes[] = 'single-species';
        $classes[] = 'template-species';
    }
    
    if (is_singular('guides')) {
        $classes[] = 'single-guide';
        $classes[] = 'template-guide';
    }
    
    if (is_post_type_archive('species')) {
        $classes[] = 'archive-species';
    }
    
    if (is_post_type_archive('guides')) {
        $classes[] = 'archive-guides';
    }
    
    if (is_tax('guide_category') || is_tax('guide_tag')) {
        $classes[] = 'archive-guides';
        $classes[] = 'taxonomy-guides';
    }
    
    return $classes;
}
add_filter('body_class', 'carni24_cpt_body_classes');

// Ensure proper template hierarchy for CPT
function carni24_template_hierarchy($template) {
    global $wp_query;
    
    // For species single pages
    if (is_singular('species')) {
        $custom_template = locate_template('single-species.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    // For guides single pages
    if (is_singular('guides')) {
        $custom_template = locate_template('single-guides.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'carni24_template_hierarchy');

// Add meta box for species-specific fields (if not already exists)
function carni24_species_meta_boxes() {
    add_meta_box(
        'species_details',
        'Szczegóły gatunku',
        'carni24_species_details_callback',
        'species',
        'side',
        'default'
    );
    
    add_meta_box(
        'species_gallery',
        'Galeria gatunku',
        'carni24_species_gallery_callback',
        'species',
        'normal',
        'default'
    );
    
    add_meta_box(
        'species_map',
        'Mapa rozmieszczenia',
        'carni24_species_map_callback',
        'species',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_species_meta_boxes');

// Callback for species details meta box
function carni24_species_details_callback($post) {
    wp_nonce_field('carni24_species_meta_box', 'carni24_species_meta_box_nonce');
    
    $scientific_name = get_post_meta($post->ID, '_species_scientific_name', true);
    $family = get_post_meta($post->ID, '_species_family', true);
    $origin = get_post_meta($post->ID, '_species_origin', true);
    $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
    $bibliography = get_post_meta($post->ID, '_species_bibliography', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="species_scientific_name">Nazwa naukowa:</label></th>
            <td>
                <input type="text" id="species_scientific_name" name="species_scientific_name" 
                       value="<?php echo esc_attr($scientific_name); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="species_family">Rodzina:</label></th>
            <td>
                <input type="text" id="species_family" name="species_family" 
                       value="<?php echo esc_attr($family); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="species_origin">Pochodzenie:</label></th>
            <td>
                <input type="text" id="species_origin" name="species_origin" 
                       value="<?php echo esc_attr($origin); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="species_difficulty">Trudność uprawy:</label></th>
            <td>
                <select id="species_difficulty" name="species_difficulty">
                    <option value="">Wybierz poziom</option>
                    <option value="Łatwa" <?php selected($difficulty, 'Łatwa'); ?>>Łatwa</option>
                    <option value="Średnia" <?php selected($difficulty, 'Średnia'); ?>>Średnia</option>
                    <option value="Trudna" <?php selected($difficulty, 'Trudna'); ?>>Trudna</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="species_bibliography">Bibliografia:</label></th>
            <td>
                <textarea id="species_bibliography" name="species_bibliography" 
                          rows="4" class="large-text"><?php echo esc_textarea($bibliography); ?></textarea>
                <p class="description">Źródła i literatura dotycząca gatunku.</p>
            </td>
        </tr>
    </table>
    <?php
}

// Callback for species gallery meta box
function carni24_species_gallery_callback($post) {
    $gallery_images = get_post_meta($post->ID, '_species_gallery', true);
    ?>
    <div id="species-gallery-container">
        <div id="species-gallery-images">
            <?php if ($gallery_images && is_array($gallery_images)) : ?>
                <?php foreach ($gallery_images as $image_id) : 
                    $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                    if ($image_url) : ?>
                        <div class="gallery-image-item" data-id="<?php echo esc_attr($image_id); ?>">
                            <img src="<?php echo esc_url($image_url); ?>" />
                            <button type="button" class="remove-gallery-image">×</button>
                        </div>
                    <?php endif;
                endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" id="add-gallery-image" class="button">Dodaj zdjęcia do galerii</button>
        <input type="hidden" id="species_gallery" name="species_gallery" 
               value="<?php echo esc_attr(is_array($gallery_images) ? implode(',', $gallery_images) : ''); ?>" />
    </div>
    
    <style>
    #species-gallery-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }
    .gallery-image-item {
        position: relative;
        width: 80px;
        height: 80px;
    }
    .gallery-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }
    .remove-gallery-image {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Add gallery images
        $('#add-gallery-image').click(function(e) {
            e.preventDefault();
            
            var frame = wp.media({
                title: 'Wybierz zdjęcia do galerii',
                multiple: true,
                library: { type: 'image' },
                button: { text: 'Dodaj do galerii' }
            });
            
            frame.on('select', function() {
                var selection = frame.state().get('selection');
                var current_ids = $('#species_gallery').val().split(',').filter(id => id !== '');
                
                selection.map(function(attachment) {
                    var id = attachment.get('id');
                    var url = attachment.get('url');
                    
                    if (current_ids.indexOf(id.toString()) === -1) {
                        var thumbnail = attachment.get('sizes').thumbnail || { url: url };
                        
                        $('#species-gallery-images').append(
                            '<div class="gallery-image-item" data-id="' + id + '">' +
                            '<img src="' + thumbnail.url + '" />' +
                            '<button type="button" class="remove-gallery-image">×</button>' +
                            '</div>'
                        );
                        
                        current_ids.push(id.toString());
                    }
                });
                
                $('#species_gallery').val(current_ids.join(','));
            });
            
            frame.open();
        });
        
        // Remove gallery image
        $(document).on('click', '.remove-gallery-image', function() {
            var $item = $(this).closest('.gallery-image-item');
            var id = $item.data('id');
            var current_ids = $('#species_gallery').val().split(',').filter(item => item !== id.toString() && item !== '');
            
            $item.remove();
            $('#species_gallery').val(current_ids.join(','));
        });
    });
    </script>
    <?php
}

// Callback for species map meta box
function carni24_species_map_callback($post) {
    $map_image = get_post_meta($post->ID, '_species_map_image', true);
    ?>
    <div id="species-map-container">
        <div id="species-map-preview">
            <?php if ($map_image) : 
                $map_url = wp_get_attachment_image_url($map_image, 'medium');
                if ($map_url) : ?>
                    <img src="<?php echo esc_url($map_url); ?>" style="max-width: 100%; height: auto;" />
                <?php endif;
            endif; ?>
        </div>
        <button type="button" id="upload-map-image" class="button">
            <?php echo $map_image ? 'Zmień mapę' : 'Dodaj mapę rozmieszczenia'; ?>
        </button>
        <?php if ($map_image) : ?>
            <button type="button" id="remove-map-image" class="button">Usuń mapę</button>
        <?php endif; ?>
        <input type="hidden" id="species_map_image" name="species_map_image"
               value="<?php echo esc_attr($map_image); ?>" />
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Upload map image
        $('#upload-map-image').click(function(e) {
            e.preventDefault();
            
            var frame = wp.media({
                title: 'Wybierz mapę rozmieszczenia',
                multiple: false,
                library: { type: 'image' },
                button: { text: 'Użyj tej mapy' }
            });
            
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                var thumbnail = attachment.sizes.medium || attachment;
                
                $('#species-map-preview').html('<img src="' + thumbnail.url + '" style="max-width: 100%; height: auto;" />');
                $('#species_map_image').val(attachment.id);
                $('#upload-map-image').text('Zmień mapę');
                
                if ($('#remove-map-image').length === 0) {
                    $('#upload-map-image').after('<button type="button" id="remove-map-image" class="button">Usuń mapę</button>');
                }
            });
            
            frame.open();
        });
        
        // Remove map image
        $(document).on('click', '#remove-map-image', function() {
            $('#species-map-preview').empty();
            $('#species_map_image').val('');
            $('#upload-map-image').text('Dodaj mapę rozmieszczenia');
            $(this).remove();
        });
    });
    </script>
    <?php
}

// Save meta box data
function carni24_save_species_meta_boxes($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['carni24_species_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['carni24_species_meta_box_nonce'], 'carni24_species_meta_box')) {
        return;
    }
    
    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Don't save during autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Save species fields
    $fields = array(
        'species_scientific_name' => '_species_scientific_name',
        'species_family' => '_species_family',
        'species_origin' => '_species_origin',
        'species_difficulty' => '_species_difficulty',
        'species_bibliography' => '_species_bibliography',
        'species_map_image' => '_species_map_image'
    );
    
    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save gallery images
    if (isset($_POST['species_gallery'])) {
        $gallery_ids = array_filter(explode(',', $_POST['species_gallery']));
        $gallery_ids = array_map('intval', $gallery_ids);
        update_post_meta($post_id, '_species_gallery', $gallery_ids);
    }
}
add_action('save_post', 'carni24_save_species_meta_boxes');

// Add meta box for guides bibliography (if not already exists)
function carni24_guides_bibliography_meta_box() {
    add_meta_box(
        'guides_bibliography',
        'Bibliografia i źródła',
        'carni24_guides_bibliography_callback',
        'guides',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_guides_bibliography_meta_box');

// Callback for guides bibliography meta box
function carni24_guides_bibliography_callback($post) {
    wp_nonce_field('carni24_guides_bibliography_meta_box', 'carni24_guides_bibliography_nonce');
    
    $bibliography = get_post_meta($post->ID, '_guide_bibliography', true);
    
    ?>
    <table class="form-table">
        <tr>
            <td>
                <textarea id="guide_bibliography" name="guide_bibliography" 
                          rows="6" class="large-text"><?php echo esc_textarea($bibliography); ?></textarea>
                <p class="description">Źródła, literatura i materiały referencyjne dla tego poradnika.</p>
            </td>
        </tr>
    </table>
    <?php
}

// Save guides bibliography
function carni24_save_guides_bibliography($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['carni24_guides_bibliography_nonce']) || 
        !wp_verify_nonce($_POST['carni24_guides_bibliography_nonce'], 'carni24_guides_bibliography_meta_box')) {
        return;
    }
    
    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Don't save during autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Save bibliography
    if (isset($_POST['guide_bibliography'])) {
        update_post_meta($post_id, '_guide_bibliography', wp_kses_post($_POST['guide_bibliography']));
    }
}
add_action('save_post', 'carni24_save_guides_bibliography');

// Enqueue media uploader on species edit pages
function carni24_enqueue_media_uploader() {
    global $pagenow, $typenow;
    
    if (in_array($pagenow, array('post.php', 'post-new.php')) && $typenow === 'species') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'carni24_enqueue_media_uploader');

// Add custom columns to species admin list
function carni24_species_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['species_scientific'] = 'Nazwa naukowa';
    $new_columns['species_family'] = 'Rodzina';
    $new_columns['species_difficulty'] = 'Trudność';
    $new_columns['featured_image'] = 'Zdjęcie';
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_species_posts_columns', 'carni24_species_admin_columns');

// Display custom column content for species
function carni24_species_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'species_scientific':
            $scientific = get_post_meta($post_id, '_species_scientific_name', true);
            echo $scientific ? '<em>' . esc_html($scientific) . '</em>' : '—';
            break;
            
        case 'species_family':
            $family = get_post_meta($post_id, '_species_family', true);
            echo $family ? esc_html($family) : '—';
            break;
            
        case 'species_difficulty':
            $difficulty = get_post_meta($post_id, '_species_difficulty', true);
            if ($difficulty) {
                $class = '';
                switch ($difficulty) {
                    case 'Łatwa':
                        $class = 'difficulty-easy';
                        break;
                    case 'Średnia':
                        $class = 'difficulty-medium';
                        break;
                    case 'Trudna':
                        $class = 'difficulty-hard';
                        break;
                }
                echo '<span class="species-difficulty ' . esc_attr($class) . '">' . esc_html($difficulty) . '</span>';
            } else {
                echo '—';
            }
            break;
            
        case 'featured_image':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_species_posts_custom_column', 'carni24_species_admin_columns_content', 10, 2);

// Add admin styles for species columns
function carni24_species_admin_styles() {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'species') {
        ?>
        <style>
        .species-difficulty {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .difficulty-easy {
            background: #d4edda;
            color: #155724;
        }
        .difficulty-medium {
            background: #fff3cd;
            color: #856404;
        }
        .difficulty-hard {
            background: #f8d7da;
            color: #721c24;
        }
        .column-featured_image {
            width: 60px;
        }
        .column-species_scientific {
            width: 200px;
        }
        .column-species_family {
            width: 150px;
        }
        .column-species_difficulty {
            width: 100px;
        }
        </style>
        <?php
    }
}
add_action('admin_head', 'carni24_species_admin_styles');
?>

        <?php
/**
 * Fragment functions.php - obsługa sortowania gatunków
 * Dodaj ten kod do swojego functions.php
 */

// ===== SORTOWANIE GATUNKÓW =====
function carni24_modify_species_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('species')) {
        
        // Domyślne sortowanie - najnowsze
        if (!isset($_GET['orderby'])) {
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
            return;
        }
        
        $orderby = sanitize_text_field($_GET['orderby']);
        
        switch ($orderby) {
            case 'title':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                break;
                
            case 'title-desc':
                $query->set('orderby', 'title');
                $query->set('order', 'DESC');
                break;
                
            case 'difficulty':
                $query->set('meta_key', '_species_difficulty');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                // Niestandardowe sortowanie trudności
                add_filter('posts_orderby', 'carni24_custom_difficulty_orderby');
                break;
                
            case 'popularity':
                $query->set('meta_key', '_species_views');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            default:
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
        }
    }
}
add_action('pre_get_posts', 'carni24_modify_species_query');

// ===== NIESTANDARDOWE SORTOWANIE TRUDNOŚCI =====
function carni24_custom_difficulty_orderby($orderby) {
    global $wpdb;
    
    // Usuń filtr, żeby nie był wywoływany w nieskończoność
    remove_filter('posts_orderby', 'carni24_custom_difficulty_orderby');
    
    // Niestandardowe sortowanie: łatwy -> średni -> trudny
    $orderby = "
        CASE {$wpdb->postmeta}.meta_value 
            WHEN 'łatwy' THEN 1
            WHEN 'easy' THEN 1
            WHEN 'średni' THEN 2
            WHEN 'medium' THEN 2
            WHEN 'trudny' THEN 3
            WHEN 'hard' THEN 3
            ELSE 4
        END ASC, {$wpdb->posts}.post_title ASC
    ";
    
    return $orderby;
}

// ===== SYSTEM POPULARNOŚCI (LICZNIK WYŚWIETLEŃ) =====
function carni24_track_species_views() {
    if (is_singular('species')) {
        $post_id = get_the_ID();
        $views = get_post_meta($post_id, '_species_views', true);
        $views = $views ? intval($views) + 1 : 1;
        update_post_meta($post_id, '_species_views', $views);
        
        // Opcjonalnie: zapisz szczegóły wyświetlenia
        $view_data = array(
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'timestamp' => current_time('mysql'),
            'referrer' => $_SERVER['HTTP_REFERER'] ?? ''
        );
        
        // Zapisz w osobnej tabeli lub meta (dla szczegółowych analiz)
        $views_history = get_post_meta($post_id, '_species_views_history', true) ?: array();
        
        // Zachowaj tylko ostatnie 100 wyświetleń, żeby nie przeciążać bazy
        if (count($views_history) >= 100) {
            $views_history = array_slice($views_history, -50);
        }
        
        $views_history[] = $view_data;
        update_post_meta($post_id, '_species_views_history', $views_history);
    }
}
add_action('wp_head', 'carni24_track_species_views');

// ===== HELPER: POBIERZ LICZBĘ WYŚWIETLEŃ =====
function carni24_get_species_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $views = get_post_meta($post_id, '_species_views', true);
    return $views ? intval($views) : 0;
}

// ===== HELPER: FORMATUJ LICZBĘ WYŚWIETLEŃ =====
function carni24_format_views_count($count) {
    if ($count >= 1000000) {
        return round($count / 1000000, 1) . 'M';
    } elseif ($count >= 1000) {
        return round($count / 1000, 1) . 'k';
    }
    return number_format($count);
}

// ===== AJAX ENDPOINT DLA SORTOWANIA (opcjonalny) =====
function carni24_ajax_load_species() {
    check_ajax_referer('carni24_species_nonce', 'nonce');
    
    $orderby = sanitize_text_field($_POST['orderby'] ?? 'date');
    $paged = intval($_POST['paged'] ?? 1);
    
    $args = array(
        'post_type' => 'species',
        'posts_per_page' => 12,
        'paged' => $paged,
    );
    
    // Zastosuj to samo sortowanie co w carni24_modify_species_query
    switch ($orderby) {
        case 'title':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'title-desc':
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
            break;
        case 'difficulty':
            $args['meta_key'] = '_species_difficulty';
            $args['orderby'] = 'meta_value';
            $args['order'] = 'ASC';
            break;
        case 'popularity':
            $args['meta_key'] = '_species_views';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
    }
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Renderuj template part lub HTML karty gatunku
            get_template_part('template-parts/species/card');
        }
    }
    
    $html = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success(array(
        'html' => $html,
        'found_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages
    ));
}
add_action('wp_ajax_load_species', 'carni24_ajax_load_species');
add_action('wp_ajax_nopriv_load_species', 'carni24_ajax_load_species');

// ===== ENQUEUE SCRIPTS I STYLES =====
function carni24_enqueue_species_assets() {
    if (is_post_type_archive('species')) {
        wp_enqueue_style(
            'carni24-species-archive',
            get_template_directory_uri() . '/assets/css/pages/species-archive.css',
            array(),
            wp_get_theme()->get('Version')
        );
        
        wp_enqueue_script(
            'carni24-species-archive',
            get_template_directory_uri() . '/assets/js/species-archive.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        
        // Lokalizacja dla AJAX
        wp_localize_script('carni24-species-archive', 'carni24_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carni24_species_nonce'),
            'loading_text' => 'Ładowanie...',
            'error_text' => 'Wystąpił błąd podczas ładowania.',
        ));
    }
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_species_assets');

// ===== DODAJ META FIELDS DO EDYTORA (jeśli jeszcze nie ma) =====
function carni24_add_species_meta_boxes() {
    add_meta_box(
        'species-details',
        'Szczegóły gatunku',
        'carni24_species_meta_box_callback',
        'species',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'carni24_add_species_meta_boxes');

function carni24_species_meta_box_callback($post) {
    wp_nonce_field('carni24_species_meta', 'carni24_species_meta_nonce');
    
    $scientific_name = get_post_meta($post->ID, '_species_scientific_name', true);
    $origin = get_post_meta($post->ID, '_species_origin', true);
    $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
    $light = get_post_meta($post->ID, '_species_light', true);
    $water = get_post_meta($post->ID, '_species_water', true);
    $views = get_post_meta($post->ID, '_species_views', true) ?: 0;
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="species_scientific_name">Nazwa naukowa</label></th>
            <td><input type="text" id="species_scientific_name" name="species_scientific_name" value="<?= esc_attr($scientific_name) ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="species_origin">Pochodzenie</label></th>
            <td><input type="text" id="species_origin" name="species_origin" value="<?= esc_attr($origin) ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="species_difficulty">Poziom trudności</label></th>
            <td>
                <select id="species_difficulty" name="species_difficulty">
                    <option value="">-- Wybierz --</option>
                    <option value="łatwy" <?= selected($difficulty, 'łatwy', false) ?>>Łatwy</option>
                    <option value="średni" <?= selected($difficulty, 'średni', false) ?>>Średni</option>
                    <option value="trudny" <?= selected($difficulty, 'trudny', false) ?>>Trudny</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="species_light">Wymagania świetlne</label></th>
            <td><input type="text" id="species_light" name="species_light" value="<?= esc_attr($light) ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="species_water">Wymagania wodne</label></th>
            <td><input type="text" id="species_water" name="species_water" value="<?= esc_attr($water) ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th>Wyświetlenia</th>
            <td><?= number_format($views) ?> <small>(automatycznie liczone)</small></td>
        </tr>
    </table>
    <?php
}

function carni24_save_species_meta($post_id) {
    if (!isset($_POST['carni24_species_meta_nonce']) || !wp_verify_nonce($_POST['carni24_species_meta_nonce'], 'carni24_species_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        'species_scientific_name' => '_species_scientific_name',
        'species_origin' => '_species_origin',
        'species_difficulty' => '_species_difficulty',
        'species_light' => '_species_light',
        'species_water' => '_species_water'
    );
    
    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'carni24_save_species_meta');

?>