<?php
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

//add_filter('the_title', 'blankslate_title');
//
//function blankslate_title($title) {
//    if ($title == '') {
//        return '...';
//    } else {
//        return $title;
//    }
//}

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


//Główne menu

function add_menu_link_class($ulclass) {
    echo $id;
    return preg_replace('/<a /', '<a class="link-secondary nav-link"', $ulclass, -1);
}
add_filter('wp_nav_menu', 'add_menu_link_class');