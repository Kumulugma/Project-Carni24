<?php

function custom_rewrite_tag() {
    add_rewrite_tag('%spec%', '([^&]+)');
}

add_action('init', 'custom_rewrite_tag', 10, 0);

function custom_rewrite_rule() {
    add_rewrite_rule('^spec/([^/]*)/?', 'index.php?page_id=111&spec=$matches[1]', 'top');
}

add_action('init', 'custom_rewrite_rule', 10, 0);
