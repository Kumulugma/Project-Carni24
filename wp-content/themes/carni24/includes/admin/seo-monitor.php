<?php
/**
 * SEO Monitor Meta Box - Dashboard widget do monitorowania SEO
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
 * Callback dla dashboard widget
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
    
    ?>
    <div class="carni24-seo-monitor">
        <!-- Nagłówek z statystykami -->
        <div class="seo-monitor-header">
            <div class="seo-stats-grid">
                <?php 
                $stats = carni24_get_seo_stats($options['post_types']);
                foreach ($stats as $stat): 
                ?>
                <div class="seo-stat-item">
                    <div class="stat-number"><?= $stat['count'] ?></div>
                    <div class="stat-label"><?= $stat['label'] ?></div>
                    <div class="stat-type"><?= $stat['type'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Filtry -->
        <div class="seo-monitor-filters">
            <select id="seo-post-type-filter">
                <option value="">Wszystkie typy postów</option>
                <?php foreach ($options['post_types'] as $post_type): 
                    $post_type_obj = get_post_type_object($post_type);
                ?>
                <option value="<?= esc_attr($post_type) ?>"><?= esc_html($post_type_obj->labels->name) ?></option>
                <?php endforeach; ?>
            </select>
            
            <select id="seo-issue-filter">
                <option value="">Wszystkie problemy</option>
                <option value="no_title">Brak tytułu SEO</option>
                <option value="no_description">Brak opisu SEO</option>
                <option value="no_keywords">Brak słów kluczowych</option>
                <option value="title_too_short">Tytuł za krótki</option>
                <option value="title_too_long">Tytuł za długi</option>
                <option value="desc_too_short">Opis za krótki</option>
                <option value="desc_too_long">Opis za długi</option>
            </select>
            
            <button type="button" class="button" onclick="carni24RefreshSeoMonitor()">🔄 Odśwież</button>
        </div>

        <!-- Lista wpisów -->
        <div class="seo-monitor-list" id="seo-monitor-list" style="margin: 10px;">
            <?php if (empty($posts_needing_seo)): ?>
                <div class="seo-no-issues">
                    <div class="no-issues-icon">🎉</div>
                    <h3>Świetnie! Wszystkie wpisy mają poprawne SEO</h3>
                    <p>Nie znaleziono wpisów wymagających uwagi SEO.</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts_needing_seo as $post_data): ?>
                    <div class="seo-issue-item" data-post-id="<?= $post_data['post']->ID ?>" data-post-type="<?= $post_data['post']->post_type ?>">
                        <div class="seo-issue-header">
                            <div class="seo-issue-title">
                                <strong><?= esc_html($post_data['post']->post_title) ?></strong>
                                <span class="post-type-badge"><?= esc_html(get_post_type_object($post_data['post']->post_type)->labels->singular_name) ?></span>
                            </div>
                            <div class="seo-issue-actions">
                                <a href="<?= get_edit_post_link($post_data['post']->ID) ?>" class="button button-small">✏️ Edytuj</a>
                                <button type="button" class="button button-small seo-ignore-btn" 
                                        onclick="carni24ToggleSeoIgnore(<?= $post_data['post']->ID ?>)"
                                        data-ignored="<?= get_post_meta($post_data['post']->ID, '_seo_ignore', true) ? 'true' : 'false' ?>">
                                    <?= get_post_meta($post_data['post']->ID, '_seo_ignore', true) ? '👁️ Przywróć' : '🙈 Ignoruj' ?>
                                </button>
                            </div>
                        </div>
                        
                        <div class="seo-issues-list">
                            <?php foreach ($post_data['issues'] as $issue): ?>
                                <div class="seo-issue-badge <?= $issue['severity'] ?>">
                                    <span class="issue-icon"><?= $issue['icon'] ?></span>
                                    <?= $issue['message'] ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="seo-issue-meta">
                            <span class="post-date">📅 <?= get_the_date('d.m.Y', $post_data['post']->ID) ?></span>
                            <span class="post-status">📊 <?= get_post_status($post_data['post']->ID) === 'publish' ? 'Opublikowany' : 'Szkic' ?></span>
                            <?php if (function_exists('carni24_get_post_views')): ?>
                                <span class="post-views">👁️ <?= number_format(carni24_get_post_views($post_data['post']->ID)) ?> wyświetleń</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Paginacja -->
                <?php if (count($posts_needing_seo) >= $options['posts_per_page']): ?>
                    <div class="seo-monitor-pagination">
                        <button type="button" class="button" onclick="carni24LoadMoreSeoIssues()">📄 Załaduj więcej</button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <style>
    .carni24-seo-monitor {
        margin: -12px -12px -6px;
    }

    .seo-monitor-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        margin-bottom: 20px;
    }

    .seo-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .seo-stat-item {
        text-align: center;
        background: rgba(255,255,255,0.1);
        padding: 15px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        opacity: 0.9;
        margin-bottom: 2px;
    }

    .stat-type {
        font-size: 11px;
        opacity: 0.7;
        text-transform: uppercase;
    }

    .seo-monitor-filters {
        display: flex;
        gap: 10px;
        margin: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .seo-monitor-filters select {
        min-width: 160px;
    }

    .seo-issue-item {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.2s ease;
    }

    .seo-issue-item:hover {
        border-color: #135e96;
        box-shadow: 0 2px 8px rgba(19, 94, 150, 0.1);
    }

    .seo-issue-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .seo-issue-title {
        flex: 1;
        margin-right: 15px;
    }

    .post-type-badge {
        display: inline-block;
        background: #f0f0f1;
        color: #646970;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        margin-left: 10px;
        text-transform: uppercase;
        font-weight: 500;
    }

    .seo-issue-actions {
        display: flex;
        gap: 8px;
    }

    .seo-issues-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }

    .seo-issue-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }

    .seo-issue-badge.high {
        background: #fee;
        color: #c41e3a;
        border: 1px solid #f8d7da;
    }

    .seo-issue-badge.medium {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .seo-issue-badge.low {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .issue-icon {
        font-size: 14px;
    }

    .seo-issue-meta {
        display: flex;
        gap: 15px;
        font-size: 12px;
        color: #646970;
        padding-top: 10px;
        border-top: 1px solid #f0f0f1;
    }

    .seo-no-issues {
        text-align: center;
        padding: 40px 20px;
        color: #646970;
    }

    .no-issues-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .seo-no-issues h3 {
        color: #00a32a;
        margin-bottom: 10px;
    }

    .seo-ignore-btn[data-ignored="true"] {
        background: #f6f7f7;
        color: #646970;
    }

    .seo-monitor-pagination {
        text-align: center;
        padding: 20px;
        border-top: 1px solid #e0e0e0;
    }

    /* Responsywność */
    @media (max-width: 600px) {
        .seo-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .seo-monitor-filters {
            flex-direction: column;
        }
        
        .seo-monitor-filters select {
            min-width: auto;
        }
        
        .seo-issue-header {
            flex-direction: column;
            gap: 10px;
        }
        
        .seo-issue-actions {
            align-self: stretch;
        }
        
        .seo-issue-meta {
            flex-direction: column;
            gap: 5px;
        }
    }
    </style>

    <script>
    // Toggle ignorowania wpisu SEO
    function carni24ToggleSeoIgnore(postId) {
        const button = document.querySelector(`[onclick="carni24ToggleSeoIgnore(${postId})"]`);
        const isIgnored = button.getAttribute('data-ignored') === 'true';
        
        button.disabled = true;
        button.textContent = '⏳ Zapisywanie...';
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_toggle_seo_ignore',
                post_id: postId,
                ignore: isIgnored ? '0' : '1',
                nonce: '<?= wp_create_nonce("carni24_seo_ignore") ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newIgnored = !isIgnored;
                button.setAttribute('data-ignored', newIgnored.toString());
                button.textContent = newIgnored ? '👁️ Przywróć' : '🙈 Ignoruj';
                
                // Opcjonalnie ukryj wpis jeśli został zignorowany
                if (newIgnored) {
                    const item = button.closest('.seo-issue-item');
                    item.style.opacity = '0.5';
                    item.style.transform = 'scale(0.98)';
                    
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            } else {
                alert('Błąd: ' + data.data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Wystąpił błąd podczas zapisywania');
        })
        .finally(() => {
            button.disabled = false;
        });
    }
    
    // Odświeżanie monitora SEO
    function carni24RefreshSeoMonitor() {
        const postTypeFilter = document.getElementById('seo-post-type-filter').value;
        const issueFilter = document.getElementById('seo-issue-filter').value;
        
        const list = document.getElementById('seo-monitor-list');
        list.innerHTML = '<div style="text-align: center; padding: 20px;">⏳ Ładowanie...</div>';
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_refresh_seo_monitor',
                post_type: postTypeFilter,
                issue_type: issueFilter,
                nonce: '<?= wp_create_nonce("carni24_seo_refresh") ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                list.innerHTML = data.data.html;
            } else {
                list.innerHTML = '<div style="text-align: center; padding: 20px; color: #d63638;">❌ Błąd ładowania danych</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            list.innerHTML = '<div style="text-align: center; padding: 20px; color: #d63638;">❌ Wystąpił błąd</div>';
        });
    }
    
    // Ładowanie większej ilości wyników
    function carni24LoadMoreSeoIssues() {
        // Implementacja paginacji
        console.log('Loading more SEO issues...');
    }
    </script>
    <?php
}

