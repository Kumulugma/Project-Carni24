<?php
/**
 * Carni24 Theme Options
 * Panel ustawień motywu w WordPress Admin
 * 
 * @package Carni24
 * @subpackage Admin
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dodaje stronę ustawień motywu do menu
 */
function carni24_add_theme_options_page() {
    add_theme_page(
        'Ustawienia Carni24',
        'Ustawienia Carni24', 
        'manage_options',
        'carni24-theme-options',
        'carni24_theme_options_page'
    );
}
add_action('admin_menu', 'carni24_add_theme_options_page');

/**
 * Renderuje stronę ustawień motywu
 */
function carni24_theme_options_page() {
    // Sprawdź uprawnienia
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Obsługa zapisywania ustawień
    if (isset($_POST['submit']) && check_admin_referer('carni24_theme_options', 'carni24_nonce')) {
        carni24_save_theme_options();
        echo '<div class="notice notice-success"><p>Ustawienia zostały zapisane!</p></div>';
    }
    
    // Pobierz obecne ustawienia
    $options = get_option('carni24_theme_options', array());
    
    // Domyślne wartości
    $defaults = array(
        'site_name' => get_bloginfo('name'),
        'site_description' => get_bloginfo('description'),
        'navigation_title' => '',
        'navigation_content' => '',
        'default_meta_description' => '',
        'default_meta_keywords' => '',
        'default_og_image' => '',
        'lazy_loading' => 1,
        'minify_css' => 0,
        'minify_js' => 0,
        'google_analytics' => '',
        'facebook_pixel' => '',
    );
    
    $options = wp_parse_args($options, $defaults);
    ?>
    
    <div class="wrap">
        <h1><span class="dashicons dashicons-admin-generic"></span> Ustawienia motywu Carni24</h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('carni24_theme_options', 'carni24_nonce'); ?>
            
            <div class="carni24-admin-container">
                <!-- Nawigacja tabów -->
                <nav class="nav-tab-wrapper">
                    <a href="#general" class="nav-tab nav-tab-active">Ogólne</a>
                    <a href="#seo" class="nav-tab">SEO</a>
                    <a href="#performance" class="nav-tab">Wydajność</a>
                    <a href="#analytics" class="nav-tab">Analityka</a>
                    <a href="#tools" class="nav-tab">Narzędzia</a>
                </nav>
                
                <!-- Tab: Ogólne -->
                <div id="general" class="tab-content active">
                    <h2>Podstawowe ustawienia</h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="site_name">Nazwa witryny</label>
                            </th>
                            <td>
                                <input type="text" id="site_name" name="site_name" 
                                       value="<?php echo esc_attr($options['site_name']); ?>" 
                                       class="regular-text" />
                                <p class="description">Używana w tytułach stron i meta tagach</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="site_description">Opis witryny</label>
                            </th>
                            <td>
                                <textarea id="site_description" name="site_description" 
                                          rows="3" class="large-text"><?php echo esc_textarea($options['site_description']); ?></textarea>
                                <p class="description">Krótki opis Twojej strony</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="navigation_title">Tytuł nawigacji</label>
                            </th>
                            <td>
                                <input type="text" id="navigation_title" name="navigation_title" 
                                       value="<?php echo esc_attr($options['navigation_title']); ?>" 
                                       class="regular-text" />
                                <p class="description">Tytuł wyświetlany w rozwijanej nawigacji</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="navigation_content">Treść nawigacji</label>
                            </th>
                            <td>
                                <textarea id="navigation_content" name="navigation_content" 
                                          rows="4" class="large-text"><?php echo esc_textarea($options['navigation_content']); ?></textarea>
                                <p class="description">Opis wyświetlany w rozwijanej nawigacji</p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Tab: SEO -->
                <div id="seo" class="tab-content">
                    <h2>Ustawienia SEO</h2>
                    
                    <div class="notice notice-info">
                        <p><strong>Hierarchia SEO:</strong></p>
                        <ol>
                            <li>Meta tagi z wpisu/strony</li>
                            <li>Ustawienia domyślne (poniżej)</li>
                            <li>Automatyczne z WordPress</li>
                        </ol>
                    </div>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="default_meta_description">Domyślny Meta Description</label>
                            </th>
                            <td>
                                <textarea id="default_meta_description" name="default_meta_description" 
                                          rows="3" class="large-text" maxlength="160"><?php echo esc_textarea($options['default_meta_description']); ?></textarea>
                                <p class="description">Fallback dla stron bez własnego opisu (max 160 znaków)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="default_meta_keywords">Domyślne Meta Keywords</label>
                            </th>
                            <td>
                                <input type="text" id="default_meta_keywords" name="default_meta_keywords" 
                                       value="<?php echo esc_attr($options['default_meta_keywords']); ?>" 
                                       class="large-text" />
                                <p class="description">Słowa kluczowe oddzielone przecinkami</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="default_og_image">Domyślny obraz OG</label>
                            </th>
                            <td>
                                <input type="hidden" id="default_og_image" name="default_og_image" 
                                       value="<?php echo esc_attr($options['default_og_image']); ?>" />
                                <button type="button" class="button" onclick="carni24OpenMediaUploader('default_og_image', 'og-image-preview')">
                                    Wybierz obraz
                                </button>
                                <button type="button" class="button" onclick="carni24ClearImage('default_og_image', 'og-image-preview')">
                                    Usuń
                                </button>
                                <div id="og-image-preview" style="margin-top: 10px;">
                                    <?php if ($options['default_og_image']): ?>
                                        <?php echo wp_get_attachment_image($options['default_og_image'], 'medium'); ?>
                                    <?php endif; ?>
                                </div>
                                <p class="description">Obraz używany gdy strona nie ma featured image (1200x630px)</p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Tab: Wydajność -->
                <div id="performance" class="tab-content">
                    <h2>Optymalizacja wydajności</h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">Lazy loading obrazów</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="lazy_loading" value="1" 
                                           <?php checked($options['lazy_loading'], 1); ?> />
                                    Włącz lazy loading dla obrazów
                                </label>
                                <p class="description">Obrazy ładują się dopiero gdy są widoczne na ekranie</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Minifikacja CSS</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="minify_css" value="1" 
                                           <?php checked($options['minify_css'], 1); ?> />
                                    Włącz minifikację plików CSS
                                </label>
                                <p class="description">Zmniejsza rozmiar plików CSS (zalecane dla produkcji)</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">Minifikacja JavaScript</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="minify_js" value="1" 
                                           <?php checked($options['minify_js'], 1); ?> />
                                    Włącz minifikację plików JavaScript
                                </label>
                                <p class="description">Zmniejsza rozmiar plików JS (zalecane dla produkcji)</p>
                            </td>
                        </tr>
                    </table>
                    
                    <h3>Rozmiary obrazów</h3>
                    <div class="carni24-image-sizes-info">
                        <p>Aktualnie zdefiniowane rozmiary obrazów:</p>
                        <div class="image-sizes-grid">
                            <?php carni24_display_image_sizes(); ?>
                        </div>
                        <button type="button" class="button" onclick="carni24RegenerateThumbnails()">
                            Regeneruj miniaturki
                        </button>
                        <div id="regenerate-progress" style="display: none; margin-top: 10px;">
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                            <p id="regenerate-status">Regenerowanie...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tab: Analityka -->
                <div id="analytics" class="tab-content">
                    <h2>Kody śledzące</h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="google_analytics">Google Analytics</label>
                            </th>
                            <td>
                                <input type="text" id="google_analytics" name="google_analytics" 
                                       value="<?php echo esc_attr($options['google_analytics']); ?>" 
                                       class="regular-text" placeholder="G-XXXXXXXXXX" />
                                <p class="description">ID śledzenia Google Analytics 4</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="facebook_pixel">Facebook Pixel</label>
                            </th>
                            <td>
                                <input type="text" id="facebook_pixel" name="facebook_pixel" 
                                       value="<?php echo esc_attr($options['facebook_pixel']); ?>" 
                                       class="regular-text" placeholder="123456789012345" />
                                <p class="description">ID Facebook Pixel</p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Tab: Narzędzia -->
                <div id="tools" class="tab-content">
                    <h2>Narzędzia i diagnostyka</h2>
                    
                    <div class="tool-section">
                        <h3>Test SEO</h3>
                        <p>Sprawdź konfigurację SEO motywu:</p>
                        <button type="button" class="button" onclick="carni24TestSEO()">Sprawdź SEO</button>
                        <div id="seo-test-results" style="margin-top: 15px;"></div>
                    </div>
                    
                    <div class="tool-section">
                        <h3>Czyszczenie cache</h3>
                        <p>Wyczyść cache motywu i zregeneruj pliki:</p>
                        <button type="button" class="button" onclick="carni24ClearCache()">Wyczyść cache</button>
                        <div id="cache-clear-results" style="margin-top: 15px;"></div>
                    </div>
                    
                    <div class="tool-section">
                        <h3>Export/Import ustawień</h3>
                        <p>Eksportuj lub importuj ustawienia motywu:</p>
                        <button type="button" class="button" onclick="carni24ExportSettings()">Eksportuj ustawienia</button>
                        <input type="file" id="import-settings" accept=".json" style="display: none;" onchange="carni24ImportSettings(this)" />
                        <button type="button" class="button" onclick="document.getElementById('import-settings').click()">Importuj ustawienia</button>
                    </div>
                    
                    <div class="tool-section">
                        <h3>Informacje o motywie</h3>
                        <table class="widefat">
                            <tr><td><strong>Wersja motywu:</strong></td><td><?php echo CARNI24_VERSION; ?></td></tr>
                            <tr><td><strong>Wersja WordPress:</strong></td><td><?php echo get_bloginfo('version'); ?></td></tr>
                            <tr><td><strong>Wersja PHP:</strong></td><td><?php echo PHP_VERSION; ?></td></tr>
                            <tr><td><strong>Aktywne wtyczki:</strong></td><td><?php echo count(get_option('active_plugins')); ?></td></tr>
                            <tr><td><strong>Rozmiary obrazów:</strong></td><td><?php echo count(get_intermediate_image_sizes()); ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <?php submit_button('Zapisz ustawienia'); ?>
        </form>
    </div>
    
    <style>
    .carni24-admin-container { max-width: 1200px; }
    .nav-tab-wrapper { margin-bottom: 20px; }
    .tab-content { display: none; background: #fff; padding: 20px; border: 1px solid #ccd0d4; }
    .tab-content.active { display: block; }
    .tool-section { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0; }
    .tool-section:last-child { border-bottom: none; }
    .image-sizes-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin: 15px 0; }
    .image-size-item { padding: 10px; background: #f9f9f9; border-radius: 4px; }
    .progress-bar { width: 100%; height: 20px; background: #f0f0f0; border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; background: #0073aa; width: 0%; transition: width 0.3s ease; }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.nav-tab').click(function(e) {
            e.preventDefault();
            const target = $(this).attr('href');
            
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            $('.tab-content').removeClass('active');
            $(target).addClass('active');
        });
        
        // Character counters
        $('#default_meta_description').on('input', function() {
            const length = $(this).val().length;
            const counter = $(this).siblings('.char-counter');
            if (counter.length === 0) {
                $(this).after('<div class="char-counter">' + length + ' / 160 znaków</div>');
            } else {
                counter.text(length + ' / 160 znaków');
                counter.toggleClass('over-limit', length > 160);
            }
        }).trigger('input');
    });
    
    // Media uploader
    function carni24OpenMediaUploader(fieldId, previewId) {
        const mediaUploader = wp.media({
            title: 'Wybierz obraz',
            button: { text: 'Użyj tego obrazu' },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById(fieldId).value = attachment.id;
            document.getElementById(previewId).innerHTML = 
                '<img src="' + attachment.sizes.medium.url + '" alt="" style="max-width: 200px;" />';
        });
        
        mediaUploader.open();
    }
    
    function carni24ClearImage(fieldId, previewId) {
        document.getElementById(fieldId).value = '';
        document.getElementById(previewId).innerHTML = '';
    }
    
    // SEO Test
    function carni24TestSEO() {
        const resultsDiv = document.getElementById('seo-test-results');
        resultsDiv.innerHTML = '<p>Sprawdzanie...</p>';
        
        jQuery.post(ajaxurl, {
            action: 'carni24_test_seo',
            nonce: '<?php echo wp_create_nonce("carni24_admin_nonce"); ?>'
        }, function(response) {
            if (response.success) {
                let html = '<div class="notice notice-success"><p><strong>Test SEO zakończony:</strong></p><ul>';
                Object.keys(response.data).forEach(key => {
                    const status = response.data[key] ? '✅' : '❌';
                    html += '<li>' + status + ' ' + key + '</li>';
                });
                html += '</ul></div>';
                resultsDiv.innerHTML = html;
            } else {
                resultsDiv.innerHTML = '<div class="notice notice-error"><p>Błąd testu SEO</p></div>';
            }
        });
    }
    
    // Clear cache
    function carni24ClearCache() {
        const resultsDiv = document.getElementById('cache-clear-results');
        resultsDiv.innerHTML = '<p>Czyszczenie cache...</p>';
        
        jQuery.post(ajaxurl, {
            action: 'carni24_clear_cache',
            nonce: '<?php echo wp_create_nonce("carni24_admin_nonce"); ?>'
        }, function(response) {
            if (response.success) {
                resultsDiv.innerHTML = '<div class="notice notice-success"><p>Cache został wyczyszczony!</p></div>';
            } else {
                resultsDiv.innerHTML = '<div class="notice notice-error"><p>Błąd czyszczenia cache</p></div>';
            }
        });
    }
    
    // Regenerate thumbnails
    function carni24RegenerateThumbnails() {
        const progressDiv = document.getElementById('regenerate-progress');
        const statusP = document.getElementById('regenerate-status');
        const progressFill = document.querySelector('.progress-fill');
        
        progressDiv.style.display = 'block';
        progressFill.style.width = '0%';
        
        jQuery.post(ajaxurl, {
            action: 'carni24_regenerate_thumbnails',
            nonce: '<?php echo wp_create_nonce("carni24_admin_nonce"); ?>'
        }, function(response) {
            if (response.success) {
                progressFill.style.width = '100%';
                statusP.textContent = 'Miniaturki zostały zregenerowane!';
                setTimeout(() => {
                    progressDiv.style.display = 'none';
                }, 2000);
            } else {
                statusP.textContent = 'Błąd regeneracji miniaturek';
            }
        });
    }
    
    // Export settings
    function carni24ExportSettings() {
        const settings = <?php echo json_encode($options); ?>;
        const dataStr = JSON.stringify(settings, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        
        const link = document.createElement('a');
        link.href = URL.createObjectURL(dataBlob);
        link.download = 'carni24-settings-' + new Date().toISOString().split('T')[0] + '.json';
        link.click();
    }
    
    // Import settings
    function carni24ImportSettings(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const settings = JSON.parse(e.target.result);
                    if (confirm('Czy na pewno chcesz zaimportować te ustawienia? Obecne ustawienia zostaną zastąpione.')) {
                        // Fill form fields with imported values
                        Object.keys(settings).forEach(key => {
                            const field = document.querySelector('[name="' + key + '"]');
                            if (field) {
                                if (field.type === 'checkbox') {
                                    field.checked = settings[key] == 1;
                                } else {
                                    field.value = settings[key];
                                }
                            }
                        });
                        alert('Ustawienia zostały zaimportowane. Pamiętaj o zapisaniu formularza.');
                    }
                } catch (error) {
                    alert('Błąd importu: nieprawidłowy format pliku.');
                }
            };
            reader.readAsText(input.files[0]);
        }
    }
    </script>
    <?php
}

