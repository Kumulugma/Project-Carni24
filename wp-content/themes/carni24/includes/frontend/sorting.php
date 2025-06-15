<?php
/**
 * Sorting functionality for all archive pages
 * File: includes/frontend/sorting.php
 * NAPRAWIONY - wykluczenie page-blog.php
 */

if (!defined('ABSPATH')) {
    exit;
}

// ===== MAIN QUERY MODIFICATIONS ===== //

/**
 * Modify Species Archive Query
 */
function carni24_modify_species_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('species')) {
        
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
        
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
                break;
                
            case 'popularity':
                $query->set('meta_key', '_species_views');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            default: // 'date'
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
        }
    }
}
add_action('pre_get_posts', 'carni24_modify_species_query');

/**
 * Modify Blog/Archive Query (for archive.php) - WYKLUCZENIE page-blog.php
 */
function carni24_modify_blog_query($query) {
    // WAŻNE: Nie modyfikuj query dla page-blog.php!
    if (!is_admin() && $query->is_main_query() && 
        (is_home() || is_category() || is_tag() || is_author() || is_date()) &&
        !is_page_template('page-blog.php')) {
        
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
        
        switch ($orderby) {
            case 'title':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                break;
                
            case 'title-desc':
                $query->set('orderby', 'title');
                $query->set('order', 'DESC');
                break;
                
            case 'popular':
                $query->set('meta_key', '_post_views');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            default: // 'date'
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
        }
    }
}
add_action('pre_get_posts', 'carni24_modify_blog_query');

/**
 * Modify Search Query
 */
function carni24_modify_search_query($query) {
    if (!is_admin() && $query->is_main_query() && is_search()) {
        
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
                
            case 'popular':
                $query->set('meta_key', '_post_views');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            case 'relevance':
            default:
                // WordPress default search relevance
                break;
        }
    }
}
add_action('pre_get_posts', 'carni24_modify_search_query');

// ===== HELPER FUNCTIONS ===== //

/**
 * Get available sort options for specific post type
 */
function carni24_get_sort_options($post_type = '') {
    $common_options = array(
        '' => 'Najnowsze',
        'date' => 'Najnowsze',
        'title' => 'Nazwa A-Z',
        'title-desc' => 'Nazwa Z-A',
        'popular' => 'Najpopularniejsze',
        'random' => 'Losowo'
    );
    
    switch ($post_type) {
        case 'species':
            return array_merge($common_options, array(
                'difficulty' => 'Według trudności',
                'origin' => 'Według pochodzenia', 
                'scientific' => 'Nazwa naukowa A-Z',
                'size' => 'Według rozmiaru'
            ));
            
        case 'post':
        default:
            return $common_options;
    }
}

/**
 * Display sort dropdown (dla archive-species.php i archive.php)
 */
function carni24_display_sort_dropdown($post_type = '', $select_id = 'sort-select') {
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
    $sort_options = carni24_get_sort_options($post_type);
    
    // Helper function
    function get_archive_sort_url($new_orderby = '') {
        $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $current_url = remove_query_arg(['orderby', 'paged'], $current_url);
        
        if ($new_orderby && $new_orderby !== 'date') {
            $current_url = add_query_arg('orderby', $new_orderby, $current_url);
        }
        
        return $current_url;
    }
    
    ?>
    <div class="sort-dropdown">
        <select id="<?= esc_attr($select_id) ?>" class="sort-select">
            <?php foreach ($sort_options as $value => $label): ?>
                <option value="<?= get_archive_sort_url($value) ?>" <?= selected($current_orderby, $value, false) ?>>
                    <?= esc_html($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('<?= esc_js($select_id) ?>');
        if (select) {
            select.addEventListener('change', function() {
                if (this.value) {
                    window.location.href = this.value;
                }
            });
        }
    });
    </script>
    <?php
}

/**
 * Debug sorting (for administrators only)
 */
function carni24_debug_sorting() {
    if (!current_user_can('administrator') || !isset($_GET['debug_sort'])) {
        return;
    }
    
    global $wp_query;
    
    echo '<div style="background: #fff; padding: 20px; margin: 20px; border: 1px solid #ccc; position: relative; z-index: 9999;">';
    echo '<h3>🐛 Debug Sortowania</h3>';
    echo '<p><strong>Template:</strong> ' . basename(get_page_template()) . '</p>';
    echo '<p><strong>Is page template page-blog.php:</strong> ' . (is_page_template('page-blog.php') ? 'YES' : 'NO') . '</p>';
    echo '<p><strong>GET Parameters:</strong> <code>' . print_r($_GET, true) . '</code></p>';
    echo '<p><strong>Query Vars:</strong> <code>' . print_r($wp_query->query_vars, true) . '</code></p>';
    echo '<p><strong>Found Posts:</strong> ' . $wp_query->found_posts . '</p>';
    echo '</div>';
}
add_action('wp_footer', 'carni24_debug_sorting');
?>