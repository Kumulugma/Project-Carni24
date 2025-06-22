<?php
/**
 * SEO Monitor Meta Box - Ulepszona wersja z wydzielonymi stylami
 * Dashboard widget do monitorowania SEO
 * 
 * @package Carni24
 * @subpackage Admin
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dodaje dashboard widget dla monitorowania SEO
 */
function carni24_add_seo_monitor_dashboard_widget() {
    wp_add_dashboard_widget(
        'carni24_seo_monitor',
        '🔍 Monitor SEO - Wpisy wymagające uwagi',
        'carni24_seo_monitor_dashboard_widget_callback',
        'carni24_seo_monitor_dashboard_widget_control'
    );
}
add_action('wp_dashboard_setup', 'carni24_add_seo_monitor_dashboard_widget');

/**
 * Callback dla dashboard widget - NAPRAWIONA WERSJA
 */
function carni24_seo_monitor_dashboard_widget_callback() {
    // Pobierz ustawienia widget
    $options = get_option('carni24_seo_monitor_settings', array(
        'post_types' => array('post', 'page', 'species', 'guides'),
        'check_title' => true,
        'check_description' => true,
        'check_keywords' => true,
        'posts_per_page' => 10
    ));
    
    // Pobierz wpisy wymagające uwagi SEO
    $posts_needing_seo = carni24_get_posts_needing_seo($options);
    
    // WAŻNE: Nie echo bezpośrednio, tylko zwróć zawartość
    ob_start();
    ?>
    <div class="carni24-seo-monitor" id="carni24-seo-monitor">
        <!-- Nagłówek z statystykami -->
        <div class="seo-monitor-header">
            <div class="seo-stats-grid">
                <?php 
                $stats = carni24_get_seo_stats($options['post_types']);
                foreach ($stats as $stat): 
                ?>
                <div class="seo-stat-item <?= esc_attr($stat['status'] ?? '') ?>">
                    <div class="stat-number"><?= esc_html($stat['count']) ?></div>
                    <div class="stat-label"><?= esc_html($stat['label']) ?></div>
                    <div class="stat-type"><?= esc_html($stat['type']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Nawigacja zakładek -->
        <div class="seo-monitor-tabs">
            <button class="seo-tab-btn active" data-tab="overview">📊 Przegląd</button>
            <button class="seo-tab-btn" data-tab="missing" data-filter="missing">🔍 Brakujące</button>
            <button class="seo-tab-btn" data-tab="warnings" data-filter="warnings">⚠️ Ostrzeżenia</button>
            <button class="seo-tab-btn" data-tab="optimized" data-filter="optimized">✅ Zoptymalizowane</button>
        </div>

        <!-- Filtry -->
        <div class="seo-monitor-filters">
            <div class="filter-row">
                <select id="seo-post-type-filter" class="seo-filter">
                    <option value="">Wszystkie typy postów</option>
                    <?php foreach ($options['post_types'] as $post_type): 
                        $post_type_obj = get_post_type_object($post_type);
                        if ($post_type_obj):
                    ?>
                    <option value="<?= esc_attr($post_type) ?>"><?= esc_html($post_type_obj->labels->name) ?></option>
                    <?php 
                        endif;
                    endforeach; ?>
                </select>

                <select id="seo-issue-filter" class="seo-filter">
                    <option value="">Wszystkie problemy</option>
                    <option value="missing_title">Brak tytułu SEO</option>
                    <option value="missing_description">Brak opisu SEO</option>
                    <option value="missing_keywords">Brak słów kluczowych</option>
                    <option value="short_title">Krótki tytuł</option>
                    <option value="long_title">Długi tytuł</option>
                    <option value="short_description">Krótki opis</option>
                    <option value="long_description">Długi opis</option>
                </select>

                <button type="button" id="refresh-seo-monitor" class="button button-secondary">
                    🔄 Odśwież
                </button>
            </div>
        </div>

        <!-- Zawartość zakładek -->
        <div class="seo-monitor-content">
            <!-- Zakładka: Przegląd -->
            <div id="tab-overview" class="seo-tab-content active">
                <?php if (empty($posts_needing_seo)): ?>
                    <div class="seo-no-issues">
                        <div class="no-issues-icon">🎉</div>
                        <h3>Świetna robota!</h3>
                        <p>Wszystkie wpisy mają kompletne informacje SEO.</p>
                    </div>
                <?php else: ?>
                    <div class="seo-posts-list">
                        <?php foreach (array_slice($posts_needing_seo, 0, $options['posts_per_page']) as $post_data): ?>
                            <?= carni24_render_seo_post_item($post_data) ?>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($posts_needing_seo) > $options['posts_per_page']): ?>
                        <div class="seo-load-more">
                            <button type="button" class="button" id="load-more-seo-posts" 
                                    data-offset="<?= $options['posts_per_page'] ?>">
                                Pokaż więcej (<?= count($posts_needing_seo) - $options['posts_per_page'] ?> pozostałych)
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Pozostałe zakładki będą ładowane przez AJAX -->
            <div id="tab-missing" class="seo-tab-content"></div>
            <div id="tab-warnings" class="seo-tab-content"></div>
            <div id="tab-optimized" class="seo-tab-content"></div>
        </div>

        <!-- Loading indicator -->
        <div class="seo-loading" style="display: none;">
            <span>Ładowanie danych SEO...</span>
        </div>
    </div>

    <!-- JavaScript inicjalizacji -->
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Sprawdź czy funkcja istnieje przed wywołaniem
            if (typeof carni24InitSeoMonitor === 'function') {
                carni24InitSeoMonitor();
            } else {
                console.warn('carni24InitSeoMonitor function not found');
            }
        });
    </script>
    
    <!-- Inline CSS jako fallback -->
    <style>
    .carni24-seo-monitor {
        margin: -12px;
        background: #fff;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .seo-monitor-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
    }
    .seo-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }
    .seo-stat-item {
        background: #fff;
        padding: 12px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        text-align: center;
        border-left: 4px solid #e9ecef;
    }
    .seo-stat-item.good { border-left-color: #46b450; }
    .seo-stat-item.warning { border-left-color: #ffb900; }
    .seo-stat-item.error { border-left-color: #dc3232; }
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #23282d;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 2px;
    }
    .stat-type {
        font-size: 11px;
        color: #999;
        text-transform: uppercase;
        font-weight: 500;
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
        font-weight: 500;
        color: #555;
        border-bottom: 3px solid transparent;
    }
    .seo-tab-btn:hover {
        background: rgba(255,255,255,0.5);
        color: #23282d;
    }
    .seo-tab-btn.active {
        background: #fff;
        color: #0073aa;
        border-bottom-color: #0073aa;
    }
    .seo-monitor-filters {
        padding: 15px 20px;
        background: #f9f9f9;
        border-bottom: 1px solid #e1e5e9;
    }
    .filter-row {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .seo-filter {
        min-width: 180px;
        padding: 6px 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
    }
    .seo-monitor-content {
        padding: 20px;
        min-height: 200px;
    }
    .seo-tab-content {
        display: none;
    }
    .seo-tab-content.active {
        display: block;
    }
    .seo-no-issues {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }
    .no-issues-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    .seo-no-issues h3 {
        margin: 0 0 10px;
        color: #23282d;
        font-size: 18px;
    }
    .seo-posts-list {
        display: grid;
        gap: 15px;
    }
    .seo-post-item {
        background: #fff;
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        padding: 15px;
        position: relative;
    }
    .seo-post-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }
    .seo-post-item.good::before { background: #46b450; }
    .seo-post-item.warning::before { background: #ffb900; }
    .seo-post-item.error::before { background: #dc3232; }
    .seo-post-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .seo-post-info {
        flex: 1;
    }
    .seo-post-title {
        margin: 0 0 6px;
        font-size: 16px;
        line-height: 1.3;
    }
    .seo-post-title a {
        color: #0073aa;
        text-decoration: none;
        font-weight: 600;
    }
    .seo-post-meta {
        display: flex;
        gap: 10px;
        align-items: center;
        font-size: 12px;
        color: #666;
    }
    .post-type {
        background: #f0f0f1;
        padding: 2px 6px;
        border-radius: 3px;
        font-weight: 500;
    }
    .seo-post-score {
        flex-shrink: 0;
    }
    .score-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #fff;
        font-size: 14px;
        position: relative;
    }
    .score-circle::before {
        content: '';
        position: absolute;
        inset: 3px;
        background: #fff;
        border-radius: 50%;
    }
    .score-number {
        position: relative;
        z-index: 2;
        font-size: 12px;
        font-weight: bold;
    }
    .score-good {
        background: #46b450;
    }
    .score-good .score-number {
        color: #2e7d2e;
    }
    .score-warning {
        background: #ffb900;
    }
    .score-warning .score-number {
        color: #856404;
    }
    .score-error {
        background: #dc3232;
    }
    .score-error .score-number {
        color: #721c24;
    }
    .seo-post-issues {
        margin-bottom: 15px;
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
        border-left: 3px solid #ffb900;
    }
    .seo-issue-error {
        background: #f8d7da;
        color: #721c24;
        border-left-color: #dc3232;
    }
    .issue-icon {
        margin-right: 8px;
        font-size: 14px;
    }
    .issue-message {
        flex: 1;
    }
    .seo-post-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    .button-small {
        padding: 4px 8px;
        font-size: 12px;
        height: auto;
        line-height: 1.4;
    }
    .seo-ignore-post {
        color: #666;
        font-size: 12px;
        padding: 4px 0;
        background: none;
        border: none;
        cursor: pointer;
    }
    .seo-ignore-post:hover {
        color: #d63638;
    }
    </style>
    <?php
    
    // Zwróć zawartość zamiast echo
    echo ob_get_clean();
}

/**
 * Renderuje pojedynczy wpis w liście SEO
 */
function carni24_render_seo_post_item($post_data) {
    $post = $post_data['post'];
    $issues = $post_data['issues'];
    $seo_score = $post_data['seo_score'];
    
    $status_class = '';
    if ($seo_score >= 80) $status_class = 'good';
    elseif ($seo_score >= 50) $status_class = 'warning';
    else $status_class = 'error';
    
    $post_type_obj = get_post_type_object($post->post_type);
    
    ob_start();
    ?>
    <div class="seo-post-item <?= esc_attr($status_class) ?>" data-post-id="<?= $post->ID ?>">
        <div class="seo-post-header">
            <div class="seo-post-info">
                <h4 class="seo-post-title">
                    <a href="<?= get_edit_post_link($post->ID) ?>" target="_blank">
                        <?= esc_html($post->post_title) ?>
                    </a>
                </h4>
                <div class="seo-post-meta">
                    <span class="post-type"><?= esc_html($post_type_obj->labels->singular_name) ?></span>
                    <span class="post-date"><?= get_the_date('d.m.Y', $post->ID) ?></span>
                    <span class="post-status status-<?= esc_attr($post->post_status) ?>"><?= esc_html($post->post_status) ?></span>
                </div>
            </div>
            
            <div class="seo-post-score">
                <div class="score-circle score-<?= esc_attr($status_class) ?>">
                    <span class="score-number"><?= esc_html($seo_score) ?></span>
                </div>
            </div>
        </div>
        
        <div class="seo-post-issues">
            <?php foreach ($issues as $issue): ?>
                <div class="seo-issue seo-issue-<?= esc_attr($issue['severity']) ?>">
                    <span class="issue-icon"><?= $issue['severity'] === 'error' ? '❌' : '⚠️' ?></span>
                    <span class="issue-message"><?= esc_html($issue['message']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="seo-post-actions">
            <a href="<?= get_edit_post_link($post->ID) ?>" class="button button-primary button-small">
                ✏️ Edytuj
            </a>
            <a href="<?= get_permalink($post->ID) ?>" class="button button-secondary button-small" target="_blank">
                👁️ Zobacz
            </a>
            <button type="button" class="button button-link seo-ignore-post" 
                    data-post-id="<?= $post->ID ?>" 
                    data-nonce="<?= wp_create_nonce('carni24_seo_ignore') ?>">
                🙈 Ignoruj
            </button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Pobiera wpisy wymagające uwagi SEO
 */
function carni24_get_posts_needing_seo($options) {
    $args = array(
        'post_type' => $options['post_types'],
        'post_status' => array('publish', 'draft'),
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_seo_ignore',
                'compare' => 'NOT EXISTS'
            )
        )
    );
    
    $posts = get_posts($args);
    $posts_with_issues = array();
    
    foreach ($posts as $post) {
        $issues = carni24_check_seo_completeness($post->ID);
        if (!empty($issues)) {
            $seo_score = carni24_calculate_seo_score($post->ID);
            $posts_with_issues[] = array(
                'post' => $post,
                'issues' => $issues,
                'seo_score' => $seo_score
            );
        }
    }
    
    // Sortuj według wyniku SEO (najgorsze pierwszne)
    usort($posts_with_issues, function($a, $b) {
        return $a['seo_score'] - $b['seo_score'];
    });
    
    return $posts_with_issues;
}

/**
 * Oblicza wynik SEO dla wpisu
 */
function carni24_calculate_seo_score($post_id) {
    $score = 0;
    $max_score = 100;
    
    $meta_title = get_post_meta($post_id, '_seo_title', true);
    $meta_description = get_post_meta($post_id, '_seo_description', true);
    $meta_keywords = get_post_meta($post_id, '_seo_keywords', true);
    
    // Tytuł SEO (30 punktów)
    if (!empty($meta_title)) {
        $title_length = strlen($meta_title);
        if ($title_length >= 30 && $title_length <= 60) {
            $score += 30;
        } elseif ($title_length > 0) {
            $score += 15;
        }
    }
    
    // Opis SEO (40 punktów)
    if (!empty($meta_description)) {
        $desc_length = strlen($meta_description);
        if ($desc_length >= 120 && $desc_length <= 160) {
            $score += 40;
        } elseif ($desc_length > 0) {
            $score += 20;
        }
    }
    
    // Słowa kluczowe (20 punktów)
    if (!empty($meta_keywords)) {
        $keywords_count = count(array_filter(explode(',', $meta_keywords)));
        if ($keywords_count >= 3 && $keywords_count <= 10) {
            $score += 20;
        } elseif ($keywords_count > 0) {
            $score += 10;
        }
    }
    
    // Focus keyword w tytule (10 punktów)
    if (!empty($meta_keywords) && !empty($meta_title)) {
        $focus_keyword = trim(explode(',', $meta_keywords)[0]);
        if (stripos($meta_title, $focus_keyword) !== false) {
            $score += 10;
        }
    }
    
    return $score;
}

/**
 * Pobiera statystyki SEO
 */
function carni24_get_seo_stats($post_types) {
    $stats = array();
    
    foreach ($post_types as $post_type) {
        $total_posts = wp_count_posts($post_type);
        $total_published = $total_posts->publish;
        
        // Sprawdź ile wpisów potrzebuje uwagi
        $posts_needing_attention = carni24_count_posts_needing_seo($post_type);
        
        $post_type_obj = get_post_type_object($post_type);
        
        $status = 'good';
        if ($posts_needing_attention > $total_published * 0.5) {
            $status = 'error';
        } elseif ($posts_needing_attention > $total_published * 0.2) {
            $status = 'warning';
        }
        
        $stats[] = array(
            'count' => $posts_needing_attention,
            'label' => 'Wymagają uwagi',
            'type' => $post_type_obj->labels->name,
            'status' => $status
        );
    }
    
    return $stats;
}

/**
 * Liczy wpisy wymagające uwagi SEO dla danego typu
 */
function carni24_count_posts_needing_seo($post_type) {
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => '_seo_ignore',
                'compare' => 'NOT EXISTS'
            )
        )
    );
    
    $posts = get_posts($args);
    $count = 0;
    
    foreach ($posts as $post_id) {
        $issues = carni24_check_seo_completeness($post_id);
        if (!empty($issues)) {
            $count++;
        }
    }
    
    return $count;
}

