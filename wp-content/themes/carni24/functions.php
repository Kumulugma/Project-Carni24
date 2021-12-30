<?php

function remove_menus() {
    remove_menu_page('edit-comments.php');          //Comments
}

add_action('admin_menu', 'remove_menus');

add_action('after_setup_theme', 'theme_setup');

function theme_setup() {
    load_theme_textdomain('carni24', get_template_directory() . '/languages');
//    add_theme_support('title-tag');
//add_theme_support( 'automatic-feed-links' );
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form'));
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1920;
    }
    register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'blankslate')));
}

add_filter('document_title_separator', 'theme_document_title_separator');

function theme_document_title_separator($sep) {
    $sep = '|';
    return $sep;
}

add_filter('the_content_more_link', 'theme_read_more_link');

function theme_read_more_link() {
    if (!is_admin()) {
        return ' <a href="' . esc_url(get_permalink()) . '" class="more-link"><button type="button" class="btn btn-sm btn-outline-secondary">Zobacz więcej</button></a>';
    }
}

add_filter('excerpt_more', 'theme_excerpt_read_more_link');

function theme_excerpt_read_more_link($more) {
    if (!is_admin()) {
        global $post;
        return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link"><button type="button" class="btn btn-sm btn-outline-secondary">Zobacz więcej</button></a>';
    }
}

//Miniaturki
add_image_size('scene', 2000, 300, true);

//Główne menu

function add_menu_link_class($ulclass) {
    return preg_replace('/<a /', '<a class="link-secondary nav-link"', $ulclass, -1);
}

add_filter('wp_nav_menu', 'add_menu_link_class');

//Gatunki
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
        'supports' => array('title', 'editor', 'thumbnail'),
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

//Searchbar
function gallery_count() {
    $query_img_args = array(
        'post_type' => 'attachment',
        'post_mime_type' => array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif' => 'image/gif',
            'png' => 'image/png',
        ),
        'post_status' => 'inherit',
        'posts_per_page' => -1,
    );
    $query_img = new WP_Query($query_img_args);
    return $query_img->post_count;
}

//Paginacja
function get_pagination() {

    global $wp_query;
    $big = 9999999; // need an unlikely integer
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages));
}

// Get total number of pages
global $wp_query;
$total = $wp_query->max_num_pages;
// Only paginate if we have more than one page
if ($total > 1) {
    // Get the current page
    if (!$current_page = get_query_var('paged'))
        $current_page = 1;
    // Structure of “format” depends on whether we’re using pretty permalinks
    $format = empty(get_option('permalink_structure')) ? '&page=%#%' : 'page/%#%/';
    echo paginate_links(array(
        'base' => get_pagenum_link(1) . '%_%',
        'format' => $format,
        'current' => $current_page,
        'total' => $total,
        'mid_size' => 4,
        'type' => 'list'
    ));
}

//Breadcrumbs
function get_breadcrumb() {
    echo '<a href="' . home_url() . '" rel="nofollow">Strona główna</a>';
    if ((is_category() || is_single()) && !is_singular('species')) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        the_category(' &bull; ');
        if (is_single()) {
            echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
            the_title();
        }
    } elseif ((is_category() || is_single()) && is_singular('species')) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";

        global $post;
        $category = get_the_category($post->ID);
        $name = reset($category)->name;
        $slug = reset($category)->slug;
        echo '<a href="/kategoria-gatunku/?spec=' . $slug . '">' . $name;
        if (is_single()) {
            echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
            the_title();
        } 
        echo "</a>";
    } elseif (is_page()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        echo the_title();
    } elseif (is_search()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Wynik wyszukiwania dla... ";
        echo '"<em>';
        echo the_search_query();
        echo '</em>"';
    }
}

function custom_rewrite_tag() {
    add_rewrite_tag('%spec%', '([^&]+)');
}

add_action('init', 'custom_rewrite_tag', 10, 0);

function custom_rewrite_rule() {
    add_rewrite_rule('^spec/([^/]*)/?', 'index.php?page_id=111&spec=$matches[1]', 'top');
}

add_action('init', 'custom_rewrite_rule', 10, 0);


//
//wp_register_script('jQuery', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', null, null, true);
//wp_enqueue_script('jQuery');
//wp_deregister_script('hoverIntent');
//wp_register_script('hoverIntent', ( 'https://cdnjs.cloudflare.com/ajax/libs/jquery.hoverintent/1.10.1/jquery.hoverIntent.min.js'), array('jquery'), 'r6.1');

add_action('wp_enqueue_scripts', 'bootstrap5_assets');

function bootstrap5_assets() {
    if (!is_admin()) {
        wp_enqueue_style('Bootstrap5', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css", false, '1.1', 'all');
    }
}

add_action('get_header', function() {
    if (is_page(242)) {
        wp_register_script('jQuery', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', null, null, true);
        wp_enqueue_script('jQuery');
        wp_enqueue_style('k3e-gallery', get_template_directory_uri() . '/css/gallery.css', false, '1.1', 'all');
    }
});
