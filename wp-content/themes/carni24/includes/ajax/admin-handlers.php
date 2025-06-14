<?php
/**
 * Carni24 AJAX Admin Handlers
 * Obsługa AJAX dla panelu administracyjnego
 * 
 * @package Carni24
 * @subpackage Ajax
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

// ===== SEO TEST AJAX ===== //

/**
 * AJAX: Test konfiguracji SEO
 */
function carni24_ajax_test_seo() {
    // Sprawdź nonce
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    // Sprawdź uprawnienia
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Brak uprawnień');
    }
    
    // Wykonaj test SEO
    $results = array(
        'site_name' => !empty(carni24_get_option('site_name', '')),
        'site_description' => !empty(carni24_get_option('site_description', '')),
        'default_meta_description' => !empty(carni24_get_option('default_meta_description', '')),
        'default_meta_keywords' => !empty(carni24_get_option('default_meta_keywords', '')),
        'default_og_image' => !empty(carni24_get_option('default_og_image', '')),
        'seo_function_hooked' => has_action('wp_head', 'carni24_seo_meta_tags'),
        'breadcrumbs_function' => function_exists('carni24_breadcrumbs'),
        'schema_function' => has_action('wp_head', 'carni24_json_ld_schema'),
    );
    
    // Dodatkowe sprawdzenia
    $results['meta_boxes_registered'] = has_action('add_meta_boxes', 'carni24_add_seo_meta_boxes');
    $results['title_filter_active'] = has_filter('document_title_parts', 'carni24_document_title_parts');
    
    wp_send_json_success($results);
}
add_action('wp_ajax_carni24_test_seo', 'carni24_ajax_test_seo');

// ===== CACHE MANAGEMENT ===== //

/**
 * AJAX: Wyczyść cache motywu
 */
function carni24_ajax_clear_cache() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Brak uprawnień');
    }
    
    $cleared = array();
    
    // Wyczyść WordPress transients
    global $wpdb;
    $result = $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' OR option_name LIKE '_site_transient_%'");
    if ($result !== false) {
        $cleared[] = 'WordPress transients (' . $result . ')';
    }
    
    // Wyczyść opcache jeśli dostępne
    if (function_exists('opcache_reset')) {
        opcache_reset();
        $cleared[] = 'OPcache';
    }
    
    // Wyczyść cache popularnych wtyczek
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
        $cleared[] = 'Object cache';
    }
    
    // W3 Total Cache
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
        $cleared[] = 'W3 Total Cache';
    }
    
    // WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
        $cleared[] = 'WP Super Cache';
    }
    
    // WP Rocket
    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
        $cleared[] = 'WP Rocket';
    }
    
    wp_send_json_success(array(
        'message' => 'Cache został wyczyszczony',
        'cleared' => $cleared
    ));
}
add_action('wp_ajax_carni24_clear_cache', 'carni24_ajax_clear_cache');

// ===== THUMBNAIL REGENERATION ===== //

/**
 * AJAX: Regeneruj miniaturki obrazów
 */
function carni24_ajax_regenerate_thumbnails() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Brak uprawnień');
    }
    
    // Zwiększ limit czasu i pamięci
    set_time_limit(300);
    ini_set('memory_limit', '512M');
    
    // Pobierz ostatnie 50 obrazów
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'numberposts' => 50,
        'post_status' => 'any',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    $regenerated = 0;
    $errors = array();
    
    foreach ($attachments as $attachment) {
        $file_path = get_attached_file($attachment->ID);
        
        if (!file_exists($file_path)) {
            $errors[] = "Plik nie istnieje: {$attachment->post_title}";
            continue;
        }
        
        // Regeneruj miniaturki
        $metadata = wp_generate_attachment_metadata($attachment->ID, $file_path);
        
        if ($metadata) {
            wp_update_attachment_metadata($attachment->ID, $metadata);
            $regenerated++;
        } else {
            $errors[] = "Błąd regeneracji: {$attachment->post_title}";
        }
    }
    
    wp_send_json_success(array(
        'regenerated' => $regenerated,
        'total' => count($attachments),
        'errors' => $errors
    ));
}
add_action('wp_ajax_carni24_regenerate_thumbnails', 'carni24_ajax_regenerate_thumbnails');

// ===== DATABASE OPTIMIZATION ===== //

/**
 * AJAX: Optymalizuj bazę danych
 */
