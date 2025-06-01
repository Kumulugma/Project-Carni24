<?php

// Add sitemap admin page
function carni24_add_sitemap_admin_page() {
    add_options_page(
        'Mapa strony XML',
        'Mapa strony',
        'manage_options',
        'carni24-sitemap',
        'carni24_sitemap_admin_page'
    );
}
add_action('admin_menu', 'carni24_add_sitemap_admin_page');

// Admin page content
function carni24_sitemap_admin_page() {
    if (isset($_POST['generate_sitemap'])) {
        check_admin_referer('carni24_sitemap_generate');
        carni24_generate_sitemap();
        echo '<div class="notice notice-success"><p>Mapa strony została wygenerowana!</p></div>';
    }
    
    if (isset($_POST['update_settings'])) {
        check_admin_referer('carni24_sitemap_settings');
        
        update_option('carni24_sitemap_posts', isset($_POST['include_posts']) ? 1 : 0);
        update_option('carni24_sitemap_pages', isset($_POST['include_pages']) ? 1 : 0);
        update_option('carni24_sitemap_species', isset($_POST['include_species']) ? 1 : 0);
        update_option('carni24_sitemap_categories', isset($_POST['include_categories']) ? 1 : 0);
        update_option('carni24_sitemap_tags', isset($_POST['include_tags']) ? 1 : 0);
        update_option('carni24_sitemap_frequency', sanitize_text_field($_POST['update_frequency']));
        update_option('carni24_sitemap_priority_posts', floatval($_POST['priority_posts']));
        update_option('carni24_sitemap_priority_pages', floatval($_POST['priority_pages']));
        update_option('carni24_sitemap_priority_species', floatval($_POST['priority_species']));
        
        echo '<div class="notice notice-success"><p>Ustawienia zostały zapisane!</p></div>';
    }
    
    $sitemap_url = home_url('/sitemap.xml');
    $sitemap_exists = file_exists(ABSPATH . 'sitemap.xml');
    $last_generated = get_option('carni24_sitemap_last_generated', '');
    
    // Get settings
    $include_posts = get_option('carni24_sitemap_posts', 1);
    $include_pages = get_option('carni24_sitemap_pages', 1);
    $include_species = get_option('carni24_sitemap_species', 1);
    $include_categories = get_option('carni24_sitemap_categories', 1);
    $include_tags = get_option('carni24_sitemap_tags', 0);
    $update_frequency = get_option('carni24_sitemap_frequency', 'weekly');
    $priority_posts = get_option('carni24_sitemap_priority_posts', 0.8);
    $priority_pages = get_option('carni24_sitemap_priority_pages', 0.9);
    $priority_species = get_option('carni24_sitemap_priority_species', 0.7);
    
    ?>
    <div class="wrap">
        <h1>Mapa strony XML</h1>
        
        <div class="card" style="max-width: 800px;">
            <h2>Status mapy strony</h2>
            <table class="form-table">
                <tr>
                    <th>URL mapy strony:</th>
                    <td>
                        <?php if ($sitemap_exists): ?>
                            <a href="<?= esc_url($sitemap_url) ?>" target="_blank"><?= esc_html($sitemap_url) ?></a>
                            <span class="dashicons dashicons-yes-alt" style="color: green;"></span>
                        <?php else: ?>
                            <span style="color: red;"><?= esc_html($sitemap_url) ?> (nie istnieje)</span>
                            <span class="dashicons dashicons-dismiss" style="color: red;"></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Ostatnia generacja:</th>
                    <td>
                        <?php if ($last_generated): ?>
                            <?= esc_html(date('Y-m-d H:i:s', $last_generated)) ?>
                        <?php else: ?>
                            <span style="color: red;">Nigdy</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Automatyczne generowanie:</th>
                    <td>
                        <span style="color: green;">Włączone</span> (przy publikacji nowych treści)
                    </td>
                </tr>
            </table>
            
            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('carni24_sitemap_generate'); ?>
                <input type="submit" name="generate_sitemap" value="Wygeneruj mapę strony teraz" class="button-primary">
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Ustawienia mapy strony</h2>
            
            <form method="post">
                <?php wp_nonce_field('carni24_sitemap_settings'); ?>
                
                <h3>Zawartość mapy strony</h3>
                <table class="form-table">
                    <tr>
                        <th>Wpisy (posts):</th>
                        <td>
                            <label>
                                <input type="checkbox" name="include_posts" value="1" <?php checked($include_posts, 1); ?>>
                                Dołącz wpisy do mapy strony
                            </label>
                            <p class="description">Priorytet: 
                                <input type="number" name="priority_posts" value="<?= esc_attr($priority_posts) ?>" min="0" max="1" step="0.1" style="width: 60px;">
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>Strony (pages):</th>
                        <td>
                            <label>
                                <input type="checkbox" name="include_pages" value="1" <?php checked($include_pages, 1); ?>>
                                Dołącz strony do mapy strony
                            </label>
                            <p class="description">Priorytet: 
                                <input type="number" name="priority_pages" value="<?= esc_attr($priority_pages) ?>" min="0" max="1" step="0.1" style="width: 60px;">
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>Gatunki (species):</th>
                        <td>
                            <label>
                                <input type="checkbox" name="include_species" value="1" <?php checked($include_species, 1); ?>>
                                Dołącz gatunki do mapy strony
                            </label>
                            <p class="description">Priorytet: 
                                <input type="number" name="priority_species" value="<?= esc_attr($priority_species) ?>" min="0" max="1" step="0.1" style="width: 60px;">
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>Kategorie:</th>
                        <td>
                            <label>
                                <input type="checkbox" name="include_categories" value="1" <?php checked($include_categories, 1); ?>>
                                Dołącz kategorie do mapy strony
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Tagi:</th>
                        <td>
                            <label>
                                <input type="checkbox" name="include_tags" value="1" <?php checked($include_tags, 1); ?>>
                                Dołącz tagi do mapy strony
                            </label>
                        </td>
                    </tr>
                </table>
                
                <h3>Częstotliwość aktualizacji</h3>
                <table class="form-table">
                    <tr>
                        <th>Domyślna częstotliwość:</th>
                        <td>
                            <select name="update_frequency">
                                <option value="always" <?php selected($update_frequency, 'always'); ?>>Zawsze</option>
                                <option value="hourly" <?php selected($update_frequency, 'hourly'); ?>>Co godzinę</option>
                                <option value="daily" <?php selected($update_frequency, 'daily'); ?>>Codziennie</option>
                                <option value="weekly" <?php selected($update_frequency, 'weekly'); ?>>Co tydzień</option>
                                <option value="monthly" <?php selected($update_frequency, 'monthly'); ?>>Co miesiąc</option>
                                <option value="yearly" <?php selected($update_frequency, 'yearly'); ?>>Co rok</option>
                                <option value="never" <?php selected($update_frequency, 'never'); ?>>Nigdy</option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="update_settings" value="Zapisz ustawienia" class="button-primary">
                </p>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Instrukcje</h2>
            <ol>
                <li>Skonfiguruj ustawienia mapy strony powyżej</li>
                <li>Wygeneruj mapę strony używając przycisku "Wygeneruj mapę strony teraz"</li>
                <li>Dodaj URL mapy strony do Google Search Console: <code><?= esc_html($sitemap_url) ?></code></li>
                <li>Dodaj mapę strony do robots.txt (opcjonalnie):
                    <br><code>Sitemap: <?= esc_html($sitemap_url) ?></code>
                </li>
            </ol>
            
            <h3>Automatyczne generowanie</h3>
            <p>Mapa strony będzie automatycznie regenerowana gdy:</p>
            <ul>
                <li>Zostanie opublikowany nowy wpis, strona lub gatunek</li>
                <li>Zostanie zaktualizowany istniejący wpis, strona lub gatunek</li>
                <li>Zostanie usunięty wpis, strona lub gatunek</li>
            </ul>
        </div>
        
        <?php if ($sitemap_exists): ?>
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>Podgląd mapy strony</h2>
            <iframe src="<?= esc_url($sitemap_url) ?>" style="width: 100%; height: 400px; border: 1px solid #ddd;"></iframe>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

// Generate sitemap XML
function carni24_generate_sitemap() {
    $sitemap_content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $sitemap_content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Homepage
    $sitemap_content .= carni24_sitemap_url_entry(
        home_url('/'),
        get_lastpostdate('gmt'),
        'daily',
        '1.0'
    );
    
    // Posts
    if (get_option('carni24_sitemap_posts', 1)) {
        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1
        ));
        
        $priority = get_option('carni24_sitemap_priority_posts', 0.8);
        $frequency = get_option('carni24_sitemap_frequency', 'weekly');
        
        foreach ($posts as $post) {
            $sitemap_content .= carni24_sitemap_url_entry(
                get_permalink($post->ID),
                $post->post_modified_gmt,
                $frequency,
                $priority
            );
        }
    }
    
    // Pages
    if (get_option('carni24_sitemap_pages', 1)) {
        $pages = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'numberposts' => -1
        ));
        
        $priority = get_option('carni24_sitemap_priority_pages', 0.9);
        $frequency = get_option('carni24_sitemap_frequency', 'weekly');
        
        foreach ($pages as $page) {
            $sitemap_content .= carni24_sitemap_url_entry(
                get_permalink($page->ID),
                $page->post_modified_gmt,
                $frequency,
                $priority
            );
        }
    }
    
    // Species
    if (get_option('carni24_sitemap_species', 1)) {
        $species = get_posts(array(
            'post_type' => 'species',
            'post_status' => 'publish',
            'numberposts' => -1
        ));
        
        $priority = get_option('carni24_sitemap_priority_species', 0.7);
        $frequency = get_option('carni24_sitemap_frequency', 'weekly');
        
        foreach ($species as $specie) {
            $sitemap_content .= carni24_sitemap_url_entry(
                get_permalink($specie->ID),
                $specie->post_modified_gmt,
                $frequency,
                $priority
            );
        }
    }
    
    // Categories
    if (get_option('carni24_sitemap_categories', 1)) {
        $categories = get_categories(array(
            'hide_empty' => true
        ));
        
        foreach ($categories as $category) {
            $sitemap_content .= carni24_sitemap_url_entry(
                get_category_link($category->term_id),
                '',
                'weekly',
                '0.6'
            );
        }
    }
    
    // Tags
    if (get_option('carni24_sitemap_tags', 0)) {
        $tags = get_tags(array(
            'hide_empty' => true
        ));
        
        foreach ($tags as $tag) {
            $sitemap_content .= carni24_sitemap_url_entry(
                get_tag_link($tag->term_id),
                '',
                'monthly',
                '0.4'
            );
        }
    }
    
    $sitemap_content .= '</urlset>';
    
    // Save sitemap
    $sitemap_file = ABSPATH . 'sitemap.xml';
    file_put_contents($sitemap_file, $sitemap_content);
    
    // Update last generated time
    update_option('carni24_sitemap_last_generated', time());
    
    return true;
}

