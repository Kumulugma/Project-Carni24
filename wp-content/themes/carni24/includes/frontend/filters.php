<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_body_classes($classes) {
    global $post;
    
    if (is_front_page()) {
        $classes[] = 'front-page';
        $classes[] = 'homepage';
    }
    
    if (is_home()) {
        $classes[] = 'blog-home';
    }
    
    if (is_singular()) {
        $classes[] = 'single-' . get_post_type();
        
        if (has_post_thumbnail()) {
            $classes[] = 'has-featured-image';
        } else {
            $classes[] = 'no-featured-image';
        }
        
        if (carni24_is_featured_post()) {
            $classes[] = 'featured-post';
        }
        
        if (get_post_type() === 'species') {
            $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
            if ($difficulty) {
                $classes[] = 'species-difficulty-' . $difficulty;
            }
            
            $origin = get_post_meta($post->ID, '_species_origin', true);
            if ($origin) {
                $classes[] = 'species-origin-' . sanitize_html_class(strtolower($origin));
            }
        }
        
        if (get_post_type() === 'guides') {
            $difficulty = get_post_meta($post->ID, '_guide_difficulty_level', true);
            if ($difficulty) {
                $classes[] = 'guide-difficulty-' . $difficulty;
            }
        }
    }
    
    if (is_archive()) {
        $classes[] = 'archive-page';
        
        if (is_post_type_archive()) {
            $classes[] = 'archive-' . get_post_type();
        }
        
        if (is_category()) {
            $classes[] = 'category-archive';
            $classes[] = 'category-' . get_query_var('cat');
        }
        
        if (is_tag()) {
            $classes[] = 'tag-archive';
        }
        
        if (is_tax()) {
            $classes[] = 'taxonomy-archive';
            $classes[] = 'taxonomy-' . get_query_var('taxonomy');
        }
    }
    
    if (is_search()) {
        $classes[] = 'search-results';
        
        if (have_posts()) {
            $classes[] = 'search-has-results';
        } else {
            $classes[] = 'search-no-results';
        }
    }
    
    if (is_404()) {
        $classes[] = 'error-404';
        $classes[] = 'not-found';
    }
    
    if (wp_is_mobile()) {
        $classes[] = 'mobile-device';
    }
    
    if (is_user_logged_in()) {
        $classes[] = 'user-logged-in';
    } else {
        $classes[] = 'user-logged-out';
    }
    
    if (current_user_can('manage_options')) {
        $classes[] = 'user-admin';
    }
    
    if (isset($_GET['sidebar']) && $_GET['sidebar'] === 'hidden') {
        $classes[] = 'sidebar-hidden';
    }
    
    if (carni24_show_sidebar()) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
        $classes[] = 'full-width';
    }
    
    return array_unique($classes);
}
add_filter('body_class', 'carni24_body_classes');

function carni24_post_classes($classes, $class, $post_id) {
    if (carni24_is_featured_post($post_id)) {
        $classes[] = 'featured-post';
    }
    
    if (get_post_type($post_id) === 'species') {
        $difficulty = get_post_meta($post_id, '_species_difficulty', true);
        if ($difficulty) {
            $classes[] = 'species-difficulty-' . $difficulty;
        }
        
        $classes[] = 'species-card';
    }
    
    if (get_post_type($post_id) === 'guides') {
        $difficulty = get_post_meta($post_id, '_guide_difficulty_level', true);
        if ($difficulty) {
            $classes[] = 'guide-difficulty-' . $difficulty;
        }
        
        $classes[] = 'guide-card';
    }
    
    if (!has_post_thumbnail($post_id)) {
        $classes[] = 'no-thumbnail';
    }
    
    $views = carni24_get_post_views($post_id);
    if ($views > 1000) {
        $classes[] = 'popular-post';
    }
    
    if ($views > 5000) {
        $classes[] = 'viral-post';
    }
    
    $post_age = (current_time('timestamp') - get_post_timestamp($post_id)) / DAY_IN_SECONDS;
    if ($post_age <= 7) {
        $classes[] = 'recent-post';
    }
    
    if ($post_age <= 1) {
        $classes[] = 'new-post';
    }
    
    return array_unique($classes);
}
add_filter('post_class', 'carni24_post_classes', 10, 3);

