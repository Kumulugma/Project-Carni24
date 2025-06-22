<?php
/**
 * Admin Assets - Naprawiony system ładowania CSS/JS dla admin
 * 
 * @package Carni24
 * @subpackage Admin
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ładuje wszystkie assets dla panelu administracyjnego
 */
function carni24_admin_enqueue_assets($hook) {
    // ===== CSS/JS dla stron edycji postów ===== //
    if (in_array($hook, array('post.php', 'post-new.php'))) {
        carni24_enqueue_post_edit_assets();
    }
    
    // ===== CSS/JS dla dashboard ===== //
    if ($hook === 'index.php') {
        carni24_enqueue_dashboard_assets();
    }
    
    // ===== CSS/JS dla list postów ===== //
    if ($hook === 'edit.php') {
        carni24_enqueue_post_list_assets();
    }
}
add_action('admin_enqueue_scripts', 'carni24_admin_enqueue_assets');

/**
 * Ładuje assets dla stron edycji postów (SEO meta box)
 */
function carni24_enqueue_post_edit_assets() {
    global $post_type;
    
    // Sprawdź czy to odpowiedni typ postu
    $allowed_post_types = array('post', 'page', 'species', 'guides');
    if (!in_array($post_type, $allowed_post_types)) {
        return;
    }
    
    // WordPress Media Uploader
    wp_enqueue_media();
    
    // SEO Meta Box CSS
    wp_enqueue_style(
        'carni24-seo-metabox',
        get_template_directory_uri() . '/assets/css/admin/seo-metabox.css',
        array(),
        CARNI24_VERSION
    );
    
    // SEO Meta Box JavaScript
    wp_enqueue_script(
        'carni24-seo-metabox',
        get_template_directory_uri() . '/assets/js/admin/seo-metabox.js',
        array('jquery', 'wp-media'),
        CARNI24_VERSION,
        true
    );
    
    // Localize script dla SEO meta box
    wp_localize_script('carni24-seo-metabox', 'carni24SeoMetabox', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_seo_metabox'),
        'strings' => array(
            'selectImage' => 'Wybierz obraz',
            'useImage' => 'Użyj tego obrazu',
            'changeImage' => '🔄 Zmień obraz',
            'addImage' => '📷 Dodaj obraz',
        )
    ));
    
    // Dodatkowe style inline dla meta boxów
    wp_add_inline_style('carni24-seo-metabox', carni24_get_metabox_inline_css());
}

/**
 * Ładuje assets dla dashboard
 */
function carni24_enqueue_dashboard_assets() {
    // SEO Monitor CSS
    wp_enqueue_style(
        'carni24-seo-monitor',
        get_template_directory_uri() . '/assets/css/admin/seo-monitor.css',
        array(),
        CARNI24_VERSION
    );
    
    // SEO Monitor JavaScript
    wp_enqueue_script(
        'carni24-seo-monitor',
        get_template_directory_uri() . '/assets/js/admin/seo-monitor.js',
        array('jquery'),
        CARNI24_VERSION,
        true
    );
    
    // Localize script dla SEO monitor
    wp_localize_script('carni24-seo-monitor', 'carni24SeoMonitor', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'refreshNonce' => wp_create_nonce('carni24_seo_refresh'),
        'ignoreNonce' => wp_create_nonce('carni24_seo_ignore'),
        'loadMoreNonce' => wp_create_nonce('carni24_seo_load_more'),
        'strings' => array(
            'confirm_ignore' => 'Czy na pewno chcesz zignorować ten wpis?',
            'loading' => 'Ładowanie...',
            'error' => 'Wystąpił błąd. Spróbuj ponownie.',
        )
    ));
    
    // Dashboard widgets CSS - inline
    wp_add_inline_style('wp-admin', carni24_get_dashboard_inline_css());
}

/**
 * Ładuje assets dla list postów
 */
function carni24_enqueue_post_list_assets() {
    global $typenow;
    
    // CSS tylko dla odpowiednich typów postów
    if (in_array($typenow, ['post', 'page', 'species', 'guides'])) {
        wp_add_inline_style('wp-admin', carni24_get_post_list_inline_css());
    }
}