/**
 * Pobiera wpisy wymagające uwagi SEO
 */
function carni24_get_posts_needing_seo($options) {
    $post_types = $options['post_types'];
    $posts_per_page = $options['posts_per_page'];
    
    // Query dla postów
    $query = new WP_Query(array(
        'post_type' => $post_types,
        'post_status' => array('publish', 'draft'),
        'posts_per_page' => $posts_per_page,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => '_seo_ignore',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => '_seo_ignore',
                'value' => '1',
                'compare' => '!='
            )
        )
    ));
    
    $posts_with_issues = array();
    
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $post = get_post($post_id);
        
        $issues = carni24_check_post_seo_issues($post_id, $options);
        
        if (!empty($issues)) {
            $posts_with_issues[] = array(
                'post' => $post,
                'issues' => $issues
            );
        }
    }
    
    wp_reset_postdata();
    return $posts_with_issues;
}

/**
 * Sprawdza problemy SEO dla pojedynczego wpisu
 */
function carni24_check_post_seo_issues($post_id, $options) {
    $issues = array();
    
    // Pobierz dane SEO
    $seo_title = get_post_meta($post_id, '_seo_title', true);
    $seo_description = get_post_meta($post_id, '_seo_description', true);
    $seo_keywords = get_post_meta($post_id, '_seo_keywords', true);
    
    // Sprawdź tytuł SEO
    if ($options['check_title']) {
        if (empty($seo_title)) {
            $issues[] = array(
                'type' => 'no_title',
                'message' => 'Brak tytułu SEO',
                'severity' => 'high',
                'icon' => '❌'
            );
        } else {
            $title_length = strlen($seo_title);
            if ($title_length < 30) {
                $issues[] = array(
                    'type' => 'title_too_short',
                    'message' => "Tytuł za krótki ({$title_length} znaków)",
                    'severity' => 'medium',
                    'icon' => '⚠️'
                );
            } elseif ($title_length > 70) {
                $issues[] = array(
                    'type' => 'title_too_long',
                    'message' => "Tytuł za długi ({$title_length} znaków)",
                    'severity' => 'medium',
                    'icon' => '⚠️'
                );
            }
        }
    }
    
    // Sprawdź opis SEO
    if ($options['check_description']) {
        if (empty($seo_description)) {
            $issues[] = array(
                'type' => 'no_description',
                'message' => 'Brak opisu SEO',
                'severity' => 'high',
                'icon' => '❌'
            );
        } else {
            $desc_length = strlen($seo_description);
            if ($desc_length < 120) {
                $issues[] = array(
                    'type' => 'desc_too_short',
                    'message' => "Opis za krótki ({$desc_length} znaków)",
                    'severity' => 'medium',
                    'icon' => '⚠️'
                );
            } elseif ($desc_length > 200) {
                $issues[] = array(
                    'type' => 'desc_too_long',
                    'message' => "Opis za długi ({$desc_length} znaków)",
                    'severity' => 'low',
                    'icon' => '💡'
                );
            }
        }
    }
    
    // Sprawdź słowa kluczowe
    if ($options['check_keywords'] && empty($seo_keywords)) {
        $issues[] = array(
            'type' => 'no_keywords',
            'message' => 'Brak słów kluczowych',
            'severity' => 'low',
            'icon' => '💡'
        );
    }
    
    // Sprawdź obraz OG
    $og_image = get_post_meta($post_id, '_seo_og_image', true);
    if (empty($og_image) && !has_post_thumbnail($post_id)) {
        $issues[] = array(
            'type' => 'no_og_image',
            'message' => 'Brak obrazu dla mediów społecznościowych',
            'severity' => 'low',
            'icon' => '📷'
        );
    }
    
    return $issues;
}