function carni24_excerpt_length($length) {
    if (is_front_page()) {
        return 20;
    }
    
    if (is_archive()) {
        return 25;
    }
    
    if (is_search()) {
        return 30;
    }
    
    return 25;
}
add_filter('excerpt_length', 'carni24_excerpt_length');

function carni24_excerpt_more($more) {
    global $post;
    
    if (is_admin()) {
        return $more;
    }
    
    return ' <a href="' . get_permalink($post->ID) . '" class="read-more-link">Czytaj więcej →</a>';
}
add_filter('excerpt_more', 'carni24_excerpt_more');

function carni24_modify_main_query($query) {
    if (!$query->is_main_query() || is_admin()) {
        return;
    }
    
    if (is_home()) {
        $query->set('posts_per_page', 9);
        $query->set('meta_query', array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        ));
    }
    
    if (is_post_type_archive('species')) {
        $query->set('posts_per_page', 12);
        $query->set('orderby', 'menu_order title');
        $query->set('order', 'ASC');
        
        if (isset($_GET['difficulty']) && !empty($_GET['difficulty'])) {
            $difficulty = sanitize_text_field($_GET['difficulty']);
            $meta_query = $query->get('meta_query') ?: array();
            $meta_query[] = array(
                'key' => '_species_difficulty',
                'value' => $difficulty,
                'compare' => '='
            );
            $query->set('meta_query', $meta_query);
        }
        
        if (isset($_GET['origin']) && !empty($_GET['origin'])) {
            $origin = sanitize_text_field($_GET['origin']);
            $meta_query = $query->get('meta_query') ?: array();
            $meta_query[] = array(
                'key' => '_species_origin',
                'value' => $origin,
                'compare' => 'LIKE'
            );
            $query->set('meta_query', $meta_query);
        }
        
        if (isset($_GET['orderby'])) {
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
                case 'difficulty':
                    $query->set('meta_key', '_species_difficulty');
                    $query->set('orderby', 'meta_value');
                    $query->set('order', 'ASC');
                    break;
            }
        }
    }
    
    if (is_post_type_archive('guides')) {
        $query->set('posts_per_page', 9);
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
        
        if (isset($_GET['difficulty']) && !empty($_GET['difficulty'])) {
            $difficulty = sanitize_text_field($_GET['difficulty']);
            $meta_query = $query->get('meta_query') ?: array();
            $meta_query[] = array(
                'key' => '_guide_difficulty_level',
                'value' => $difficulty,
                'compare' => '='
            );
            $query->set('meta_query', $meta_query);
        }
    }
    
    if (is_search()) {
        $query->set('post_type', array('post', 'page', 'species', 'guides'));
        $query->set('posts_per_page', 10);
    }
}
add_action('pre_get_posts', 'carni24_modify_main_query');

function carni24_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && is_search()) {
        $search_term = get_search_query();
        
        if (strlen($search_term) >= 3) {
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => '_species_scientific_name',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_species_common_names',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => '_guide_target_plants',
                    'value' => $search_term,
                    'compare' => 'LIKE'
                )
            );
            
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'carni24_search_filter');

function carni24_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_post_type_archive('species')) {
        $title = 'Gatunki roślin mięsożernych';
    } elseif (is_post_type_archive('guides')) {
        $title = 'Poradniki uprawy';
    } elseif (is_tax('species_category')) {
        $title = single_term_title('', false);
    } elseif (is_tax('guide_category')) {
        $title = single_term_title('', false);
    } elseif (is_author()) {
        $title = 'Artykuły autora: ' . get_the_author();
    } elseif (is_date()) {
        if (is_year()) {
            $title = 'Archiwum roku ' . get_the_date('Y');
        } elseif (is_month()) {
            $title = 'Archiwum ' . get_the_date('F Y');
        }
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'carni24_archive_title');

