<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'carni24_disable_comments_post_types_support');

function carni24_disable_comments_status() {
    return false;
}
add_filter('comments_open', 'carni24_disable_comments_status', 20, 2);
add_filter('pings_open', 'carni24_disable_comments_status', 20, 2);

function carni24_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'carni24_disable_comments_hide_existing_comments', 10, 2);

function carni24_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'carni24_disable_comments_admin_menu');

function carni24_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'carni24_disable_comments_admin_bar');

function carni24_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'carni24_disable_comments_dashboard');

function carni24_disable_comments_admin_redirect() {
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'carni24_disable_comments_admin_redirect');

function carni24_disable_comments_feed() {
    wp_die(__('Komentarze nie są obsługiwane.'), '', array('response' => 410));
}
add_action('do_feed_rdf', 'carni24_disable_comments_feed', 1);
add_action('do_feed_rss', 'carni24_disable_comments_feed', 1);
add_action('do_feed_rss2', 'carni24_disable_comments_feed', 1);
add_action('do_feed_atom', 'carni24_disable_comments_feed', 1);
add_action('do_feed_rss2_comments', 'carni24_disable_comments_feed', 1);
add_action('do_feed_atom_comments', 'carni24_disable_comments_feed', 1);

function carni24_disable_comments_admin_css() {
    echo '<style>
        .comment-php #wpbody-content .wrap h1:first-child,
        .comment-php #adminmenu #menu-comments,
        #dashboard_recent_comments,
        .edit-php .tablenav select[name="comment_status"],
        .edit-php .column-comments,
        .edit-php .manage-column.column-comments,
        #bulk-edit .inline-edit-group.wp-clearfix:nth-of-type(3),
        #quick-edit-legend .comment,
        #bulk-edit-legend .comment,
        .quick-edit-row .inline-edit-group.wp-clearfix:nth-of-type(3),
        .inline-edit-row .inline-edit-group.wp-clearfix:nth-of-type(3) {
            display: none !important;
        }
    </style>';
}
add_action('admin_head', 'carni24_disable_comments_admin_css');

function carni24_disable_comments_columns($columns) {
    unset($columns['comments']);
    return $columns;
}
add_filter('manage_posts_columns', 'carni24_disable_comments_columns');
add_filter('manage_pages_columns', 'carni24_disable_comments_columns');

function carni24_disable_comments_xmlrpc($methods) {
    unset($methods['wp.newComment']);
    unset($methods['wp.getComments']);
    unset($methods['wp.getComment']);
    unset($methods['wp.deleteComment']);
    unset($methods['wp.editComment']);
    unset($methods['wp.getCommentCount']);
    unset($methods['wp.getCommentStatusList']);
    return $methods;
}
add_filter('xmlrpc_methods', 'carni24_disable_comments_xmlrpc');

remove_action('comment_post', 'wp_new_comment_notify_moderator');
remove_action('comment_post', 'wp_new_comment_notify_postauthor');

function carni24_disable_comments_meta_boxes() {
    remove_meta_box('commentstatusdiv', 'post', 'normal');
    remove_meta_box('commentstatusdiv', 'page', 'normal');
    remove_meta_box('commentstatusdiv', 'species', 'normal');
    remove_meta_box('commentstatusdiv', 'guides', 'normal');
    remove_meta_box('commentsdiv', 'post', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'species', 'normal');
    remove_meta_box('commentsdiv', 'guides', 'normal');
    remove_meta_box('trackbacksdiv', 'post', 'normal');
    remove_meta_box('trackbacksdiv', 'page', 'normal');
}
add_action('admin_init', 'carni24_disable_comments_meta_boxes');

function carni24_disable_comments_options() {
    update_option('default_comment_status', 'closed');
    update_option('default_ping_status', 'closed');
}
add_action('init', 'carni24_disable_comments_options');

function carni24_disable_comments_row_actions($actions) {
    unset($actions['inline hide-if-no-js']);
    return $actions;
}
add_filter('post_row_actions', 'carni24_disable_comments_row_actions', 10, 1);
add_filter('page_row_actions', 'carni24_disable_comments_row_actions', 10, 1);

function carni24_filter_rest_endpoints($endpoints) {
    unset($endpoints['/wp/v2/comments']);
    unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
    return $endpoints;
}
add_filter('rest_endpoints', 'carni24_filter_rest_endpoints');

remove_theme_support('html5', array('comment-form', 'comment-list'));

function carni24_disable_comments_template_redirect() {
    if (is_comment_feed()) {
        wp_die(__('Komentarze nie są obsługiwane.'), '', array('response' => 410));
    }
}
add_action('template_redirect', 'carni24_disable_comments_template_redirect');

function carni24_disable_comments_walker($walker_class, $comment_type) {
    return '';
}
add_filter('wp_list_comments_args', function($args) {
    $args['walker'] = null;
    return $args;
});

function carni24_remove_comment_support_from_custom_post_types() {
    $post_types = array('species', 'guides');
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
        }
        if (post_type_supports($post_type, 'trackbacks')) {
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('init', 'carni24_remove_comment_support_from_custom_post_types', 999);

function carni24_disable_comment_reply_link() {
    return false;
}
add_filter('comment_reply_link', 'carni24_disable_comment_reply_link');

function carni24_disable_comment_form() {
    return '';
}
add_filter('comment_form_default_fields', 'carni24_disable_comment_form');
add_filter('comment_form_field_comment', 'carni24_disable_comment_form');

function carni24_force_comment_status_closed($open, $post_id) {
    return false;
}
add_filter('comments_open', 'carni24_force_comment_status_closed', 50, 2);

function carni24_remove_comment_cookies() {
    if (isset($_COOKIE['comment_author_' . COOKIEHASH])) {
        setcookie('comment_author_' . COOKIEHASH, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
    }
    if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
        setcookie('comment_author_email_' . COOKIEHASH, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
    }
    if (isset($_COOKIE['comment_author_url_' . COOKIEHASH])) {
        setcookie('comment_author_url_' . COOKIEHASH, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
    }
}
add_action('init', 'carni24_remove_comment_cookies');

function carni24_remove_comments_from_search($query) {
    if ($query->is_search() && !is_admin()) {
        $query->set('post_type', array('post', 'page', 'species', 'guides'));
    }
    return $query;
}
add_filter('pre_get_posts', 'carni24_remove_comments_from_search');

function carni24_disable_comment_autop($content) {
    if (is_singular() && !comments_open()) {
        remove_filter('comment_text', 'wpautop', 30);
    }
    return $content;
}
add_filter('the_content', 'carni24_disable_comment_autop', 999);

function carni24_remove_comment_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_enqueue_scripts', 'carni24_remove_comment_styles', 100);

function carni24_disable_comment_metadata() {
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
}
add_action('init', 'carni24_disable_comment_metadata');

function carni24_cleanup_comment_queries($pieces, $query) {
    if (!is_admin() && $query->is_main_query()) {
        global $wpdb;
        $pieces['where'] = str_replace(
            "AND {$wpdb->posts}.comment_count", 
            "AND 1=1 AND {$wpdb->posts}.comment_count", 
            $pieces['where']
        );
    }
    return $pieces;
}
add_filter('posts_clauses', 'carni24_cleanup_comment_queries', 10, 2);