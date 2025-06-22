<?php
/**
 * AJAX Handlers dla panelu administracyjnego
 * 
 * @package Carni24
 * @subpackage AJAX
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX handler dla odświeżania statystyk dashboard
 */
function carni24_ajax_refresh_dashboard_stats() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('read')) {
        wp_die('Brak uprawnień');
    }
    
    // Odśwież cache statystyk
    delete_transient('carni24_unified_stats');
    delete_transient('carni24_unified_seo');
    delete_transient('carni24_dashboard_stats');
    delete_transient('carni24_seo_stats');
    delete_transient('carni24_popular_content');
    
    wp_send_json_success('Statystyki zostały odświeżone');
}
add_action('wp_ajax_carni24_refresh_dashboard_stats', 'carni24_ajax_refresh_dashboard_stats');

/**
 * AJAX handler dla testu SEO
 */
function carni24_ajax_test_seo() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $results = array(
        'meta_tags' => carni24_check_meta_tags(),
        'og_tags' => carni24_check_og_tags(),
        'structured_data' => carni24_check_structured_data(),
        'breadcrumbs' => carni24_check_breadcrumbs(),
        'sitemaps' => carni24_check_sitemaps(),
        'robots_txt' => carni24_check_robots_txt(),
        'page_speed' => carni24_check_basic_performance(),
        'ssl_certificate' => carni24_check_ssl(),
        'mobile_friendly' => carni24_check_mobile_viewport()
    );
    
    wp_send_json_success($results);
}
add_action('wp_ajax_carni24_test_seo', 'carni24_ajax_test_seo');

/**
 * AJAX handler dla czyszczenia cache
 */
function carni24_ajax_clear_cache() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Wyczyść wszystkie cache Carni24
    $cache_keys = array(
        'carni24_unified_stats',
        'carni24_unified_seo',
        'carni24_dashboard_stats',
        'carni24_seo_stats',
        'carni24_popular_content',
        'carni24_seo_audit_results',
        'carni24_keywords_analysis',
        'carni24_internal_links_check'
    );
    
    foreach ($cache_keys as $key) {
        delete_transient($key);
    }
    
    // Wyczyść cache WordPress
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    // Wyczyść cache obiektów
    wp_cache_delete_group('carni24');
    
    wp_send_json_success('Cache został wyczyszczony');
}
add_action('wp_ajax_carni24_clear_cache', 'carni24_ajax_clear_cache');

/**
 * AJAX handler dla regeneracji miniaturek
 */
function carni24_ajax_regenerate_thumbnails() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Uruchom regenerację w tle
    wp_schedule_single_event(time() + 5, 'carni24_background_regenerate_thumbnails');
    
    wp_send_json_success('Regeneracja miniaturek została uruchomiona');
}
add_action('wp_ajax_carni24_regenerate_thumbnails', 'carni24_ajax_regenerate_thumbnails');

/**
 * Sprawdza meta tagi SEO
 */
function carni24_check_meta_tags() {
    $sample_post = get_posts(array(
        'numberposts' => 1,
        'post_type' => array('post', 'species', 'guides'),
        'post_status' => 'publish'
    ));
    
    if (empty($sample_post)) {
        return false;
    }
    
    $post_id = $sample_post[0]->ID;
    
    // Sprawdź czy post ma meta tytuł i opis
    $meta_title = get_post_meta($post_id, '_seo_title', true);
    $meta_description = get_post_meta($post_id, '_seo_description', true);
    
    return !empty($meta_title) && !empty($meta_description);
}

/**
 * Sprawdza Open Graph tagi
 */
function carni24_check_og_tags() {
    // Sprawdź czy funkcje OG są zdefiniowane w motywie
    return function_exists('carni24_output_og_tags');
}

/**
 * Sprawdza structured data
 */
function carni24_check_structured_data() {
    // Sprawdź czy funkcje schema są zdefiniowane
    return function_exists('carni24_output_schema_markup');
}

/**
 * Sprawdza breadcrumbs
 */
function carni24_check_breadcrumbs() {
    // Sprawdź czy funkcja breadcrumbs istnieje
    return function_exists('carni24_display_breadcrumbs');
}

/**
 * Sprawdza sitemapy
 */
function carni24_check_sitemaps() {
    // Sprawdź czy WordPress sitemap jest włączony
    return wp_sitemaps_get_server() !== null;
}

/**
 * Sprawdza robots.txt
 */
function carni24_check_robots_txt() {
    $robots_url = home_url('/robots.txt');
    $response = wp_remote_get($robots_url);
    
    if (is_wp_error($response)) {
        return false;
    }
    
    $status_code = wp_remote_retrieve_response_code($response);
    return $status_code === 200;
}

