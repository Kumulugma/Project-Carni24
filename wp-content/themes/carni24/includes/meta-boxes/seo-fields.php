<?php
/**
 * Carni24 SEO Meta Boxes - Ulepszona wersja
 * Meta boxy SEO dla postów, stron i CPT
 * 
 * @package Carni24
 * @subpackage MetaBoxes
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Zastępuje istniejące meta boxy SEO ulepszonym interfejsem
 */
function carni24_override_seo_meta_boxes() {
    // Usuń istniejące meta boxy SEO jeśli istnieją
    remove_meta_box('carni24_seo_settings', 'post', 'normal');
    remove_meta_box('carni24_seo_settings', 'page', 'normal');
    remove_meta_box('carni24_seo_settings', 'species', 'normal');
    remove_meta_box('carni24_seo_settings', 'guides', 'normal');
    
    // Dodaj nowe ulepszone meta boxy
    $post_types = array('post', 'page', 'species', 'guides');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'carni24_seo_settings_improved',
            '🔍 Optymalizacja SEO',
            'carni24_seo_improved_meta_box_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'carni24_override_seo_meta_boxes', 15);

/**
 * Callback dla ulepszonego meta boxa SEO
 */
function carni24_seo_improved_meta_box_callback($post) {
    wp_nonce_field('carni24_seo_improved_meta_box', 'carni24_seo_improved_meta_box_nonce');
    
    // Pobierz istniejące wartości
    $meta_title = get_post_meta($post->ID, '_seo_title', true);
    $meta_description = get_post_meta($post->ID, '_seo_description', true);
    $meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);
    $canonical_url = get_post_meta($post->ID, '_seo_canonical', true);
    $noindex = get_post_meta($post->ID, '_seo_noindex', true);
    $nofollow = get_post_meta($post->ID, '_seo_nofollow', true);
    $og_title = get_post_meta($post->ID, '_seo_og_title', true);
    $og_description = get_post_meta($post->ID, '_seo_og_description', true);
    $og_image = get_post_meta($post->ID, '_seo_og_image', true);
    
    // Domyślne wartości
    $default_title = get_the_title($post->ID);
    $default_description = wp_trim_words(strip_tags(get_post_field('post_content', $post->ID)), 25);
    
    ?>
    <div class="carni24-seo-metabox">
        <!-- Nawigacja zakładek -->
        <ul class="carni24-seo-tabs">
            <li><a href="#seo-basic" class="tab-link active" data-tab="seo-basic">📄 Podstawowe SEO</a></li>
            <li><a href="#seo-social" class="tab-link" data-tab="seo-social">📱 Media społecznościowe</a></li>
            <li><a href="#seo-advanced" class="tab-link" data-tab="seo-advanced">⚙️ Zaawansowane</a></li>
        </ul>

        <!-- Zawartość zakładek -->
        <div class="carni24-seo-content">
            
            <!-- Zakładka: Podstawowe SEO -->
            <div id="seo-basic" class="seo-tab-content active">
                <div class="seo-preview-section">
                    <h4>📋 Podgląd w wynikach wyszukiwania</h4>
                    <div class="google-preview">
                        <div class="preview-title" id="preview-title"><?= esc_html($meta_title ?: $default_title) ?></div>
                        <div class="preview-url"><?= esc_url(get_permalink($post->ID)) ?></div>
                        <div class="preview-description" id="preview-description"><?= esc_html($meta_description ?: $default_description) ?></div>
                    </div>
                </div>

                <div class="seo-field-group">
                    <div class="seo-field">
                        <label for="seo_title">
                            <strong>Tytuł SEO</strong>
                            <span class="field-hint">Optymalnie 50-60 znaków</span>
                        </label>
                        <input type="text" id="seo_title" name="seo_title" 
                               value="<?= esc_attr($meta_title) ?>" 
                               placeholder="<?= esc_attr($default_title) ?>"
                               maxlength="70" />
                        <div class="char-counter">
                            <span class="char-count" id="title-char-count"><?= strlen($meta_title) ?></span>/70 znaków
                            <span class="char-status" id="title-char-status"></span>
                        </div>
                    </div>

                    <div class="seo-field">
                        <label for="seo_description">
                            <strong>Meta opis</strong>
                            <span class="field-hint">Optymalnie 120-160 znaków</span>
                        </label>
                        <textarea id="seo_description" name="seo_description" 
                                  rows="3" maxlength="200"
                                  placeholder="<?= esc_attr($default_description) ?>"><?= esc_textarea($meta_description) ?></textarea>
                        <div class="char-counter">
                            <span class="char-count" id="desc-char-count"><?= strlen($meta_description) ?></span>/200 znaków
                            <span class="char-status" id="desc-char-status"></span>
                        </div>
                    </div>

                    <div class="seo-field">
                        <label for="seo_keywords">
                            <strong>Słowa kluczowe</strong>
                            <span class="field-hint">Oddziel przecinkami, max 10 słów</span>
                        </label>
                        <input type="text" id="seo_keywords" name="seo_keywords" 
                               value="<?= esc_attr($meta_keywords) ?>" 
                               placeholder="roślina mięsożerna, dionea, pielęgnacja" />
                    </div>
                </div>
            </div>

            <!-- Zakładka: Media społecznościowe -->
            <div id="seo-social" class="seo-tab-content">
                <div class="social-preview-section">
                    <h4>📱 Podgląd udostępnienia</h4>
                    <div class="facebook-preview">
                        <div class="social-image">
                            <?php if ($og_image): 
                                $image = wp_get_attachment_image_src($og_image, 'medium'); ?>
                                <img src="<?= esc_url($image[0]) ?>" alt="OG Image preview" />
                            <?php else: ?>
                                <div class="no-image">Brak obrazu</div>
                            <?php endif; ?>
                        </div>
                        <div class="social-content">
                            <div class="social-title" id="social-preview-title"><?= esc_html($og_title ?: $meta_title ?: $default_title) ?></div>
                            <div class="social-description" id="social-preview-desc"><?= esc_html($og_description ?: $meta_description ?: $default_description) ?></div>
                            <div class="social-domain"><?= parse_url(home_url(), PHP_URL_HOST) ?></div>
                        </div>
                    </div>
                </div>

                <div class="seo-field-group">
                    <div class="seo-field">
                        <label for="seo_og_title">
                            <strong>Tytuł dla mediów społecznościowych</strong>
                            <span class="field-hint">Jeśli pusty, użyty zostanie tytuł SEO</span>
                        </label>
                        <input type="text" id="seo_og_title" name="seo_og_title" 
                               value="<?= esc_attr($og_title) ?>" 
                               placeholder="<?= esc_attr($meta_title ?: $default_title) ?>" />
                    </div>

                    <div class="seo-field">
                        <label for="seo_og_description">
                            <strong>Opis dla mediów społecznościowych</strong>
                            <span class="field-hint">Jeśli pusty, użyty zostanie meta opis</span>
                        </label>
                        <textarea id="seo_og_description" name="seo_og_description" 
                                  rows="3"><?= esc_textarea($og_description) ?></textarea>
                    </div>

                    <div class="seo-field">
                        <label for="seo_og_image">
                            <strong>Obraz dla mediów społecznościowych</strong>
                            <span class="field-hint">Optymalny rozmiar: 1200x630px</span>
                        </label>
                        <div class="image-upload-field">
                            <input type="hidden" id="seo_og_image" name="seo_og_image" value="<?= esc_attr($og_image) ?>" />
                            <button type="button" class="button upload-image-button">
                                <?= $og_image ? '🔄 Zmień obraz' : '📷 Dodaj obraz' ?>
                            </button>
                            <button type="button" class="button remove-image-button" <?= !$og_image ? 'style="display:none"' : '' ?>>
                                🗑️ Usuń
                            </button>
                            <div class="image-preview" id="og-image-preview">
                                <?php if ($og_image): 
                                    $image = wp_get_attachment_image_src($og_image, 'medium'); ?>
                                    <img src="<?= esc_url($image[0]) ?>" alt="OG Image" />
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zakładka: Zaawansowane -->
            <div id="seo-advanced" class="seo-tab-content">
                <div class="seo-field-group">
                    <div class="seo-field">
                        <label for="seo_canonical">
                            <strong>Canonical URL</strong>
                            <span class="field-hint">Pozostaw puste dla domyślnego URL</span>
                        </label>
                        <input type="url" id="seo_canonical" name="seo_canonical" 
                               value="<?= esc_attr($canonical_url) ?>" 
                               placeholder="<?= esc_url(get_permalink($post->ID)) ?>" />
                    </div>

                    <div class="seo-field">
                        <label><strong>Ustawienia indeksowania</strong></label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="seo_noindex" value="1" <?= checked($noindex, 1, false) ?> />
                                <span class="checkmark">🚫</span>
                                <strong>No-index</strong> - ukryj przed wyszukiwarkami
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="seo_nofollow" value="1" <?= checked($nofollow, 1, false) ?> />
                                <span class="checkmark">🔗</span>
                                <strong>No-follow</strong> - nie śledź linków na tej stronie
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .carni24-seo-metabox {
        margin: -6px -12px -12px;
        background: #fff;
    }

    /* Zakładki */
    .carni24-seo-tabs {
        margin: 0;
        padding: 0;
        list-style: none;
        border-bottom: 1px solid #e1e1e1;
        background: #f9f9f9;
        display: flex;
    }

    .carni24-seo-tabs li {
        margin: 0;
    }

    .carni24-seo-tabs .tab-link {
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        color: #646970;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .carni24-seo-tabs .tab-link:hover {
        color: #135e96;
        background: #f0f0f1;
    }

    .carni24-seo-tabs .tab-link.active {
        color: #135e96;
        border-bottom-color: #135e96;
        background: #fff;
    }

    /* Zawartość zakładek */
    .carni24-seo-content {
        padding: 20px;
    }

    .seo-tab-content {
        display: none;
    }

    .seo-tab-content.active {
        display: block;
    }

    /* Grupy pól */
    .seo-field-group {
        display: grid;
        gap: 20px;
    }

    .seo-field {
        margin-bottom: 0;
    }

    .seo-field label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1d2327;
    }

    .field-hint {
        font-weight: 400;
        color: #646970;
        font-size: 13px;
        margin-left: 8px;
    }

    .seo-field input[type="text"],
    .seo-field input[type="url"],
    .seo-field textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 14px;
        line-height: 1.4;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .seo-field input:focus,
    .seo-field textarea:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: none;
    }

    /* Licznik znaków */
    .char-counter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
        font-size: 12px;
        color: #646970;
    }

    .char-count {
        font-weight: 600;
    }

    .char-status.optimal {
        color: #00a32a;
    }

    .char-status.warning {
        color: #dba617;
    }

    .char-status.error {
        color: #d63638;
    }

    /* Podglądy */
    .seo-preview-section,
    .social-preview-section {
        background: #f6f7f7;
        border: 1px solid #c3c4c7;
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .seo-preview-section h4,
    .social-preview-section h4 {
        margin: 0 0 12px;
        color: #1d2327;
        font-size: 14px;
        font-weight: 600;
    }

    /* Podgląd Google */
    .google-preview {
        background: #fff;
        border: 1px solid #dadce0;
        border-radius: 8px;
        padding: 16px;
        font-family: arial, sans-serif;
    }

    .preview-title {
        color: #1a0dab;
        font-size: 18px;
        line-height: 1.3;
        margin-bottom: 4px;
        cursor: pointer;
    }

    .preview-url {
        color: #006621;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .preview-description {
        color: #4d5156;
        font-size: 14px;
        line-height: 1.4;
    }

    /* Podgląd Facebook */
    .facebook-preview {
        background: #fff;
        border: 1px solid #dadde1;
        border-radius: 8px;
        overflow: hidden;
        max-width: 500px;
    }

    .social-image {
        height: 200px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #dadde1;
    }

    .social-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .no-image {
        color: #65676b;
        font-size: 14px;
    }

    .social-content {
        padding: 12px;
    }

    .social-title {
        color: #1d2327;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: 4px;
    }

    .social-description {
        color: #65676b;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 4px;
    }

    .social-domain {
        color: #65676b;
        font-size: 13px;
        text-transform: uppercase;
    }

    /* Upload obrazu */
    .image-upload-field {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        flex-wrap: wrap;
    }

    .image-preview {
        margin-top: 10px;
        max-width: 200px;
    }

    .image-preview img {
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

    .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 10px;
        border: 1px solid #c3c4c7;
        border-radius: 4px;
        transition: background-color 0.15s ease;
    }

    .checkbox-label:hover {
        background-color: #f6f7f7;
    }

    .checkbox-label input[type="checkbox"] {
        margin-right: 8px;
    }

    .checkmark {
        margin-right: 8px;
        font-size: 16px;
    }

    /* Responsywność */
    @media (max-width: 782px) {
        .carni24-seo-tabs {
            flex-direction: column;
        }
        
        .carni24-seo-content {
            padding: 15px;
        }
        
        .facebook-preview {
            max-width: 100%;
        }
        
        .image-upload-field {
            flex-direction: column;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obsługa zakładek
        const tabLinks = document.querySelectorAll('.carni24-seo-tabs .tab-link');
        const tabContents = document.querySelectorAll('.seo-tab-content');

        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Usuń aktywne klasy
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Dodaj aktywną klasę
                this.classList.add('active');
                const targetTab = document.getElementById(this.getAttribute('data-tab'));
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            });
        });

        // Licznik znaków i podglądy
        const titleInput = document.getElementById('seo_title');
        const titleCounter = document.getElementById('title-char-count');
        const titleStatus = document.getElementById('title-char-status');
        const previewTitle = document.getElementById('preview-title');
        const socialPreviewTitle = document.getElementById('social-preview-title');

        function updateTitleCounter() {
            const length = titleInput.value.length;
            titleCounter.textContent = length;
            
            const displayTitle = titleInput.value || titleInput.placeholder;
            previewTitle.textContent = displayTitle;
            socialPreviewTitle.textContent = displayTitle;
            
            titleStatus.className = 'char-status';
            if (length >= 50 && length <= 60) {
                titleStatus.classList.add('optimal');
                titleStatus.textContent = '✓ Optymalna długość';
            } else if (length > 60 && length <= 70) {
                titleStatus.classList.add('warning');
                titleStatus.textContent = '⚠ Może być za długi';
            } else if (length > 70) {
                titleStatus.classList.add('error');
                titleStatus.textContent = '✗ Za długi';
            } else if (length > 0) {
                titleStatus.classList.add('warning');
                titleStatus.textContent = '⚠ Może być za krótki';
            }
        }

        if (titleInput) {
            titleInput.addEventListener('input', updateTitleCounter);
            updateTitleCounter();
        }

        // Podobnie dla opisu
        const descInput = document.getElementById('seo_description');
        const descCounter = document.getElementById('desc-char-count');
        const descStatus = document.getElementById('desc-char-status');
        const previewDesc = document.getElementById('preview-description');
        const socialPreviewDesc = document.getElementById('social-preview-desc');

        function updateDescCounter() {
            const length = descInput.value.length;
            descCounter.textContent = length;
            
            const displayDesc = descInput.value || descInput.placeholder;
            previewDesc.textContent = displayDesc;
            socialPreviewDesc.textContent = displayDesc;
            
            descStatus.className = 'char-status';
            if (length >= 120 && length <= 160) {
                descStatus.classList.add('optimal');
                descStatus.textContent = '✓ Optymalna długość';
            } else if (length > 160 && length <= 200) {
                descStatus.classList.add('warning');
                descStatus.textContent = '⚠ Może być za długi';
            } else if (length > 200) {
                descStatus.classList.add('error');
                descStatus.textContent = '✗ Za długi';
            } else if (length > 0) {
                descStatus.classList.add('warning');
                descStatus.textContent = '⚠ Może być za krótki';
            }
        }

        if (descInput) {
            descInput.addEventListener('input', updateDescCounter);
            updateDescCounter();
        }

        // Upload obrazu OG
        const uploadButton = document.querySelector('.upload-image-button');
        const removeButton = document.querySelector('.remove-image-button');
        const imageInput = document.getElementById('seo_og_image');
        const imagePreview = document.getElementById('og-image-preview');

        if (uploadButton) {
            uploadButton.addEventListener('click', function() {
                const mediaUploader = wp.media({
                    title: 'Wybierz obraz dla mediów społecznościowych',
                    button: { text: 'Wybierz obraz' },
                    multiple: false,
                    library: { type: 'image' }
                });

                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    imageInput.value = attachment.id;
                    imagePreview.innerHTML = '<img src="' + attachment.sizes.medium.url + '" alt="OG Image" />';
                    uploadButton.textContent = '🔄 Zmień obraz';
                    removeButton.style.display = 'inline-block';
                });

                mediaUploader.open();
            });
        }

        if (removeButton) {
            removeButton.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.innerHTML = '';
                uploadButton.textContent = '📷 Dodaj obraz';
                removeButton.style.display = 'none';
            });
        }
    });
    </script>
    <?php
}

