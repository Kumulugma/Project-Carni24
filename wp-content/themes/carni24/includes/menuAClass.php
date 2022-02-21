<?php

function add_menu_link_class($ulclass) {
    echo $id;
    return preg_replace('/<a /', '<a class="link-secondary nav-link"', $ulclass, -1);
}

add_filter('wp_nav_menu', 'add_menu_link_class');