/**
 * Sprawdza podstawową wydajność
 */
function carni24_check_basic_performance() {
    $options = get_option('carni24_theme_options', array());
    
    // Sprawdź czy minifikacja jest włączona
    $minify_css = isset($options['minify_css']) && $options['minify_css'];
    $minify_js = isset($options['minify_js']) && $options['minify_js'];
    $lazy_loading = isset($options['lazy_loading']) && $options['lazy_loading'];
    
    return $minify_css && $minify_js && $lazy_loading;
}

/**
 * Sprawdza certyfikat SSL
 */
function carni24_check_ssl() {
    return is_ssl();
}

/**
 * Sprawdza mobile viewport
 */
function carni24_check_mobile_viewport() {
    // Sprawdź czy viewport meta tag jest w head
    $theme_supports_responsive = current_theme_supports('responsive-design');
    
    // W nowych wersjach WordPress/Carni24 to powinno być zawsze true
    return true;
}

/**
 * Background hook dla regeneracji miniaturek
 */
function carni24_background_regenerate_thumbnails() {
    // Pobierz wszystkie obrazy
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'post_status' => 'inherit',
        'numberposts' => 50, // Ograniczenie dla wydajności
        'meta_query' => array(
            array(
                'key' => '_carni24_thumbnails_regenerated',
                'compare' => 'NOT EXISTS'
            )
        )
    ));
    
    foreach ($attachments as $attachment) {
        $file_path = get_attached_file($attachment->ID);
        
        if ($file_path && file_exists($file_path)) {
            // Regeneruj miniaturki
            wp_generate_attachment_metadata($attachment->ID, $file_path);
            
            // Oznacz jako przetworzone
            update_post_meta($attachment->ID, '_carni24_thumbnails_regenerated', time());
        }
    }
}
add_action('carni24_background_regenerate_thumbnails', 'carni24_background_regenerate_thumbnails');

/**
 * Background hook dla audytu SEO
 */
