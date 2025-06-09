<?php
// wp-content/themes/carni24/includes/disable-comments.php
// Kompletne wyłączenie obsługi komentarzy

// Wyłącz obsługę komentarzy dla wszystkich typów postów
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

// Zamknij komentarze w istniejących postach
function carni24_disable_comments_status() {
    return false;
}
add_filter('comments_open', 'carni24_disable_comments_status', 20, 2);
add_filter('pings_open', 'carni24_disable_comments_status', 20, 2);

// Ukryj istniejące komentarze
function carni24_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'carni24_disable_comments_hide_existing_comments', 10, 2);

// Usuń menu komentarzy z panelu admina
function carni24_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'carni24_disable_comments_admin_menu');

// Usuń komentarze z paska administracyjnego
function carni24_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'carni24_disable_comments_admin_bar');

// Usuń widget komentarzy z dashboardu
function carni24_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'carni24_disable_comments_dashboard');

// Przekieruj próby dostępu do strony komentarzy
function carni24_disable_comments_admin_redirect() {
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'carni24_disable_comments_admin_redirect');

// Usuń komentarze z RSS
function carni24_disable_comments_feed() {
    wp_die(__('Komentarze nie są obsługiwane.'), '', array('response' => 410));
}
add_action('do_feed_rdf', 'carni24_disable_comments_feed', 1);
add_action('do_feed_rss', 'carni24_disable_comments_feed', 1);
add_action('do_feed_rss2', 'carni24_disable_comments_feed', 1);
add_action('do_feed_atom', 'carni24_disable_comments_feed', 1);
add_action('do_feed_rss2_comments', 'carni24_disable_comments_feed', 1);
add_action('do_feed_atom_comments', 'carni24_disable_comments_feed', 1);

// Usuń pola komentarzy z Quick/Bulk Edit
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

// Usuń kolumnę komentarzy z list postów
function carni24_disable_comments_columns($columns) {
    unset($columns['comments']);
    return $columns;
}
add_filter('manage_posts_columns', 'carni24_disable_comments_columns');
add_filter('manage_pages_columns', 'carni24_disable_comments_columns');

// Wyłącz XML-RPC methods związane z komentarzami
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

// Usuń notyfikacje o komentarzach
remove_action('comment_post', 'wp_new_comment_notify_moderator');
remove_action('comment_post', 'wp_new_comment_notify_postauthor');

// Usuń meta boxy komentarzy z ekranów edycji
function carni24_disable_comments_meta_boxes() {
    remove_meta_box('commentstatusdiv', 'post', 'normal');
    remove_meta_box('commentstatusdiv', 'page', 'normal');
    remove_meta_box('commentstatusdiv', 'species', 'normal');
    remove_meta_box('commentsdiv', 'post', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'species', 'normal');
    remove_meta_box('trackbacksdiv', 'post', 'normal');
    remove_meta_box('trackbacksdiv', 'page', 'normal');
}
add_action('admin_init', 'carni24_disable_comments_meta_boxes');

// Wyłącz discussion settings
function carni24_disable_comments_options() {
    update_option('default_comment_status', 'closed');
    update_option('default_ping_status', 'closed');
}
add_action('init', 'carni24_disable_comments_options');

// Usuń linki do komentarzy z post_row_actions
function carni24_disable_comments_row_actions($actions) {
    unset($actions['inline hide-if-no-js']);
    return $actions;
}
add_filter('post_row_actions', 'carni24_disable_comments_row_actions', 10, 1);
add_filter('page_row_actions', 'carni24_disable_comments_row_actions', 10, 1);

// Wyłącz REST API endpoints dla komentarzy
function carni24_disable_comments_rest_api() {
    // Usuń endpoint komentarzy
    remove_action('rest_api_init', 'create_initial_rest_routes', 99);
}
add_action('rest_api_init', 'carni24_disable_comments_rest_api', 9);

// Filtruj REST API aby usunąć endpoints komentarzy
function carni24_filter_rest_endpoints($endpoints) {
    unset($endpoints['/wp/v2/comments']);
    unset($endpoints['/wp/v2/comments/(?P<id>[\d]+)']);
    return $endpoints;
}
add_filter('rest_endpoints', 'carni24_filter_rest_endpoints');

// Usuń wsparcie dla komentarzy w theme
remove_theme_support('html5', array('comment-form', 'comment-list'));

// Przekieruj URLs komentarzy do 410 Gone
function carni24_disable_comments_template_redirect() {
    if (is_comment_feed()) {
        wp_die(__('Komentarze nie są obsługiwane.'), '', array('response' => 410));
    }
}
add_action('template_redirect', 'carni24_disable_comments_template_redirect');
?>