function carni24_ajax_optimize_database() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Brak uprawnień');
    }
    
    global $wpdb;
    $optimized = array();
    
    // Usuń spam komentarze
    $spam_comments = $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
    if ($spam_comments > 0) {
        $optimized[] = "Usunięto {$spam_comments} spam komentarzy";
    }
    
    // Usuń komentarze w koszu
    $trash_comments = $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'trash'");
    if ($trash_comments > 0) {
        $optimized[] = "Usunięto {$trash_comments} komentarzy z kosza";
    }
    
    // Usuń nieużywane tagi
    $unused_terms = $wpdb->query("
        DELETE t, tt FROM {$wpdb->terms} t 
        INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id 
        WHERE tt.count = 0 AND tt.taxonomy = 'post_tag'
    ");
    if ($unused_terms > 0) {
        $optimized[] = "Usunięto {$unused_terms} nieużywanych tagów";
    }
    
    // Usuń drafty starsze niż 30 dni
    $old_drafts = $wpdb->query($wpdb->prepare("
        DELETE FROM {$wpdb->posts} 
        WHERE post_status = 'draft' 
        AND post_modified < %s
    ", date('Y-m-d H:i:s', strtotime('-30 days'))));
    
    if ($old_drafts > 0) {
        $optimized[] = "Usunięto {$old_drafts} starych szkiców";
    }
    
    // Optymalizuj tabele
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    $optimized_tables = 0;
    
    foreach ($tables as $table) {
        $table_name = $table[0];
        if (strpos($table_name, $wpdb->prefix) === 0) {
            $wpdb->query("OPTIMIZE TABLE {$table_name}");
            $optimized_tables++;
        }
    }
    
    $optimized[] = "Zoptymalizowano {$optimized_tables} tabel";
    
    wp_send_json_success(array(
        'message' => 'Baza danych została zoptymalizowana',
        'operations' => $optimized
    ));
}
add_action('wp_ajax_carni24_optimize_database', 'carni24_ajax_optimize_database');

// ===== SYSTEM INFO ===== //

/**
 * AJAX: Pobierz informacje systemowe
 */
function carni24_ajax_get_system_info() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Brak uprawnień');
    }
    
    $info = array(
        'php_version' => PHP_VERSION,
        'wp_version' => get_bloginfo('version'),
        'theme_version' => CARNI24_VERSION,
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'active_plugins' => count(get_option('active_plugins')),
        'db_version' => $GLOBALS['wpdb']->db_version(),
        'multisite' => is_multisite(),
        'wp_debug' => defined('WP_DEBUG') && WP_DEBUG,
        'wp_cache' => defined('WP_CACHE') && WP_CACHE,
    );
    
    // Sprawdź rozmiary obrazów
    $image_sizes = get_intermediate_image_sizes();
    $info['image_sizes_count'] = count($image_sizes);
    
    // Sprawdź czy są niezbędne foldery
    $required_dirs = array(
        wp_upload_dir()['basedir'],
        ABSPATH . 'wp-content/themes',
        ABSPATH . 'wp-content/plugins',
    );
    
    $writable_dirs = 0;
    foreach ($required_dirs as $dir) {
        if (is_writable($dir)) {
            $writable_dirs++;
        }
    }
    
    $info['writable_dirs'] = $writable_dirs . '/' . count($required_dirs);
    
    wp_send_json_success($info);
}
add_action('wp_ajax_carni24_get_system_info', 'carni24_ajax_get_system_info');

// ===== IMPORT/EXPORT SETTINGS ===== //

/**
 * AJAX: Export ustawień motywu
 */
function carni24_ajax_export_settings() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Brak uprawnień');
    }
    
    $settings = get_option('carni24_theme_options', array());
    
    // Dodaj metadata
    $export_data = array(
        'export_date' => current_time('Y-m-d H:i:s'),
        'theme_version' => CARNI24_VERSION,
        'wp_version' => get_bloginfo('version'),
        'site_url' => get_site_url(),
        'settings' => $settings
    );
    
    wp_send_json_success(array(
        'filename' => 'carni24-settings-' . date('Y-m-d') . '.json',
        'data' => $export_data
    ));
}
add_action('wp_ajax_carni24_export_settings', 'carni24_ajax_export_settings');

/**
 * AJAX: Import ustawień motywu
 */
function carni24_ajax_import_settings() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Brak uprawnień');
    }
    
    if (!isset($_POST['settings_data'])) {
        wp_send_json_error('Brak danych do importu');
    }
    
    $import_data = json_decode(stripslashes($_POST['settings_data']), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Nieprawidłowy format JSON');
    }
    
    if (!isset($import_data['settings'])) {
        wp_send_json_error('Brak ustawień w pliku');
    }
    
    // Walidacja i import ustawień
    $allowed_options = array(
        'site_name', 'site_description', 'navigation_title', 'navigation_content',
        'default_meta_description', 'default_meta_keywords', 'default_og_image',
        'lazy_loading', 'minify_css', 'minify_js', 'google_analytics', 'facebook_pixel'
    );
    
    $validated_settings = array();
    foreach ($import_data['settings'] as $key => $value) {
        if (in_array($key, $allowed_options)) {
            $validated_settings[$key] = sanitize_text_field($value);
        }
    }
    
    // Zapisz ustawienia
    update_option('carni24_theme_options', $validated_settings);
    
    wp_send_json_success(array(
        'message' => 'Ustawienia zostały zaimportowane',
        'imported_count' => count($validated_settings),
        'source_version' => isset($import_data['theme_version']) ? $import_data['theme_version'] : 'nieznana'
    ));
}
add_action('wp_ajax_carni24_import_settings', 'carni24_ajax_import_settings');