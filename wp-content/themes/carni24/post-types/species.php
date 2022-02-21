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
    );
    register_post_type('species', $config);
}

add_action('init', 'c24_species_post_type', 0);