/**
 * Kontrolka konfiguracji widget
 */
function carni24_seo_monitor_dashboard_widget_control() {
    $options = get_option('carni24_seo_monitor_settings', array(
        'post_types' => array('post', 'page', 'species', 'guides'),
        'check_title' => true,
        'check_description' => true,
        'check_keywords' => true,
        'posts_per_page' => 10
    ));
    
    if (isset($_POST['carni24_seo_monitor_submit'])) {
        $options['post_types'] = isset($_POST['post_types']) ? array_map('sanitize_text_field', $_POST['post_types']) : array();
        $options['check_title'] = isset($_POST['check_title']);
        $options['check_description'] = isset($_POST['check_description']);
        $options['check_keywords'] = isset($_POST['check_keywords']);
        $options['posts_per_page'] = absint($_POST['posts_per_page']) ?: 10;
        
        update_option('carni24_seo_monitor_settings', $options);
    }
    
    $available_post_types = get_post_types(array('public' => true), 'objects');
    ?>
    <p>
        <label><strong>Typy postów do monitorowania:</strong></label><br>
        <?php foreach ($available_post_types as $post_type => $obj): ?>
            <label style="display: block; margin: 5px 0;">
                <input type="checkbox" name="post_types[]" value="<?= esc_attr($post_type) ?>" 
                       <?= checked(in_array($post_type, $options['post_types']), true, false) ?> />
                <?= esc_html($obj->labels->name) ?>
            </label>
        <?php endforeach; ?>
    </p>
    
    <p>
        <label><strong>Sprawdzaj:</strong></label><br>
        <label style="display: block; margin: 5px 0;">
            <input type="checkbox" name="check_title" <?= checked($options['check_title'], true, false) ?> />
            Tytuł SEO
        </label>
        <label style="display: block; margin: 5px 0;">
            <input type="checkbox" name="check_description" <?= checked($options['check_description'], true, false) ?> />
            Opis SEO
        </label>
        <label style="display: block; margin: 5px 0;">
            <input type="checkbox" name="check_keywords" <?= checked($options['check_keywords'], true, false) ?> />
            Słowa kluczowe
        </label>
    </p>
    
    <p>
        <label><strong>Wpisów na stronę:</strong></label><br>
        <input type="number" name="posts_per_page" value="<?= esc_attr($options['posts_per_page']) ?>" 
               min="5" max="50" style="width: 80px;" />
    </p>
    
    <input type="hidden" name="carni24_seo_monitor_submit" value="1" />
    <?php
}