/**
 * Pobiera statystyki SEO
 */
function carni24_get_seo_stats($post_types) {
    $stats = array();
    
    foreach ($post_types as $post_type) {
        $total_posts = wp_count_posts($post_type);
        $total_published = $total_posts->publish;
        
        // Policz posty bez SEO
        $posts_without_seo = new WP_Query(array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_seo_title',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_seo_title',
                    'value' => '',
                    'compare' => '='
                )
            )
        ));
        
        $post_type_obj = get_post_type_object($post_type);
        $needs_attention = $posts_without_seo->found_posts;
        
        $stats[] = array(
            'count' => $needs_attention,
            'label' => 'Wymaga uwagi',
            'type' => $post_type_obj->labels->name,
            'percentage' => $total_published > 0 ? round(($needs_attention / $total_published) * 100) : 0
        );
    }
    
    return $stats;
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
    
    $post_type_filter = sanitize_text_field($_POST['post_type']);
    $issue_filter = sanitize_text_field($_POST['issue_type']);
    
    // Tu możesz zaimplementować filtrowanie
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
        // Renderuj wpisy (podobnie jak w głównym callbacku)
        foreach ($posts_needing_seo as $post_data) {
            // Kod renderowania wpisu...
        }
    }
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_carni24_refresh_seo_monitor', 'carni24_ajax_refresh_seo_monitor');

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
        <label>Typy postów do monitorowania:</label><br>
        <?php foreach ($available_post_types as $post_type => $obj): ?>
            <label>
                <input type="checkbox" name="post_types[]" value="<?= esc_attr($post_type) ?>" 
                       <?= checked(in_array($post_type, $options['post_types']), true, false) ?> />
                <?= esc_html($obj->labels->name) ?>
            </label><br>
        <?php endforeach; ?>
    </p>
    
    <p>
        <label>Sprawdzaj:</label><br>
        <label>
            <input type="checkbox" name="check_title" <?= checked($options['check_title'], true, false) ?> />
            Tytuł SEO
        </label><br>
        <label>
            <input type="checkbox" name="check_description" <?= checked($options['check_description'], true, false) ?> />
            Opis SEO
        </label><br>
        <label>
            <input type="checkbox" name="check_keywords" <?= checked($options['check_keywords'], true, false) ?> />
            Słowa kluczowe
        </label>
    </p>
    
    <p>
        <label>Wpisów na stronę:</label>
        <input type="number" name="posts_per_page" value="<?= esc_attr($options['posts_per_page']) ?>" min="5" max="50" />
    </p>
    
    <input type="hidden" name="carni24_seo_monitor_submit" value="1" />
    <?php
}