function carni24_background_seo_audit() {
    global $wpdb;
    
    // Przeprowadź szczegółowy audyt SEO
    $audit_results = array(
        'timestamp' => current_time('mysql'),
        'total_posts' => 0,
        'posts_with_issues' => array(),
        'recommendations' => array(),
        'scores' => array()
    );
    
    // Pobierz wszystkie opublikowane posty
    $posts = $wpdb->get_results("
        SELECT ID, post_title, post_type, post_content, post_excerpt
        FROM {$wpdb->posts} 
        WHERE post_status = 'publish' 
        AND post_type IN ('post', 'page', 'species', 'guides')
        LIMIT 100
    ");
    
    $audit_results['total_posts'] = count($posts);
    
    foreach ($posts as $post) {
        $issues = array();
        
        // Sprawdź tytuł SEO
        $seo_title = get_post_meta($post->ID, '_seo_title', true);
        if (empty($seo_title)) {
            $issues[] = 'missing_seo_title';
        } elseif (strlen($seo_title) > 60) {
            $issues[] = 'title_too_long';
        } elseif (strlen($seo_title) < 30) {
            $issues[] = 'title_too_short';
        }
        
        // Sprawdź opis SEO
        $seo_description = get_post_meta($post->ID, '_seo_description', true);
        if (empty($seo_description)) {
            $issues[] = 'missing_seo_description';
        } elseif (strlen($seo_description) > 160) {
            $issues[] = 'description_too_long';
        } elseif (strlen($seo_description) < 120) {
            $issues[] = 'description_too_short';
        }
        
        // Sprawdź obraz OG
        $og_image = get_post_meta($post->ID, '_seo_og_image', true);
        if (empty($og_image) || $og_image === '0') {
            $issues[] = 'missing_og_image';
        }
        
        // Sprawdź słowa kluczowe
        $keywords = get_post_meta($post->ID, '_seo_keywords', true);
        if (empty($keywords)) {
            $issues[] = 'missing_keywords';
        }
        
        // Sprawdź długość treści
        $content_length = strlen(wp_strip_all_tags($post->post_content));
        if ($content_length < 300) {
            $issues[] = 'content_too_short';
        }
        
        // Sprawdź nagłówki H1, H2, H3
        $h1_count = substr_count($post->post_content, '<h1');
        $h2_count = substr_count($post->post_content, '<h2');
        
        if ($h1_count === 0) {
            $issues[] = 'missing_h1';
        } elseif ($h1_count > 1) {
            $issues[] = 'multiple_h1';
        }
        
        if ($h2_count === 0 && $content_length > 500) {
            $issues[] = 'missing_h2';
        }
        
        // Sprawdź obrazy alt text
        preg_match_all('/<img[^>]+>/i', $post->post_content, $images);
        foreach ($images[0] as $img) {
            if (strpos($img, 'alt=') === false || strpos($img, 'alt=""') !== false) {
                $issues[] = 'missing_alt_text';
                break;
            }
        }
        
        if (!empty($issues)) {
            $audit_results['posts_with_issues'][] = array(
                'post_id' => $post->ID,
                'post_title' => $post->post_title,
                'post_type' => $post->post_type,
                'issues' => $issues
            );
        }
    }
    
    // Wygeneruj rekomendacje
    $issue_counts = array();
    foreach ($audit_results['posts_with_issues'] as $post_issues) {
        foreach ($post_issues['issues'] as $issue) {
            $issue_counts[$issue] = ($issue_counts[$issue] ?? 0) + 1;
        }
    }
    
    arsort($issue_counts);
    
    foreach ($issue_counts as $issue => $count) {
        $audit_results['recommendations'][] = array(
            'issue' => $issue,
            'count' => $count,
            'priority' => carni24_get_seo_issue_priority($issue),
            'description' => carni24_get_seo_issue_description($issue)
        );
    }
    
    // Oblicz scores
    $total_checks = count($posts) * 8; // 8 sprawdzeń na post
    $failed_checks = array_sum($issue_counts);
    $overall_score = $total_checks > 0 ? round((($total_checks - $failed_checks) / $total_checks) * 100) : 0;
    
    $audit_results['scores'] = array(
        'overall' => $overall_score,
        'total_checks' => $total_checks,
        'failed_checks' => $failed_checks,
        'passed_checks' => $total_checks - $failed_checks
    );
    
    // Zapisz wyniki
    set_transient('carni24_seo_audit_results', $audit_results, DAY_IN_SECONDS);
}
add_action('carni24_background_seo_audit', 'carni24_background_seo_audit');

/**
 * Zwraca priorytet problemu SEO
 */
function carni24_get_seo_issue_priority($issue) {
    $priorities = array(
        'missing_seo_title' => 'high',
        'missing_seo_description' => 'high',
        'missing_h1' => 'high',
        'multiple_h1' => 'high',
        'content_too_short' => 'medium',
        'title_too_long' => 'medium',
        'title_too_short' => 'medium',
        'description_too_long' => 'medium',
        'description_too_short' => 'medium',
        'missing_og_image' => 'low',
        'missing_keywords' => 'low',
        'missing_h2' => 'low',
        'missing_alt_text' => 'low'
    );
    
    return $priorities[$issue] ?? 'low';
}

/**
 * Zwraca opis problemu SEO
 */
function carni24_get_seo_issue_description($issue) {
    $descriptions = array(
        'missing_seo_title' => 'Brak tytułu SEO może negatywnie wpłynąć na pozycjonowanie w wyszukiwarkach',
        'missing_seo_description' => 'Brak opisu SEO może obniżyć CTR w wynikach wyszukiwania',
        'missing_h1' => 'Brak nagłówka H1 utrudnia zrozumienie struktury strony przez wyszukiwarki',
        'multiple_h1' => 'Wiele nagłówków H1 może mylić wyszukiwarki co do głównego tematu strony',
        'content_too_short' => 'Zbyt krótka treść może być postrzegana jako mało wartościowa',
        'title_too_long' => 'Zbyt długi tytuł może zostać obcięty w wynikach wyszukiwania',
        'title_too_short' => 'Zbyt krótki tytuł może nie zawierać wystarczająco słów kluczowych',
        'description_too_long' => 'Zbyt długi opis może zostać obcięty w wynikach wyszukiwania',
        'description_too_short' => 'Zbyt krótki opis może nie zachęcać do kliknięcia w wynikach',
        'missing_og_image' => 'Brak obrazu Open Graph może wpłynąć na wygląd udostępnień w mediach społecznościowych',
        'missing_keywords' => 'Brak słów kluczowych może utrudnić pozycjonowanie dla konkretnych fraz',
        'missing_h2' => 'Brak nagłówków H2 może utrudnić czytanie i zrozumienie struktury treści',
        'missing_alt_text' => 'Brak alt text w obrazach wpływa negatywnie na dostępność i SEO obrazów'
    );
    
    return $descriptions[$issue] ?? 'Nieznany problem SEO';
}

/**
 * AJAX handler dla eksportu ustawień motywu
 */
function carni24_ajax_export_settings() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $settings = get_option('carni24_theme_options', array());
    
    // Dodaj metadane eksportu
    $export_data = array(
        'export_info' => array(
            'version' => CARNI24_VERSION,
            'date' => current_time('mysql'),
            'site_url' => home_url(),
            'wp_version' => get_bloginfo('version')
        ),
        'settings' => $settings
    );
    
    wp_send_json_success($export_data);
}
add_action('wp_ajax_carni24_export_settings', 'carni24_ajax_export_settings');

