<?php
/**
 * Carni24 SEO Meta Tags
 * System generowania meta tagów SEO, Open Graph i Twitter Cards
 * 
 * @package Carni24
 * @subpackage SEO
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Główna funkcja generująca meta tagi SEO
 */
function carni24_seo_meta_tags() {
    global $post;
    
    $meta_title = '';
    $meta_description = '';
    $meta_keywords = '';
    $canonical_url = '';
    $noindex = false;
    $nofollow = false;
    $og_title = '';
    $og_description = '';
    $og_image = '';
    $og_url = '';
    
    // Pobierz meta z wpisu/strony
    if (is_singular()) {
        $meta_title = get_post_meta($post->ID, '_seo_title', true);
        $meta_description = get_post_meta($post->ID, '_seo_description', true);
        $meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);
        $canonical_url = get_post_meta($post->ID, '_seo_canonical', true);
        $noindex = get_post_meta($post->ID, '_seo_noindex', true);
        $nofollow = get_post_meta($post->ID, '_seo_nofollow', true);
        $og_title = get_post_meta($post->ID, '_seo_og_title', true);
        $og_description = get_post_meta($post->ID, '_seo_og_description', true);
        $og_image_id = get_post_meta($post->ID, '_seo_og_image', true);
        
        if ($og_image_id) {
            $og_image = wp_get_attachment_image_url($og_image_id, 'large');
        }
        
        $og_url = get_permalink($post->ID);
    }
    
    // === FALLBACKS ===
    
    // Meta Title
    if (empty($meta_title)) {
        if (is_front_page() || is_home()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $site_description = carni24_get_option('site_description', get_bloginfo('description'));
            if (!empty($site_name) && !empty($site_description)) {
                $meta_title = $site_name . ' - ' . $site_description;
            } else {
                $meta_title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
            }
        } elseif (is_singular()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = get_the_title() . ' - ' . $site_name;
        } elseif (is_category()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = single_cat_title('', false) . ' - ' . $site_name;
        } elseif (is_tag()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = single_tag_title('', false) . ' - ' . $site_name;
        } elseif (is_search()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = 'Wyniki wyszukiwania: ' . get_search_query() . ' - ' . $site_name;
        } else {
            $meta_title = wp_get_document_title();
        }
    }
    
    // Meta Description  
    if (empty($meta_description)) {
        if (is_front_page() || is_home()) {
            $meta_description = carni24_get_option('default_meta_description', '');
            if (empty($meta_description)) {
                $meta_description = carni24_get_option('site_description', get_bloginfo('description'));
            }
        } elseif (is_singular()) {
            $meta_description = carni24_get_custom_excerpt($post->ID, 25);
        } elseif (is_category()) {
            $meta_description = category_description();
            if (empty($meta_description)) {
                $meta_description = 'Kategoria: ' . single_cat_title('', false);
            }
        } elseif (is_tag()) {
            $meta_description = tag_description();
            if (empty($meta_description)) {
                $meta_description = 'Tag: ' . single_tag_title('', false);
            }
        } else {
            $meta_description = carni24_get_option('default_meta_description', '');
            if (empty($meta_description)) {
                $meta_description = carni24_get_option('site_description', get_bloginfo('description'));
            }
        }
    }
    
    // Meta Keywords
    if (empty($meta_keywords)) {
        $meta_keywords = carni24_get_option('default_meta_keywords', '');
    }
    
    // Canonical URL
    if (empty($canonical_url)) {
        if (is_front_page() || is_home()) {
            $canonical_url = home_url('/');
        } elseif (is_singular()) {
            $canonical_url = get_permalink();
        } else {
            $canonical_url = home_url(add_query_arg(array(), $GLOBALS['wp']->request));
        }
    }
    
    // Open Graph Title
    if (empty($og_title)) {
        $og_title = $meta_title;
    }
    
    // Open Graph Description
    if (empty($og_description)) {
        $og_description = $meta_description;
    }
    
    // Open Graph Image
    if (empty($og_image)) {
        if (is_singular() && has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url($post->ID, 'large');
        } else {
            $default_og_image_id = carni24_get_option('default_og_image', '');
            if ($default_og_image_id) {
                $og_image = wp_get_attachment_image_url($default_og_image_id, 'large');
            }
        }
    }
    
    // Open Graph URL
    if (empty($og_url)) {
        $og_url = $canonical_url;
    }
    
    // === OUTPUT META TAGS ===
    echo "\n<!-- Carni24 SEO -->\n";
    
    if ($meta_title) {
        echo '<meta name="title" content="' . esc_attr($meta_title) . '">' . "\n";
    }
    
    if ($meta_description) {
        echo '<meta name="description" content="' . esc_attr(wp_trim_words($meta_description, 25)) . '">' . "\n";
    }
    
    if ($meta_keywords) {
        echo '<meta name="keywords" content="' . esc_attr($meta_keywords) . '">' . "\n";
    }
    
    if ($canonical_url) {
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
    }
    
    // Robots meta
    $robots = array();
    if ($noindex) $robots[] = 'noindex';
    if ($nofollow) $robots[] = 'nofollow';
    
    if (!empty($robots)) {
        echo '<meta name="robots" content="' . implode(', ', $robots) . '">' . "\n";
    }
    
    // Open Graph
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(wp_trim_words($og_description, 25)) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
    echo '<meta property="og:type" content="' . (is_singular() ? 'article' : 'website') . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(carni24_get_option('site_name', get_bloginfo('name'))) . '">' . "\n";
    
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        echo '<meta property="og:image:width" content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
    }
    
    // Twitter Cards
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr(wp_trim_words($og_description, 25)) . '">' . "\n";
    
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '">' . "\n";
    }
    
    echo "<!-- /Carni24 SEO -->\n\n";
}
add_action('wp_head', 'carni24_seo_meta_tags', 1);

/**
 * Custom title dla WordPress
 */
function carni24_document_title_parts($title) {
    if (is_front_page() || is_home()) {
        $site_name = carni24_get_option('site_name', get_bloginfo('name'));
        $site_description = carni24_get_option('site_description', get_bloginfo('description'));
        
        if (!empty($site_name) && !empty($site_description)) {
            $title['title'] = $site_name;
            $title['tagline'] = $site_description;
        }
    }
    
    return $title;
}
add_filter('document_title_parts', 'carni24_document_title_parts');

/**
 * SEO testing functions
 */
function carni24_quick_seo_test() {
    $results = array();
    
    $results['site_name'] = !empty(carni24_get_option('site_name', ''));
    $results['site_description'] = !empty(carni24_get_option('site_description', ''));
    $results['default_meta_description'] = !empty(carni24_get_option('default_meta_description', ''));
    $results['default_meta_keywords'] = !empty(carni24_get_option('default_meta_keywords', ''));
    $results['default_og_image'] = !empty(carni24_get_option('default_og_image', ''));
    $results['seo_function_hooked'] = has_action('wp_head', 'carni24_seo_meta_tags');
    
    return $results;
}