/**
 * Zapisuje ustawienia motywu
 */
function carni24_save_theme_options() {
    $options = array();
    
    // Lista dozwolonych opcji
    $allowed_options = array(
        'site_name',
        'site_description', 
        'navigation_title',
        'navigation_content',
        'default_meta_description',
        'default_meta_keywords',
        'default_og_image',
        'lazy_loading',
        'minify_css',
        'minify_js',
        'google_analytics',
        'facebook_pixel'
    );
    
    foreach ($allowed_options as $option) {
        if (isset($_POST[$option])) {
            if (in_array($option, array('lazy_loading', 'minify_css', 'minify_js'))) {
                $options[$option] = 1; // Checkbox
            } elseif ($option === 'default_og_image') {
                $options[$option] = absint($_POST[$option]); // ID obrazu
            } else {
                $options[$option] = sanitize_text_field($_POST[$option]);
            }
        } else {
            // Checkboxy nie zaznaczone
            if (in_array($option, array('lazy_loading', 'minify_css', 'minify_js'))) {
                $options[$option] = 0;
            }
        }
    }
    
    update_option('carni24_theme_options', $options);
}

/**
 * Wyświetla aktualnie zdefiniowane rozmiary obrazów
 */
function carni24_display_image_sizes() {
    $sizes = carni24_get_all_image_sizes();
    
    foreach ($sizes as $name => $size) {
        echo '<div class="image-size-item">';
        echo '<strong>' . esc_html($name) . '</strong><br>';
        echo esc_html($size['width']) . ' × ' . esc_html($size['height']);
        if ($size['crop']) {
            echo ' <span class="crop-badge">CROP</span>';
        }
        echo '</div>';
    }
}

/**
 * Pobiera wszystkie rozmiary obrazów
 */
function carni24_get_all_image_sizes() {
    global $_wp_additional_image_sizes;
    
    $sizes = array();
    
    // WordPress domyślne
    $default_sizes = array('thumbnail', 'medium', 'medium_large', 'large');
    foreach ($default_sizes as $size) {
        $sizes[$size] = array(
            'width'  => get_option($size . '_size_w'),
            'height' => get_option($size . '_size_h'),
            'crop'   => get_option($size . '_crop')
        );
    }
    
    // Custom rozmiary
    if (isset($_wp_additional_image_sizes)) {
        $sizes = array_merge($sizes, $_wp_additional_image_sizes);
    }
    
    return $sizes;
}