<?php

function carni24_add_theme_options_page() {
    add_theme_page(
        'Ustawienia motywu Carni24',
        'Ustawienia motywu',
        'manage_options',
        'carni24-theme-options',
        'carni24_theme_options_page'
    );
}
add_action('admin_menu', 'carni24_add_theme_options_page');

function carni24_theme_options_page() {
    if (isset($_POST['save_theme_options'])) {
        check_admin_referer('carni24_theme_options');
        
        update_option('carni24_navigation_heading', sanitize_text_field($_POST['navigation_heading']));
        update_option('carni24_navigation_content', wp_kses_post($_POST['navigation_content']));
        update_option('carni24_site_logo_text', sanitize_text_field($_POST['site_logo_text']));
        update_option('carni24_search_placeholder', sanitize_text_field($_POST['search_placeholder']));
        update_option('carni24_default_meta_description', sanitize_textarea_field($_POST['default_meta_description']));
        update_option('carni24_default_meta_keywords', sanitize_text_field($_POST['default_meta_keywords']));
        update_option('carni24_default_og_image', absint($_POST['default_og_image']));
        update_option('carni24_site_name', sanitize_text_field($_POST['site_name']));
        update_option('carni24_site_description', sanitize_textarea_field($_POST['site_description']));
        
        echo '<div class="notice notice-success is-dismissible">
                <div class="notice-content">
                    <div class="notice-icon">✅</div>
                    <div class="notice-text">
                        <strong>Sukces!</strong> Ustawienia zostały zapisane pomyślnie.
                    </div>
                </div>
              </div>';
    }
    
    $navigation_heading = get_option('carni24_navigation_heading', 'O nas');
    $navigation_content = get_option('carni24_navigation_content', 'Krótki opis strony poświęconej roślinom mięsożernym.');
    $site_logo_text = get_option('carni24_site_logo_text', 'Carni24');
    $search_placeholder = get_option('carni24_search_placeholder', 'Wpisz czego poszukujesz...');
    $default_meta_description = get_option('carni24_default_meta_description', '');
    $default_meta_keywords = get_option('carni24_default_meta_keywords', '');
    $default_og_image = get_option('carni24_default_og_image', '');
    $site_name = get_option('carni24_site_name', get_bloginfo('name'));
    $site_description = get_option('carni24_site_description', get_bloginfo('description'));
    
    ?>
    <div class="wrap carni24-theme-options">
        <div class="carni24-header">
            <div class="carni24-header-content">
                <div class="carni24-logo">
                    <span class="carni24-icon">🌿</span>
                    <h1>Carni24 <span class="version">v2024.1</span></h1>
                </div>
                <p class="carni24-subtitle">Zaawansowane ustawienia motywu dla stron o roślinach mięsożernych</p>
            </div>
        </div>
        
        <form method="post" action="" class="carni24-form">
            <?php wp_nonce_field('carni24_theme_options'); ?>
            
            <div class="carni24-grid">
                <div class="carni24-main-content">
                    
                    <div class="carni24-section" id="general-settings">
                        <div class="carni24-section-header">
                            <h2><span class="icon">⚙️</span> Ogólne ustawienia</h2>
                            <p>Podstawowe opcje konfiguracyjne motywu</p>
                        </div>
                        <div class="carni24-section-content">
                            <div class="carni24-field">
                                <label for="site_logo_text" class="carni24-label">
                                    <strong>Tekst logo</strong>
                                    <span class="carni24-help" title="Tekst wyświetlany jako logo w nawigacji">?</span>
                                </label>
                                <input type="text" id="site_logo_text" name="site_logo_text" 
                                       value="<?= esc_attr($site_logo_text) ?>" 
                                       class="carni24-input" 
                                       placeholder="np. Carni24" />
                            </div>
                            
                            <div class="carni24-field">
                                <label for="search_placeholder" class="carni24-label">
                                    <strong>Placeholder wyszukiwarki</strong>
                                    <span class="carni24-help" title="Tekst placeholder w polu wyszukiwania">?</span>
                                </label>
                                <input type="text" id="search_placeholder" name="search_placeholder" 
                                       value="<?= esc_attr($search_placeholder) ?>" 
                                       class="carni24-input" 
                                       placeholder="np. Wpisz czego poszukujesz..." />
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-section" id="navigation-settings">
                        <div class="carni24-section-header">
                            <h2><span class="icon">🧭</span> Nawigacja</h2>
                            <p>Zawartość rozwijanego menu nawigacji</p>
                        </div>
                        <div class="carni24-section-content">
                            <div class="carni24-field">
                                <label for="navigation_heading" class="carni24-label">
                                    <strong>Nagłówek menu</strong>
                                    <span class="carni24-help" title="Tytuł wyświetlany w rozwijanej nawigacji">?</span>
                                </label>
                                <input type="text" id="navigation_heading" name="navigation_heading" 
                                       value="<?= esc_attr($navigation_heading) ?>" 
                                       class="carni24-input" 
                                       placeholder="np. O nas" />
                            </div>
                            
                            <div class="carni24-field">
                                <label for="navigation_content" class="carni24-label">
                                    <strong>Treść menu</strong>
                                    <span class="carni24-help" title="Opis wyświetlany w rozwijanej nawigacji">?</span>
                                </label>
                                <textarea id="navigation_content" name="navigation_content" 
                                          rows="4" class="carni24-textarea" 
                                          placeholder="Krótki opis Twojej strony..."><?= esc_textarea($navigation_content) ?></textarea>
                                <div class="carni24-char-counter">
                                    <span id="nav-content-counter">0</span> / 200 znaków
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-section" id="seo-settings">
                        <div class="carni24-section-header">
                            <h2><span class="icon">🎯</span> SEO - Ustawienia domyślne</h2>
                            <p>Domyślne meta tagi używane jako fallback</p>
                        </div>
                        <div class="carni24-section-content">
                            <div class="carni24-info-box">
                                <strong>Hierarchia SEO:</strong>
                                <ol>
                                    <li>Meta tagi z wpisu/strony</li>
                                    <li>Ustawienia domyślne (poniżej)</li>
                                    <li>Automatyczne z WordPress</li>
                                </ol>
                            </div>
                            
                            <div class="carni24-field">
                                <label for="site_name" class="carni24-label">
                                    <strong>Nazwa witryny</strong>
                                    <span class="carni24-help" title="Nazwa używana w tytułach i Open Graph">?</span>
                                </label>
                                <input type="text" id="site_name" name="site_name" 
                                       value="<?= esc_attr($site_name) ?>" 
                                       class="carni24-input" />
                            </div>
                            
                            <div class="carni24-field">
                                <label for="site_description" class="carni24-label">
                                    <strong>Opis witryny</strong>
                                    <span class="carni24-help" title="Domyślny opis witryny">?</span>
                                </label>
                                <textarea id="site_description" name="site_description" 
                                          rows="3" class="carni24-textarea"><?= esc_textarea($site_description) ?></textarea>
                            </div>
                            
                            <div class="carni24-field">
                                <label for="default_meta_description" class="carni24-label">
                                    <strong>Meta Description</strong>
                                    <span class="carni24-help" title="Domyślny opis dla stron bez własnego meta description">?</span>
                                </label>
                                <textarea id="default_meta_description" name="default_meta_description" 
                                          rows="3" class="carni24-textarea" maxlength="160" 
                                          placeholder="Krótki opis Twojej strony dla wyszukiwarek..."><?= esc_textarea($default_meta_description) ?></textarea>
                                <div class="carni24-char-counter">
                                    <span id="meta-desc-counter">0</span> / 160 znaków
                                </div>
                            </div>
                            
                            <div class="carni24-field">
                                <label for="default_meta_keywords" class="carni24-label">
                                    <strong>Meta Keywords</strong>
                                    <span class="carni24-help" title="Domyślne słowa kluczowe oddzielone przecinkami">?</span>
                                </label>
                                <input type="text" id="default_meta_keywords" name="default_meta_keywords" 
                                       value="<?= esc_attr($default_meta_keywords) ?>" 
                                       class="carni24-input" 
                                       placeholder="rośliny mięsożerne, carni, uprawy, gatunki..." />
                            </div>
                            
                            <div class="carni24-field">
                                <label class="carni24-label">
                                    <strong>Domyślny obraz OG</strong>
                                    <span class="carni24-help" title="Obraz używany w social media gdy strona nie ma własnego">?</span>
                                </label>
                                <div class="carni24-media-upload">
                                    <input type="hidden" id="default_og_image" name="default_og_image" value="<?= esc_attr($default_og_image) ?>" />
                                    <div class="carni24-media-preview" id="default_og_image_preview">
                                        <?php if ($default_og_image) : ?>
                                            <?= wp_get_attachment_image($default_og_image, 'medium') ?>
                                        <?php else : ?>
                                            <div class="carni24-no-image">
                                                <span class="dashicons dashicons-format-image"></span>
                                                <p>Brak obrazu</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="carni24-media-buttons">
                                        <button type="button" class="carni24-btn carni24-btn-primary" onclick="openDefaultOgImageUploader()">
                                            <span class="dashicons dashicons-upload"></span>
                                            Wybierz obraz
                                        </button>
                                        <button type="button" class="carni24-btn carni24-btn-secondary" onclick="clearDefaultOgImage()">
                                            <span class="dashicons dashicons-trash"></span>
                                            Usuń
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-save-section">
                        <button type="submit" name="save_theme_options" class="carni24-btn carni24-btn-success carni24-btn-large">
                            <span class="dashicons dashicons-yes"></span>
                            Zapisz wszystkie ustawienia
                        </button>
                    </div>
                    
                </div>
                <div class="carni24-sidebar">
                    
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">ℹ️</span> Informacje o motywie</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <div class="carni24-theme-info">
                                <div class="info-item">
                                    <strong>Nazwa:</strong> Carni24
                                </div>
                                <div class="info-item">
                                    <strong>Wersja:</strong> 2024.1
                                </div>
                                <div class="info-item">
                                    <strong>Autor:</strong> Kumulugma
                                </div>
                                <div class="info-item">
                                    <strong>Framework:</strong> Bootstrap 5
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">✨</span> Funkcje motywu</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <div class="carni24-features">
                                <div class="feature-item active">
                                    <span class="feature-icon">✅</span>
                                    <span>Custom Post Type "Gatunki"</span>
                                </div>
                                <div class="feature-item active">
                                    <span class="feature-icon">✅</span>
                                    <span>SEO meta tagi</span>
                                </div>
                                <div class="feature-item active">
                                    <span class="feature-icon">✅</span>
                                    <span>Mapa strony XML</span>
                                </div>
                                <div class="feature-item active">
                                    <span class="feature-icon">✅</span>
                                    <span>Optymalizacja obrazów</span>
                                </div>
                                <div class="feature-item active">
                                    <span class="feature-icon">✅</span>
                                    <span>Responsive design</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">🔗</span> Szybkie linki</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <div class="carni24-quick-links">
                                <a href="<?= admin_url('edit.php?post_type=species') ?>" class="quick-link">
                                    <span class="dashicons dashicons-admin-post"></span>
                                    Zarządzaj gatunkami
                                    <span class="count"><?= wp_count_posts('species')->publish ?></span>
                                </a>
                                <a href="<?= admin_url('options-general.php?page=carni24-sitemap') ?>" class="quick-link">
                                    <span class="dashicons dashicons-networking"></span>
                                    Mapa strony XML
                                </a>
                                <a href="<?= admin_url('nav-menus.php') ?>" class="quick-link">
                                    <span class="dashicons dashicons-menu-alt3"></span>
                                    Zarządzaj menu
                                </a>
                                <a href="<?= admin_url('customize.php') ?>" class="quick-link">
                                    <span class="dashicons dashicons-admin-customizer"></span>
                                    Personalizator
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">⚡</span> Szybkie akcje</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <div class="carni24-actions">
                                <a href="<?= admin_url('edit.php?post_type=species&carni24_flush_species=1') ?>" 
                                   class="carni24-btn carni24-btn-primary carni24-btn-block">
                                    <span class="dashicons dashicons-update"></span>
                                    Odśwież URL gatunków
                                </a>
                                <a href="<?= admin_url('options-general.php?page=carni24-sitemap') ?>" 
                                   class="carni24-btn carni24-btn-primary carni24-btn-block">
                                    <span class="dashicons dashicons-networking"></span>
                                    Regeneruj mapę strony
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">📝</span> Test Title</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <p><strong>Sprawdź jak działa tag &lt;title&gt;:</strong></p>
                            
                            <div class="carni24-title-test">
                                <div class="title-preview-box">
                                    <h4>Aktualny tytuł strony głównej:</h4>
                                    <div class="title-preview" id="title-preview">
                                        <?php
                                        $site_name = carni24_get_option('site_name', get_bloginfo('name'));
                                        $site_description = carni24_get_option('site_description', get_bloginfo('description'));
                                        
                                        if (!empty($site_name) && !empty($site_description)) {
                                            echo esc_html($site_name . ' - ' . $site_description);
                                        } elseif (!empty($site_name)) {
                                            echo esc_html($site_name);
                                        } else {
                                            echo esc_html(get_bloginfo('name') . ' - ' . get_bloginfo('description'));
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="title-test-buttons">
                                    <a href="<?= home_url('/?title_debug=1') ?>" target="_blank" class="carni24-btn carni24-btn-primary carni24-btn-block">
                                        <span class="dashicons dashicons-search"></span>
                                        Debug Title strony głównej
                                    </a>
                                    
                                    <a href="<?= admin_url('?check_title=1') ?>" class="carni24-btn carni24-btn-secondary carni24-btn-block">
                                        <span class="dashicons dashicons-warning"></span>
                                        Sprawdź header.php
                                    </a>
                                </div>
                                
                                <div class="title-status">
                                    <h4>Diagnostyka:</h4>
                                    <div class="status-item">
                                        <span class="status-label">Theme support 'title-tag':</span>
                                        <span class="status-value <?= current_theme_supports('title-tag') ? 'ok' : 'missing' ?>">
                                            <?= current_theme_supports('title-tag') ? '✅ Włączone' : '❌ Brak' ?>
                                        </span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-label">wp_head() w header.php:</span>
                                        <span class="status-value <?= has_action('wp_head') ? 'ok' : 'missing' ?>">
                                            <?= has_action('wp_head') ? '✅ OK' : '❌ Brak' ?>
                                        </span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-label">SEO title filter:</span>
                                        <span class="status-value <?= has_filter('document_title_parts', 'carni24_document_title_parts') ? 'ok' : 'missing' ?>">
                                            <?= has_filter('document_title_parts', 'carni24_document_title_parts') ? '✅ Aktywny' : '❌ Nieaktywny' ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="title-help">
                                    <h4>Jak naprawić problemy:</h4>
                                    <ul style="font-size: 12px; color: #666;">
                                        <li><strong>Brak title-tag support:</strong> Dodaj <code>add_theme_support('title-tag');</code> do functions.php</li>
                                        <li><strong>Hardcoded &lt;title&gt;:</strong> Usuń ręczne tagi &lt;title&gt; z header.php</li>
                                        <li><strong>Brak wp_head():</strong> Dodaj <code>&lt;?php wp_head(); ?&gt;</code> przed &lt;/head&gt;</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">🔍</span> Test SEO</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <p><strong>Sprawdź jak działają Twoje ustawienia SEO:</strong></p>
                            
                            <div class="carni24-seo-test">
                                <a href="<?= home_url('/?seo_debug=1') ?>" target="_blank" class="carni24-btn carni24-btn-primary carni24-btn-block">
                                    <span class="dashicons dashicons-search"></span>
                                    Sprawdź SEO strony głównej
                                </a>
                                
                                <div class="carni24-seo-preview" id="seo-preview">
                                    <h4>Podgląd w wyszukiwarce:</h4>
                                    <div class="seo-preview-box">
                                        <div class="seo-title" id="preview-title">
                                            <?= esc_html(carni24_get_option('site_name', get_bloginfo('name'))) ?> - <?= esc_html(carni24_get_option('site_description', get_bloginfo('description'))) ?>
                                        </div>
                                        <div class="seo-url"><?= esc_url(home_url('/')) ?></div>
                                        <div class="seo-description" id="preview-description">
                                            <?= esc_html(carni24_get_option('default_meta_description', carni24_get_option('site_description', get_bloginfo('description')))) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="carni24-seo-status">
                                    <h4>Status ustawień:</h4>
                                    <div class="status-item">
                                        <span class="status-label">Nazwa witryny:</span>
                                        <span class="status-value <?= carni24_get_option('site_name', '') ? 'ok' : 'missing' ?>">
                                            <?= carni24_get_option('site_name', '') ? '✅ Ustawiona' : '❌ Brak' ?>
                                        </span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-label">Opis witryny:</span>
                                        <span class="status-value <?= carni24_get_option('site_description', '') ? 'ok' : 'missing' ?>">
                                            <?= carni24_get_option('site_description', '') ? '✅ Ustawiony' : '❌ Brak' ?>
                                        </span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-label">Meta Description:</span>
                                        <span class="status-value <?= carni24_get_option('default_meta_description', '') ? 'ok' : 'missing' ?>">
                                            <?= carni24_get_option('default_meta_description', '') ? '✅ Ustawiony' : '❌ Brak' ?>
                                        </span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-label">Meta Keywords:</span>
                                        <span class="status-value <?= carni24_get_option('default_meta_keywords', '') ? 'ok' : 'missing' ?>">
                                            <?= carni24_get_option('default_meta_keywords', '') ? '✅ Ustawione' : '❌ Brak' ?>
                                        </span>
                                    </div>
                                    <div class="status-item">
                                        <span class="status-label">Obraz OG:</span>
                                        <span class="status-value <?= carni24_get_option('default_og_image', '') ? 'ok' : 'missing' ?>">
                                            <?= carni24_get_option('default_og_image', '') ? '✅ Ustawiony' : '❌ Brak' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    $last_species = get_posts(array('post_type' => 'species', 'numberposts' => 1));
                    if (!empty($last_species)) : ?>
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">🆕</span> Ostatnia aktywność</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <div class="carni24-recent-activity">
                                <p><strong>Ostatnio dodany gatunek:</strong></p>
                                <a href="<?= admin_url('post.php?post=' . $last_species[0]->ID . '&action=edit') ?>" 
                                   class="recent-item">
                                    <span class="dashicons dashicons-admin-post"></span>
                                    <?= esc_html($last_species[0]->post_title) ?>
                                    <small><?= human_time_diff(strtotime($last_species[0]->post_date), current_time('timestamp')) ?> temu</small>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="carni24-widget">
                        <div class="carni24-widget-header">
                            <h3><span class="icon">🖼️</span> Diagnostyka miniaturek</h3>
                        </div>
                        <div class="carni24-widget-content">
                            <div class="carni24-thumbnails-info">
                                <h4>Zarejestrowane rozmiary obrazów:</h4>
                                <?php
                                global $_wp_additional_image_sizes;
                                $image_sizes = array();
                                
                                // Pobierz domyślne rozmiary WordPress
                                foreach (array('thumbnail', 'medium', 'medium_large', 'large') as $size) {
                                    $image_sizes[$size] = array(
                                        'width' => get_option($size . '_size_w'),
                                        'height' => get_option($size . '_size_h'),
                                        'crop' => get_option($size . '_crop')
                                    );
                                }
                                
                                // Pobierz dodatkowe rozmiary
                                if (isset($_wp_additional_image_sizes)) {
                                    $image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
                                }
                                ?>
                                
                                <div class="image-sizes-list">
                                    <?php foreach ($image_sizes as $name => $size) : ?>
                                        <div class="size-item">
                                            <strong><?= esc_html($name) ?>:</strong>
                                            <?= esc_html($size['width']) ?>×<?= esc_html($size['height']) ?>
                                            <?php if (isset($size['crop']) && $size['crop']) : ?>
                                                <span class="crop-indicator">⚡ przycinane</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="thumbnail-actions">
                                    <h4>Narzędzia:</h4>
                                    <a href="<?= admin_url('tools.php?page=regenerate-thumbnails') ?>" 
                                       class="carni24-btn carni24-btn-secondary carni24-btn-block">
                                        <span class="dashicons dashicons-image-rotate"></span>
                                        Regeneruj miniaturki (wtyczka)
                                    </a>
                                    
                                    <button type="button" onclick="carni24CheckThumbnails()" 
                                            class="carni24-btn carni24-btn-primary carni24-btn-block">
                                        <span class="dashicons dashicons-search"></span>
                                        Sprawdź ostatnie obrazy
                                    </button>
                                    
                                    <button type="button" onclick="carni24ForceRegenerateThumbnails()" 
                                            class="carni24-btn carni24-btn-secondary carni24-btn-block">
                                        <span class="dashicons dashicons-update"></span>
                                        Wymuś regenerację (10 ostatnich)
                                    </button>
                                </div>
                                
                                <div id="thumbnail-results" style="margin-top: 15px;"></div>
                                
                                <div class="thumbnail-help">
                                    <h4>Rozwiązywanie problemów:</h4>
                                    <ul style="font-size: 12px; color: #666; margin: 8px 0 0 20px;">
                                        <li><strong>Brak miniaturek:</strong> Sprawdź uprawnienia folderów /uploads/</li>
                                        <li><strong>Błędy PHP:</strong> Zwiększ memory_limit i max_execution_time</li>
                                        <li><strong>Duże pliki:</strong> Zmniejsz rozmiary obrazów przed uplodem</li>
                                        <li><strong>Regeneracja nie działa:</strong> Użyj wtyczki Force Regenerate Thumbnails</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
    
    <style>
    .image-sizes-list .size-item {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .image-sizes-list .size-item:last-child {
        border-bottom: none;
    }
    
    .crop-indicator {
        background: #ffc107;
        color: #333;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: bold;
    }
    
    .thumbnail-actions {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }
    
    .thumbnail-help {
        margin-top: 15px;
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 4px;
        padding: 10px;
    }
    
    #thumbnail-results {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        padding: 10px;
        font-size: 12px;
        max-height: 200px;
        overflow-y: auto;
        display: none;
    }
    
    #thumbnail-results.show {
        display: block;
    }
    
    .thumbnail-result-item {
        padding: 5px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .thumbnail-result-item:last-child {
        border-bottom: none;
    }
    
    .thumbnail-result-success {
        color: #28a745;
    }
    
    .thumbnail-result-error {
        color: #dc3545;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Update SEO preview when form fields change
        function updateSeoPreview() {
            const siteName = $('#site_name').val() || '<?= esc_js(get_bloginfo('name')) ?>';
            const siteDesc = $('#site_description').val() || '<?= esc_js(get_bloginfo('description')) ?>';
            const metaDesc = $('#default_meta_description').val() || siteDesc;
            
            $('#preview-title').text(siteName + ' - ' + siteDesc);
            $('#preview-description').text(metaDesc);
        }
        
        function updateTitlePreview() {
            const siteName = $('#site_name').val() || '<?= esc_js(get_bloginfo('name')) ?>';
            const siteDesc = $('#site_description').val() || '<?= esc_js(get_bloginfo('description')) ?>';
            
            let titleText = siteName;
            if (siteDesc) {
                titleText += ' - ' + siteDesc;
            }
            
            $('#title-preview').text(titleText);
        }
        
        $('#site_name, #site_description, #default_meta_description').on('input', updateSeoPreview);
        $('#site_name, #site_description').on('input', updateTitlePreview);
    });
    </script>
    <?php
}

