<?php
/**
 * Carni24 Dashboard Widgets - Oczyszczona wersja
 * Podstawowe widgety dla dashboard WordPress Admin (bez duplikatów SEO)
 * 
 * @package Carni24
 * @subpackage Admin
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dodaje podstawowe widgety do dashboard
 */
function carni24_add_dashboard_widgets() {
    wp_add_dashboard_widget(
        'carni24_stats_widget',
        '📊 Statystyki Carni24',
        'carni24_stats_widget_callback'
    );
    
    wp_add_dashboard_widget(
        'carni24_recent_activity_widget',
        '🔄 Ostatnia aktywność',
        'carni24_recent_activity_widget_callback'
    );
    
    wp_add_dashboard_widget(
        'carni24_quick_tools_widget',
        '🛠️ Szybkie narzędzia',
        'carni24_quick_tools_widget_callback'
    );
}
add_action('wp_dashboard_setup', 'carni24_add_dashboard_widgets');

/**
 * Widget statystyk podstawowych
 */
function carni24_stats_widget_callback() {
    // Cache dla wydajności
    $stats = get_transient('carni24_basic_stats');
    if ($stats === false) {
        $stats = carni24_calculate_basic_stats();
        set_transient('carni24_basic_stats', $stats, 10 * MINUTE_IN_SECONDS);
    }
    
    ?>
    <div class="carni24-stats-widget">
        <div class="stats-grid">
            <?php foreach ($stats as $stat): ?>
                <div class="stat-item">
                    <div class="stat-number"><?= esc_html($stat['count']) ?></div>
                    <div class="stat-label"><?= esc_html($stat['label']) ?></div>
                    <div class="stat-trend <?= esc_attr($stat['trend']) ?>">
                        <?= $stat['trend'] === 'up' ? '📈' : ($stat['trend'] === 'down' ? '📉' : '➡️') ?>
                        <?= esc_html($stat['change']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="stats-actions">
            <button type="button" class="button button-secondary" onclick="carni24RefreshStats()">
                🔄 Odśwież
            </button>
        </div>
    </div>
    <?php
}

/**
 * Widget ostatniej aktywności
 */
function carni24_recent_activity_widget_callback() {
    $recent_activity = carni24_get_recent_activity();
    
    ?>
    <div class="carni24-activity-widget">
        <?php if (empty($recent_activity)): ?>
            <div class="no-activity">
                <div class="no-activity-icon">😴</div>
                <p>Brak ostatniej aktywności</p>
            </div>
        <?php else: ?>
            <ul class="activity-list">
                <?php foreach ($recent_activity as $activity): ?>
                    <li class="activity-item activity-<?= esc_attr($activity['type']) ?>">
                        <div class="activity-icon"><?= $activity['icon'] ?></div>
                        <div class="activity-content">
                            <div class="activity-title"><?= esc_html($activity['title']) ?></div>
                            <div class="activity-meta">
                                <span class="activity-time"><?= esc_html($activity['time']) ?></span>
                                <span class="activity-author"><?= esc_html($activity['author']) ?></span>
                            </div>
                        </div>
                        <?php if ($activity['action_url']): ?>
                            <div class="activity-action">
                                <a href="<?= esc_url($activity['action_url']) ?>" class="button button-small">
                                    <?= esc_html($activity['action_text']) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Widget szybkich narzędzi
 */
function carni24_quick_tools_widget_callback() {
    ?>
    <div class="carni24-tools-widget">
        <div class="tools-grid">
            <a href="<?= admin_url('post-new.php') ?>" class="tool-button tool-primary">
                <span class="tool-icon">📝</span>
                <span class="tool-title">Nowy wpis</span>
            </a>
            
            <a href="<?= admin_url('post-new.php?post_type=species') ?>" class="tool-button">
                <span class="tool-icon">🌱</span>
                <span class="tool-title">Nowy gatunek</span>
            </a>
            
            <a href="<?= admin_url('post-new.php?post_type=guides') ?>" class="tool-button">
                <span class="tool-icon">📖</span>
                <span class="tool-title">Nowy poradnik</span>
            </a>
            
            <a href="<?= admin_url('upload.php') ?>" class="tool-button">
                <span class="tool-icon">📷</span>
                <span class="tool-title">Media</span>
            </a>
            
            <button type="button" class="tool-button" onclick="carni24ClearCache()">
                <span class="tool-icon">🧹</span>
                <span class="tool-title">Wyczyść cache</span>
            </button>
            
            <button type="button" class="tool-button" onclick="carni24BackupContent()">
                <span class="tool-icon">💾</span>
                <span class="tool-title">Backup</span>
            </button>
        </div>
    </div>
    <?php
}

/**
 * Oblicza podstawowe statystyki
 */
function carni24_calculate_basic_stats() {
    $stats = array();
    
    // Wpisy
    $posts_count = wp_count_posts('post');
    $last_month_posts = carni24_count_posts_last_month('post');
    $stats[] = array(
        'count' => number_format($posts_count->publish),
        'label' => 'Wpisy',
        'trend' => $last_month_posts > 0 ? 'up' : 'stable',
        'change' => '+' . $last_month_posts . ' w tym miesiącu'
    );
    
    // Gatunki
    $species_count = wp_count_posts('species');
    $last_month_species = carni24_count_posts_last_month('species');
    $stats[] = array(
        'count' => number_format($species_count->publish),
        'label' => 'Gatunki',
        'trend' => $last_month_species > 0 ? 'up' : 'stable',
        'change' => '+' . $last_month_species . ' w tym miesiącu'
    );
    
    // Poradniki
    $guides_count = wp_count_posts('guides');
    $last_month_guides = carni24_count_posts_last_month('guides');
    $stats[] = array(
        'count' => number_format($guides_count->publish),
        'label' => 'Poradniki',
        'trend' => $last_month_guides > 0 ? 'up' : 'stable',
        'change' => '+' . $last_month_guides . ' w tym miesiącu'
    );
    
    // Media
    $media_count = wp_count_attachments();
    $stats[] = array(
        'count' => number_format(array_sum((array)$media_count)),
        'label' => 'Pliki media',
        'trend' => 'stable',
        'change' => 'Wszystkie typy'
    );
    
    return $stats;
}

/**
 * Pobiera ostatnią aktywność
 */
function carni24_get_recent_activity() {
    $activity = array();
    
    // Ostatnie wpisy
    $recent_posts = get_posts(array(
        'numberposts' => 3,
        'post_status' => array('publish', 'draft'),
        'post_type' => array('post', 'species', 'guides'),
        'orderby' => 'modified',
        'order' => 'DESC'
    ));
    
    foreach ($recent_posts as $post) {
        $post_type_obj = get_post_type_object($post->post_type);
        $icon_map = array(
            'post' => '📝',
            'species' => '🌱',
            'guides' => '📖'
        );
        
        $activity[] = array(
            'type' => 'post',
            'icon' => $icon_map[$post->post_type] ?? '📄',
            'title' => $post->post_title,
            'time' => human_time_diff(strtotime($post->post_modified)) . ' temu',
            'author' => get_the_author_meta('display_name', $post->post_author),
            'action_url' => get_edit_post_link($post->ID),
            'action_text' => 'Edytuj'
        );
    }
    
    // Ostatnie media
    $recent_media = get_posts(array(
        'numberposts' => 2,
        'post_type' => 'attachment',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    foreach ($recent_media as $media) {
        $activity[] = array(
            'type' => 'media',
            'icon' => '📷',
            'title' => 'Dodano: ' . $media->post_title,
            'time' => human_time_diff(strtotime($media->post_date)) . ' temu',
            'author' => get_the_author_meta('display_name', $media->post_author),
            'action_url' => admin_url('upload.php?item=' . $media->ID),
            'action_text' => 'Zobacz'
        );
    }
    
    // Sortuj według daty
    usort($activity, function($a, $b) {
        return strcmp($b['time'], $a['time']);
    });
    
    return array_slice($activity, 0, 5);
}

/**
 * Liczy wpisy z ostatniego miesiąca
 */
function carni24_count_posts_last_month($post_type) {
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'date_query' => array(
            array(
                'after' => '1 month ago',
                'before' => 'now',
                'inclusive' => true,
            ),
        ),
    );
    
    $posts = get_posts($args);
    return count($posts);
}

/**
 * Usuwa domyślne widgety WordPress
 */
function carni24_remove_default_dashboard_widgets() {
    if (!current_user_can('manage_options')) {
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
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
    if (isset($wp_meta_boxes['dashboard']['normal']['core']['carni24_stats_widget'])) {
        $stats_widget = $wp_meta_boxes['dashboard']['normal']['core']['carni24_stats_widget'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['carni24_stats_widget']);
        $wp_meta_boxes['dashboard']['normal']['high']['carni24_stats_widget'] = $stats_widget;
    }
}
add_action('wp_dashboard_setup', 'carni24_reorder_dashboard_widgets', 25);

/**
 * Dodaje CSS i JavaScript dla widgetów
 */
function carni24_dashboard_widgets_assets() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'dashboard') {
        return;
    }
    
    ?>
    <style>
    /* ===== PODSTAWOWE STYLE WIDGETÓW ===== */
    .carni24-stats-widget,
    .carni24-activity-widget,
    .carni24-tools-widget {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    /* ===== WIDGET STATYSTYK ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #0073aa;
    }
    
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #23282d;
        margin-bottom: 5px;
    }
    
    .stat-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 8px;
    }
    
    .stat-trend {
        font-size: 11px;
        color: #999;
    }
    
    .stat-trend.up {
        color: #46b450;
    }
    
    .stat-trend.down {
        color: #dc3232;
    }
    
    .stats-actions {
        text-align: center;
        padding-top: 10px;
        border-top: 1px solid #e1e5e9;
    }
    
    /* ===== WIDGET AKTYWNOŚCI ===== */
    .activity-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        font-size: 18px;
        margin-right: 12px;
        flex-shrink: 0;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        font-weight: 500;
        color: #23282d;
        margin-bottom: 4px;
    }
    
    .activity-meta {
        font-size: 12px;
        color: #666;
    }
    
    .activity-action {
        margin-left: 10px;
    }
    
    .no-activity {
        text-align: center;
        padding: 30px 20px;
        color: #666;
    }
    
    .no-activity-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }
    
    /* ===== WIDGET NARZĘDZI ===== */
    .tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 10px;
    }
    
    .tool-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px 10px;
        background: #f8f9fa;
        border: 1px solid #e1e5e9;
        border-radius: 6px;
        text-decoration: none;
        color: #23282d;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .tool-button:hover {
        background: #fff;
        border-color: #0073aa;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        color: #0073aa;
        text-decoration: none;
    }
    
    .tool-button:focus {
        outline: 2px solid #0073aa;
        outline-offset: 2px;
    }
    
    .tool-primary {
        background: #0073aa;
        color: #fff;
        border-color: #005a87;
    }
    
    .tool-primary:hover {
        background: #005a87;
        color: #fff;
    }
    
    .tool-icon {
        font-size: 20px;
        margin-bottom: 6px;
    }
    
    .tool-title {
        font-size: 12px;
        font-weight: 500;
        text-align: center;
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 782px) {
        .stats-grid {
            grid-template-columns: 1fr 1fr;
        }
        
        .tools-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .activity-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .activity-action {
            margin-left: 0;
            align-self: flex-end;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .tools-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    </style>

    <script>
    // ===== FUNKCJE NARZĘDZI ===== //
    function carni24RefreshStats() {
        const button = event.target;
        const originalText = button.textContent;
        
        button.textContent = '⏳ Odświeżanie...';
        button.disabled = true;
        
        // Wyczyść cache
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_clear_widget_cache',
                cache_key: 'carni24_basic_stats',
                nonce: '<?= wp_create_nonce('carni24_admin') ?>'
            })
        })
        .then(() => {
            setTimeout(() => {
                location.reload();
            }, 1000);
        })
        .catch(() => {
            button.textContent = originalText;
            button.disabled = false;
        });
    }
    
    function carni24ClearCache() {
        if (!confirm('Czy na pewno chcesz wyczyścić wszystkie cache?')) {
            return;
        }
        
        const button = event.target;
        const originalText = button.querySelector('.tool-title').textContent;
        
        button.querySelector('.tool-title').textContent = 'Czyszczenie...';
        button.disabled = true;
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_clear_all_cache',
                nonce: '<?= wp_create_nonce('carni24_admin') ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.querySelector('.tool-title').textContent = 'Wyczyszczono ✓';
                setTimeout(() => {
                    button.querySelector('.tool-title').textContent = originalText;
                    button.disabled = false;
                }, 2000);
            }
        })
        .catch(() => {
            button.querySelector('.tool-title').textContent = originalText;
            button.disabled = false;
        });
    }
    
    function carni24BackupContent() {
        if (!confirm('Czy chcesz utworzyć backup treści?')) {
            return;
        }
        
        const button = event.target;
        const originalText = button.querySelector('.tool-title').textContent;
        
        button.querySelector('.tool-title').textContent = 'Backup...';
        button.disabled = true;
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'carni24_create_backup',
                nonce: '<?= wp_create_nonce('carni24_admin') ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.querySelector('.tool-title').textContent = 'Utworzono ✓';
                setTimeout(() => {
                    button.querySelector('.tool-title').textContent = originalText;
                    button.disabled = false;
                }, 3000);
            }
        })
        .catch(() => {
            button.querySelector('.tool-title').textContent = originalText;
            button.disabled = false;
        });
    }
    </script>
    <?php
}
add_action('admin_head-index.php', 'carni24_dashboard_widgets_assets');