/**
 * Zwraca inline CSS dla meta boxów
 */
function carni24_get_metabox_inline_css() {
    return '
    /* ===== OGÓLNE STYLE METABOXÓW ===== */
    .post-type-species .postbox,
    .post-type-guides .postbox {
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        overflow: hidden;
    }
    
    .post-type-species .postbox .postbox-header,
    .post-type-guides .postbox .postbox-header {
        background: linear-gradient(135deg, #e8f5e8, #d4edda);
        border-bottom: 1px solid #c3e6cb;
    }
    
    .post-type-species .postbox .postbox-header h2,
    .post-type-guides .postbox .postbox-header h2 {
        color: #155724;
        font-weight: 600;
    }
    
    /* SEO Meta Box - ulepszona wersja */
    #carni24_seo_settings_improved .postbox-header {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb) !important;
        border-bottom: 1px solid #90caf9 !important;
    }
    
    #carni24_seo_settings_improved .postbox-header h2 {
        color: #0d47a1 !important;
    }
    
    /* Meta box loading state */
    .carni24-metabox-loading {
        position: relative;
        opacity: 0.6;
        pointer-events: none;
    }
    
    .carni24-metabox-loading::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #0073aa;
        border-radius: 50%;
        animation: carni24-spin 1s linear infinite;
    }
    
    @keyframes carni24-spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    ';
}

/**
 * Zwraca inline CSS dla dashboard
 */
function carni24_get_dashboard_inline_css() {
    return '
    /* ===== DASHBOARD WIDGETS PODSTAWOWE ===== */
    .carni24-stats-widget,
    .carni24-activity-widget,
    .carni24-tools-widget {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    /* Widget loading state */
    .carni24-widget-loading {
        position: relative;
        opacity: 0.6;
    }
    
    .carni24-widget-loading::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 30px;
        height: 30px;
        margin: -15px 0 0 -15px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #0073aa;
        border-radius: 50%;
        animation: carni24-spin 1s linear infinite;
        z-index: 1000;
    }
    
    /* Podstawowe style dla kompatybilności */
    .carni24-seo-monitor {
        margin: -12px;
        background: #fff;
    }
    
    /* Fallback dla brakujących CSS */
    .seo-tab-btn {
        background: none;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        font-size: 13px;
    }
    
    .seo-tab-btn.active {
        background: #0073aa;
        color: #fff;
    }
    
    .seo-post-item {
        padding: 15px;
        border: 1px solid #e1e5e9;
        margin-bottom: 10px;
        border-radius: 4px;
    }
    ';
}

/**
 * Zwraca inline CSS dla list postów
 */
function carni24_get_post_list_inline_css() {
    return '
    /* ===== FEATURED IMAGE COLUMN ===== */
    .column-featured_image {
        width: 80px;
    }
    
    .featured-image-thumb {
        width: 60px;
        height: 60px;
        border-radius: 4px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .featured-image-thumb:hover {
        transform: scale(1.1);
    }
    
    .no-featured-image {
        width: 60px;
        height: 60px;
        background: #f1f1f1;
        border: 2px dashed #ccc;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #666;
        text-align: center;
    }
    
    /* Modal dla podglądu obrazu */
    .featured-image-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100000;
        cursor: pointer;
    }
    
    .featured-image-modal img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    ';
}

/**
 * Dodaje nonce dla AJAX requests
 */
function carni24_admin_add_ajax_nonce() {
    if (is_admin()) {
        ?>
        <script type="text/javascript">
            window.carni24_admin_nonce = '<?= wp_create_nonce('carni24_admin') ?>';
            window.ajaxurl = '<?= admin_url('admin-ajax.php') ?>';
        </script>
        <?php
    }
}
add_action('admin_footer', 'carni24_admin_add_ajax_nonce');

/**
 * Sprawdza czy pliki CSS/JS istnieją
 */