// Helper function to create URL entry
function carni24_sitemap_url_entry($url, $lastmod = '', $changefreq = 'weekly', $priority = '0.5') {
    $entry = "\t<url>\n";
    $entry .= "\t\t<loc>" . esc_url($url) . "</loc>\n";
    
    if (!empty($lastmod)) {
        $lastmod_formatted = date('Y-m-d\TH:i:s+00:00', strtotime($lastmod));
        $entry .= "\t\t<lastmod>" . $lastmod_formatted . "</lastmod>\n";
    }
    
    $entry .= "\t\t<changefreq>" . esc_html($changefreq) . "</changefreq>\n";
    $entry .= "\t\t<priority>" . esc_html($priority) . "</priority>\n";
    $entry .= "\t</url>\n";
    
    return $entry;
}

// Auto-generate sitemap on content updates
function carni24_auto_generate_sitemap($post_id) {
    // Only for published posts
    if (get_post_status($post_id) !== 'publish') {
        return;
    }
    
    // Only for relevant post types
    $post_type = get_post_type($post_id);
    if (!in_array($post_type, array('post', 'page', 'species'))) {
        return;
    }
    
    // Generate sitemap
    carni24_generate_sitemap();
}
add_action('save_post', 'carni24_auto_generate_sitemap');
add_action('delete_post', 'carni24_auto_generate_sitemap');

