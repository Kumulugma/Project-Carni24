<?php
/**
 * Carni24 WordPress Theme - Main Functions File
 * Uporządkowany system ładowania modułów
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
require_once CARNI24_THEME_PATH . '/includes/core/theme-setup.php';     // Podstawowe wsparcie motywu i funkcje WordPress

// ===== ASSETS I SKRYPTY ===== //
require_once CARNI24_THEME_PATH . '/includes/core/assets.php';          // Ładowanie CSS/JS, system conditional loading

// ===== FUNKCJE POMOCNICZE ===== //
require_once CARNI24_THEME_PATH . '/includes/helpers/polish-numbers.php';  // Funkcje do polskiej odmiany liczebników
require_once CARNI24_THEME_PATH . '/includes/helpers/utils.php';           // Uniwersalne funkcje pomocnicze (views, reading time, excerpts)

// ===== OPTYMALIZACJE I KONFIGURACJA ===== //
require_once CARNI24_THEME_PATH . '/includes/optimization/disable-comments.php';  // Kompletne wyłączenie komentarzy
require_once CARNI24_THEME_PATH . '/includes/optimization/image-sizes.php';       // Custom rozmiary obrazów i lazy loading

// ===== SEO I NAWIGACJA ===== //
require_once CARNI24_THEME_PATH . '/includes/seo/meta-tags.php';        // SEO meta tagi, Open Graph, Twitter Cards
require_once CARNI24_THEME_PATH . '/includes/seo/breadcrumbs.php';      // Breadcrumbs z JSON-LD structured data
require_once CARNI24_THEME_PATH . '/includes/seo/schema.php';           // JSON-LD schema markup

// ===== CUSTOM POST TYPES I TAXONOMIE ===== //
require_once CARNI24_THEME_PATH . '/includes/post-types/species.php';   // Custom Post Type dla gatunków roślin
require_once CARNI24_THEME_PATH . '/includes/post-types/guides.php';    // Custom Post Type dla poradników

// ===== META BOXES I CUSTOM FIELDS ===== //
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/seo-fields.php';      // Meta boxy SEO dla postów i stron
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/species-fields.php';  // Meta boxy dla gatunków (pochodzenie, trudność)
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/feature-fields.php';  // Meta boxy dla wyróżnionych treści
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/guides-fields.php';
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/custom-excerpt.php';

// ===== ADMIN I THEME OPTIONS ===== //
if (is_admin()) {
    require_once CARNI24_THEME_PATH . '/includes/admin/theme-options.php';     // Panel ustawień motywu
    require_once CARNI24_THEME_PATH . '/includes/admin/admin-assets.php';      // CSS/JS dla panelu admina
    require_once CARNI24_THEME_PATH . '/includes/admin/dashboard-widgets.php'; // Widgety dashboard
    require_once CARNI24_THEME_PATH . '/includes/admin/seo-monitor.php';       // DODAJ TĘ LINIĘ - Monitor SEO
    require_once CARNI24_THEME_PATH . '/includes/admin/featured-image-columns.php';
}

// ===== FRONTEND FEATURES ===== //
require_once CARNI24_THEME_PATH . '/includes/frontend/navigation.php';   // Menu i nawigacja
require_once CARNI24_THEME_PATH . '/includes/frontend/widgets.php';      // Widget areas i sidebar
require_once CARNI24_THEME_PATH . '/includes/frontend/filters.php';      // Filtry treści i body classes

// ===== AJAX I API ===== //
require_once CARNI24_THEME_PATH . '/includes/ajax/admin-handlers.php';   // AJAX handlers dla panelu admina
require_once CARNI24_THEME_PATH . '/includes/ajax/frontend-handlers.php'; // AJAX handlers dla frontend

// ===== COMPATIBILITY I HOOKS ===== //
require_once CARNI24_THEME_PATH . '/includes/compatibility/plugins.php'; // Kompatybilność z popularnymi wtyczkami
require_once CARNI24_THEME_PATH . '/includes/hooks/theme-hooks.php';     // Custom hooks i filtry motywu

function carni24_unified_search_query($query) {
    // Tylko dla głównej query, frontend i search
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }
    
    // DEBUG: Loguj co się dzieje
    error_log('=== SEARCH DEBUG START ===');
    error_log('Search term: ' . get_search_query());
    error_log('Original post_type: ' . print_r($query->get('post_type'), true));
    
    // 1. USTAW TYPY POSTÓW
    $query->set('post_type', array('post', 'page', 'species', 'guides'));
    
    // 2. ZWIĘKSZ LIMIT WYNIKÓW
    $query->set('posts_per_page', 20);
    
    // 3. DODAJ SORTOWANIE (jeśli jest w URL)
    $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'relevance';
    
    switch ($orderby) {
        case 'title':
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
            break;
        case 'date':
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
            break;
        case 'relevance':
        default:
            // WordPress default search relevance
            break;
    }
    
    // 4. ROZSZERZ WYSZUKIWANIE O CUSTOM FIELDS
    $search_term = get_search_query();
    
    if (!empty($search_term) && strlen($search_term) >= 2) {
        // Dodaj meta query dla custom fields
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key' => '_species_scientific_name',
                'value' => $search_term,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_species_origin',
                'value' => $search_term,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_species_family',
                'value' => $search_term,
                'compare' => 'LIKE'
            ),
            array(
                'key' => '_species_common_names',
                'value' => $search_term,
                'compare' => 'LIKE'
            )
        );
        
        $query->set('meta_query', $meta_query);
    }
    
    // DEBUG: Loguj końcową konfigurację
    error_log('Final post_type: ' . print_r($query->get('post_type'), true));
    error_log('Posts per page: ' . $query->get('posts_per_page'));
    error_log('Meta query: ' . print_r($query->get('meta_query'), true));
    error_log('=== SEARCH DEBUG END ===');
}

// WAŻNE: Usuń wszystkie inne pre_get_posts dla search i dodaj tylko ten
add_action('pre_get_posts', 'carni24_unified_search_query', 1); // Priorytet 1 = wykonuje się jako pierwszy

// Rozszerz wyszukiwanie o custom fields poprzez modyfikację SQL
function carni24_extend_search_where($where, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $search_term = get_search_query();
        
        if (!empty($search_term)) {
            $search_term = $wpdb->esc_like($search_term);
            $search_term = '%' . $search_term . '%';
            
            // Dodaj wyszukiwanie w meta fields do WHERE clause
            $where .= " OR (
                {$wpdb->posts}.ID IN (
                    SELECT post_id FROM {$wpdb->postmeta} 
                    WHERE meta_key IN (
                        '_species_scientific_name', 
                        '_species_origin', 
                        '_species_family',
                        '_species_common_names',
                        '_guide_target_plants'
                    )
                    AND meta_value LIKE '$search_term'
                )
            )";
            
            // Dodaj wyszukiwanie w taxonomiach
            $where .= " OR (
                {$wpdb->posts}.ID IN (
                    SELECT object_id FROM {$wpdb->term_relationships} tr
                    INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                    WHERE tt.taxonomy IN ('species_category', 'species_tag', 'guide_category', 'category', 'post_tag')
                    AND t.name LIKE '$search_term'
                )
            )";
        }
        
        error_log('Modified WHERE clause: ' . $where);
    }
    
    return $where;
}
add_filter('posts_where', 'carni24_extend_search_where', 10, 2);

// Popraw sortowanie by relevance
function carni24_search_orderby($orderby, $query) {
    global $wpdb;
    
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $search_term = get_search_query();
        
        if (!empty($search_term)) {
            // Sortuj według relevance: tytuł ma najwyższy priorytet
            $orderby = "
                CASE 
                    WHEN {$wpdb->posts}.post_title LIKE '%{$search_term}%' THEN 1
                    WHEN {$wpdb->posts}.post_content LIKE '%{$search_term}%' THEN 2
                    WHEN {$wpdb->posts}.post_excerpt LIKE '%{$search_term}%' THEN 3
                    ELSE 4
                END ASC,
                {$wpdb->posts}.post_date DESC
            ";
        }
    }
    
    return $orderby;
}
add_filter('posts_orderby', 'carni24_search_orderby', 10, 2);

// Test function - sprawdza czy search działa
function carni24_test_search_setup() {
    if (isset($_GET['test_search']) && current_user_can('manage_options')) {
        $test_query = new WP_Query(array(
            'post_type' => array('post', 'page', 'species'),
            'post_status' => 'publish',
            's' => 'test',
            'posts_per_page' => 5
        ));
        
        echo '<div style="background: white; padding: 20px; margin: 20px; border: 2px solid red;">';
        echo '<h3>Search Test Results:</h3>';
        echo '<p>Query: ' . $test_query->request . '</p>';
        echo '<p>Found posts: ' . $test_query->found_posts . '</p>';
        echo '<p>Post types: ' . print_r($test_query->get('post_type'), true) . '</p>';
        
        if ($test_query->have_posts()) {
            echo '<ul>';
            while ($test_query->have_posts()) {
                $test_query->the_post();
                echo '<li>' . get_post_type() . ': ' . get_the_title() . '</li>';
            }
            echo '</ul>';
        }
        wp_reset_postdata();
        echo '</div>';
    }
}
add_action('wp_head', 'carni24_test_search_setup');

// Flush rewrite rules
function carni24_search_flush_rules() {
    if (get_option('carni24_search_rules_flushed') !== 'yes') {
        flush_rewrite_rules();
        update_option('carni24_search_rules_flushed', 'yes');
    }
}
add_action('init', 'carni24_search_flush_rules');