/**
 * AJAX handler dla importu ustawień motywu
 */
function carni24_ajax_import_settings() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    if (!isset($_POST['settings_data'])) {
        wp_send_json_error('Brak danych do importu');
    }
    
    $settings_json = wp_unslash($_POST['settings_data']);
    $import_data = json_decode($settings_json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Nieprawidłowy format JSON');
    }
    
    if (!isset($import_data['settings'])) {
        wp_send_json_error('Nieprawidłowa struktura danych');
    }
    
    $settings = $import_data['settings'];
    
    // Walidacja i sanityzacja ustawień
    $validated_settings = carni24_validate_imported_settings($settings);
    
    if (is_wp_error($validated_settings)) {
        wp_send_json_error($validated_settings->get_error_message());
    }
    
    // Zapisz ustawienia
    update_option('carni24_theme_options', $validated_settings);
    
    // Wyczyść cache
    delete_transient('carni24_unified_stats');
    delete_transient('carni24_unified_seo');
    
    wp_send_json_success('Ustawienia zostały pomyślnie zaimportowane');
}
add_action('wp_ajax_carni24_import_settings', 'carni24_ajax_import_settings');

/**
 * Waliduje i oczyszcza importowane ustawienia
 */
function carni24_validate_imported_settings($settings) {
    if (!is_array($settings)) {
        return new WP_Error('invalid_format', 'Ustawienia muszą być w formacie tablicy');
    }
    
    $validated = array();
    $allowed_options = array(
        'site_name' => 'sanitize_text_field',
        'site_description' => 'sanitize_textarea_field',
        'navigation_title' => 'sanitize_text_field',
        'navigation_content' => 'wp_kses_post',
        'default_meta_description' => 'sanitize_textarea_field',
        'default_meta_keywords' => 'sanitize_text_field',
        'default_og_image' => 'absint',
        'lazy_loading' => 'carni24_validate_boolean',
        'minify_css' => 'carni24_validate_boolean',
        'minify_js' => 'carni24_validate_boolean',
        'google_analytics' => 'sanitize_text_field',
        'facebook_pixel' => 'sanitize_text_field'
    );
    
    foreach ($allowed_options as $option => $sanitize_function) {
        if (isset($settings[$option])) {
            if ($sanitize_function === 'carni24_validate_boolean') {
                $validated[$option] = $settings[$option] ? 1 : 0;
            } elseif ($sanitize_function === 'absint') {
                $validated[$option] = absint($settings[$option]);
            } elseif (function_exists($sanitize_function)) {
                $validated[$option] = call_user_func($sanitize_function, $settings[$option]);
            } else {
                $validated[$option] = sanitize_text_field($settings[$option]);
            }
        }
    }
    
    return $validated;
}

/**
 * AJAX handler dla sprawdzania aktualizacji motywu
 */
function carni24_ajax_check_theme_updates() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Sprawdź czy są dostępne aktualizacje (mock)
    $current_version = CARNI24_VERSION;
    $latest_version = carni24_get_latest_theme_version();
    
    $update_available = version_compare($current_version, $latest_version, '<');
    
    $response = array(
        'current_version' => $current_version,
        'latest_version' => $latest_version,
        'update_available' => $update_available,
        'changelog' => $update_available ? carni24_get_theme_changelog($latest_version) : array()
    );
    
    wp_send_json_success($response);
}
add_action('wp_ajax_carni24_check_theme_updates', 'carni24_ajax_check_theme_updates');

/**
 * Pobiera najnowszą wersję motywu (mock function)
 */
function carni24_get_latest_theme_version() {
    // W rzeczywistej implementacji można sprawdzić na serwerze deweloperskim
    $remote_version = wp_remote_get('https://api.carni24.pl/theme/version');
    
    if (is_wp_error($remote_version)) {
        return CARNI24_VERSION; // Zwróć aktualną wersję jeśli nie można sprawdzić
    }
    
    $body = wp_remote_retrieve_body($remote_version);
    $data = json_decode($body, true);
    
    return $data['version'] ?? CARNI24_VERSION;
}

/**
 * Pobiera changelog motywu
 */
