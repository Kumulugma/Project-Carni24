<?php
/**
 * Meta boxes dla wyróżnień (features) - Ulepszona wersja
 * 
 * @package Carni24
 * @subpackage MetaBoxes
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Zastępuje istniejące meta boxy dla wyróżnień ulepszonym interfejsem
 */
function carni24_override_feature_meta_boxes() {
    // Usuń istniejące meta boxy feature jeśli istnieją
    remove_meta_box('carni24_feature_settings', 'post', 'side');
    remove_meta_box('carni24_feature_settings', 'page', 'side');
    remove_meta_box('carni24_feature_settings', 'species', 'side');
    remove_meta_box('carni24_feature_settings', 'guides', 'side');
    
    // Dodaj nowe ulepszone meta boxy
    $post_types = array('post', 'page', 'species', 'guides');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'carni24_feature_settings_improved',
            '⭐ Ustawienia wyróżnienia',
            'carni24_feature_improved_meta_box_callback',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'carni24_override_feature_meta_boxes', 15);

/**
 * Callback dla ulepszonego meta boxa wyróżnień
 */
function carni24_feature_improved_meta_box_callback($post) {
    wp_nonce_field('carni24_feature_improved_meta_box', 'carni24_feature_improved_meta_box_nonce');
    
    // Pobierz istniejące wartości
    $is_featured = get_post_meta($post->ID, '_is_featured', true);
    $feature_order = get_post_meta($post->ID, '_feature_order', true);
    $feature_title = get_post_meta($post->ID, '_feature_title', true);
    $feature_subtitle = get_post_meta($post->ID, '_feature_subtitle', true);
    $feature_excerpt = get_post_meta($post->ID, '_feature_excerpt', true);
    $feature_image = get_post_meta($post->ID, '_feature_image', true);
    $feature_color = get_post_meta($post->ID, '_feature_color', true);
    $feature_layout = get_post_meta($post->ID, '_feature_layout', true);
    $show_on_homepage = get_post_meta($post->ID, '_show_on_homepage', true);
    $show_in_slider = get_post_meta($post->ID, '_show_in_slider', true);
    $feature_badge = get_post_meta($post->ID, '_feature_badge', true);
    
    // Domyślne wartości
    $default_title = get_the_title($post->ID);
    $default_excerpt = wp_trim_words(get_post_field('post_content', $post->ID), 20);
    ?>
    
    <div class="carni24-feature-metabox">
        
        <!-- Status wyróżnienia -->
        <div class="feature-status-section">
            <label class="feature-toggle">
                <input type="checkbox" name="is_featured" value="1" <?= checked($is_featured, 1, false) ?> />
                <span class="toggle-slider"></span>
                <strong>Wyróżnij ten wpis</strong>
            </label>
            <p class="description">Włącz wyróżnienie aby wpis pojawił się w specjalnych sekcjach.</p>
        </div>

        <!-- Ustawienia wyróżnienia -->
        <div class="feature-settings" <?= !$is_featured ? 'style="display:none"' : '' ?>>
            
            <!-- Podstawowe ustawienia -->
            <div class="feature-section">
                <h4>🎯 Podstawowe ustawienia</h4>
                
                <div class="feature-field">
                    <label for="feature_order">
                        <strong>Kolejność wyświetlania</strong>
                        <span class="field-hint">Niższa liczba = wyższa pozycja</span>
                    </label>
                    <input type="number" id="feature_order" name="feature_order" 
                           value="<?= esc_attr($feature_order ?: '1') ?>" 
                           min="1" max="100" step="1" />
                </div>

                <div class="feature-field">
                    <label for="feature_badge">
                        <strong>Odznaka</strong>
                        <span class="field-hint">Krótki tekst wyświetlany na miniaturze</span>
                    </label>
                    <select id="feature_badge" name="feature_badge">
                        <option value="">Brak odznaki</option>
                        <option value="HOT" <?= selected($feature_badge, 'HOT', false) ?>>🔥 HOT</option>
                        <option value="NEW" <?= selected($feature_badge, 'NEW', false) ?>>✨ NEW</option>
                        <option value="POPULAR" <?= selected($feature_badge, 'POPULAR', false) ?>>👑 POPULAR</option>
                        <option value="EXCLUSIVE" <?= selected($feature_badge, 'EXCLUSIVE', false) ?>>💎 EXCLUSIVE</option>
                        <option value="UPDATED" <?= selected($feature_badge, 'UPDATED', false) ?>>🆙 UPDATED</option>
                        <option value="GUIDE" <?= selected($feature_badge, 'GUIDE', false) ?>>📖 GUIDE</option>
                        <option value="TIP" <?= selected($feature_badge, 'TIP', false) ?>>💡 TIP</option>
                    </select>
                </div>

                <div class="feature-field">
                    <label for="feature_layout">
                        <strong>Układ wyświetlania</strong>
                    </label>
                    <select id="feature_layout" name="feature_layout">
                        <option value="standard" <?= selected($feature_layout, 'standard', false) ?>>📱 Standardowy</option>
                        <option value="wide" <?= selected($feature_layout, 'wide', false) ?>>📺 Szeroki</option>
                        <option value="card" <?= selected($feature_layout, 'card', false) ?>>🃏 Karta</option>
                        <option value="minimal" <?= selected($feature_layout, 'minimal', false) ?>>⚪ Minimalny</option>
                    </select>
                </div>
            </div>

            <!-- Treść wyróżnienia -->
            <div class="feature-section">
                <h4>📝 Niestandardowa treść</h4>
                <p class="description">Pozostaw puste aby użyć domyślnych wartości z wpisu.</p>
                
                <div class="feature-field">
                    <label for="feature_title">
                        <strong>Tytuł wyróżnienia</strong>
                    </label>
                    <input type="text" id="feature_title" name="feature_title" 
                           value="<?= esc_attr($feature_title) ?>" 
                           placeholder="<?= esc_attr($default_title) ?>" />
                </div>

                <div class="feature-field">
                    <label for="feature_subtitle">
                        <strong>Podtytuł</strong>
                    </label>
                    <input type="text" id="feature_subtitle" name="feature_subtitle" 
                           value="<?= esc_attr($feature_subtitle) ?>" 
                           placeholder="Opcjonalny podtytuł..." />
                </div>

                <div class="feature-field">
                    <label for="feature_excerpt">
                        <strong>Opis wyróżnienia</strong>
                    </label>
                    <textarea id="feature_excerpt" name="feature_excerpt" 
                              rows="3" maxlength="200"
                              placeholder="<?= esc_attr($default_excerpt) ?>"><?= esc_textarea($feature_excerpt) ?></textarea>
                    <div class="char-counter">
                        <span class="char-count" id="excerpt-char-count"><?= strlen($feature_excerpt) ?></span>/200 znaków
                    </div>
                </div>
            </div>

            <!-- Wygląd -->
            <div class="feature-section">
                <h4>🎨 Wygląd</h4>
                
                <div class="feature-field">
                    <label for="feature_color">
                        <strong>Kolor akcentu</strong>
                    </label>
                    <div class="color-picker-field">
                        <input type="color" id="feature_color" name="feature_color" 
                               value="<?= esc_attr($feature_color ?: '#1976d2') ?>" />
                        <input type="text" class="color-text" 
                               value="<?= esc_attr($feature_color ?: '#1976d2') ?>" 
                               placeholder="#1976d2" />
                        <button type="button" class="reset-color" title="Resetuj do domyślnego">🔄</button>
                    </div>
                </div>

                <div class="feature-field">
                    <label for="feature_image_button">
                        <strong>Niestandardowy obraz wyróżnienia</strong>
                        <span class="field-hint">Optymalny rozmiar: 800x600px</span>
                    </label>
                    <div class="image-upload-field">
                        <input type="hidden" id="feature_image" name="feature_image" value="<?= esc_attr($feature_image) ?>" />
                        <button type="button" class="button upload-feature-image">
                            <?= $feature_image ? '🔄 Zmień obraz' : '📷 Dodaj obraz' ?>
                        </button>
                        <button type="button" class="button remove-feature-image" <?= !$feature_image ? 'style="display:none"' : '' ?>>
                            🗑️ Usuń
                        </button>
                        <div class="feature-image-preview" id="feature-image-preview">
                            <?php if ($feature_image): 
                                $image = wp_get_attachment_image_src($feature_image, 'medium'); ?>
                                <img src="<?= esc_url($image[0]) ?>" alt="Feature Image" />
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Miejsca wyświetlania -->
            <div class="feature-section">
                <h4>📍 Miejsca wyświetlania</h4>
                
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" name="show_on_homepage" value="1" <?= checked($show_on_homepage, 1, false) ?> />
                        <span class="checkmark">🏠</span>
                        <strong>Strona główna</strong>
                        <span class="checkbox-desc">Pokaż w sekcji wyróżnionych na głównej</span>
                    </label>
                    
                    <label class="checkbox-item">
                        <input type="checkbox" name="show_in_slider" value="1" <?= checked($show_in_slider, 1, false) ?> />
                        <span class="checkmark">🎠</span>
                        <strong>Slider główny</strong>
                        <span class="checkbox-desc">Pokaż w sliderze na górze strony</span>
                    </label>
                </div>
            </div>

            <!-- Podgląd wyróżnienia -->
            <div class="feature-preview-section">
                <h4>👁️ Podgląd wyróżnienia</h4>
                <div class="feature-preview-card">
                    <div class="preview-image">
                        <?php if ($feature_image): 
                            $image = wp_get_attachment_image_src($feature_image, 'thumbnail'); ?>
                            <img src="<?= esc_url($image[0]) ?>" alt="Preview" />
                        <?php else: ?>
                            <div class="no-preview-image">📷</div>
                        <?php endif; ?>
                        <?php if ($feature_badge): ?>
                            <div class="preview-badge"><?= esc_html($feature_badge) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="preview-content">
                        <div class="preview-title" id="preview-feature-title">
                            <?= esc_html($feature_title ?: $default_title) ?>
                        </div>
                        <?php if ($feature_subtitle): ?>
                            <div class="preview-subtitle" id="preview-feature-subtitle">
                                <?= esc_html($feature_subtitle) ?>
                            </div>
                        <?php endif; ?>
                        <div class="preview-excerpt" id="preview-feature-excerpt">
                            <?= esc_html($feature_excerpt ?: $default_excerpt) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .carni24-feature-metabox {
        padding: 5px 0;
    }

    /* Toggle wyróżnienia */
    .feature-status-section {
        margin-bottom: 20px;
        padding: 15px;
        background: #f6f7f7;
        border-radius: 6px;
        border-left: 4px solid #ffb74d;
    }

    .feature-toggle {
        display: flex;
        align-items: center;
        cursor: pointer;
        margin-bottom: 8px;
    }

    .feature-toggle input[type="checkbox"] {
        display: none;
    }

    .toggle-slider {
        position: relative;
        width: 50px;
        height: 24px;
        background: #ccc;
        border-radius: 24px;
        margin-right: 12px;
        transition: background 0.3s ease;
    }

    .toggle-slider:before {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        top: 2px;
        left: 2px;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .feature-toggle input[type="checkbox"]:checked + .toggle-slider {
        background: #4caf50;
    }

    .feature-toggle input[type="checkbox"]:checked + .toggle-slider:before {
        transform: translateX(26px);
    }

    /* Sekcje */
    .feature-section {
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    .feature-section h4 {
        margin: 0 0 12px;
        color: #495057;
        font-size: 14px;
        font-weight: 600;
        padding-bottom: 6px;
        border-bottom: 1px solid #dee2e6;
    }

    /* Pola formularza */
    .feature-field {
        margin-bottom: 15px;
    }

    .feature-field:last-child {
        margin-bottom: 0;
    }

    .feature-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #1d2327;
        font-size: 13px;
    }

    .field-hint {
        font-weight: 400;
        color: #646970;
        font-size: 12px;
        margin-left: 4px;
    }

    .feature-field input[type="text"],
    .feature-field input[type="number"],
    .feature-field select,
    .feature-field textarea {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 13px;
        transition: border-color 0.15s ease-in-out;
    }

    .feature-field input:focus,
    .feature-field select:focus,
    .feature-field textarea:focus {
        border-color: #007cba;
        box-shadow: 0 0 0 1px #007cba;
        outline: none;
    }

    /* Licznik znaków */
    .char-counter {
        text-align: right;
        font-size: 11px;
        color: #646970;
        margin-top: 4px;
    }

    /* Color picker */
    .color-picker-field {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .color-picker-field input[type="color"] {
        width: 40px;
        height: 32px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .color-picker-field .color-text {
        flex: 1;
        font-family: monospace;
    }

    .color-picker-field .reset-color {
        background: none;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        font-size: 12px;
    }

    /* Upload obrazu */
    .image-upload-field {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        flex-wrap: wrap;
    }

    .feature-image-preview {
        width: 100%;
        margin-top: 8px;
    }

    .feature-image-preview img {
        max-width: 100%;
        height: auto;
        border: 1px solid #c3c4c7;
        border-radius: 4px;
    }

    /* Checkboxy */
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .checkbox-item {
        display: flex;
        align-items: flex-start;
        cursor: pointer;
        padding: 10px;
        border: 1px solid #c3c4c7;
        border-radius: 4px;
        transition: background-color 0.15s ease;
    }

    .checkbox-item:hover {
        background-color: #f6f7f7;
    }

    .checkbox-item input[type="checkbox"] {
        margin-right: 8px;
        margin-top: 2px;
    }

    .checkmark {
        margin-right: 8px;
        font-size: 16px;
    }

    .checkbox-item strong {
        display: block;
        margin-bottom: 2px;
    }

    .checkbox-desc {
        font-size: 12px;
        color: #646970;
    }

    /* Podgląd wyróżnienia */
    .feature-preview-section {
        background: #fff;
        border: 1px solid #c3c4c7;
        border-radius: 6px;
        padding: 15px;
    }

    .feature-preview-card {
        display: flex;
        gap: 12px;
        padding: 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: #f8f9fa;
    }

    .preview-image {
        position: relative;
        width: 60px;
        height: 60px;
        flex-shrink: 0;
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 4px;
    }

    .no-preview-image {
        width: 100%;
        height: 100%;
        background: #e9ecef;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #6c757d;
    }

    .preview-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #dc3545;
        color: white;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: bold;
    }

    .preview-content {
        flex: 1;
        min-width: 0;
    }

    .preview-title {
        font-weight: 600;
        font-size: 14px;
        color: #1d2327;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .preview-subtitle {
        font-size: 12px;
        color: #646970;
        margin-bottom: 4px;
        font-style: italic;
    }

    .preview-excerpt {
        font-size: 12px;
        color: #495057;
        line-height: 1.4;
    }

    /* Responsywność */
    @media (max-width: 600px) {
        .feature-preview-card {
            flex-direction: column;
        }
        
        .preview-image {
            width: 80px;
            height: 80px;
            align-self: center;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const featuredCheckbox = document.querySelector('input[name="is_featured"]');
        const featureSettings = document.querySelector('.feature-settings');
        
        // Toggle ustawień wyróżnienia
        if (featuredCheckbox && featureSettings) {
            featuredCheckbox.addEventListener('change', function() {
                featureSettings.style.display = this.checked ? 'block' : 'none';
            });
        }

        // Licznik znaków dla excerpta
        const excerptField = document.getElementById('feature_excerpt');
        const excerptCounter = document.getElementById('excerpt-char-count');
        
        if (excerptField && excerptCounter) {
            excerptField.addEventListener('input', function() {
                excerptCounter.textContent = this.value.length;
                
                // Aktualizuj podgląd
                const previewExcerpt = document.getElementById('preview-feature-excerpt');
                if (previewExcerpt) {
                    previewExcerpt.textContent = this.value || this.placeholder;
                }
            });
        }

        // Aktualizacja podglądu tytułu
        const titleField = document.getElementById('feature_title');
        if (titleField) {
            titleField.addEventListener('input', function() {
                const previewTitle = document.getElementById('preview-feature-title');
                if (previewTitle) {
                    previewTitle.textContent = this.value || this.placeholder;
                }
            });
        }

        // Aktualizacja podglądu podtytułu
        const subtitleField = document.getElementById('feature_subtitle');
        if (subtitleField) {
            subtitleField.addEventListener('input', function() {
                let previewSubtitle = document.getElementById('preview-feature-subtitle');
                if (!previewSubtitle && this.value) {
                    // Utwórz element podtytułu jeśli nie istnieje
                    previewSubtitle = document.createElement('div');
                    previewSubtitle.id = 'preview-feature-subtitle';
                    previewSubtitle.className = 'preview-subtitle';
                    
                    const previewTitle = document.getElementById('preview-feature-title');
                    previewTitle.parentNode.insertBefore(previewSubtitle, previewTitle.nextSibling);
                }
                
                if (previewSubtitle) {
                    if (this.value) {
                        previewSubtitle.textContent = this.value;
                        previewSubtitle.style.display = 'block';
                    } else {
                        previewSubtitle.style.display = 'none';
                    }
                }
            });
        }

        // Color picker synchronizacja
        const colorPicker = document.getElementById('feature_color');
        const colorText = document.querySelector('.color-text');
        const resetColorBtn = document.querySelector('.reset-color');
        
        if (colorPicker && colorText) {
            colorPicker.addEventListener('input', function() {
                colorText.value = this.value;
            });
            
            colorText.addEventListener('input', function() {
                if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                    colorPicker.value = this.value;
                }
            });
        }
        
        if (resetColorBtn) {
            resetColorBtn.addEventListener('click', function() {
                colorPicker.value = '#1976d2';
                colorText.value = '#1976d2';
            });
        }

        // Upload obrazu wyróżnienia
        const uploadBtn = document.querySelector('.upload-feature-image');
        const removeBtn = document.querySelector('.remove-feature-image');
        const imageInput = document.getElementById('feature_image');
        const imagePreview = document.getElementById('feature-image-preview');

        if (uploadBtn) {
            uploadBtn.addEventListener('click', function() {
                const mediaUploader = wp.media({
                    title: 'Wybierz obraz wyróżnienia',
                    button: { text: 'Wybierz obraz' },
                    multiple: false,
                    library: { type: 'image' }
                });

                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    imageInput.value = attachment.id;
                    
                    // Aktualizuj podgląd w ustawieniach
                    const imageSize = attachment.sizes.medium || attachment.sizes.full;
                    imagePreview.innerHTML = '<img src="' + imageSize.url + '" alt="Feature Image" />';
                    
                    // Aktualizuj podgląd w karcie
                    const previewImage = document.querySelector('.preview-image img');
                    if (previewImage) {
                        const thumbSize = attachment.sizes.thumbnail || attachment.sizes.medium || attachment.sizes.full;
                        previewImage.src = thumbSize.url;
                    } else {
                        const noImageDiv = document.querySelector('.no-preview-image');
                        if (noImageDiv) {
                            const thumbSize = attachment.sizes.thumbnail || attachment.sizes.medium || attachment.sizes.full;
                            noImageDiv.parentNode.innerHTML = '<img src="' + thumbSize.url + '" alt="Preview" />';
                        }
                    }
                    
                    uploadBtn.textContent = '🔄 Zmień obraz';
                    removeBtn.style.display = 'inline-block';
                });

                mediaUploader.open();
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.innerHTML = '';
                
                // Przywróć domyślny podgląd
                const previewImageContainer = document.querySelector('.preview-image');
                if (previewImageContainer) {
                    previewImageContainer.innerHTML = '<div class="no-preview-image">📷</div>';
                }
                
                uploadBtn.textContent = '📷 Dodaj obraz';
                removeBtn.style.display = 'none';
            });
        }

        // Aktualizacja podglądu odznaki
        const badgeSelect = document.getElementById('feature_badge');
        if (badgeSelect) {
            badgeSelect.addEventListener('change', function() {
                let previewBadge = document.querySelector('.preview-badge');
                
                if (this.value) {
                    if (!previewBadge) {
                        previewBadge = document.createElement('div');
                        previewBadge.className = 'preview-badge';
                        document.querySelector('.preview-image').appendChild(previewBadge);
                    }
                    previewBadge.textContent = this.value;
                    previewBadge.style.display = 'block';
                } else if (previewBadge) {
                    previewBadge.style.display = 'none';
                }
            });
        }

        // Auto-save draft przy zmianie ważnych ustawień
        const importantFields = ['is_featured', 'feature_order', 'show_on_homepage', 'show_in_slider'];
        importantFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('change', function() {
                    // Dodaj wizualną wskazówkę o niezapisanych zmianach
                    const saveButton = document.getElementById('publish') || document.getElementById('save-post');
                    if (saveButton && !saveButton.classList.contains('unsaved-changes')) {
                        saveButton.classList.add('unsaved-changes');
                        saveButton.style.boxShadow = '0 0 0 2px #ffb74d';
                    }
                });
            }
        });
    });
    </script>
    <?php
}