/**
 * AJAX: Toggle ignorowania wpisu
 */
function carni24_ajax_toggle_seo_ignore() {
    check_ajax_referer('carni24_seo_ignore', 'nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_die('Brak uprawnień');
    }
    
    $post_id = intval($_POST['post_id']);
    $ignore = $_POST['ignore'] === '1';
    
    if ($ignore) {
        update_post_meta($post_id, '_seo_ignore', '1');
        update_post_meta($post_id, '_seo_ignore_date', current_time('mysql'));
        update_post_meta($post_id, '_seo_ignore_user', get_current_user_id());
    } else {
        delete_post_meta($post_id, '_seo_ignore');
        delete_post_meta($post_id, '_seo_ignore_date');
        delete_post_meta($post_id, '_seo_ignore_user');
    }
    
    wp_send_json_success();
}
add_action('wp_ajax_carni24_toggle_seo_ignore', 'carni24_ajax_toggle_seo_ignore');

/**
 * AJAX: Odświeżanie monitora SEO
 */
function carni24_ajax_refresh_seo_monitor() {
    check_ajax_referer('carni24_seo_refresh', 'nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_die('Brak uprawnień');
    }
    
    $post_type_filter = sanitize_text_field($_POST['post_type'] ?? '');
    $issue_filter = sanitize_text_field($_POST['issue_type'] ?? '');
    $tab = sanitize_text_field($_POST['tab'] ?? 'overview');
    
    $options = get_option('carni24_seo_monitor_settings', array(
        'post_types' => array('post', 'page', 'species', 'guides'),
        'check_title' => true,
        'check_description' => true,
        'check_keywords' => true,
        'posts_per_page' => 10
    ));
    
    if (!empty($post_type_filter)) {
        $options['post_types'] = array($post_type_filter);
    }
    
    $posts_needing_seo = carni24_get_posts_needing_seo($options);
    
    // Filtruj według zakładki
    switch ($tab) {
        case 'missing':
            $posts_needing_seo = array_filter($posts_needing_seo, function($post_data) {
                return $post_data['seo_score'] < 30;
            });
            break;
        case 'warnings':
            $posts_needing_seo = array_filter($posts_needing_seo, function($post_data) {
                return $post_data['seo_score'] >= 30 && $post_data['seo_score'] < 80;
            });
            break;
        case 'optimized':
            $posts_needing_seo = array_filter($posts_needing_seo, function($post_data) {
                return $post_data['seo_score'] >= 80;
            });
            break;
    }
    
    // Filtruj po typie problemu
    if (!empty($issue_filter)) {
        $posts_needing_seo = array_filter($posts_needing_seo, function($post_data) use ($issue_filter) {
            foreach ($post_data['issues'] as $issue) {
                if ($issue['type'] === $issue_filter) {
                    return true;
                }
            }
            return false;
        });
    }
    
    ob_start();
    if (empty($posts_needing_seo)) {
        echo '<div class="seo-no-issues">
            <div class="no-issues-icon">🎉</div>
            <h3>Brak problemów dla wybranych filtrów</h3>
            <p>Wszystkie wpisy spełniają kryteria SEO.</p>
        </div>';
    } else {
        echo '<div class="seo-posts-list">';
        foreach ($posts_needing_seo as $post_data) {
            echo carni24_render_seo_post_item($post_data);
        }
        echo '</div>';
    }
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_carni24_refresh_seo_monitor', 'carni24_ajax_refresh_seo_monitor');

/**
 * AJAX: Ładowanie więcej wpisów
 */
function carni24_ajax_load_more_seo_posts() {
    check_ajax_referer('carni24_seo_load_more', 'nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_die('Brak uprawnień');
    }
    
    $offset = intval($_POST['offset']);
    $limit = intval($_POST['limit']) ?: 10;
    
    $options = get_option('carni24_seo_monitor_settings', array(
        'post_types' => array('post', 'page', 'species', 'guides'),
        'check_title' => true,
        'check_description' => true,
        'check_keywords' => true,
        'posts_per_page' => 10
    ));
    
    $posts_needing_seo = carni24_get_posts_needing_seo($options);
    $posts_slice = array_slice($posts_needing_seo, $offset, $limit);
    
    ob_start();
    foreach ($posts_slice as $post_data) {
        echo carni24_render_seo_post_item($post_data);
    }
    $html = ob_get_clean();
    
    $has_more = count($posts_needing_seo) > ($offset + $limit);
    
    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $has_more,
        'remaining' => $has_more ? count($posts_needing_seo) - ($offset + $limit) : 0
    ));
}
add_action('wp_ajax_carni24_load_more_seo_posts', 'carni24_ajax_load_more_seo_posts');
