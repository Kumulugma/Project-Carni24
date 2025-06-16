<?php
/**
 * Carni24 Dashboard Widgets - ENHANCED
 * Widgety dla dashboard WordPress Admin
 * 
 * @package Carni24
 * @subpackage Admin
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dodaje custom widgety do dashboard
 */
function carni24_add_dashboard_widgets() {
    wp_add_dashboard_widget(
        'carni24_overview_widget',
        '🌿 Przegląd Carni24',
        'carni24_overview_widget_callback'
    );
    
    wp_add_dashboard_widget(
        'carni24_quick_stats_widget',
        '📊 Szybkie statystyki',
        'carni24_quick_stats_widget_callback'
    );
    
    wp_add_dashboard_widget(
        'carni24_recent_activity_widget',
        '🔄 Ostatnia aktywność',
        'carni24_recent_activity_widget_callback'
    );
    
    wp_add_dashboard_widget(
        'carni24_seo_overview_widget',
        '🔍 Przegląd SEO',
        'carni24_seo_overview_widget_callback'
    );
}
add_action('wp_dashboard_setup', 'carni24_add_dashboard_widgets');

/**
 * Widget przeglądu ogólnego
 */
function carni24_overview_widget_callback() {
    $theme_version = CARNI24_VERSION;
    $wp_version = get_bloginfo('version');
    $active_plugins = count(get_option('active_plugins'));
    $current_theme = wp_get_theme();
    
    ?>
    <div class="carni24-dashboard-widget">
        <div class="carni24-quick-stats">
            <div class="carni24-stat-item">
                <span class="carni24-stat-number"><?= $theme_version ?></span>
                <span class="carni24-stat-label">Wersja motywu</span>
            </div>
            <div class="carni24-stat-item">
                <span class="carni24-stat-number"><?= $wp_version ?></span>
                <span class="carni24-stat-label">WordPress</span>
            </div>
            <div class="carni24-stat-item">
                <span class="carni24-stat-number"><?= $active_plugins ?></span>
                <span class="carni24-stat-label">Aktywne wtyczki</span>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
            <h4 style="margin: 0 0 10px; color: #374151;">🚀 Szybkie akcje</h4>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="<?= admin_url('post-new.php?post_type=species') ?>" class="button button-primary">
                    + Nowy gatunek
                </a>
                <a href="<?= admin_url('post-new.php?post_type=guides') ?>" class="button button-secondary">
                    + Nowy poradnik
                </a>
                <a href="<?= admin_url('themes.php?page=carni24-theme-options') ?>" class="button button-secondary">
                    ⚙️ Ustawienia
                </a>
            </div>
        </div>
        
        <?php
        // Sprawdź aktualizacje motywu
        $theme_updates = carni24_check_theme_updates();
        if ($theme_updates):
        ?>
        <div style="margin-top: 15px; padding: 12px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
            <strong>⚠️ Dostępna aktualizacja motywu!</strong><br>
            <small>Dostępna jest nowsza wersja motywu Carni24. <a href="#">Aktualizuj teraz</a></small>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Widget szybkich statystyk
 */
function carni24_quick_stats_widget_callback() {
    // Pobierz statystyki
    $species_count = wp_count_posts('species');
    $guides_count = wp_count_posts('guides');
    $total_posts = wp_count_posts('post');
    $total_pages = wp_count_posts('page');
    
    // Pobierz statystyki wyświetleń
    global $wpdb;
    $total_views = $wpdb->get_var("
        SELECT SUM(meta_value) 
        FROM {$wpdb->postmeta} 
        WHERE meta_key = 'post_views_count'
    ") ?: 0;
    
    $popular_species = $wpdb->get_results("
        SELECT p.ID, p.post_title, pm.meta_value as views
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'species' 
        AND p.post_status = 'publish'
        AND pm.meta_key = 'post_views_count'
        ORDER BY CAST(pm.meta_value AS UNSIGNED) DESC
        LIMIT 5
    ");
    
    ?>
    <div class="carni24-dashboard-widget">
        <div class="carni24-quick-stats">
            <div class="carni24-stat-item">
                <span class="carni24-stat-number"><?= number_format($species_count->publish) ?></span>
                <span class="carni24-stat-label">Gatunków</span>
            </div>
            <div class="carni24-stat-item">
                <span class="carni24-stat-number"><?= number_format($guides_count->publish) ?></span>
                <span class="carni24-stat-label">Poradników</span>
            </div>
            <div class="carni24-stat-item">
                <span class="carni24-stat-number"><?= number_format($total_posts->publish) ?></span>
                <span class="carni24-stat-label">Artykułów</span>
            </div>
            <div class="carni24-stat-item">
                <span class="carni24-stat-number"><?= number_format($total_views) ?></span>
                <span class="carni24-stat-label">Wyświetleń</span>
            </div>
        </div>
        
        <?php if ($popular_species): ?>
        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
            <h4 style="margin: 0 0 10px; color: #374151;">🔥 Najpopularniejsze gatunki</h4>
            <ul style="margin: 0; padding: 0; list-style: none;">
                <?php foreach ($popular_species as $species): ?>
                <li style="display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid #f3f4f6;">
                    <a href="<?= get_edit_post_link($species->ID) ?>" style="text-decoration: none; color: #374151; font-weight: 500;">
                        <?= esc_html($species->post_title) ?>
                    </a>
                    <span style="background: #f3f4f6; color: #6b7280; padding: 2px 8px; border-radius: 12px; font-size: 12px;">
                        <?= number_format($species->views) ?> wyświetleń
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
 * Widget ostatniej aktywności
 */
function carni24_recent_activity_widget_callback() {
    // Pobierz ostatnie posty
    $recent_posts = get_posts(array(
        'post_type' => array('species', 'guides', 'post'),
        'numberposts' => 8,
        'post_status' => array('publish', 'draft'),
        'orderby' => 'modified',
        'order' => 'DESC'
    ));
    
    // Pobierz ostatnio zmodyfikowane
    global $wpdb;
    $recent_activity = $wpdb->get_results("
        SELECT p.ID, p.post_title, p.post_type, p.post_status, p.post_modified
        FROM {$wpdb->posts} p
        WHERE p.post_type IN ('species', 'guides', 'post', 'page')
        AND p.post_status IN ('publish', 'draft', 'pending')
        ORDER BY p.post_modified DESC
        LIMIT 10
    ");
    
    ?>
    <div class="carni24-dashboard-widget">
        <?php if ($recent_activity): ?>
        <ul style="margin: 0; padding: 0; list-style: none;">
            <?php foreach ($recent_activity as $item): 
                $post_type_obj = get_post_type_object($item->post_type);
                $post_type_name = $post_type_obj ? $post_type_obj->labels->singular_name : $item->post_type;
                $time_diff = human_time_diff(strtotime($item->post_modified), current_time('timestamp'));
                
                $status_colors = array(
                    'publish' => '#059669',
                    'draft' => '#6b7280',
                    'pending' => '#f59e0b'
                );
                $status_color = $status_colors[$item->post_status] ?? '#6b7280';
                
                $type_icons = array(
                    'species' => '🌿',
                    'guides' => '📖',
                    'post' => '📝',
                    'page' => '📄'
                );
                $type_icon = $type_icons[$item->post_type] ?? '📄';
            ?>
            <li style="display: flex; align-items: center; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="margin-right: 8px; font-size: 16px;"><?= $type_icon ?></span>
                <div style="flex: 1; min-width: 0;">
                    <div>
                        <a href="<?= get_edit_post_link($item->ID) ?>" 
                           style="text-decoration: none; color: #374151; font-weight: 500;">
                            <?= esc_html(wp_trim_words($item->post_title, 6)) ?>
                        </a>
                        <span style="color: <?= $status_color ?>; font-size: 12px; margin-left: 8px;">
                            <?= ucfirst($item->post_status) ?>
                        </span>
                    </div>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">
                        <?= $post_type_name ?> • <?= $time_diff ?> temu
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <div style="margin-top: 15px; text-align: center;">
            <a href="<?= admin_url('edit.php') ?>" class="button button-secondary">
                Zobacz wszystkie posty
            </a>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: #6b7280; font-style: italic;">
            Brak ostatniej aktywności
        </p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Widget przeglądu SEO
 */
function carni24_seo_overview_widget_callback() {
    // Sprawdź posty bez SEO
    global $wpdb;
    
    $posts_without_meta_title = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_title'
        WHERE p.post_type IN ('species', 'guides', 'post', 'page')
        AND p.post_status = 'publish'
        AND (pm.meta_value IS NULL OR pm.meta_value = '')
    ");
    
    $posts_without_meta_description = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_description'
        WHERE p.post_type IN ('species', 'guides', 'post', 'page')
        AND p.post_status = 'publish'
        AND (pm.meta_value IS NULL OR pm.meta_value = '')
    ");
    
    $posts_without_og_image = $wpdb->get_var("
        SELECT COUNT(DISTINCT p.ID) 
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_seo_og_image'
        WHERE p.post_type IN ('species', 'guides', 'post', 'page')
        AND p.post_status = 'publish'
        AND (pm.meta_value IS NULL OR pm.meta_value = '' OR pm.meta_value = '0')
    ");
    
    // Sprawdź posty z najlepszym i najgorszym SEO
    $best_seo_posts = $wpdb->get_results("
        SELECT p.ID, p.post_title, p.post_type
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_seo_title'
        INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_seo_description'
        WHERE p.post_type IN ('species', 'guides', 'post', 'page')
        AND p.post_status = 'publish'
        AND pm1.meta_value != ''
        AND pm2.meta_value != ''
        AND LENGTH(pm1.meta_value) BETWEEN 50 AND 60
        AND LENGTH(pm2.meta_value) BETWEEN 150 AND 160
        ORDER BY p.post_modified DESC
        LIMIT 5
    ");
    
    $total_published = $wpdb->get_var("
        SELECT COUNT(*) 
        FROM {$wpdb->posts} 
        WHERE post_type IN ('species', 'guides', 'post', 'page')
        AND post_status = 'publish'
    ");
    
    $seo_completion = $total_published > 0 ? 
        round((($total_published - $posts_without_meta_title) / $total_published) * 100) : 0;
    
    ?>
    <div class="carni24-dashboard-widget">
        <!-- SEO Progress Bar -->
        <div style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <h4 style="margin: 0; color: #374151;">Kompletność SEO</h4>
                <span style="font-weight: bold; color: <?= $seo_completion >= 80 ? '#059669' : ($seo_completion >= 60 ? '#d97706' : '#dc2626') ?>;">
                    <?= $seo_completion ?>%
                </span>
            </div>
            <div style="background: #f3f4f6; height: 8px; border-radius: 4px; overflow: hidden;">
                <div style="background: <?= $seo_completion >= 80 ? '#059669' : ($seo_completion >= 60 ? '#d97706' : '#dc2626') ?>; 
                     height: 100%; width: <?= $seo_completion ?>%; transition: width 0.3s ease;"></div>
            </div>
        </div>
        
        <!-- SEO Issues -->
        <div class="carni24-quick-stats">
            <div class="carni24-stat-item" style="<?= $posts_without_meta_title > 0 ? 'border-left: 3px solid #dc2626;' : '' ?>">
                <span class="carni24-stat-number" style="color: <?= $posts_without_meta_title > 0 ? '#dc2626' : '#059669' ?>;">
                    <?= $posts_without_meta_title ?>
                </span>
                <span class="carni24-stat-label">Bez tytułu SEO</span>
            </div>
            
            <div class="carni24-stat-item" style="<?= $posts_without_meta_description > 0 ? 'border-left: 3px solid #dc2626;' : '' ?>">
                <span class="carni24-stat-number" style="color: <?= $posts_without_meta_description > 0 ? '#dc2626' : '#059669' ?>;">
                    <?= $posts_without_meta_description ?>
                </span>
                <span class="carni24-stat-label">Bez opisu SEO</span>
            </div>
            
            <div class="carni24-stat-item" style="<?= $posts_without_og_image > 0 ? 'border-left: 3px solid #f59e0b;' : '' ?>">
                <span class="carni24-stat-number" style="color: <?= $posts_without_og_image > 0 ? '#f59e0b' : '#059669' ?>;">
                    <?= $posts_without_og_image ?>
                </span>
                <span class="carni24-stat-label">Bez obrazu OG</span>
            </div>
        </div>
        
        <!-- SEO Actions -->
        <?php if ($posts_without_meta_title > 0 || $posts_without_meta_description > 0): ?>
        <div style="margin-top: 15px; padding: 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 4px;">
            <strong>⚠️ Wymagana uwaga SEO</strong><br>
            <small>Niektóre posty wymagają uzupełnienia danych SEO.</small>
            <div style="margin-top: 8px;">
                <a href="<?= admin_url('edit.php?meta_key=_seo_title&meta_value=&meta_compare=NOT EXISTS') ?>" 
                   class="button button-small">
                    Napraw SEO
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Best SEO Posts -->
        <?php if ($best_seo_posts): ?>
        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb;">
            <h4 style="margin: 0 0 10px; color: #374151;">✅ Najlepsze SEO</h4>
            <ul style="margin: 0; padding: 0; list-style: none;">
                <?php foreach ($best_seo_posts as $post): 
                    $type_icons = array(
                        'species' => '🌿',
                        'guides' => '📖',
                        'post' => '📝',
                        'page' => '📄'
                    );
                    $icon = $type_icons[$post->post_type] ?? '📄';
                ?>
                <li style="display: flex; align-items: center; padding: 5px 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="margin-right: 8px;"><?= $icon ?></span>
                    <a href="<?= get_edit_post_link($post->ID) ?>" 
                       style="text-decoration: none; color: #059669; font-weight: 500; flex: 1;">
                        <?= esc_html(wp_trim_words($post->post_title, 6)) ?>
                    </a>
                    <span style="background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                        OPTIMAL
                    </span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <!-- Quick SEO Tools -->
        <div style="margin-top: 15px; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 15px;">
            <a href="<?= admin_url('themes.php?page=carni24-theme-options#seo') ?>" class="button button-secondary">
                🔧 Ustawienia SEO
            </a>
        </div>
    </div>
    <?php
}

/**
 * Sprawdza dostępność aktualizacji motywu
 */
function carni24_check_theme_updates() {
    // Placeholder - w rzeczywistej implementacji sprawdzałby aktualizacje
    $current_version = CARNI24_VERSION;
    $latest_version = get_transient('carni24_latest_version');
    
    if (!$latest_version) {
        // Symulacja sprawdzenia aktualizacji
        $latest_version = $current_version; // W rzeczywistości: sprawdź z serwera
        set_transient('carni24_latest_version', $latest_version, 12 * HOUR_IN_SECONDS);
    }
    
    return version_compare($latest_version, $current_version, '>');
}

/**
 * Usuwa domyślne widgety WordPress (opcjonalnie)
 */
function carni24_remove_default_dashboard_widgets() {
    // Tylko dla zwykłych użytkowników, administratorzy zachowują wszystkie widgety
    if (!current_user_can('manage_options')) {
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
    if (isset($wp_meta_boxes['dashboard']['normal']['core']['carni24_overview_widget'])) {
        $carni24_overview = $wp_meta_boxes['dashboard']['normal']['core']['carni24_overview_widget'];
        unset($wp_meta_boxes['dashboard']['normal']['core']['carni24_overview_widget']);
        $wp_meta_boxes['dashboard']['normal']['high']['carni24_overview_widget'] = $carni24_overview;
    }
}
add_action('wp_dashboard_setup', 'carni24_reorder_dashboard_widgets', 25);

/**
 * Dodaje custom CSS dla dashboard widgets
 */
function carni24_dashboard_widgets_css() {
    ?>
    <style>
    .carni24-dashboard-widget {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .carni24-dashboard-widget h3 {
        color: #16a34a !important;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }
    
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
    }
    
    .carni24-stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .carni24-stat-number {
        display: block;
        font-size: 24px;
        font-weight: bold;
        color: #16a34a;
        margin-bottom: 4px;
    }
    
    .carni24-stat-label {
        color: #6b7280;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    
    @media (max-width: 782px) {
        .carni24-quick-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        
        .carni24-stat-item {
            padding: 12px;
        }
        
        .carni24-stat-number {
            font-size: 20px;
        }
    }
    </style>
    <?php
}
add_action('admin_head', 'carni24_dashboard_widgets_css');

/**
 * AJAX handler dla aktualizacji statystyk dashboard
 */
function carni24_ajax_refresh_dashboard_stats() {
    check_ajax_referer('carni24_dashboard_nonce', 'nonce');
    
    if (!current_user_can('read')) {
        wp_die('Brak uprawnień');
    }
    
    // Odśwież cache statystyk
    delete_transient('carni24_dashboard_stats');
    delete_transient('carni24_seo_stats');
    delete_transient('carni24_popular_content');
    
    wp_send_json_success('Statystyki zostały odświeżone');
}
add_action('wp_ajax_carni24_refresh_dashboard_stats', 'carni24_ajax_refresh_dashboard_stats');

/**
 * Dodaje przycisk odświeżania do widgetów dashboard
 */
function carni24_add_dashboard_refresh_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Dodaj przycisk odświeżania do każdego carni24 widgetu
        $('.carni24-dashboard-widget').each(function() {
            var widget = $(this);
            var widgetBox = widget.closest('.postbox');
            var widgetHeader = widgetBox.find('.postbox-header h2, .hndle');
            
            if (widgetHeader.length) {
                var refreshBtn = $('<button type="button" class="button button-small" style="margin-left: 10px; font-size: 11px;">🔄</button>');
                refreshBtn.on('click', function(e) {
                    e.preventDefault();
                    refreshDashboardWidget(widget);
                });
                widgetHeader.append(refreshBtn);
            }
        });
        
        function refreshDashboardWidget(widget) {
            widget.css('opacity', '0.6');
            
            $.post(ajaxurl, {
                action: 'carni24_refresh_dashboard_stats',
                nonce: '<?= wp_create_nonce('carni24_dashboard_nonce') ?>'
            }, function(response) {
                if (response.success) {
                    // Odśwież stronę lub konkretny widget
                    location.reload();
                } else {
                    widget.css('opacity', '1');
                    alert('Błąd odświeżania: ' + (response.data || 'Nieznany błąd'));
                }
            }).fail(function() {
                widget.css('opacity', '1');
                alert('Błąd połączenia z serwerem');
            });
        }
        
        // Auto-refresh co 5 minut dla statystyk
        setInterval(function() {
            if (document.hasFocus()) {
                $('.carni24-stat-number').each(function() {
                    var $this = $(this);
                    // Subtle animation to indicate refresh
                    $this.fadeOut(100).fadeIn(100);
                });
            }
        }, 300000); // 5 minut
    });
    </script>
    <?php
}
add_action('admin_footer', 'carni24_add_dashboard_refresh_script');

/**
 * Personalizacja welcome panel
 */
function carni24_custom_welcome_panel() {
    $user = wp_get_current_user();
    $species_count = wp_count_posts('species')->publish;
    $guides_count = wp_count_posts('guides')->publish;
    ?>
    <div class="welcome-panel-content">
        <h2>👋 Witaj w panelu Carni24, <?= esc_html($user->display_name) ?>!</h2>
        
        <p class="about-description">
            Zarządzaj swoją kolekcją roślin mięsożernych, twórz poradniki i buduj społeczność miłośników carnivorous plants.
        </p>
        
        <div class="welcome-panel-column-container">
            <div class="welcome-panel-column">
                <h3>🌿 Gatunki</h3>
                <p>Masz już <strong><?= $species_count ?></strong> gatunków w bazie.</p>
                <a class="button button-primary button-hero" href="<?= admin_url('post-new.php?post_type=species') ?>">
                    Dodaj nowy gatunek
                </a>
                <p>lub <a href="<?= admin_url('edit.php?post_type=species') ?>">zarządzaj istniejącymi</a></p>
            </div>
            
            <div class="welcome-panel-column">
                <h3>📖 Poradniki</h3>
                <p>Opublikowałeś <strong><?= $guides_count ?></strong> poradników.</p>
                <a class="button button-primary button-hero" href="<?= admin_url('post-new.php?post_type=guides') ?>">
                    Napisz poradnik
                </a>
                <p>lub <a href="<?= admin_url('edit.php?post_type=guides') ?>">edytuj istniejące</a></p>
            </div>
            
            <div class="welcome-panel-column welcome-panel-last">
                <h3>⚙️ Dostosowania</h3>
                <ul>
                    <li><a href="<?= admin_url('themes.php?page=carni24-theme-options') ?>">Ustawienia motywu</a></li>
                    <li><a href="<?= admin_url('customize.php') ?>">Dostosuj wygląd</a></li>
                    <li><a href="<?= admin_url('options-general.php') ?>">Ustawienia ogólne</a></li>
                </ul>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Włącza custom welcome panel dla nowych użytkowników
 */
function carni24_enable_custom_welcome_panel() {
    $user_id = get_current_user_id();
    $show_welcome = get_user_meta($user_id, 'show_welcome_panel', true);
    
    // Pokaż custom welcome panel dla nowych użytkowników lub gdy nie ma żadnych postów
    if ($show_welcome !== '0') {
        $total_posts = wp_count_posts('species')->publish + wp_count_posts('guides')->publish;
        if ($total_posts < 3) {
            remove_action('welcome_panel', 'wp_welcome_panel');
            add_action('welcome_panel', 'carni24_custom_welcome_panel');
        }
    }
}
add_action('load-index.php', 'carni24_enable_custom_welcome_panel');