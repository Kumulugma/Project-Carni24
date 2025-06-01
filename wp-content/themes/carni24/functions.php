<?php

//Register headerAsset
include("components/headerAsset.php");
//Register Species
include("post-types/species.php");
//Register Pagination
include("includes/pagination.php");
//Register Title Separator
include("includes/titleSeparator.php");
//Register Read More
include("includes/readMore.php");
//Gallery count
include("includes/galleryCount.php");
//Menu Link Class
include("includes/menuAClass.php");
//Breadcrumbs
include("includes/breadcrumbs.php");
//Spec ID
include("includes/specID.php");
//Sitemap
include("includes/sitemap.php");
//Theme Options
include("includes/theme-options.php");
//SEO Functions
include("includes/seo.php");
//Image Sizes
include("includes/image-sizes.php");
//Meta Boxes
include("includes/meta-boxes.php");
//Admin Functions
include("includes/admin.php");


function mytheme_enqueue_admin_assets($hook) {
    // Opcjonalnie – ogranicz do konkretnego hooka (np. tylko do theme options page)
    // if ($hook !== 'toplevel_page_theme-options') {
    //     return;
    // }

    $css_url = get_template_directory_uri() . '/assets/admin/css/admin-theme-options.css';
    $js_url  = get_template_directory_uri() . '/assets/admin/js/admin-theme-options.js';

    // Dodaj CSS
    wp_enqueue_style('mytheme-admin-style', $css_url, [], filemtime(get_template_directory() . '/assets/admin/css/admin-theme-options.css'));

    // Dodaj JS
    wp_enqueue_script('mytheme-admin-script', $js_url, ['jquery'], filemtime(get_template_directory() . '/assets/admin/js/admin-theme-options.js'), true);
}
add_action('admin_enqueue_scripts', 'mytheme_enqueue_admin_assets');


// Basic theme support
add_theme_support('post-thumbnails');

// Włącz obsługę title-tag w motywie
add_theme_support('title-tag');

// Setup theme support
function carni24_setup_theme_support() {
    // Dodaj obsługę title-tag
    add_theme_support('title-tag');
    
    // Dodaj obsługę innych funkcji
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'carni24_setup_theme_support');

// Helper function for theme options
if (!function_exists('carni24_get_option')) {
    function carni24_get_option($option_name, $default = '') {
        return get_option('carni24_' . $option_name, $default);
    }
}