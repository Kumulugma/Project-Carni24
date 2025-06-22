<?php
/**
 * Carni24 Dashboard Widgets - UNIFIED SYSTEM (Bez duplikatów AJAX)
 * Ujednolicone widgety dla dashboard WordPress Admin
 * 
 * @package Carni24
 * @subpackage Admin
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dodaje ujednolicone widgety do dashboard
 */
function carni24_add_dashboard_widgets() {
    wp_add_dashboard_widget(
        'carni24_unified_stats_widget',
        '📊 Statystyki i przegląd Carni24',
        'carni24_unified_stats_widget_callback'
    );
    
    wp_add_dashboard_widget(
        'carni24_unified_seo_widget',
        '🔍 Monitor SEO i optymalizacja',
        'carni24_unified_seo_widget_callback'
    );
    
    wp_add_dashboard_widget(
        'carni24_recent_activity_widget',
        '🔄 Ostatnia aktywność',
        'carni24_recent_activity_widget_callback'
    );
}
add_action('wp_dashboard_setup', 'carni24_add_dashboard_widgets');
/**
 * Unified Stats Widget - łączy szybkie statystyki i statystyki Carni24
 */
function carni24_unified_stats_widget_callback() {
    // Cache dla wydajności
    $stats = get_transient('carni24_unified_stats');
    if ($stats === false) {
        $stats = carni24_calculate_unified_stats();
        set_transient('carni24_unified_stats', $stats, 5 * MINUTE_IN_SECONDS);
    }
    
    ?>
    <div class="carni24-unified-widget" id="carni24-stats-widget">
        <!-- Nawigacja zakładek -->
        <div class="carni24-widget-tabs">
            <button class="carni24-tab-btn active" data-tab="overview">
                🏠 Przegląd
            </button>
            <button class="carni24-tab-btn" data-tab="content">
                📝 Treści
            </button>
            <button class="carni24-tab-btn" data-tab="system">
                ⚙️ System
            </button>
            <button class="carni24-tab-btn" data-tab="popular">
                🔥 Popularne
            </button>
        </div>

        <!-- Zawartość zakładek -->
        <div class="carni24-widget-content">
            <!-- Tab: Przegląd -->
            <div class="carni24-tab-content active" id="tab-overview">
                <div class="carni24-quick-stats">
                    <div class="carni24-stat-item featured">
                        <span class="carni24-stat-number"><?= $stats['total_content'] ?></span>
                        <span class="carni24-stat-label">Wszystkie treści</span>
                        <span class="carni24-stat-meta">Ostatni: <?= $stats['last_post_date'] ?></span>
                    </div>
                    
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= $stats['published_posts'] ?></span>
                        <span class="carni24-stat-label">Opublikowane wpisy</span>
                    </div>
                    
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= $stats['total_species'] ?></span>
                        <span class="carni24-stat-label">Gatunki roślin</span>
                    </div>
                    
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= $stats['total_guides'] ?></span>
                        <span class="carni24-stat-label">Poradniki</span>
                    </div>
                </div>

                <!-- Progress bars -->
                <div class="carni24-progress-section">
                    <div class="progress-item">
                        <div class="progress-header">
                            <span>Gatunki z pełnymi danymi</span>
                            <span class="progress-value"><?= $stats['species_completion'] ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $stats['species_completion'] ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="progress-item">
                        <div class="progress-header">
                            <span>Poradniki kompletne</span>
                            <span class="progress-value"><?= $stats['guides_completion'] ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $stats['guides_completion'] ?>%"></div>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="carni24-actions">
                    <a href="<?= admin_url('post-new.php') ?>" class="button button-primary">
                        📝 Nowy wpis
                    </a>
                    <a href="<?= admin_url('post-new.php?post_type=species') ?>" class="button">
                        🌿 Nowy gatunek
                    </a>
                    <a href="<?= admin_url('post-new.php?post_type=guides') ?>" class="button">
                        📖 Nowy poradnik
                    </a>
                </div>
            </div>

            <!-- Tab: Treści -->
            <div class="carni24-tab-content" id="tab-content">
                <div class="carni24-content-stats">
                    <div class="content-type-grid">
                        <div class="content-type-item">
                            <div class="content-type-header">
                                <span class="content-icon">📝</span>
                                <span class="content-title">Wpisy</span>
                            </div>
                            <div class="content-numbers">
                                <span class="main-number"><?= $stats['published_posts'] ?></span>
                                <span class="sub-number"><?= $stats['draft_posts'] ?> roboczych</span>
                            </div>
                        </div>
                        
                        <div class="content-type-item">
                            <div class="content-type-header">
                                <span class="content-icon">📄</span>
                                <span class="content-title">Strony</span>
                            </div>
                            <div class="content-numbers">
                                <span class="main-number"><?= $stats['published_pages'] ?></span>
                                <span class="sub-number"><?= $stats['draft_pages'] ?> roboczych</span>
                            </div>
                        </div>
                        
                        <div class="content-type-item">
                            <div class="content-type-header">
                                <span class="content-icon">🌿</span>
                                <span class="content-title">Gatunki</span>
                            </div>
                            <div class="content-numbers">
                                <span class="main-number"><?= $stats['total_species'] ?></span>
                                <span class="sub-number"><?= $stats['easy_species'] ?> łatwych</span>
                            </div>
                        </div>
                        
                        <div class="content-type-item">
                            <div class="content-type-header">
                                <span class="content-icon">📖</span>
                                <span class="content-title">Poradniki</span>
                            </div>
                            <div class="content-numbers">
                                <span class="main-number"><?= $stats['total_guides'] ?></span>
                                <span class="sub-number"><?= $stats['beginner_guides'] ?> dla początkujących</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Najnowsze treści -->
                <div class="recent-content-section">
                    <h4>📅 Ostatnio dodane</h4>
                    <ul class="recent-content-list">
                        <?php foreach ($stats['recent_content'] as $item): ?>
                        <li class="recent-content-item">
                            <span class="content-type-badge <?= $item['type'] ?>"><?= $item['type_icon'] ?></span>
                            <a href="<?= get_edit_post_link($item['id']) ?>" class="content-title">
                                <?= esc_html($item['title']) ?>
                            </a>
                            <span class="content-date"><?= $item['date'] ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tab: System -->
            <div class="carni24-tab-content" id="tab-system">
                <div class="carni24-quick-stats">
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= CARNI24_VERSION ?></span>
                        <span class="carni24-stat-label">Wersja motywu</span>
                    </div>
                    
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= get_bloginfo('version') ?></span>
                        <span class="carni24-stat-label">WordPress</span>
                    </div>
                    
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= count(get_option('active_plugins')) ?></span>
                        <span class="carni24-stat-label">Aktywne wtyczki</span>
                    </div>
                    
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= wp_count_posts('attachment')->inherit ?></span>
                        <span class="carni24-stat-label">Pliki mediów</span>
                    </div>
                </div>

                <!-- System Health -->
                <div class="system-health-section">
                    <h4>🏥 Stan systemu</h4>
                    <div class="health-checks">
                        <div class="health-item <?= $stats['php_version_ok'] ? 'healthy' : 'warning' ?>">
                            <span class="health-icon"><?= $stats['php_version_ok'] ? '✅' : '⚠️' ?></span>
                            <span class="health-text">PHP <?= phpversion() ?></span>
                        </div>
                        
                        <div class="health-item <?= $stats['memory_ok'] ? 'healthy' : 'warning' ?>">
                            <span class="health-icon"><?= $stats['memory_ok'] ? '✅' : '⚠️' ?></span>
                            <span class="health-text">Pamięć: <?= $stats['memory_usage'] ?></span>
                        </div>
                        
                        <div class="health-item <?= $stats['uploads_writable'] ? 'healthy' : 'error' ?>">
                            <span class="health-icon"><?= $stats['uploads_writable'] ? '✅' : '❌' ?></span>
                            <span class="health-text">Katalog uploads</span>
                        </div>
                    </div>
                </div>

                <!-- Maintenance tools -->
                <div class="carni24-actions">
                    <button type="button" class="button" onclick="carni24RefreshStats()">
                        🔄 Odśwież statystyki
                    </button>
                    <a href="<?= admin_url('admin.php?page=carni24-theme-options') ?>" class="button">
                        ⚙️ Ustawienia motywu
                    </a>
                </div>
            </div>

            <!-- Tab: Popularne -->
            <div class="carni24-tab-content" id="tab-popular">
                <div class="popular-content-section">
                    <h4>🔥 Najczęściej odwiedzane</h4>
                    <ul class="popular-content-list">
                        <?php foreach ($stats['popular_content'] as $item): ?>
                        <li class="popular-content-item">
                            <div class="popular-content-info">
                                <a href="<?= get_edit_post_link($item['id']) ?>" class="content-title">
                                    <?= esc_html($item['title']) ?>
                                </a>
                                <span class="content-views"><?= $item['views'] ?> wyświetleń</span>
                            </div>
                            <div class="popular-content-meta">
                                <span class="content-type-badge <?= $item['type'] ?>"><?= $item['type_icon'] ?></span>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="trending-searches">
                    <h4>🔍 Popularne wyszukiwania</h4>
                    <div class="search-terms">
                        <?php foreach ($stats['popular_searches'] as $term): ?>
                        <span class="search-term"><?= esc_html($term) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
/**
 * Unified SEO Widget - łączy Monitor SEO i Przegląd SEO
 */
function carni24_unified_seo_widget_callback() {
    // Cache dla wydajności
    $seo_data = get_transient('carni24_unified_seo');
    if ($seo_data === false) {
        $seo_data = carni24_calculate_unified_seo();
        set_transient('carni24_unified_seo', $seo_data, 5 * MINUTE_IN_SECONDS);
    }
    
    ?>
    <div class="carni24-unified-widget" id="carni24-seo-widget">
        <!-- Nawigacja zakładek -->
        <div class="carni24-widget-tabs">
            <button class="carni24-tab-btn active" data-tab="overview">
                📊 Przegląd
            </button>
            <button class="carni24-tab-btn" data-tab="issues">
                ⚠️ Problemy
            </button>
            <button class="carni24-tab-btn" data-tab="optimization">
                🎯 Optymalizacja
            </button>
            <button class="carni24-tab-btn" data-tab="analysis">
                📈 Analiza
            </button>
        </div>

        <!-- Zawartość zakładek -->
        <div class="carni24-widget-content">
            <!-- Tab: Przegląd SEO -->
            <div class="carni24-tab-content active" id="tab-overview">
                <!-- SEO Score -->
                <div class="seo-score-section">
                    <div class="seo-score-main">
                        <div class="score-circle" data-score="<?= $seo_data['overall_score'] ?>">
                            <span class="score-number"><?= $seo_data['overall_score'] ?></span>
                            <span class="score-label">SEO Score</span>
                        </div>
                        <div class="score-breakdown">
                            <div class="score-item">
                                <span class="score-icon">📝</span>
                                <span class="score-text">Tytuły: <?= $seo_data['titles_score'] ?>%</span>
                            </div>
                            <div class="score-item">
                                <span class="score-icon">📄</span>
                                <span class="score-text">Opisy: <?= $seo_data['descriptions_score'] ?>%</span>
                            </div>
                            <div class="score-item">
                                <span class="score-icon">🖼️</span>
                                <span class="score-text">Obrazy: <?= $seo_data['images_score'] ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick SEO Stats -->
                <div class="carni24-quick-stats">
                    <div class="carni24-stat-item <?= $seo_data['posts_without_title'] > 0 ? 'warning' : 'success' ?>">
                        <span class="carni24-stat-number"><?= $seo_data['posts_without_title'] ?></span>
                        <span class="carni24-stat-label">Bez tytułu SEO</span>
                    </div>
                    
                    <div class="carni24-stat-item <?= $seo_data['posts_without_description'] > 0 ? 'warning' : 'success' ?>">
                        <span class="carni24-stat-number"><?= $seo_data['posts_without_description'] ?></span>
                        <span class="carni24-stat-label">Bez opisu SEO</span>
                    </div>
                    
                    <div class="carni24-stat-item <?= $seo_data['posts_without_og_image'] > 0 ? 'info' : 'success' ?>">
                        <span class="carni24-stat-number"><?= $seo_data['posts_without_og_image'] ?></span>
                        <span class="carni24-stat-label">Bez obrazu OG</span>
                    </div>
                    
                    <div class="carni24-stat-item">
                        <span class="carni24-stat-number"><?= $seo_data['posts_optimized'] ?></span>
                        <span class="carni24-stat-label">W pełni zoptymalizowane</span>
                    </div>
                </div>

                <!-- Best performing content -->
                <div class="seo-best-content">
                    <h4>✅ Najlepsze SEO</h4>
                    <ul class="seo-content-list">
                        <?php foreach ($seo_data['best_seo_posts'] as $post): ?>
                        <li class="seo-content-item">
                            <span class="content-type-badge <?= $post['type'] ?>"><?= $post['type_icon'] ?></span>
                            <a href="<?= get_edit_post_link($post['id']) ?>" class="content-title">
                                <?= esc_html($post['title']) ?>
                            </a>
                            <span class="seo-score-badge"><?= $post['seo_score'] ?>%</span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Tab: Problemy SEO -->
            <div class="carni24-tab-content" id="tab-issues">
                <div class="seo-issues-filters">
                    <select id="seo-issue-type-filter" onchange="carni24FilterSEOIssues()">
                        <option value="">Wszystkie problemy</option>
                        <option value="title">Brak tytułu SEO</option>
                        <option value="description">Brak opisu SEO</option>
                        <option value="og_image">Brak obrazu OG</option>
                        <option value="keywords">Brak słów kluczowych</option>
                    </select>
                    
                    <select id="seo-post-type-filter" onchange="carni24FilterSEOIssues()">
                        <option value="">Wszystkie typy</option>
                        <option value="post">Wpisy</option>
                        <option value="page">Strony</option>
                        <option value="species">Gatunki</option>
                        <option value="guides">Poradniki</option>
                    </select>
                </div>

                <div id="seo-issues-list" class="seo-issues-container">
                    <!-- Issues will be loaded here -->
                    <?php foreach ($seo_data['seo_issues'] as $issue): ?>
                    <div class="seo-issue-item" data-issue-type="<?= $issue['issue_type'] ?>" data-post-type="<?= $issue['post_type'] ?>">
                        <div class="issue-header">
                            <span class="issue-icon"><?= $issue['icon'] ?></span>
                            <span class="issue-severity <?= $issue['severity'] ?>"><?= $issue['severity_text'] ?></span>
                        </div>
                        <div class="issue-content">
                            <div class="issue-title">
                                <a href="<?= get_edit_post_link($issue['post_id']) ?>" target="_blank">
                                    <?= esc_html($issue['post_title']) ?>
                                </a>
                                <span class="post-type-badge <?= $issue['post_type'] ?>"><?= $issue['post_type_name'] ?></span>
                            </div>
                            <div class="issue-description"><?= $issue['description'] ?></div>
                            <div class="issue-actions">
                                <a href="<?= get_edit_post_link($issue['post_id']) ?>" class="button button-small">
                                    Napraw
                                </a>
                                <button type="button" class="button button-small" onclick="carni24MarkIssueResolved(<?= $issue['post_id'] ?>, '<?= $issue['issue_type'] ?>')">
                                    Oznacz jako naprawione
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bulk actions -->
                <div class="seo-bulk-actions">
                    <button type="button" class="button" onclick="carni24BulkFixSEO('title')">
                        🔧 Wygeneruj brakujące tytuły
                    </button>
                    <button type="button" class="button" onclick="carni24BulkFixSEO('description')">
                        📝 Wygeneruj brakujące opisy
                    </button>
                </div>
            </div>

            <!-- Tab: Optymalizacja -->
            <div class="carni24-tab-content" id="tab-optimization">
                <!-- SEO Recommendations -->
                <div class="seo-recommendations">
                    <h4>💡 Rekomendacje</h4>
                    <div class="recommendations-list">
                        <?php foreach ($seo_data['recommendations'] as $recommendation): ?>
                        <div class="recommendation-item <?= $recommendation['priority'] ?>">
                            <div class="recommendation-header">
                                <span class="recommendation-icon"><?= $recommendation['icon'] ?></span>
                                <span class="recommendation-title"><?= $recommendation['title'] ?></span>
                                <span class="priority-badge <?= $recommendation['priority'] ?>"><?= $recommendation['priority_text'] ?></span>
                            </div>
                            <div class="recommendation-description"><?= $recommendation['description'] ?></div>
                            <?php if ($recommendation['action_url']): ?>
                            <div class="recommendation-actions">
                                <a href="<?= $recommendation['action_url'] ?>" class="button button-small">
                                    <?= $recommendation['action_text'] ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- SEO Tools -->
                <div class="seo-tools-section">
                    <h4>🔧 Narzędzia SEO</h4>
                    <div class="seo-tools-grid">
                        <button type="button" class="seo-tool-button" onclick="carni24RunSEOAudit()">
                            <span class="tool-icon">🕵️</span>
                            <span class="tool-title">Audyt SEO</span>
                            <span class="tool-desc">Kompletna analiza SEO</span>
                        </button>
                        
                        <button type="button" class="seo-tool-button" onclick="carni24GenerateSitemaps()">
                            <span class="tool-icon">🗺️</span>
                            <span class="tool-title">Mapy stron</span>
                            <span class="tool-desc">Wygeneruj/odśwież</span>
                        </button>
                        
                        <button type="button" class="seo-tool-button" onclick="carni24AnalyzeKeywords()">
                            <span class="tool-icon">🔑</span>
                            <span class="tool-title">Słowa kluczowe</span>
                            <span class="tool-desc">Analiza i sugestie</span>
                        </button>
                        
                        <button type="button" class="seo-tool-button" onclick="carni24CheckInternalLinks()">
                            <span class="tool-icon">🔗</span>
                            <span class="tool-title">Linki wewnętrzne</span>
                            <span class="tool-desc">Sprawdź połączenia</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tab: Analiza -->
            <div class="carni24-tab-content" id="tab-analysis">
                <!-- Top Keywords -->
                <div class="top-keywords-section">
                    <h4>🏆 Top słowa kluczowe</h4>
                    <div class="keywords-cloud">
                        <?php foreach ($seo_data['top_keywords'] as $keyword): ?>
                        <span class="keyword-tag" style="font-size: <?= $keyword['size'] ?>px;">
                            <?= esc_html($keyword['word']) ?>
                            <span class="keyword-count"><?= $keyword['count'] ?></span>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Content gaps -->
                <div class="content-gaps-section">
                    <h4>📊 Luki w treści</h4>
                    <div class="gaps-list">
                        <?php foreach ($seo_data['content_gaps'] as $gap): ?>
                        <div class="gap-item">
                            <div class="gap-topic"><?= esc_html($gap['topic']) ?></div>
                            <div class="gap-opportunity"><?= $gap['opportunity'] ?> możliwości</div>
                            <div class="gap-difficulty">Trudność: <?= $gap['difficulty'] ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
/**
 * Widget ostatniej aktywności (pozostaje bez zmian)
 */
function carni24_recent_activity_widget_callback() {
    $recent_posts = wp_get_recent_posts(array(
        'numberposts' => 5,
        'post_status' => 'publish',
        'post_type' => array('post', 'species', 'guides')
    ));
    
    $recent_comments = get_comments(array(
        'status' => 'approve',
        'number' => 5
    ));
    
    ?>
    <div class="carni24-dashboard-widget">
        <div class="activity-section">
            <h4>📝 Najnowsze publikacje</h4>
            <?php if ($recent_posts): ?>
                <ul class="activity-list">
                    <?php foreach ($recent_posts as $post): 
                        $type_icons = array(
                            'species' => '🌿',
                            'guides' => '📖',
                            'post' => '📝'
                        );
                        $icon = $type_icons[$post['post_type']] ?? '📄';
                    ?>
                        <li>
                            <span class="activity-icon"><?= $icon ?></span>
                            <a href="<?= get_edit_post_link($post['ID']) ?>">
                                <?= esc_html($post['post_title']) ?>
                            </a>
                            <span class="activity-time">
                                <?= human_time_diff(strtotime($post['post_date'])) ?> temu
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Brak ostatnich publikacji</p>
            <?php endif; ?>
        </div>
        
        <?php if ($recent_comments): ?>
        <div class="activity-section">
            <h4>💬 Najnowsze komentarze</h4>
            <ul class="activity-list">
                <?php foreach ($recent_comments as $comment): ?>
                    <li>
                        <span class="activity-icon">💬</span>
                        <strong><?= esc_html($comment->comment_author) ?></strong>
                        w <a href="<?= get_edit_post_link($comment->comment_post_ID) ?>">
                            <?= esc_html(get_the_title($comment->comment_post_ID)) ?>
                        </a>
                        <span class="activity-time">
                            <?= human_time_diff(strtotime($comment->comment_date)) ?> temu
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Oblicza ujednolicone statystyki
 */
function carni24_calculate_unified_stats() {
    global $wpdb;
    
    // Podstawowe liczby
    $published_posts = wp_count_posts('post')->publish;
    $draft_posts = wp_count_posts('post')->draft;
    $published_pages = wp_count_posts('page')->publish;
    $draft_pages = wp_count_posts('page')->draft;
    $total_species = wp_count_posts('species')->publish ?? 0;
    $total_guides = wp_count_posts('guides')->publish ?? 0;
    
    // Zaawansowane statystyki
    $species_with_full_data = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p 
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_species_difficulty'
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_species_origin'
        WHERE p.post_type = 'species' AND p.post_status = 'publish'
        AND pm1.meta_value != '' AND pm2.meta_value != ''
    ");
    
    $guides_with_full_data = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p 
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_guide_difficulty'
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_guide_estimated_time'
        WHERE p.post_type = 'guides' AND p.post_status = 'publish'
        AND pm1.meta_value != '' AND pm2.meta_value != ''
    ");
    
    // Ostatni post
    $last_post = get_posts(array(
        'numberposts' => 1,
        'post_type' => array('post', 'species', 'guides'),
        'post_status' => 'publish'
    ));
    
    // Najnowsze treści
    $recent_content = get_posts(array(
        'numberposts' => 5,
        'post_type' => array('post', 'page', 'species', 'guides'),
        'post_status' => 'publish'
    ));
    
    $recent_content_formatted = array();
    foreach ($recent_content as $post) {
        $type_icons = array(
            'species' => '🌿',
            'guides' => '📖',
            'post' => '📝',
            'page' => '📄'
        );
        
        $recent_content_formatted[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'type' => $post->post_type,
            'type_icon' => $type_icons[$post->post_type] ?? '📄',
            'date' => human_time_diff(strtotime($post->post_date)) . ' temu'
        );
    }
    
    // Gatunki łatwe
    $easy_species = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM {$wpdb->posts} p 
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
        WHERE p.post_type = 'species' AND p.post_status = 'publish'
        AND pm.meta_key = '_species_difficulty' AND pm.meta_value = 'easy'
    ");
    
    // Poradniki dla początkujących
    $beginner_guides = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM {$wpdb->posts} p 
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id 
        WHERE p.post_type = 'guides' AND p.post_status = 'publish'
        AND pm.meta_key = '_guide_difficulty' AND pm.meta_value = 'beginner'
    ");
    
    // Popularne treści (mock data - można zintegrować z Google Analytics)
    $popular_content = array(
        array('id' => 1, 'title' => 'Uprawa Rosiczki okrągłolistnej', 'views' => 1250, 'type' => 'species', 'type_icon' => '🌿'),
        array('id' => 2, 'title' => 'Jak zacząć przygodę z roślinami mięsożernymi', 'views' => 980, 'type' => 'guides', 'type_icon' => '📖'),
        array('id' => 3, 'title' => 'Muchołówka amerykańska - pielęgnacja', 'views' => 850, 'type' => 'post', 'type_icon' => '📝'),
        array('id' => 4, 'title' => 'Najłatwiejsze rośliny mięsożerne', 'views' => 720, 'type' => 'guides', 'type_icon' => '📖'),
        array('id' => 5, 'title' => 'Problemy z roślinami mięsożernymi', 'views' => 650, 'type' => 'post', 'type_icon' => '📝')
    );
    
    // Popularne wyszukiwania (mock data)
    $popular_searches = array('rosiczka', 'muchołówka', 'pielęgnacja', 'podłoże', 'zimowanie', 'nasiona', 'dzbanek');
    
    // Sprawdzenia systemu
    $php_version_ok = version_compare(PHP_VERSION, '7.4', '>=');
    $memory_limit = ini_get('memory_limit');
    $memory_ok = intval($memory_limit) >= 256;
    $uploads_dir = wp_upload_dir();
    $uploads_writable = is_writable($uploads_dir['basedir']);
    
    return array(
        'total_content' => $published_posts + $published_pages + $total_species + $total_guides,
        'published_posts' => $published_posts,
        'draft_posts' => $draft_posts,
        'published_pages' => $published_pages,
        'draft_pages' => $draft_pages,
        'total_species' => $total_species,
        'total_guides' => $total_guides,
        'easy_species' => $easy_species,
        'beginner_guides' => $beginner_guides,
        'species_completion' => $total_species > 0 ? round(($species_with_full_data / $total_species) * 100) : 0,
        'guides_completion' => $total_guides > 0 ? round(($guides_with_full_data / $total_guides) * 100) : 0,
        'last_post_date' => $last_post ? human_time_diff(strtotime($last_post[0]->post_date)) . ' temu' : 'Brak',
        'recent_content' => $recent_content_formatted,
        'popular_content' => $popular_content,
        'popular_searches' => $popular_searches,
        'php_version_ok' => $php_version_ok,
        'memory_ok' => $memory_ok,
        'memory_usage' => $memory_limit,
        'uploads_writable' => $uploads_writable
    );
}
/**
 * Oblicza ujednolicone dane SEO
 */
function carni24_calculate_unified_seo() {
    global $wpdb;
    
    // Podstawowe statystyki SEO
    $total_published = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM {$wpdb->posts} 
        WHERE post_status = 'publish' 
        AND post_type IN ('post', 'page', 'species', 'guides')
    ");
    
    $posts_without_title = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p 
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_title'
        WHERE p.post_status = 'publish' 
        AND p.post_type IN ('post', 'page', 'species', 'guides')
        AND (pm.meta_value IS NULL OR pm.meta_value = '')
    ");
    
    $posts_without_description = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p 
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_description'
        WHERE p.post_status = 'publish' 
        AND p.post_type IN ('post', 'page', 'species', 'guides')
        AND (pm.meta_value IS NULL OR pm.meta_value = '')
    ");
    
    $posts_without_og_image = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p 
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_og_image'
        WHERE p.post_status = 'publish' 
        AND p.post_type IN ('post', 'page', 'species', 'guides')
        AND (pm.meta_value IS NULL OR pm.meta_value = '' OR pm.meta_value = '0')
    ");
    
    // Oblicz scores
    $titles_score = $total_published > 0 ? round((($total_published - $posts_without_title) / $total_published) * 100) : 0;
    $descriptions_score = $total_published > 0 ? round((($total_published - $posts_without_description) / $total_published) * 100) : 0;
    $images_score = $total_published > 0 ? round((($total_published - $posts_without_og_image) / $total_published) * 100) : 0;
    $overall_score = round(($titles_score + $descriptions_score + $images_score) / 3);
    
    // Posty w pełni zoptymalizowane
    $posts_optimized = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p 
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_seo_title'
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_seo_description'
        WHERE p.post_status = 'publish' 
        AND p.post_type IN ('post', 'page', 'species', 'guides')
        AND pm1.meta_value != '' AND pm2.meta_value != ''
    ");
    
    // Najlepsze posty SEO
    $best_seo_posts = $wpdb->get_results("
        SELECT p.ID, p.post_title, p.post_type,
               CASE 
                   WHEN pm1.meta_value != '' AND pm2.meta_value != '' AND pm3.meta_value IS NOT NULL THEN 100
                   WHEN pm1.meta_value != '' AND pm2.meta_value != '' THEN 85
                   WHEN pm1.meta_value != '' OR pm2.meta_value != '' THEN 60
                   ELSE 30
               END as seo_score
        FROM {$wpdb->posts} p 
        LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_seo_title'
        LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_seo_description'
        LEFT JOIN {$wpdb->postmeta} pm3 ON p.ID = pm3.post_id AND pm3.meta_key = '_seo_og_image'
        WHERE p.post_status = 'publish' 
        AND p.post_type IN ('post', 'page', 'species', 'guides')
        ORDER BY seo_score DESC, p.post_date DESC
        LIMIT 5
    ");
    
    $best_seo_formatted = array();
    foreach ($best_seo_posts as $post) {
        $type_icons = array(
            'species' => '🌿',
            'guides' => '📖',
            'post' => '📝',
            'page' => '📄'
        );
        
        $best_seo_formatted[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'type' => $post->post_type,
            'type_icon' => $type_icons[$post->post_type] ?? '📄',
            'seo_score' => $post->seo_score
        );
    }
    
    // Problemy SEO (tylko przykładowe)
    $seo_issues = array();
    
    // Rekomendacje SEO
    $recommendations = array();
    
    if ($posts_without_title > 0) {
        $recommendations[] = array(
            'icon' => '📝',
            'title' => 'Uzupełnij tytuły SEO',
            'description' => "Masz {$posts_without_title} postów bez tytułów SEO. Uzupełnienie ich może znacznie poprawić pozycjonowanie.",
            'priority' => 'high',
            'priority_text' => 'Wysoki priorytet',
            'action_url' => admin_url('edit.php?meta_key=_seo_title&meta_value=&meta_compare=NOT EXISTS'),
            'action_text' => 'Napraw teraz'
        );
    }
    
    if ($posts_without_description > 5) {
        $recommendations[] = array(
            'icon' => '📄',
            'title' => 'Dodaj opisy SEO',
            'description' => "Opisy SEO poprawiają CTR w wynikach wyszukiwania. Masz {$posts_without_description} postów do uzupełnienia.",
            'priority' => 'medium',
            'priority_text' => 'Średni priorytet',
            'action_url' => admin_url('edit.php?meta_key=_seo_description&meta_value=&meta_compare=NOT EXISTS'),
            'action_text' => 'Dodaj opisy'
        );
    }
    
    // Top słowa kluczowe (mock data)
    $top_keywords = array(
        array('word' => 'rośliny mięsożerne', 'count' => 45, 'size' => 18),
        array('word' => 'muchołówka', 'count' => 32, 'size' => 16),
        array('word' => 'rosiczka', 'count' => 28, 'size' => 15),
        array('word' => 'pielęgnacja', 'count' => 25, 'size' => 14),
        array('word' => 'uprawa', 'count' => 22, 'size' => 13),
        array('word' => 'podłoże', 'count' => 18, 'size' => 12),
        array('word' => 'nasiona', 'count' => 15, 'size' => 11),
        array('word' => 'zimowanie', 'count' => 12, 'size' => 10)
    );
    
    // Luki w treści
    $content_gaps = array(
        array('topic' => 'Rośliny mięsożerne w terrarium', 'opportunity' => 8, 'difficulty' => 'Średnia'),
        array('topic' => 'Problemy chorobowe roślin mięsożernych', 'opportunity' => 6, 'difficulty' => 'Niska'),
        array('topic' => 'Rośliny mięsożerne dla dzieci', 'opportunity' => 5, 'difficulty' => 'Niska'),
        array('topic' => 'Fertilizacja roślin mięsożernych', 'opportunity' => 4, 'difficulty' => 'Wysoka')
    );
    
    return array(
        'overall_score' => $overall_score,
        'titles_score' => $titles_score,
        'descriptions_score' => $descriptions_score,
        'images_score' => $images_score,
        'posts_without_title' => $posts_without_title,
        'posts_without_description' => $posts_without_description,
        'posts_without_og_image' => $posts_without_og_image,
        'posts_optimized' => $posts_optimized,
        'best_seo_posts' => $best_seo_formatted,
        'seo_issues' => $seo_issues,
        'recommendations' => $recommendations,
        'top_keywords' => $top_keywords,
        'content_gaps' => $content_gaps
    );
}

/**
 * Usuwa domyślne widgety dashboard
 */
function carni24_remove_default_dashboard_widgets() {
    if (!current_user_can('manage_options')) {
        // Usuń więcej widgetów dla zwykłych użytkowników
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');
    }
}
add_action('wp_dashboard_setup', 'carni24_remove_default_dashboard_widgets', 20);

/**
 * Zmienia kolejność widgetów na dashboard
 */
function carni24_reorder_dashboard_widgets() {
    global $wp_meta_boxes;
    
    // Przeniesienie naszych widgetów na góre
    if (isset($wp_meta_boxes['dashboard']['normal']['core']['carni24_unified_stats_widget'])) {
        $unified_stats = $wp_meta_boxes['dashboard']['normal']['core']['carni24_unified_stats_widget'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['carni24_unified_stats_widget']);
        $wp_meta_boxes['dashboard']['normal']['high']['carni24_unified_stats_widget'] = $unified_stats;
    }
    
    if (isset($wp_meta_boxes['dashboard']['normal']['core']['carni24_unified_seo_widget'])) {
        $unified_seo = $wp_meta_boxes['dashboard']['normal']['core']['carni24_unified_seo_widget'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['carni24_unified_seo_widget']);
        $wp_meta_boxes['dashboard']['normal']['high']['carni24_unified_seo_widget'] = $unified_seo;
    }
}
add_action('wp_dashboard_setup', 'carni24_reorder_dashboard_widgets', 25);
/**
 * Dodaje CSS i JavaScript dla ujednoliconych widgetów
 */
function carni24_dashboard_widgets_css() {
    ?>
    <style>
    /* ===== PODSTAWOWE STYLE WIDGETÓW ===== */
    .carni24-unified-widget {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    /* ===== NAWIGACJA ZAKŁADEK ===== */
    .carni24-widget-tabs {
        display: flex;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-bottom: 1px solid #dee2e6;
        margin: -12px -12px 0 -12px;
        padding: 0;
    }
    
    .carni24-tab-btn {
        flex: 1;
        background: none;
        border: none;
        padding: 12px 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
    }
    
    .carni24-tab-btn:hover {
        background: rgba(255,255,255,0.5);
        color: #495057;
    }
    
    .carni24-tab-btn.active {
        background: #fff;
        color: #16a34a;
        border-bottom-color: #16a34a;
        font-weight: 600;
    }
    
    /* ===== ZAWARTOŚĆ ZAKŁADEK ===== */
    .carni24-widget-content {
        padding: 15px 0;
    }
    
    .carni24-tab-content {
        display: none;
    }
    
    .carni24-tab-content.active {
        display: block;
    }
    
    /* ===== STATYSTYKI ===== */
    .carni24-quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin: 15px 0;
    }
    
    .carni24-stat-item {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .carni24-stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .carni24-stat-item.featured {
        background: linear-gradient(135deg, #e8f5e8, #c8e6c8);
        border-color: #16a34a;
    }
    
    .carni24-stat-item.warning {
        background: linear-gradient(135deg, #fef3cd, #fde68a);
        border-color: #f59e0b;
        border-left: 4px solid #f59e0b;
    }
    
    .carni24-stat-item.success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-color: #059669;
        border-left: 4px solid #059669;
    }
    
    .carni24-stat-item.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-color: #3b82f6;
        border-left: 4px solid #3b82f6;
    }
    
    .carni24-stat-number {
        display: block;
        font-size: 24px;
        font-weight: bold;
        color: #16a34a;
        margin-bottom: 4px;
    }
    
    .carni24-stat-item.warning .carni24-stat-number {
        color: #f59e0b;
    }
    
    .carni24-stat-item.success .carni24-stat-number {
        color: #059669;
    }
    
    .carni24-stat-item.info .carni24-stat-number {
        color: #3b82f6;
    }
    
    .carni24-stat-label {
        display: block;
        font-size: 11px;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .carni24-stat-meta {
        display: block;
        font-size: 10px;
        color: #9ca3af;
        margin-top: 4px;
        font-style: italic;
    }
    
    /* ===== PROGRESS BARS ===== */
    .carni24-progress-section {
        margin: 20px 0;
    }
    
    .progress-item {
        margin-bottom: 15px;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }
    
    .progress-value {
        font-weight: 600;
        color: #16a34a;
    }
    
    .progress-bar {
        height: 8px;
        background: #f3f4f6;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #16a34a, #22c55e);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    /* ===== CONTENT GRIDS ===== */
    .content-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin: 15px 0;
    }
    
    .content-type-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.2s ease;
    }
    
    .content-type-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .content-type-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }
    
    .content-icon {
        font-size: 18px;
    }
    
    .content-title {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    
    .content-numbers {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .main-number {
        font-size: 20px;
        font-weight: bold;
        color: #16a34a;
    }
    
    .sub-number {
        font-size: 11px;
        color: #6b7280;
    }
    
    /* ===== LISTY TREŚCI ===== */
    .recent-content-list,
    .seo-content-list,
    .popular-content-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .recent-content-item,
    .seo-content-item,
    .popular-content-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        margin-bottom: 6px;
        background: #f8f9fa;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .recent-content-item:hover,
    .seo-content-item:hover,
    .popular-content-item:hover {
        background: #e9ecef;
    }
    
    .content-type-badge {
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .content-type-badge.post { color: #3b82f6; }
    .content-type-badge.page { color: #6b7280; }
    .content-type-badge.species { color: #059669; }
    .content-type-badge.guides { color: #f59e0b; }
    
    .content-title {
        flex: 1;
        font-size: 13px;
        text-decoration: none;
        color: #374151;
        font-weight: 500;
    }
    
    .content-title:hover {
        color: #16a34a;
    }
    
    .content-date,
    .content-views {
        font-size: 11px;
        color: #9ca3af;
        flex-shrink: 0;
    }
    
    .seo-score-badge {
        background: #16a34a;
        color: white;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 6px;
        border-radius: 10px;
        flex-shrink: 0;
    }
    
    /* ===== HEALTH CHECKS ===== */
    .system-health-section {
        margin: 20px 0;
    }
    
    .health-checks {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .health-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
    }
    
    .health-item.healthy {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }
    
    .health-item.warning {
        background: #fffbeb;
        border: 1px solid #fed7aa;
    }
    
    .health-item.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
    }
    
    .health-icon {
        font-size: 16px;
    }
    
    .health-text {
        flex: 1;
        font-weight: 500;
        color: #374151;
    }
    
    /* ===== SEARCH TERMS ===== */
    .search-terms {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin: 15px 0;
    }
    
    .search-term {
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 4px 10px;
        font-size: 12px;
        color: #6b7280;
        transition: all 0.2s ease;
    }
    
    .search-term:hover {
        background: #e5e7eb;
        color: #374151;
    }
    
    /* ===== ACTIONS ===== */
    .carni24-actions {
        display: flex;
        gap: 8px;
        margin-top: 20px;
        flex-wrap: wrap;
    }
    
    .button.button-primary {
        background: #16a34a !important;
        border-color: #15803d !important;
        text-shadow: none !important;
        box-shadow: 0 1px 0 #15803d !important;
    }
    
    .button.button-primary:hover {
        background: #15803d !important;
        border-color: #166534 !important;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 782px) {
        .carni24-widget-tabs {
            flex-wrap: wrap;
        }
        
        .carni24-tab-btn {
            flex: 1 1 50%;
            min-width: 50%;
        }
        
        .carni24-quick-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        
        .content-type-grid {
            grid-template-columns: 1fr;
        }
        
        .carni24-actions {
            flex-direction: column;
        }
        
        .carni24-actions .button {
            width: 100%;
            text-align: center;
        }
        
        .carni24-stat-item {
            padding: 12px;
        }
        
        .carni24-stat-number {
            font-size: 20px;
        }
    }
    </style>
    
    <script>
    // ===== VANILLA JAVASCRIPT DLA WIDGETÓW (bez duplikatów) ===== //
    
    document.addEventListener('DOMContentLoaded', function() {
        // Inicjalizacja zakładek
        initTabs();
        
        // Inicjalizacja przycisków odświeżania
        initRefreshButtons();
    });
    
    /**
     * Inicjalizacja systemu zakładek
     */
    function initTabs() {
        // Obsługa kliknięć na zakładki
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('carni24-tab-btn')) {
                const widget = e.target.closest('.carni24-unified-widget');
                const tabId = e.target.dataset.tab;
                
                // Usuń aktywne klasy ze wszystkich zakładek w tym widgecie
                widget.querySelectorAll('.carni24-tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                widget.querySelectorAll('.carni24-tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Dodaj aktywną klasę do klikniętej zakładki i odpowiedniej zawartości
                e.target.classList.add('active');
                const targetContent = widget.querySelector('#tab-' + tabId);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
                
                // Zapisz aktywną zakładkę w localStorage
                const widgetId = widget.id;
                localStorage.setItem('carni24_active_tab_' + widgetId, tabId);
            }
        });
        
        // Przywróć ostatnio aktywne zakładki
        document.querySelectorAll('.carni24-unified-widget').forEach(widget => {
            const widgetId = widget.id;
            const savedTab = localStorage.getItem('carni24_active_tab_' + widgetId);
            
            if (savedTab) {
                const tabBtn = widget.querySelector(`[data-tab="${savedTab}"]`);
                const tabContent = widget.querySelector(`#tab-${savedTab}`);
                
                if (tabBtn && tabContent) {
                    // Usuń wszystkie aktywne klasy
                    widget.querySelectorAll('.carni24-tab-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    widget.querySelectorAll('.carni24-tab-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    
                    // Ustaw aktywną zakładkę
                    tabBtn.classList.add('active');
                    tabContent.classList.add('active');
                }
            }
        });
    }
    
    /**
     * Inicjalizacja przycisków odświeżania
     */
    function initRefreshButtons() {
        // Dodaj przyciski odświeżania do nagłówków widgetów
        document.querySelectorAll('.carni24-unified-widget').forEach(widget => {
            const widgetBox = widget.closest('.postbox');
            const widgetHeader = widgetBox.querySelector('.postbox-header h2, .hndle');
            
            if (widgetHeader) {
                const refreshBtn = document.createElement('button');
                refreshBtn.type = 'button';
                refreshBtn.className = 'button button-small';
                refreshBtn.style.cssText = 'margin-left: 10px; font-size: 11px;';
                refreshBtn.innerHTML = '🔄';
                refreshBtn.title = 'Odśwież dane';
                
                refreshBtn.addEventListener('click', function() {
                    refreshWidgetData(widget.id);
                });
                
                widgetHeader.appendChild(refreshBtn);
            }
        });
    }
    
    /**
     * Odświeża dane widgetu
     */
    function refreshWidgetData(widgetId) {
        const widget = document.getElementById(widgetId);
        if (!widget) return;
        
        // Dodaj stan ładowania
        widget.classList.add('loading');
        
        // Symulacja ładowania (w rzeczywistości wysłałbyś request AJAX)
        setTimeout(() => {
            widget.classList.remove('loading');
            
            // Usuń cache
            if (widgetId === 'carni24-stats-widget') {
                deleteCacheForWidget('carni24_unified_stats');
            } else if (widgetId === 'carni24-seo-widget') {
                deleteCacheForWidget('carni24_unified_seo');
            }
            
            // Przeładuj stronę lub zaktualizuj dane
            location.reload();
        }, 1000);
    }
    
    /**
     * Usuwa cache dla widgetu
     */
    function deleteCacheForWidget(cacheKey) {
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_clear_widget_cache',
                cache_key: cacheKey,
                nonce: carni24_admin_nonce
            })
        });
    }
    
    // ===== FUNKCJE GLOBALNE DLA PRZYCISKÓW (BEZ DUPLIKATÓW AJAX) ===== //
    
    /**
     * Odświeża wszystkie statystyki
     */
    function carni24RefreshStats() {
        const widget = document.getElementById('carni24-stats-widget');
        if (widget) {
            refreshWidgetData('carni24-stats-widget');
        }
    }
    
    /**
     * Filtruje problemy SEO
     */
    function carni24FilterSEOIssues() {
        const issueTypeFilter = document.getElementById('seo-issue-type-filter');
        const postTypeFilter = document.getElementById('seo-post-type-filter');
        const issuesList = document.getElementById('seo-issues-list');
        
        if (!issueTypeFilter || !postTypeFilter || !issuesList) return;
        
        const selectedIssueType = issueTypeFilter.value;
        const selectedPostType = postTypeFilter.value;
        
        const issues = issuesList.querySelectorAll('.seo-issue-item');
        
        issues.forEach(issue => {
            const issueType = issue.dataset.issueType;
            const postType = issue.dataset.postType;
            
            let show = true;
            
            if (selectedIssueType && issueType !== selectedIssueType) {
                show = false;
            }
            
            if (selectedPostType && postType !== selectedPostType) {
                show = false;
            }
            
            issue.style.display = show ? 'block' : 'none';
        });
    }
    
    /**
     * Oznacza problem jako rozwiązany - używa AJAX z admin-handlers.php
     */
    function carni24MarkIssueResolved(postId, issueType) {
        if (!confirm('Czy na pewno chcesz oznaczyć ten problem jako rozwiązany?')) {
            return;
        }
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_mark_seo_issue_resolved',
                post_id: postId,
                issue_type: issueType,
                nonce: carni24_admin_nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Problem został oznaczony jako rozwiązany', 'success');
            } else {
                showNotification('Błąd podczas oznaczania problemu', 'error');
            }
        })
        .catch(error => {
            showNotification('Błąd połączenia', 'error');
        });
    }
    
    /**
     * Masowa naprawa problemów SEO - używa AJAX z admin-handlers.php
     */
    function carni24BulkFixSEO(type) {
        if (!confirm(`Czy na pewno chcesz automatycznie wygenerować brakujące ${type === 'title' ? 'tytuły' : 'opisy'} SEO?`)) {
            return;
        }
        
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Generowanie...';
        button.disabled = true;
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_bulk_fix_seo',
                fix_type: type,
                nonce: carni24_admin_nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`Wygenerowano ${data.data.count} ${type === 'title' ? 'tytułów' : 'opisów'} SEO`, 'success');
                
                // Odśwież widget po 2 sekundach
                setTimeout(() => {
                    refreshWidgetData('carni24-seo-widget');
                }, 2000);
            } else {
                showNotification('Błąd podczas generowania', 'error');
            }
        })
        .catch(error => {
            showNotification('Błąd połączenia', 'error');
        })
        .finally(() => {
            button.textContent = originalText;
            button.disabled = false;
        });
    }
    
    /**
     * Uruchamia audyt SEO - używa AJAX z admin-handlers.php
     */
    function carni24RunSEOAudit() {
        showNotification('Audyt SEO został uruchomiony w tle', 'info');
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_run_seo_audit',
                nonce: carni24_admin_nonce
            })
        });
    }
    
    /**
     * Generuje mapy stron - używa AJAX z admin-handlers.php
     */
    function carni24GenerateSitemaps() {
        showNotification('Generowanie map stron...', 'info');
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_generate_sitemaps',
                nonce: carni24_admin_nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Mapy stron zostały wygenerowane', 'success');
            } else {
                showNotification('Błąd podczas generowania map stron', 'error');
            }
        });
    }
    
    /**
     * Analizuje słowa kluczowe - używa AJAX z admin-handlers.php
     */
    function carni24AnalyzeKeywords() {
        showNotification('Analiza słów kluczowych...', 'info');
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_analyze_keywords',
                nonce: carni24_admin_nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Analiza słów kluczowych zakończona', 'success');
            } else {
                showNotification('Błąd podczas analizy słów kluczowych', 'error');
            }
        });
    }
    
    /**
     * Sprawdza linki wewnętrzne - używa AJAX z admin-handlers.php
     */
    function carni24CheckInternalLinks() {
        showNotification('Sprawdzanie linków wewnętrznych...', 'info');
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_check_internal_links',
                nonce: carni24_admin_nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Sprawdzanie linków zakończone', 'success');
            } else {
                showNotification('Błąd podczas sprawdzania linków', 'error');
            }
        });
    }
    
    /**
     * Pokazuje powiadomienie
     */
    function showNotification(message, type = 'info') {
        // Usuń poprzednie powiadomienia
        const existingNotifications = document.querySelectorAll('.carni24-notification');
        existingNotifications.forEach(n => n.remove());
        
        // Utwórz nowe powiadomienie
        const notification = document.createElement('div');
        notification.className = `carni24-notification carni24-notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 32px;
            right: 20px;
            background: ${type === 'success' ? '#d1fae5' : type === 'error' ? '#fee2e2' : '#dbeafe'};
            color: ${type === 'success' ? '#065f46' : type === 'error' ? '#991b1b' : '#1e40af'};
            border: 1px solid ${type === 'success' ? '#a7f3d0' : type === 'error' ? '#fecaca' : '#bfdbfe'};
            border-radius: 6px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Usuń po 4 sekundach
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
    
    // Dodaj style dla animacji powiadomień
    const notificationStyles = document.createElement('style');
    notificationStyles.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #16a34a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(notificationStyles);
    
    // Ustaw globalną zmienną dla nonce
    window.carni24_admin_nonce = '<?php echo wp_create_nonce("carni24_dashboard_nonce"); ?>';
    </script>
    <?php
}
add_action('admin_head', 'carni24_dashboard_widgets_css');