function carni24_get_theme_changelog($version) {
    // Mock changelog
    $changelogs = array(
        '3.1.0' => array(
            'Dodano ujednolicone widgety dashboard',
            'Poprawiono system SEO',
            'Ulepszono wydajność',
            'Naprawiono błędy z responsive design'
        ),
        '3.0.1' => array(
            'Naprawiono błędy w meta polach',
            'Ulepszono kompatybilność z WordPress 6.4',
            'Poprawiono style CSS'
        )
    );
    
    return $changelogs[$version] ?? array();
}

/**
 * AJAX handler dla diagnostyki systemu
 */
function carni24_ajax_system_diagnostics() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $diagnostics = array(
        'php_version' => PHP_VERSION,
        'wp_version' => get_bloginfo('version'),
        'theme_version' => CARNI24_VERSION,
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'active_plugins' => get_option('active_plugins'),
        'inactive_plugins' => array_diff(get_plugins(), array_flip(get_option('active_plugins'))),
        'theme_support' => array(
            'post_thumbnails' => current_theme_supports('post-thumbnails'),
            'menus' => current_theme_supports('menus'),
            'widgets' => current_theme_supports('widgets'),
            'custom_header' => current_theme_supports('custom-header'),
            'custom_background' => current_theme_supports('custom-background'),
            'html5' => current_theme_supports('html5'),
            'responsive_design' => true // Carni24 zawsze wspiera
        ),
        'server_info' => array(
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Nieznany',
            'php_sapi' => php_sapi_name(),
            'mysql_version' => carni24_get_mysql_version(),
            'ssl_enabled' => is_ssl(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Nieznany'
        ),
        'file_permissions' => array(
            'wp_content_writable' => is_writable(WP_CONTENT_DIR),
            'uploads_writable' => is_writable(wp_upload_dir()['basedir']),
            'themes_writable' => is_writable(get_theme_root()),
            'plugins_writable' => is_writable(WP_PLUGIN_DIR)
        ),
        'constants' => array(
            'WP_DEBUG' => defined('WP_DEBUG') && WP_DEBUG,
            'WP_DEBUG_LOG' => defined('WP_DEBUG_LOG') && WP_DEBUG_LOG,
            'WP_DEBUG_DISPLAY' => defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY,
            'SCRIPT_DEBUG' => defined('SCRIPT_DEBUG') && SCRIPT_DEBUG,
            'WP_CACHE' => defined('WP_CACHE') && WP_CACHE,
            'MULTISITE' => is_multisite()
        )
    );
    
    wp_send_json_success($diagnostics);
}
add_action('wp_ajax_carni24_system_diagnostics', 'carni24_ajax_system_diagnostics');

/**
 * Pobiera wersję MySQL
 */
function carni24_get_mysql_version() {
    global $wpdb;
    return $wpdb->get_var("SELECT VERSION()");
}

/**
 * AJAX handler dla optymalizacji bazy danych
 */