// Add sitemap to robots.txt
function carni24_add_sitemap_to_robots($output) {
    $sitemap_url = home_url('/sitemap.xml');
    $output .= "\nSitemap: " . $sitemap_url . "\n";
    return $output;
}
add_filter('robots_txt', 'carni24_add_sitemap_to_robots');

// Handle sitemap.xml requests
function carni24_handle_sitemap_request() {
    if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/sitemap.xml') {
        $sitemap_file = ABSPATH . 'sitemap.xml';
        
        if (file_exists($sitemap_file)) {
            header('Content-Type: application/xml; charset=UTF-8');
            readfile($sitemap_file);
            exit;
        } else {
            // Generate sitemap if it doesn't exist
            carni24_generate_sitemap();
            if (file_exists($sitemap_file)) {
                header('Content-Type: application/xml; charset=UTF-8');
                readfile($sitemap_file);
                exit;
            }
        }
    }
}
add_action('init', 'carni24_handle_sitemap_request');

// Add settings link on plugins page
function carni24_sitemap_settings_link($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=carni24-sitemap') . '">Mapa strony</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Dashboard widget
function carni24_sitemap_dashboard_widget() {
    wp_add_dashboard_widget(
        'carni24_sitemap_widget',
        'Mapa strony XML',
        'carni24_sitemap_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'carni24_sitemap_dashboard_widget');

function carni24_sitemap_dashboard_widget_content() {
    $sitemap_url = home_url('/sitemap.xml');
    $sitemap_exists = file_exists(ABSPATH . 'sitemap.xml');
    $last_generated = get_option('carni24_sitemap_last_generated', '');
    
    echo '<div style="display: flex; align-items: center; margin-bottom: 10px;">';
    
    if ($sitemap_exists) {
        echo '<span class="dashicons dashicons-yes-alt" style="color: green; margin-right: 8px;"></span>';
        echo '<strong>Mapa strony jest aktywna</strong>';
    } else {
        echo '<span class="dashicons dashicons-dismiss" style="color: red; margin-right: 8px;"></span>';
        echo '<strong style="color: red;">Mapa strony nie istnieje</strong>';
    }
    
    echo '</div>';
    
    if ($last_generated) {
        echo '<p><strong>Ostatnia aktualizacja:</strong> ' . date('Y-m-d H:i', $last_generated) . '</p>';
    }
    
    echo '<p>';
    echo '<a href="' . esc_url($sitemap_url) . '" target="_blank" class="button">Wyświetl mapę strony</a> ';
    echo '<a href="' . admin_url('options-general.php?page=carni24-sitemap') . '" class="button">Zarządzaj</a>';
    echo '</p>';
}