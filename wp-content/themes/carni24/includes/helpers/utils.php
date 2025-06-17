<?php
/**
 * Carni24 Utility Functions
 * Uniwersalne funkcje pomocnicze motywu
 * 
 * @package Carni24
 * @subpackage Helpers
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Pobiera opcję motywu z fallbackiem
 */
if (!function_exists('carni24_get_option')) {
    function carni24_get_option($option_name, $default = '') {
        $options = get_option('carni24_theme_options', array());
        return isset($options[$option_name]) ? $options[$option_name] : $default;
    }
}

/**
 * Oblicza czas czytania artykułu
 */
if (!function_exists('carni24_calculate_reading_time')) {
    function carni24_calculate_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 słów na minutę
        return max(1, $reading_time);
    }
}

/**
 * Pobiera custom excerpt z fallbackiem
 */
if (!function_exists('carni24_get_custom_excerpt')) {
    function carni24_get_custom_excerpt($post_id = null, $fallback_words = 20) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        // Sprawdź custom excerpt
        $custom_excerpt = get_post_meta($post_id, '_custom_excerpt', true);
        if (!empty($custom_excerpt)) {
            return $custom_excerpt;
        }
        
        // WordPress excerpt
        $excerpt = get_the_excerpt($post_id);
        if (!empty($excerpt)) {
            return $excerpt;
        }
        
        // Fragment treści
        $content = get_post_field('post_content', $post_id);
        $content = wp_strip_all_tags($content);
        return wp_trim_words($content, $fallback_words, '...');
    }
}

/**
 * Ustawia licznik wyświetleń posta
 */
if (!function_exists('carni24_set_post_views')) {
    function carni24_set_post_views($post_id) {
        $count_key = 'post_views_count';
        $count = get_post_meta($post_id, $count_key, true);
        
        if ($count == '') {
            $count = 0;
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, '0');
        } else {
            $count++;
            update_post_meta($post_id, $count_key, $count);
        }
    }
}

/**
 * Pobiera licznik wyświetleń posta
 */
if (!function_exists('carni24_get_post_views')) {
    function carni24_get_post_views($post_id) {
        $count_key = 'post_views_count';
        $count = get_post_meta($post_id, $count_key, true);
        return ($count == '') ? 0 : $count;
    }
}

/**
 * Automatycznie śledzi wyświetlenia postów
 */
function carni24_track_post_views($post_id = null) {
    if (!is_single()) return;
    
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;    
    }
    
    // Nie zliczaj dla adminów
    if (current_user_can('manage_options')) return;
    
    carni24_set_post_views($post_id);
}
add_action('wp_head', 'carni24_track_post_views');

/**
 * Pobiera miniaturę z fallbackiem
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
 * Formatuje datę po polsku
 */
if (!function_exists('carni24_format_date')) {
    function carni24_format_date($date = null, $format = 'j F Y') {
        if (!$date) {
            $date = get_the_date('Y-m-d');
        }
        
        $months = array(
            'January'   => 'stycznia',
            'February'  => 'lutego', 
            'March'     => 'marca',
            'April'     => 'kwietnia',
            'May'       => 'maja',
            'June'      => 'czerwca',
            'July'      => 'lipca',
            'August'    => 'sierpnia',
            'September' => 'września',
            'October'   => 'października',
            'November'  => 'listopada',
            'December'  => 'grudnia'
        );
        
        $formatted_date = date_i18n($format, strtotime($date));
        
        foreach ($months as $english => $polish) {
            $formatted_date = str_replace($english, $polish, $formatted_date);
        }
        
        return $formatted_date;
    }
}

/**
 * Bezpieczne pobieranie URL z attachment
 */
if (!function_exists('carni24_get_attachment_url')) {
    function carni24_get_attachment_url($attachment_id, $size = 'full') {
        if (!$attachment_id) return false;
        
        $url = wp_get_attachment_image_url($attachment_id, $size);
        return $url ? esc_url($url) : false;
    }
}

/**
 * Sprawdza czy post ma określoną kategorię (działa też z CPT)
 */
if (!function_exists('carni24_has_term')) {
    function carni24_has_term($term, $taxonomy = 'category', $post_id = null) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        return has_term($term, $taxonomy, $post_id);
    }
}

/**
 * Debug helper - wyświetla informacje tylko dla adminów
 */
if (!function_exists('carni24_debug')) {
    function carni24_debug($data, $die = false) {
        if (current_user_can('manage_options') && isset($_GET['debug'])) {
            echo '<pre style="background: #f1f1f1; padding: 15px; margin: 15px; border-left: 4px solid #0073aa;">';
            print_r($data);
            echo '</pre>';
            
            if ($die) {
                die();
            }
        }
    }
}

// Sprawdza czy post jest wyróżniony
if (!function_exists('carni24_is_featured_post')) {
    function carni24_is_featured_post($post_id = null) {
        if (!$post_id) {
            global $post;
            if (!$post || !isset($post->ID)) {
                return false;
            }
            $post_id = $post->ID;
        }
        
        return get_post_meta($post_id, '_is_featured', true) == '1';
    }
}

// Sprawdza czy sidebar powinien być pokazany
if (!function_exists('carni24_show_sidebar')) {
    function carni24_show_sidebar() {
        // Ukryj sidebar na stronie głównej
        if (is_front_page()) {
            return false;
        }
        
        // Ukryj sidebar na single species/guides
        if (is_singular(array('species', 'guides'))) {
            return false;
        }
        
        // Pokaż sidebar na archiwach i blogach
        if (is_home() || is_archive() || is_search()) {
            return true;
        }
        
        // Domyślnie pokaż sidebar
        return true;
    }
}

// Pobiera liczbę wyświetleń posta
if (!function_exists('carni24_get_post_views')) {
    function carni24_get_post_views($post_id = null) {
        if (!$post_id) {
            global $post;
            if (!$post || !isset($post->ID)) {
                return 0;
            }
            $post_id = $post->ID;
        }
        
        $views = get_post_meta($post_id, 'post_views_count', true);
        return $views ? intval($views) : 0;
    }
}

// Zwiększa licznik wyświetleń
if (!function_exists('carni24_set_post_views')) {
    function carni24_set_post_views($post_id = null) {
        if (!$post_id) {
            global $post;
            if (!$post || !isset($post->ID)) {
                return;
            }
            $post_id = $post->ID;
        }
        
        $key = 'post_views_count';
        $count = get_post_meta($post_id, $key, true);
        
        if ($count == '') {
            $count = 0;
            delete_post_meta($post_id, $key);
            add_post_meta($post_id, $key, '1');
        } else {
            $count++;
            update_post_meta($post_id, $key, $count);
        }
    }
}

// Automatycznie zwiększ licznik wyświetleń na single postach
if (!function_exists('carni24_track_post_views')) {
    function carni24_track_post_views() {
        if (is_singular() && !is_user_logged_in()) {
            carni24_set_post_views();
        }
    }
    add_action('wp_head', 'carni24_track_post_views');
}