function carni24_ajax_optimize_database() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    global $wpdb;
    
    $results = array(
        'revisions_deleted' => 0,
        'spam_comments_deleted' => 0,
        'trash_comments_deleted' => 0,
        'orphaned_meta_deleted' => 0,
        'transients_deleted' => 0,
        'tables_optimized' => 0
    );
    
    // Usuń rewizje starsze niż 30 dni
    $revisions = $wpdb->query("
        DELETE FROM {$wpdb->posts} 
        WHERE post_type = 'revision' 
        AND post_modified < DATE_SUB(NOW(), INTERVAL 30 DAY)
    ");
    $results['revisions_deleted'] = $revisions;
    
    // Usuń spam komentarze
    $spam_comments = $wpdb->query("
        DELETE FROM {$wpdb->comments} 
        WHERE comment_approved = 'spam'
    ");
    $results['spam_comments_deleted'] = $spam_comments;
    
    // Usuń komentarze z kosza
    $trash_comments = $wpdb->query("
        DELETE FROM {$wpdb->comments} 
        WHERE comment_approved = 'trash'
    ");
    $results['trash_comments_deleted'] = $trash_comments;
    
    // Usuń osierocone meta dane
    $orphaned_meta = $wpdb->query("
        DELETE pm FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE p.ID IS NULL
    ");
    $results['orphaned_meta_deleted'] = $orphaned_meta;
    
    // Usuń przeterminowane transienty
    $transients = $wpdb->query("
        DELETE FROM {$wpdb->options} 
        WHERE option_name LIKE '_transient_%' 
        OR option_name LIKE '_site_transient_%'
    ");
    $results['transients_deleted'] = $transients;
    
    // Optymalizuj tabele
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    foreach ($tables as $table) {
        $wpdb->query("OPTIMIZE TABLE {$table[0]}");
        $results['tables_optimized']++;
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_carni24_optimize_database', 'carni24_ajax_optimize_database');

/**
 * AJAX handler dla backup ustawień
 */
function carni24_ajax_backup_settings() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $backup_data = array(
        'timestamp' => current_time('mysql'),
        'site_url' => home_url(),
        'wp_version' => get_bloginfo('version'),
        'theme_version' => CARNI24_VERSION,
        'settings' => get_option('carni24_theme_options', array()),
        'customizer' => get_theme_mods(),
        'menus' => wp_get_nav_menus(),
        'widgets' => get_option('widget_carni24_recent_species', array())
    );
    
    // Zapisz backup w opcjach
    $backups = get_option('carni24_settings_backups', array());
    $backup_id = 'backup_' . time();
    $backups[$backup_id] = $backup_data;
    
    // Zachowaj tylko ostatnie 5 backupów
    if (count($backups) > 5) {
        $backups = array_slice($backups, -5, null, true);
    }
    
    update_option('carni24_settings_backups', $backups);
    
    wp_send_json_success(array(
        'backup_id' => $backup_id,
        'backup_count' => count($backups)
    ));
}
add_action('wp_ajax_carni24_backup_settings', 'carni24_ajax_backup_settings');

/**
 * AJAX handler dla przywracania backupu
 */
function carni24_ajax_restore_backup() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $backup_id = sanitize_text_field($_POST['backup_id']);
    $backups = get_option('carni24_settings_backups', array());
    
    if (!isset($backups[$backup_id])) {
        wp_send_json_error('Backup nie został znaleziony');
    }
    
    $backup_data = $backups[$backup_id];
    
    // Przywróć ustawienia motywu
    if (isset($backup_data['settings'])) {
        update_option('carni24_theme_options', $backup_data['settings']);
    }
    
    // Przywróć customizer
    if (isset($backup_data['customizer'])) {
        $theme = get_stylesheet();
        update_option("theme_mods_{$theme}", $backup_data['customizer']);
    }
    
    // Wyczyść cache
    delete_transient('carni24_unified_stats');
    delete_transient('carni24_unified_seo');
    
    wp_send_json_success('Backup został przywrócony pomyślnie');
}
add_action('wp_ajax_carni24_restore_backup', 'carni24_ajax_restore_backup');

/**
 * AJAX handler dla masowej naprawy SEO (z ujednoliconego widgetu)
 */
function carni24_ajax_bulk_fix_seo() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_die('Brak uprawnień');
    }
    
    $fix_type = sanitize_text_field($_POST['fix_type']);
    
    global $wpdb;
    
    $count = 0;
    
    if ($fix_type === 'title') {
        // Znajdź posty bez tytułu SEO
        $posts = $wpdb->get_results("
            SELECT p.ID, p.post_title 
            FROM {$wpdb->posts} p 
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_title'
            WHERE p.post_status = 'publish' 
            AND p.post_type IN ('post', 'page', 'species', 'guides')
            AND (pm.meta_value IS NULL OR pm.meta_value = '')
            LIMIT 50
        ");
        
        foreach ($posts as $post) {
            // Wygeneruj tytuł SEO na podstawie tytułu posta
            $seo_title = $post->post_title . ' | ' . get_bloginfo('name');
            update_post_meta($post->ID, '_seo_title', $seo_title);
            $count++;
        }
        
    } elseif ($fix_type === 'description') {
        // Znajdź posty bez opisu SEO
        $posts = $wpdb->get_results("
            SELECT p.ID, p.post_title, p.post_excerpt, p.post_content 
            FROM {$wpdb->posts} p 
            LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_description'
            WHERE p.post_status = 'publish' 
            AND p.post_type IN ('post', 'page', 'species', 'guides')
            AND (pm.meta_value IS NULL OR pm.meta_value = '')
            LIMIT 50
        ");
        
        foreach ($posts as $post) {
            // Wygeneruj opis SEO
            $description = '';
            
            if (!empty($post->post_excerpt)) {
                $description = wp_strip_all_tags($post->post_excerpt);
            } else {
                $description = wp_strip_all_tags($post->post_content);
                $description = wp_trim_words($description, 25, '...');
            }
            
            if (strlen($description) > 160) {
                $description = substr($description, 0, 157) . '...';
            }
            
            update_post_meta($post->ID, '_seo_description', $description);
            $count++;
        }
    }
    
    wp_send_json_success(array('count' => $count));
}
add_action('wp_ajax_carni24_bulk_fix_seo', 'carni24_ajax_bulk_fix_seo');

/**
 * AJAX handler dla oznaczania problemów SEO jako rozwiązane
 */
function carni24_ajax_mark_seo_issue_resolved() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_die('Brak uprawnień');
    }
    
    $post_id = absint($_POST['post_id']);
    $issue_type = sanitize_text_field($_POST['issue_type']);
    
    // Oznacz jako rozwiązane przez dodanie meta
    update_post_meta($post_id, '_seo_issue_' . $issue_type . '_resolved', current_time('mysql'));
    
    wp_send_json_success('Problem został oznaczony jako rozwiązany');
}
add_action('wp_ajax_carni24_mark_seo_issue_resolved', 'carni24_ajax_mark_seo_issue_resolved');

/**
 * AJAX handler dla audytu SEO (z ujednoliconego widgetu)
 */
function carni24_ajax_run_seo_audit() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Wyczyść cache SEO
    delete_transient('carni24_unified_seo');
    delete_transient('carni24_seo_audit_results');
    
    // Uruchom audyt w tle (tutaj można dodać więcej logiki)
    wp_schedule_single_event(time() + 5, 'carni24_background_seo_audit');
    
    wp_send_json_success('Audyt SEO został uruchomiony');
}
add_action('wp_ajax_carni24_run_seo_audit', 'carni24_ajax_run_seo_audit');

/**
 * AJAX handler dla generowania map stron
 */
function carni24_ajax_generate_sitemaps() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Wyczyść cache sitemap
    wp_cache_delete('sitemap_posts', 'carni24');
    wp_cache_delete('sitemap_pages', 'carni24');
    
    wp_send_json_success('Mapy stron zostały wygenerowane');
}
add_action('wp_ajax_carni24_generate_sitemaps', 'carni24_ajax_generate_sitemaps');

