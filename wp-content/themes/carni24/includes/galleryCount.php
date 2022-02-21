<?php

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
