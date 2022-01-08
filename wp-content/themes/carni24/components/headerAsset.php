<?php

add_action('get_header', function () {
    if (!is_admin()) {
        wp_enqueue_style('Bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css", false, '1.1', 'all');
        wp_enqueue_script('Bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js', false, '1.0', 'all');
        wp_enqueue_style('Style', get_template_directory_uri() . '/assets/css/style.css', false, '1.0');
    }
});

add_action('get_header', function () {
    if (is_single()) {
        wp_enqueue_style('Article', get_template_directory_uri() . '/assets/css/article.css');
    }

    if (is_search()) {
        wp_enqueue_style('Search', get_template_directory_uri() . '/assets/css/search.css');
    }

    if (is_tag()) {
        wp_enqueue_style('Tag', get_template_directory_uri() . '/assets/css/tag.css');
    }

    if (is_tag()) {
        wp_enqueue_style('Tag', get_template_directory_uri() . '/assets/css/tag.css');
    }

    if (is_404()) {
        wp_enqueue_script('AnimeJs', get_template_directory_uri() . '/node_modules/animejs/lib/anime.min.js');
        wp_enqueue_script('404', get_template_directory_uri() . '/assets/js/404.js');
        wp_enqueue_style('404', get_template_directory_uri() . '/assets/css/404.css');
    }

    if (is_front_page()) {
        wp_enqueue_script('Homepage', get_template_directory_uri() . '/assets/js/homepage.js');
        wp_enqueue_style('Homepage', get_template_directory_uri() . '/assets/css/homepage.css');
    }

    if (is_category()) {
        wp_enqueue_script('Category', get_template_directory_uri() . '/assets/js/category.js');
        wp_enqueue_style('Category', get_template_directory_uri() . '/assets/css/category.css');
    }

    if (is_page(242)) {
        wp_register_script('jQuery', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', null, null, true);
        wp_enqueue_script('jQuery');
        wp_enqueue_style('k3e-gallery', get_template_directory_uri() . '/assets/css/gallery.css', false, '1.1', 'all');
    }
});