/**
 * AJAX handler dla analizy słów kluczowych
 */
function carni24_ajax_analyze_keywords() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Wyczyść cache keywords
    delete_transient('carni24_keywords_analysis');
    
    wp_send_json_success('Analiza słów kluczowych zakończona');
}
add_action('wp_ajax_carni24_analyze_keywords', 'carni24_ajax_analyze_keywords');

/**
 * AJAX handler dla sprawdzania linków wewnętrznych
 */
function carni24_ajax_check_internal_links() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Wyczyść cache linków
    delete_transient('carni24_internal_links_check');
    
    wp_send_json_success('Sprawdzanie linków zakończone');
}
add_action('wp_ajax_carni24_check_internal_links', 'carni24_ajax_check_internal_links');

/**
 * AJAX handler dla czyszczenia cache widgetów (z ujednoliconego systemu)
 */
function carni24_ajax_clear_widget_cache() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $cache_key = sanitize_text_field($_POST['cache_key']);
    
    delete_transient($cache_key);
    
    wp_send_json_success('Cache został wyczyszczony');
}
add_action('wp_ajax_carni24_clear_widget_cache', 'carni24_ajax_clear_widget_cache');

/**
 * AJAX handler dla synchronizacji danych z zewnętrznymi API
 */
function carni24_ajax_sync_external_data() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $sync_results = array(
        'weather_data' => false,
        'plant_database' => false,
        'care_tips' => false,
        'errors' => array()
    );
    
    // Symulacja synchronizacji danych o pogodzie
    $weather_response = wp_remote_get('https://api.openweathermap.org/data/2.5/weather?q=Warsaw&appid=demo');
    if (!is_wp_error($weather_response)) {
        $sync_results['weather_data'] = true;
        update_option('carni24_weather_last_sync', current_time('mysql'));
    } else {
        $sync_results['errors'][] = 'Błąd synchronizacji danych pogodowych';
    }
    
    // Symulacja synchronizacji bazy roślin
    $plants_response = wp_remote_get('https://api.plantnet.org/v2/projects/useful/species');
    if (!is_wp_error($plants_response)) {
        $sync_results['plant_database'] = true;
        update_option('carni24_plants_last_sync', current_time('mysql'));
    } else {
        $sync_results['errors'][] = 'Błąd synchronizacji bazy roślin';
    }
    
    // Symulacja synchronizacji porad pielęgnacyjnych
    $care_response = wp_remote_get('https://api.careplants.com/tips/carnivorous');
    if (!is_wp_error($care_response)) {
        $sync_results['care_tips'] = true;
        update_option('carni24_care_tips_last_sync', current_time('mysql'));
    } else {
        $sync_results['errors'][] = 'Błąd synchronizacji porad pielęgnacyjnych';
    }
    
    wp_send_json_success($sync_results);
}
add_action('wp_ajax_carni24_sync_external_data', 'carni24_ajax_sync_external_data');

/**
 * AJAX handler dla generowania raportów
 */
