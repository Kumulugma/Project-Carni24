<?php

// Add theme options page
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

// Theme options page content
function carni24_theme_options_page() {
    if (isset($_POST['save_theme_options'])) {
        check_admin_referer('carni24_theme_options');
        
        // Save navigation settings
        update_option('carni24_navigation_heading', sanitize_text_field($_POST['navigation_heading']));
        update_option('carni24_navigation_content', wp_kses_post($_POST['navigation_content']));
        
        // Save general settings
        update_option('carni24_site_logo_text', sanitize_text_field($_POST['site_logo_text']));
        update_option('carni24_search_placeholder', sanitize_text_field($_POST['search_placeholder']));
        
        // Save SEO defaults
        update_option('carni24_default_meta_description', sanitize_textarea_field($_POST['default_meta_description']));
        update_option('carni24_default_meta_keywords', sanitize_text_field($_POST['default_meta_keywords']));
        update_option('carni24_default_og_image', absint($_POST['default_og_image']));
        update_option('carni24_site_name', sanitize_text_field($_POST['site_name']));
        update_option('carni24_site_description', sanitize_textarea_field($_POST['site_description']));
        
        echo '<div class="notice notice-success"><p>Ustawienia zostały zapisane!</p></div>';
    }
    
    // Get current values
    $navigation_heading = get_option('carni24_navigation_heading', 'O nas');
    $navigation_content = get_option('carni24_navigation_content', 'Krótki opis strony poświęconej roślinom mięsożernym.');
    $site_logo_text = get_option('carni24_site_logo_text', 'Carni24');
    $search_placeholder = get_option('carni24_search_placeholder', 'Wpisz czego poszukujesz...');
    
    // SEO defaults
    $default_meta_description = get_option('carni24_default_meta_description', '');
    $default_meta_keywords = get_option('carni24_default_meta_keywords', '');
    $default_og_image = get_option('carni24_default_og_image', '');
    $site_name = get_option('carni24_site_name', get_bloginfo('name'));
    $site_description = get_option('carni24_site_description', get_bloginfo('description'));
    
    ?>
    <div class="wrap">
        <h1>Ustawienia motywu Carni24</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('carni24_theme_options'); ?>
            
            <div style="display: flex; gap: 20px;">
                <div style="flex: 1; max-width: 600px;">
                    
                    <!-- Ogólne ustawienia -->
                    <div class="card" style="margin-bottom: 20px;">
                        <h2>Ogólne ustawienia</h2>
                        <table class="form-table">
                            <tr>
                                <th><label for="site_logo_text">Tekst logo:</label></th>
                                <td>
                                    <input type="text" id="site_logo_text" name="site_logo_text" value="<?= esc_attr($site_logo_text) ?>" class="regular-text" />
                                    <p class="description">Tekst wyświetlany jako logo w nawigacji.</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="search_placeholder">Placeholder wyszukiwarki:</label></th>
                                <td>
                                    <input type="text" id="search_placeholder" name="search_placeholder" value="<?= esc_attr($search_placeholder) ?>" class="regular-text" />
                                    <p class="description">Tekst placeholder w polu wyszukiwania.</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Ustawienia nawigacji -->
                    <div class="card" style="margin-bottom: 20px;">
                        <h2>Nawigacja - zawartość rozwijanego menu</h2>
                        <table class="form-table">
                            <tr>
                                <th><label for="navigation_heading">Nagłówek:</label></th>
                                <td>
                                    <input type="text" id="navigation_heading" name="navigation_heading" value="<?= esc_attr($navigation_heading) ?>" class="regular-text" />
                                    <p class="description">Tytuł wyświetlany w rozwijanej nawigacji.</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="navigation_content">Treść:</label></th>
                                <td>
                                    <textarea id="navigation_content" name="navigation_content" rows="4" class="large-text"><?= esc_textarea($navigation_content) ?></textarea>
                                    <p class="description">Opis wyświetlany w rozwijanej nawigacji.</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- SEO - ustawienia domyślne -->
                    <div class="card" style="margin-bottom: 20px;">
                        <h2>SEO - ustawienia domyślne</h2>
                        <p class="description">Te ustawienia będą używane jako fallback, gdy dla konkretnej strony/wpisu nie zostały ustawione własne meta tagi.</p>
                        <table class="form-table">
                            <tr>
                                <th><label for="site_name">Nazwa witryny:</label></th>
                                <td>
                                    <input type="text" id="site_name" name="site_name" value="<?= esc_attr($site_name) ?>" class="regular-text" />
                                    <p class="description">Nazwa używana w tytułach i Open Graph.</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="site_description">Opis witryny:</label></th>
                                <td>
                                    <textarea id="site_description" name="site_description" rows="3" class="large-text"><?= esc_textarea($site_description) ?></textarea>
                                    <p class="description">Domyślny opis witryny używany gdy brak meta description.</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="default_meta_description">Meta Description (domyślny):</label></th>
                                <td>
                                    <textarea id="default_meta_description" name="default_meta_description" rows="3" class="large-text" maxlength="160"><?= esc_textarea($default_meta_description) ?></textarea>
                                    <p class="description">Domyślny opis dla stron bez własnego meta description. Maksymalnie 160 znaków.</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="default_meta_keywords">Meta Keywords (domyślne):</label></th>
                                <td>
                                    <input type="text" id="default_meta_keywords" name="default_meta_keywords" value="<?= esc_attr($default_meta_keywords) ?>" class="regular-text" />
                                    <p class="description">Domyślne słowa kluczowe oddzielone przecinkami.</p>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="default_og_image">Domyślny obraz OG:</label></th>
                                <td>
                                    <input type="hidden" id="default_og_image" name="default_og_image" value="<?= esc_attr($default_og_image) ?>" />
                                    <button type="button" class="button" onclick="openDefaultOgImageUploader()">Wybierz domyślny obraz OG</button>
                                    <button type="button" class="button" onclick="clearDefaultOgImage()" style="margin-left: 10px;">Usuń</button>
                                    <?php if ($default_og_image) : ?>
                                        <div id="default_og_image_preview" style="margin-top: 10px;">
                                            <?= wp_get_attachment_image($default_og_image, 'thumbnail') ?>
                                        </div>
                                    <?php else : ?>
                                        <div id="default_og_image_preview" style="margin-top: 10px;"></div>
                                    <?php endif; ?>
                                    <p class="description">Obraz używany w social media gdy strona nie ma własnego.</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <p class="submit">
                        <input type="submit" name="save_theme_options" value="Zapisz ustawienia" class="button-primary" />
                    </p>
                    
                </div>
                
                <!-- Sidebar z informacjami -->
                <div style="flex: 0 0 300px;">
                    <div class="card">
                        <h3>Informacje o motywie</h3>
                        <p><strong>Nazwa:</strong> Carni24</p>
                        <p><strong>Wersja:</strong> 2024.1</p>
                        <p><strong>Autor:</strong> Kumulugma</p>
                        
                        <h4>Funkcje motywu:</h4>
                        <ul>
                            <li>✅ Custom Post Type "Gatunki"</li>
                            <li>✅ SEO meta tagi</li>
                            <li>✅ Mapa strony XML</li>
                            <li>✅ Optymalizacja obrazów</li>
                            <li>✅ Responsive design</li>
                            <li>✅ Bootstrap 5</li>
                        </ul>
                        
                        <h4>Przydatne linki:</h4>
                        <ul>
                            <li><a href="<?= admin_url('edit.php?post_type=species') ?>">Zarządzaj gatunkami</a></li>
                            <li><a href="<?= admin_url('options-general.php?page=carni24-sitemap') ?>">Mapa strony</a></li>
                            <li><a href="<?= admin_url('nav-menus.php') ?>">Menu</a></li>
                            <li><a href="<?= admin_url('customize.php') ?>">Personalizator</a></li>
                        </ul>
                    </div>
                    
                    <div class="card" style="margin-top: 20px;">
                        <h3>Szybkie akcje</h3>
                        <p>
                            <a href="<?= admin_url('edit.php?post_type=species&carni24_flush_species=1') ?>" class="button">Odśwież URL gatunków</a>
                        </p>
                        <p>
                            <a href="<?= admin_url('options-general.php?page=carni24-sitemap') ?>" class="button">Regeneruj mapę strony</a>
                        </p>
                    </div>
                    
                    <div class="card" style="margin-top: 20px;">
                        <h3>SEO Info</h3>
                        <p><small>Hierarchia SEO:</small></p>
                        <ol style="font-size: 12px;">
                            <li>Meta tagi z wpisu/strony</li>
                            <li>Ustawienia domyślne (powyżej)</li>
                            <li>Automatyczne z WordPress</li>
                        </ol>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <style>
    .card {
        background: #fff;
        border: 1px solid #ccd0d4;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        padding: 20px;
        margin-bottom: 20px;
    }
    .card h2, .card h3 {
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    </style>
    
    <script>
    function openDefaultOgImageUploader() {
        var mediaUploader = wp.media({
            title: 'Wybierz domyślny obraz OG',
            button: {text: 'Użyj tego obrazu'},
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById('default_og_image').value = attachment.id;
            document.getElementById('default_og_image_preview').innerHTML = '<img src="' + attachment.sizes.thumbnail.url + '" />';
        });
        
        mediaUploader.open();
    }
    
    function clearDefaultOgImage() {
        document.getElementById('default_og_image').value = '';
        document.getElementById('default_og_image_preview').innerHTML = '';
    }
    </script>
    <?php
}

// Helper functions to get theme options
function carni24_get_option($option_name, $default = '') {
    return get_option('carni24_' . $option_name, $default);
}

// Dashboard widget
function carni24_theme_dashboard_widget() {
    wp_add_dashboard_widget(
        'carni24_theme_widget',
        'Motyw Carni24',
        'carni24_theme_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'carni24_theme_dashboard_widget');

function carni24_theme_dashboard_widget_content() {
    echo '<p>Witaj w panelu administracyjnym motywu Carni24!</p>';
    echo '<p><strong>Szybkie linki:</strong></p>';
    echo '<ul>';
    echo '<li><a href="' . admin_url('themes.php?page=carni24-theme-options') . '">Ustawienia motywu</a></li>';
    echo '<li><a href="' . admin_url('edit.php?post_type=species') . '">Gatunki (' . wp_count_posts('species')->publish . ')</a></li>';
    echo '<li><a href="' . admin_url('options-general.php?page=carni24-sitemap') . '">Mapa strony</a></li>';
    echo '</ul>';
    
    $last_species = get_posts(array('post_type' => 'species', 'numberposts' => 1));
    if (!empty($last_species)) {
        echo '<p><strong>Ostatnio dodany gatunek:</strong><br>';
        echo '<a href="' . admin_url('post.php?post=' . $last_species[0]->ID . '&action=edit') . '">' . $last_species[0]->post_title . '</a></p>';
    }
}