function carni24_archive_description($description) {
    if (is_post_type_archive('species')) {
        $description = 'Przeglądaj naszą kolekcję gatunków roślin mięsożernych. Znajdziesz tu szczegółowe informacje o uprawie, pochodzeniu i wymaganiach każdego gatunku.';
    } elseif (is_post_type_archive('guides')) {
        $description = 'Praktyczne poradniki uprawy roślin mięsożernych. Dowiedz się jak prawidłowo pielęgnować swoje rośliny i unikać najczęstszych błędów.';
    } elseif (is_category() || is_tag() || is_tax()) {
        if (empty($description)) {
            $term = get_queried_object();
            if ($term && !empty($term->description)) {
                $description = $term->description;
            }
        }
    }
    
    return $description;
}
add_filter('get_the_archive_description', 'carni24_archive_description');

function carni24_pagination_args($args) {
    $args['prev_text'] = '&laquo; Poprzednia';
    $args['next_text'] = 'Następna &raquo;';
    $args['before_page_number'] = '<span class="screen-reader-text">Strona </span>';
    
    return $args;
}
add_filter('paginate_links_args', 'carni24_pagination_args');

function carni24_comment_form_fields($fields) {
    return array();
}
add_filter('comment_form_default_fields', 'carni24_comment_form_fields');

function carni24_search_highlight($content) {
    if (is_search() && !is_admin()) {
        $search_term = get_search_query();
        if (!empty($search_term)) {
            $content = preg_replace(
                '/(' . preg_quote($search_term, '/') . ')/i',
                '<mark class="search-highlight">$1</mark>',
                $content
            );
        }
    }
    
    return $content;
}
add_filter('the_content', 'carni24_search_highlight');
add_filter('the_excerpt', 'carni24_search_highlight');
add_filter('the_title', 'carni24_search_highlight');

function carni24_wp_title_separator($sep) {
    return '|';
}
add_filter('document_title_separator', 'carni24_wp_title_separator');

function carni24_redirect_attachment_pages() {
    if (is_attachment()) {
        global $post;
        if ($post && $post->post_parent) {
            wp_redirect(get_permalink($post->post_parent), 301);
            exit;
        } else {
            wp_redirect(home_url(), 301);
            exit;
        }
    }
}
add_action('template_redirect', 'carni24_redirect_attachment_pages');

function carni24_remove_category_url($termlink, $term, $taxonomy) {
    if ($taxonomy === 'category') {
        return str_replace('/category/', '/', $termlink);
    }
    return $termlink;
}
add_filter('term_link', 'carni24_remove_category_url', 10, 3);

function carni24_custom_rewrite_rules() {
    add_rewrite_rule('^([^/]+)/?$', 'index.php?category_name=$matches[1]', 'top');
}
add_action('init', 'carni24_custom_rewrite_rules');

function carni24_filter_widget_text($text) {
    return do_shortcode($text);
}
add_filter('widget_text', 'carni24_filter_widget_text');

function carni24_custom_login_logo() {
    $logo_id = carni24_get_option('custom_logo', '');
    if ($logo_id) {
        $logo_url = wp_get_attachment_image_url($logo_id, 'medium');
        if ($logo_url) {
            echo '<style type="text/css">
                #login h1 a, .login h1 a {
                    background-image: url(' . $logo_url . ');
                    background-size: contain;
                    background-repeat: no-repeat;
                    padding-bottom: 30px;
                    width: 100%;
                }
            </style>';
        }
    }
}
add_action('login_enqueue_scripts', 'carni24_custom_login_logo');

function carni24_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'carni24_login_logo_url');

function carni24_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'carni24_login_logo_url_title');

function carni24_add_theme_version_to_scripts($src) {
    if (strpos($src, CARNI24_THEME_URL) !== false) {
        $src = add_query_arg('v', CARNI24_VERSION, $src);
    }
    return $src;
}
add_filter('script_loader_src', 'carni24_add_theme_version_to_scripts');
add_filter('style_loader_src', 'carni24_add_theme_version_to_scripts');