/**
 * Zapisuje meta dane SEO dla ulepszonego interfejsu
 */
function carni24_save_seo_improved_meta($post_id) {
    // Sprawdzenia bezpieczeństwa
    if (!isset($_POST['carni24_seo_improved_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['carni24_seo_improved_meta_box_nonce'], 'carni24_seo_improved_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Lista pól do zapisania
    $seo_fields = array(
        'seo_title' => 'sanitize_text_field',
        'seo_description' => 'sanitize_textarea_field',
        'seo_keywords' => 'sanitize_text_field',
        'seo_canonical' => 'esc_url_raw',
        'seo_og_title' => 'sanitize_text_field',
        'seo_og_description' => 'sanitize_textarea_field'
    );
    
    // Zapisz pola tekstowe
    foreach ($seo_fields as $field => $sanitize_function) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_function($_POST[$field]));
        }
    }
    
    // Zapisz checkboxy
    update_post_meta($post_id, '_seo_noindex', isset($_POST['seo_noindex']) ? 1 : 0);
    update_post_meta($post_id, '_seo_nofollow', isset($_POST['seo_nofollow']) ? 1 : 0);
    
    // Zapisz OG Image
    if (isset($_POST['seo_og_image'])) {
        update_post_meta($post_id, '_seo_og_image', absint($_POST['seo_og_image']));
    }
}
add_action('save_post', 'carni24_save_seo_improved_meta');