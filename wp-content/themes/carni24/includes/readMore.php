<?php

add_filter('the_content_more_link', 'theme_read_more_link');

function theme_read_more_link() {
    if (!is_admin()) {
        //return ' <a href="' . esc_url(get_permalink()) . '" class="more-link"><button type="button" class="btn btn-sm btn-outline-secondary">Zobacz więcej</button></a>';

        return readMore(get_permalink($post->ID));
    }
}

add_filter('excerpt_more', 'theme_excerpt_read_more_link');

function theme_excerpt_read_more_link($more) {
    if (!is_admin()) {
        global $post;
        //return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link"><button type="button" class="btn btn-sm btn-outline-secondary">Zobacz więcej</button></a>';

        return readMore(get_permalink($post->ID));
    }
}

function readMore($url) {

    $result = '<div class="d-grid gap-2 col-6 mx-auto">';
    $result .= '<a href="' . esc_url($url) . '" class="more-link"><button class="btn btn-sm btn-outline-secondary" type="button">Zobacz więcej</button></a>';
    $result .= '</div>';

    return $result;
}
