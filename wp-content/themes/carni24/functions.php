<?php

//Register headerAsset
include("components/headerAsset.php");
//Register Species
include("post-types/species.php");

//Register Pagination
include("includes/pagination.php");
//Register Title Separator
include("includes/titleSeparator.php");
//Register Read More
include("includes/readMore.php");

function remove_menus() {
    remove_menu_page('edit-comments.php');          //Comments
}

add_action('admin_menu', 'remove_menus');


add_action('after_setup_theme', 'theme_setup');

function theme_setup() {
    load_theme_textdomain('carni24', get_template_directory() . '/languages');
//add_theme_support('title-tag');
//add_theme_support( 'automatic-feed-links' );
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form'));
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1920;
    }
    register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'carni24')));
}

//Miniaturki
add_image_size('scene', 2000, 300, true);

//Główne menu

function add_menu_link_class($ulclass) {
    return preg_replace('/<a /', '<a class="link-secondary nav-link"', $ulclass, -1);
}

add_filter('wp_nav_menu', 'add_menu_link_class');

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
