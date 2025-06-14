<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_modify_species_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('species')) {
        
        if (!isset($_GET['orderby'])) {
            $query->set('orderby', 'menu_order title');
            $query->set('order', 'ASC');
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
                
            case 'date':
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
                
            case 'date-asc':
                $query->set('orderby', 'date');
                $query->set('order', 'ASC');
                break;
                
            case 'difficulty':
                $query->set('meta_key', '_species_difficulty');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                add_filter('posts_orderby', 'carni24_custom_difficulty_orderby');
                break;
                
            case 'origin':
                $query->set('meta_key', '_species_origin');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                break;
                
            case 'popular':
                $query->set('meta_key', 'post_views_count');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            case 'scientific':
                $query->set('meta_key', '_species_scientific_name');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                break;
                
            case 'size':
                $query->set('meta_key', '_species_size');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                break;
                
            case 'featured':
                $query->set('meta_query', array(
                    'relation' => 'OR',
                    array(
                        'key' => '_is_featured',
                        'value' => '1',
                        'compare' => '='
                    ),
                    array(
                        'key' => '_is_featured',
                        'compare' => 'NOT EXISTS'
                    )
                ));
                $query->set('orderby', 'meta_value date');
                $query->set('order', 'DESC');
                break;
                
            case 'random':
                $query->set('orderby', 'rand');
                break;
        }
    }
}
add_action('pre_get_posts', 'carni24_modify_species_query');

function carni24_custom_difficulty_orderby($orderby) {
    global $wpdb;
    
    $custom_orderby = "
        CASE {$wpdb->postmeta}.meta_value 
        WHEN 'easy' THEN 1
        WHEN 'medium' THEN 2  
        WHEN 'hard' THEN 3
        ELSE 4
        END ASC, {$wpdb->posts}.post_title ASC
    ";
    
    return $custom_orderby;
}

function carni24_modify_guides_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('guides')) {
        
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
                
            case 'date':
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
                
            case 'date-asc':
                $query->set('orderby', 'date');
                $query->set('order', 'ASC');
                break;
                
            case 'difficulty':
                $query->set('meta_key', '_guide_difficulty_level');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                add_filter('posts_orderby', 'carni24_custom_guide_difficulty_orderby');
                break;
                
            case 'time':
                $query->set('meta_key', '_guide_estimated_time');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                break;
                
            case 'popular':
                $query->set('meta_key', 'post_views_count');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            case 'featured':
                $query->set('meta_query', array(
                    'relation' => 'OR',
                    array(
                        'key' => '_is_featured',
                        'value' => '1',
                        'compare' => '='
                    ),
                    array(
                        'key' => '_is_featured',
                        'compare' => 'NOT EXISTS'
                    )
                ));
                $query->set('orderby', 'meta_value date');
                $query->set('order', 'DESC');
                break;
                
            case 'season':
                $query->set('meta_key', '_guide_best_season');
                $query->set('orderby', 'meta_value');
                $query->set('order', 'ASC');
                add_filter('posts_orderby', 'carni24_custom_season_orderby');
                break;
                
            case 'random':
                $query->set('orderby', 'rand');
                break;
        }
    }
}
add_action('pre_get_posts', 'carni24_modify_guides_query');

function carni24_custom_guide_difficulty_orderby($orderby) {
    global $wpdb;
    
    $custom_orderby = "
        CASE {$wpdb->postmeta}.meta_value 
        WHEN 'beginner' THEN 1
        WHEN 'intermediate' THEN 2
        WHEN 'advanced' THEN 3
        WHEN 'expert' THEN 4
        ELSE 5
        END ASC, {$wpdb->posts}.post_title ASC
    ";
    
    return $custom_orderby;
}

function carni24_custom_season_orderby($orderby) {
    global $wpdb;
    
    $custom_orderby = "
        CASE {$wpdb->postmeta}.meta_value 
        WHEN 'spring' THEN 1
        WHEN 'summer' THEN 2
        WHEN 'autumn' THEN 3
        WHEN 'winter' THEN 4
        WHEN 'year_round' THEN 5
        ELSE 6
        END ASC, {$wpdb->posts}.post_title ASC
    ";
    
    return $custom_orderby;
}

function carni24_modify_blog_query($query) {
    if (!is_admin() && $query->is_main_query() && is_home()) {
        
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
                
            case 'date':
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
                
            case 'popular':
                $query->set('meta_key', 'post_views_count');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            case 'comments':
                $query->set('orderby', 'comment_count');
                $query->set('order', 'DESC');
                break;
                
            case 'featured':
                $query->set('meta_query', array(
                    'relation' => 'OR',
                    array(
                        'key' => '_is_featured',
                        'value' => '1',
                        'compare' => '='
                    ),
                    array(
                        'key' => '_is_featured',
                        'compare' => 'NOT EXISTS'
                    )
                ));
                $query->set('orderby', 'meta_value date');
                $query->set('order', 'DESC');
                break;
                
            case 'random':
                $query->set('orderby', 'rand');
                break;
        }
    }
}
add_action('pre_get_posts', 'carni24_modify_blog_query');

function carni24_category_sorting($query) {
    if (!is_admin() && $query->is_main_query() && is_category()) {
        
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
        
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
                $query->set('meta_key', 'post_views_count');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            case 'random':
                $query->set('orderby', 'rand');
                break;
        }
    }
}
add_action('pre_get_posts', 'carni24_category_sorting');