/**
 * Zapisuje meta dane wyróżnień - ulepszona wersja
 */
function carni24_save_feature_improved_meta($post_id) {
    // Sprawdzenia bezpieczeństwa
    if (!isset($_POST['carni24_feature_improved_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['carni24_feature_improved_meta_box_nonce'], 'carni24_feature_improved_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Lista pól tekstowych do zapisania
    $feature_fields = array(
        'feature_order' => 'intval',
        'feature_title' => 'sanitize_text_field',
        'feature_subtitle' => 'sanitize_text_field', 
        'feature_excerpt' => 'sanitize_textarea_field',
        'feature_color' => 'sanitize_hex_color',
        'feature_layout' => 'sanitize_text_field',
        'feature_badge' => 'sanitize_text_field'
    );
    
    // Zapisz pola tekstowe
    foreach ($feature_fields as $field => $sanitize_function) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            if (function_exists($sanitize_function)) {
                $value = $sanitize_function($value);
            }
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
    
    // Zapisz checkboxy
    $checkbox_fields = array('is_featured', 'show_on_homepage', 'show_in_slider');
    foreach ($checkbox_fields as $field) {
        update_post_meta($post_id, '_' . $field, isset($_POST[$field]) ? 1 : 0);
    }
    
    // Zapisz obraz wyróżnienia
    if (isset($_POST['feature_image'])) {
        update_post_meta($post_id, '_feature_image', absint($_POST['feature_image']));
    }
}
add_action('save_post', 'carni24_save_feature_improved_meta');