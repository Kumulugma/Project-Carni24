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
            'slug' => '',
            'with_front' => false
        ),
    );
    register_post_type('species', $config);
}

add_action('init', 'c24_species_post_type', 0);

// Simple rewrite rules for species without prefix
function carni24_species_rewrite_rules() {
    add_rewrite_rule(
        '^([^/]+)/?$',
        'index.php?species_slug=$matches[1]',
        'bottom'  // Zmienione na 'bottom' żeby nie przeszkadzało stronie głównej
    );
}
add_action('init', 'carni24_species_rewrite_rules');

// Add custom query var
function carni24_species_query_vars($vars) {
    $vars[] = 'species_slug';
    return $vars;
}
add_filter('query_vars', 'carni24_species_query_vars');

// Handle species query only when it's actually a species
function carni24_species_parse_request($query) {
    // Only on main query, frontend, and not home page
    if (!$query->is_main_query() || is_admin() || $query->is_home()) {
        return;
    }
    
    $species_slug = get_query_var('species_slug');
    
    if (!empty($species_slug)) {
        // Check if this slug is actually a species
        $species = get_posts(array(
            'name' => $species_slug,
            'post_type' => 'species',
            'post_status' => 'publish',
            'numberposts' => 1
        ));
        
        if (!empty($species)) {
            // It's a species, set up the query
            $query->set('post_type', 'species');
            $query->set('name', $species_slug);
            $query->is_single = true;
            $query->is_singular = true;
            $query->is_404 = false;
        }
        // If it's not a species, let WordPress handle it normally
    }
}
add_action('pre_get_posts', 'carni24_species_parse_request');

// Fix species permalinks
function carni24_species_permalink($post_link, $post) {
    if ($post->post_type == 'species') {
        return home_url('/' . $post->post_name . '/');
    }
    return $post_link;
}
add_filter('post_type_link', 'carni24_species_permalink', 10, 2);

// Flush rewrite rules safely
function carni24_flush_species_rules() {
    flush_rewrite_rules();
}

// Add flush button to species admin page
function carni24_species_admin_button() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'species') {
        if (isset($_GET['carni24_flush_species']) && current_user_can('manage_options')) {
            carni24_flush_species_rules();
            echo '<div class="notice notice-success"><p>Reguły URL zostały odświeżone!</p></div>';
        }
        
        $flush_url = add_query_arg('carni24_flush_species', '1');
        echo '<div style="margin: 10px 0;"><a href="' . esc_url($flush_url) . '" class="button button-secondary">Odśwież reguły URL</a></div>';
    }
}
add_action('admin_notices', 'carni24_species_admin_button');

// Initial setup on theme activation
function carni24_species_activate() {
    carni24_species_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'carni24_species_activate');