function carni24_search_sorting($query) {
    if (!is_admin() && $query->is_main_query() && is_search()) {
        
        $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'relevance';
        
        switch ($orderby) {
            case 'relevance':
                break;
                
            case 'title':
                $query->set('orderby', 'title');
                $query->set('order', 'ASC');
                break;
                
            case 'date':
                $query->set('orderby', 'date');
                $query->set('order', 'DESC');
                break;
                
            case 'popular':
                $query->set('meta_key', 'post_views_count');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
                break;
                
            case 'type':
                $query->set('orderby', 'type');
                $query->set('order', 'ASC');
                break;
        }
    }
}
add_action('pre_get_posts', 'carni24_search_sorting');

function carni24_get_sort_options($post_type = '') {
    $common_options = array(
        'title' => 'Nazwa A-Z',
        'date' => 'Najnowsze',
        'popular' => 'Najpopularniejsze',
        'featured' => 'Wyróżnione',
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
            
        case 'guides':
            return array_merge($common_options, array(
                'difficulty' => 'Według poziomu',
                'time' => 'Według czasu',
                'season' => 'Według sezonu'
            ));
            
        case 'post':
            return array_merge($common_options, array(
                'comments' => 'Według komentarzy'
            ));
            
        default:
            return $common_options;
    }
}

function carni24_display_sort_dropdown($post_type = '') {
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
    $sort_options = carni24_get_sort_options($post_type);
    
    ?>
    <div class="sort-dropdown">
        <select name="orderby" class="form-select" onchange="this.form.submit()">
            <option value="">Sortuj według...</option>
            <?php foreach ($sort_options as $value => $label): ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($current_orderby, $value); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php
}

function carni24_get_current_sort_label($post_type = '') {
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
    $sort_options = carni24_get_sort_options($post_type);
    
    if (empty($current_orderby)) {
        return 'Domyślne';
    }
    
    return isset($sort_options[$current_orderby]) ? $sort_options[$current_orderby] : 'Nieznane';
}

function carni24_remove_orderby_from_pagination($link) {
    if (isset($_GET['orderby'])) {
        $link = add_query_arg('orderby', $_GET['orderby'], $link);
    }
    
    if (isset($_GET['difficulty'])) {
        $link = add_query_arg('difficulty', $_GET['difficulty'], $link);
    }
    
    if (isset($_GET['origin'])) {
        $link = add_query_arg('origin', $_GET['origin'], $link);
    }
    
    return $link;
}
add_filter('paginate_links', 'carni24_remove_orderby_from_pagination');

function carni24_preserve_query_vars($query_vars) {
    $preserve = array('orderby', 'difficulty', 'origin', 'time', 'season');
    
    foreach ($preserve as $var) {
        if (isset($_GET[$var])) {
            $query_vars[$var] = sanitize_text_field($_GET[$var]);
        }
    }
    
    return $query_vars;
}
add_filter('query_vars', 'carni24_preserve_query_vars');

function carni24_sort_javascript() {
    if (!is_archive() && !is_home() && !is_search()) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.querySelector('select[name="orderby"]');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                const form = this.closest('form');
                if (form) {
                    form.submit();
                } else {
                    const url = new URL(window.location);
                    if (this.value) {
                        url.searchParams.set('orderby', this.value);
                    } else {
                        url.searchParams.delete('orderby');
                    }
                    window.location.href = url.toString();
                }
            });
        }
        
        const filterInputs = document.querySelectorAll('.archive-filters input, .archive-filters select');
        filterInputs.forEach(function(input) {
            if (input.type === 'text') {
                let timeout;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        input.closest('form').submit();
                    }, 800);
                });
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'carni24_sort_javascript');

function carni24_get_filtered_posts_count($post_type = 'post') {
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids'
    );
    
    if (isset($_GET['difficulty']) && !empty($_GET['difficulty'])) {
        $difficulty = sanitize_text_field($_GET['difficulty']);
        $meta_key = ($post_type === 'species') ? '_species_difficulty' : '_guide_difficulty_level';
        
        $args['meta_query'] = array(
            array(
                'key' => $meta_key,
                'value' => $difficulty,
                'compare' => '='
            )
        );
    }
    
    if (isset($_GET['origin']) && !empty($_GET['origin']) && $post_type === 'species') {
        $origin = sanitize_text_field($_GET['origin']);
        $args['meta_query'][] = array(
            'key' => '_species_origin',
            'value' => $origin,
            'compare' => 'LIKE'
        );
    }
    
    $query = new WP_Query($args);
    return $query->found_posts;
}

function carni24_display_results_info($post_type = '') {
    global $wp_query;
    
    $total_posts = $wp_query->found_posts;
    $current_page = max(1, get_query_var('paged'));
    $posts_per_page = $wp_query->query_vars['posts_per_page'];
    
    $start = (($current_page - 1) * $posts_per_page) + 1;
    $end = min($current_page * $posts_per_page, $total_posts);
    
    if ($total_posts > 0) {
        echo '<div class="results-info text-muted mb-3">';
        echo 'Wyświetlanie ' . $start . '-' . $end . ' z ' . number_format($total_posts) . ' wyników';
        
        $current_sort = carni24_get_current_sort_label($post_type);
        if ($current_sort !== 'Domyślne') {
            echo ' • Sortowanie: ' . $current_sort;
        }
        
        echo '</div>';
    }
}