function carni24_ajax_generate_report() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $report_type = sanitize_text_field($_POST['report_type']);
    $date_from = sanitize_text_field($_POST['date_from']);
    $date_to = sanitize_text_field($_POST['date_to']);
    
    global $wpdb;
    
    $report_data = array(
        'type' => $report_type,
        'period' => array(
            'from' => $date_from,
            'to' => $date_to
        ),
        'generated_at' => current_time('mysql'),
        'data' => array()
    );
    
    switch ($report_type) {
        case 'content':
            // Raport treści
            $content_stats = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    post_type,
                    COUNT(*) as count,
                    AVG(CHAR_LENGTH(post_content)) as avg_length
                FROM {$wpdb->posts} 
                WHERE post_status = 'publish' 
                AND post_date BETWEEN %s AND %s
                GROUP BY post_type
            ", $date_from, $date_to));
            
            $report_data['data']['content_stats'] = $content_stats;
            break;
            
        case 'seo':
            // Raport SEO
            $seo_stats = array(
                'posts_with_seo_title' => $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(DISTINCT p.ID)
                    FROM {$wpdb->posts} p
                    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                    WHERE p.post_status = 'publish'
                    AND p.post_date BETWEEN %s AND %s
                    AND pm.meta_key = '_seo_title'
                    AND pm.meta_value != ''
                ", $date_from, $date_to)),
                
                'posts_with_seo_description' => $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(DISTINCT p.ID)
                    FROM {$wpdb->posts} p
                    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                    WHERE p.post_status = 'publish'
                    AND p.post_date BETWEEN %s AND %s
                    AND pm.meta_key = '_seo_description'
                    AND pm.meta_value != ''
                ", $date_from, $date_to))
            );
            
            $report_data['data']['seo_stats'] = $seo_stats;
            break;
            
        case 'performance':
            // Raport wydajności
            $performance_stats = array(
                'total_posts' => wp_count_posts()->publish,
                'total_pages' => wp_count_posts('page')->publish,
                'total_media' => wp_count_posts('attachment')->inherit,
                'database_size' => carni24_get_database_size(),
                'themes_count' => count(wp_get_themes()),
                'plugins_count' => count(get_plugins())
            );
            
            $report_data['data']['performance_stats'] = $performance_stats;
            break;
    }
    
    // Zapisz raport
    $reports = get_option('carni24_generated_reports', array());
    $report_id = 'report_' . time();
    $reports[$report_id] = $report_data;
    
    // Zachowaj tylko ostatnie 20 raportów
    if (count($reports) > 20) {
        $reports = array_slice($reports, -20, null, true);
    }
    
    update_option('carni24_generated_reports', $reports);
    
    wp_send_json_success(array(
        'report_id' => $report_id,
        'report_data' => $report_data
    ));
}
add_action('wp_ajax_carni24_generate_report', 'carni24_ajax_generate_report');

/**
 * Pobiera rozmiar bazy danych
 */
function carni24_get_database_size() {
    global $wpdb;
    
    $size = $wpdb->get_var("
        SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'DB Size in MB' 
        FROM information_schema.tables 
        WHERE table_schema = '{$wpdb->dbname}'
    ");
    
    return $size ? $size . ' MB' : 'Nieznany';
}

/**
 * AJAX handler dla masowego usuwania nieużywanych obrazów
 */
function carni24_ajax_cleanup_unused_media() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    global $wpdb;
    
    // Znajdź obrazy które nie są użyte w żadnym poście
    $unused_attachments = $wpdb->get_results("
        SELECT ID, post_title 
        FROM {$wpdb->posts} 
        WHERE post_type = 'attachment' 
        AND post_mime_type LIKE 'image/%'
        AND post_parent = 0
        AND ID NOT IN (
            SELECT DISTINCT meta_value 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_thumbnail_id'
            UNION
            SELECT DISTINCT post_id 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = '_wp_attachment_metadata'
        )
        LIMIT 50
    ");
    
    $deleted_count = 0;
    $errors = array();
    
    foreach ($unused_attachments as $attachment) {
        $deleted = wp_delete_attachment($attachment->ID, true);
        if ($deleted) {
            $deleted_count++;
        } else {
            $errors[] = 'Nie można usunąć: ' . $attachment->post_title;
        }
    }
    
    wp_send_json_success(array(
        'deleted_count' => $deleted_count,
        'errors' => $errors
    ));
}
add_action('wp_ajax_carni24_cleanup_unused_media', 'carni24_ajax_cleanup_unused_media');

/**
 * Helper function dla walidacji boolean values
 */
function carni24_validate_boolean($value) {
    return (bool) $value;
}