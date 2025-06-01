<?php

// Register Custom Post Type
function c24_species_post_type() {

    $labelsList = array(
        'name' => _x('Gatunki', 'Post Type General Name', 'carni24'),
        'singular_name' => _x('Gatunek', 'Post Type Singular Name', 'carni24'),
        'menu_name' => __('Gatunki', 'carni24'),
        'name_admin_bar' => __('Post Type', 'carni24'),
        'archives' => __('Gatunek archiwalny', 'carni24'),
        'attributes' => __('Szczegóły gatunki', 'carni24'),
        'parent_item_colon' => __('Gatunek nadrzędny', 'carni24'),
        'all_items' => __('Wszyskie gatunki', 'carni24'),
        'add_new_item' => __('Dodaj nowy gatunek', 'carni24'),
        'add_new' => __('Dodaj nowy', 'carni24'),
        'new_item' => __('Nowy gatunek', 'carni24'),
        'edit_item' => __('Edytuj gatunek', 'carni24'),
        'update_item' => __('Zaktualizuj gatunek', 'carni24'),
        'view_item' => __('Zobacz gatunek', 'carni24'),
        'view_items' => __('Zobacz gatunki', 'carni24'),
        'search_items' => __('Szukaj gatunku', 'carni24'),
        'not_found' => __('Brak gatunków', 'carni24'),
        'not_found_in_trash' => __('Brak gatunków z koszu', 'carni24'),
        'featured_image' => __('Główna grafika', 'carni24'),
        'set_featured_image' => __('Ustaw główną grafikę', 'carni24'),
        'remove_featured_image' => __('Usuń główną grafiką', 'carni24'),
        'use_featured_image' => __('Użyj jako głównej grafiki', 'carni24'),
        'insert_into_item' => __('Dodaj do gatunku', 'carni24'),
        'uploaded_to_this_item' => __('Wgraj do tego gatunku', 'carni24'),
        'items_list' => __('Lista gatunków', 'carni24'),
        'items_list_navigation' => __('Gatunki', 'carni24'),
        'filter_items_list' => __('Gatunki', 'carni24'),
    );
    $config = array(
        'label' => __('Gatunek', 'carni24'),
        'description' => __('Gatunek rośliny wraz z opisem i szczegółami.', 'carni24'),
        'labels' => $labelsList,
        'supports' => array('title', 'editor', 'thumbnail', 'editor'),
        'taxonomies' => array('category', 'post_tag'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-carrot',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'gatunek',
            'with_front' => false
        ),
    );
    register_post_type('species', $config);
}

add_action('init', 'c24_species_post_type', 0);

// Custom rewrite rules for species without prefix
function carni24_species_custom_rewrite_rules() {
    global $wp_rewrite;
    
    // Add custom rewrite rules at the very top
    add_rewrite_rule(
        '^([^/]+)/?$',
        'index.php?carni24_species_slug=$matches[1]',
        'top'
    );
    
    add_rewrite_rule(
        '^([^/]+)/page/([0-9]+)/?$',
        'index.php?carni24_species_slug=$matches[1]&paged=$matches[2]',
        'top'
    );
}
add_action('init', 'carni24_species_custom_rewrite_rules');

// Add custom query var
function carni24_species_add_query_vars($vars) {
    $vars[] = 'carni24_species_slug';
    return $vars;
}
add_filter('query_vars', 'carni24_species_add_query_vars');

// Handle the custom query
function carni24_species_template_redirect() {
    $species_slug = get_query_var('carni24_species_slug');
    
    if (!empty($species_slug)) {
        // Check if this slug belongs to a species
        $species_post = get_posts(array(
            'name' => $species_slug,
            'post_type' => 'species',
            'post_status' => 'publish',
            'numberposts' => 1
        ));
        
        if (!empty($species_post)) {
            // Set global post
            global $wp_query;
            $wp_query->queried_object = $species_post[0];
            $wp_query->queried_object_id = $species_post[0]->ID;
            $wp_query->is_single = true;
            $wp_query->is_singular = true;
            $wp_query->is_404 = false;
            $wp_query->found_posts = 1;
            $wp_query->post_count = 1;
            $wp_query->posts = $species_post;
            
            // Load the template
            include(get_template_directory() . '/single-species.php');
            exit;
        } else {
            // Check if it's a regular page/post to avoid 404
            $regular_post = get_posts(array(
                'name' => $species_slug,
                'post_type' => array('post', 'page'),
                'post_status' => 'publish',
                'numberposts' => 1
            ));
            
            if (empty($regular_post)) {
                // It's a 404
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                nocache_headers();
            }
        }
    }
}
add_action('template_redirect', 'carni24_species_template_redirect', 1);

// Custom permalink for species
function carni24_species_post_link($post_link, $post) {
    if ($post->post_type === 'species' && $post->post_status === 'publish') {
        return home_url('/' . $post->post_name . '/');
    }
    return $post_link;
}
add_filter('post_type_link', 'carni24_species_post_link', 10, 2);

// Ensure species links are correct in admin
function carni24_species_admin_post_link($post_link, $post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'species') {
        return home_url('/' . $post->post_name . '/');
    }
    return $post_link;
}
add_filter('post_link', 'carni24_species_admin_post_link', 10, 2);

// Handle conflicts with pages/posts by checking slug uniqueness
function carni24_check_species_slug_conflict($slug, $post_ID, $post_status, $post_type) {
    if ($post_type !== 'species') {
        return $slug;
    }
    
    // Check if any page or post has this slug
    $conflicting_posts = get_posts(array(
        'name' => $slug,
        'post_type' => array('post', 'page'),
        'post_status' => 'any',
        'numberposts' => 1,
        'exclude' => array($post_ID)
    ));
    
    if (!empty($conflicting_posts)) {
        // Add suffix to avoid conflict
        $original_slug = $slug;
        $suffix = 1;
        
        do {
            $slug = $original_slug . '-gatunek-' . $suffix;
            $conflicting_posts = get_posts(array(
                'name' => $slug,
                'post_type' => array('post', 'page', 'species'),
                'post_status' => 'any',
                'numberposts' => 1,
                'exclude' => array($post_ID)
            ));
            $suffix++;
        } while (!empty($conflicting_posts) && $suffix < 100);
    }
    
    return $slug;
}
add_filter('wp_unique_post_slug', 'carni24_check_species_slug_conflict', 10, 4);

// Flush rewrite rules when needed
function carni24_species_flush_rewrites() {
    carni24_species_custom_rewrite_rules();
    flush_rewrite_rules();
}

// Flush on theme activation
add_action('after_switch_theme', 'carni24_species_flush_rewrites');

// Add admin notice for rewrite rules flush
function carni24_species_admin_notice() {
    if (isset($_GET['carni24_flush_rewrites'])) {
        carni24_species_flush_rewrites();
        echo '<div class="notice notice-success is-dismissible"><p>Reguły URL zostały odświeżone!</p></div>';
    }
}
add_action('admin_notices', 'carni24_species_admin_notice');

// Add flush button to species list page
function carni24_add_flush_button() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'species' && $screen->base === 'edit') {
        echo '<div class="wrap"><a href="' . admin_url('edit.php?post_type=species&carni24_flush_rewrites=1') . '" class="button">Odśwież reguły URL</a></div>';
    }
}
add_action('admin_notices', 'carni24_add_flush_button');