function carni24_check_admin_assets() {
    $required_files = array(
        get_template_directory() . '/assets/css/admin/seo-metabox.css',
        get_template_directory() . '/assets/css/admin/seo-monitor.css',
        get_template_directory() . '/assets/js/admin/seo-metabox.js',
        get_template_directory() . '/assets/js/admin/seo-monitor.js',
    );
    
    $missing_files = array();
    foreach ($required_files as $file) {
        if (!file_exists($file)) {
            $missing_files[] = basename($file);
        }
    }
    
    if (!empty($missing_files)) {
        add_action('admin_notices', function() use ($missing_files) {
            echo '<div class="notice notice-error"><p>';
            echo '<strong>Carni24:</strong> Brakujące pliki assets: ' . implode(', ', $missing_files);
            echo '</p></div>';
        });
    }
}
add_action('admin_init', 'carni24_check_admin_assets');

/**
 * Fallback CSS dla przypadku gdy pliki nie załadują się
 */
function carni24_admin_fallback_css() {
    ?>
    <style>
    /* FALLBACK CSS - ładuje się zawsze */
    .carni24-seo-metabox {
        margin: -6px -12px -12px;
        background: #fff;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .carni24-seo-tabs {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        background: #f1f1f1;
        border-bottom: 1px solid #ccd0d4;
    }
    
    .carni24-seo-tabs li {
        flex: 1;
    }
    
    .carni24-seo-tabs .tab-link {
        display: block;
        padding: 12px 16px;
        text-decoration: none;
        color: #555;
        font-weight: 500;
        font-size: 13px;
        border-bottom: 3px solid transparent;
        text-align: center;
    }
    
    .carni24-seo-tabs .tab-link.active {
        background: #fff;
        color: #0073aa;
        border-bottom-color: #0073aa;
    }
    
    .carni24-seo-content {
        padding: 20px;
    }
    
    .seo-tab-content {
        display: none;
    }
    
    .seo-tab-content.active {
        display: block;
    }
    
    .seo-field {
        margin-bottom: 20px;
    }
    
    .seo-field label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .seo-field input[type="text"],
    .seo-field input[type="url"],
    .seo-field textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .char-counter {
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }
    
    .google-preview {
        max-width: 600px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid #4285f4;
    }
    
    .preview-title {
        color: #1a0dab;
        font-size: 18px;
        margin-bottom: 2px;
    }
    
    .preview-url {
        color: #006621;
        font-size: 14px;
        margin-bottom: 6px;
    }
    
    .preview-description {
        color: #545454;
        font-size: 14px;
        line-height: 1.4;
    }
    
    /* SEO Monitor basic styles */
    .carni24-seo-monitor {
        margin: -12px;
        background: #fff;
    }
    
    .seo-monitor-tabs {
        display: flex;
        background: #f1f1f1;
        border-bottom: 1px solid #ccd0d4;
    }
    
    .seo-tab-btn {
        flex: 1;
        background: none;
        border: none;
        padding: 12px 16px;
        cursor: pointer;
        font-size: 13px;
        color: #555;
    }
    
    .seo-tab-btn.active {
        background: #fff;
        color: #0073aa;
        border-bottom: 3px solid #0073aa;
    }
    
    .seo-monitor-content {
        padding: 20px;
    }
    
    .seo-post-item {
        background: #fff;
        border: 1px solid #e1e5e9;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .seo-post-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .seo-post-title {
        margin: 0 0 6px;
        font-size: 16px;
    }
    
    .seo-post-title a {
        color: #0073aa;
        text-decoration: none;
    }
    
    .seo-issue {
        display: flex;
        align-items: center;
        padding: 6px 8px;
        margin-bottom: 6px;
        border-radius: 4px;
        font-size: 13px;
        background: #fff3cd;
        color: #856404;
    }
    
    .issue-icon {
        margin-right: 8px;
    }
    </style>
    <?php
}
add_action('admin_head', 'carni24_admin_fallback_css', 1);

/**
 * Debug info dla admin assets
 */
function carni24_admin_debug_assets() {
    if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) {
        add_action('admin_footer', function() {
            $screen = get_current_screen();
            echo '<!-- Carni24 Admin Assets Debug: Screen=' . $screen->id . ', Post Type=' . (isset($GLOBALS['post_type']) ? $GLOBALS['post_type'] : 'none') . ' -->';
        });
    }
}
add_action('admin_init', 'carni24_admin_debug_assets');