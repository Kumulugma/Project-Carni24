<?php
/**
 * Carni24 SEO Meta Boxes - Kompletna wersja
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
            <li><a href="#seo-analysis" class="tab-link" data-tab="seo-analysis">📊 Analiza SEO</a></li>
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
                        <div class="keywords-preview" id="keywords-preview"></div>
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
                            <div class="social-url"><?= esc_url(home_url()) ?></div>
                        </div>
                    </div>
                </div>

                <div class="seo-field-group">
                    <div class="seo-field">
                        <label for="seo_og_title">
                            <strong>Tytuł dla mediów społecznościowych</strong>
                            <span class="field-hint">Pozostaw puste dla domyślnego</span>
                        </label>
                        <input type="text" id="seo_og_title" name="seo_og_title" 
                               value="<?= esc_attr($og_title) ?>" 
                               placeholder="<?= esc_attr($meta_title ?: $default_title) ?>"
                               maxlength="70" />
                    </div>

                    <div class="seo-field">
                        <label for="seo_og_description">
                            <strong>Opis dla mediów społecznościowych</strong>
                            <span class="field-hint">Pozostaw puste dla domyślnego</span>
                        </label>
                        <textarea id="seo_og_description" name="seo_og_description" 
                                  rows="3" maxlength="200"
                                  placeholder="<?= esc_attr($meta_description ?: $default_description) ?>"><?= esc_textarea($og_description) ?></textarea>
                    </div>

                    <div class="seo-field">
                        <label><strong>Obraz dla mediów społecznościowych</strong></label>
                        <div class="image-upload-section">
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

            <!-- Zakładka: Analiza SEO -->
            <div id="seo-analysis" class="seo-tab-content">
                <div class="seo-analysis-section">
                    <h4>📊 Analiza SEO</h4>
                    <div class="seo-score-overview">
                        <div class="seo-score-circle">
                            <div class="score-number" id="seo-score">0</div>
                            <div class="score-label">Score</div>
                        </div>
                        <div class="seo-status-list" id="seo-status-list">
                            <!-- Dynamicznie generowane przez JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FALLBACK CSS - ładuje się zawsze, żeby zakładki działały -->
    <style>
    .carni24-seo-metabox {
        margin: -6px -12px -12px;
        background: #fff;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .carni24-seo-tabs {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        background: #f1f1f1;
        border-bottom: 1px solid #ccd0d4;
    }
    
    .carni24-seo-tabs li {
        flex: 1;
    }
    
    .carni24-seo-tabs .tab-link {
        display: block;
        padding: 12px 16px;
        text-decoration: none;
        color: #555;
        font-weight: 500;
        font-size: 13px;
        border-bottom: 3px solid transparent;
        text-align: center;
        transition: all 0.2s ease;
    }
    
    .carni24-seo-tabs .tab-link:hover {
        background: rgba(255,255,255,0.5);
        color: #23282d;
        text-decoration: none;
    }
    
    .carni24-seo-tabs .tab-link.active {
        background: #fff;
        color: #0073aa;
        border-bottom-color: #0073aa;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .carni24-seo-content {
        padding: 20px;
    }
    
    .seo-tab-content {
        display: none;
    }
    
    .seo-tab-content.active {
        display: block;
    }
    
    .seo-field {
        margin-bottom: 20px;
    }
    
    .seo-field label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .field-hint {
        font-weight: normal;
        color: #666;
        font-size: 12px;
        margin-left: 8px;
    }
    
    .seo-field input[type="text"],
    .seo-field input[type="url"],
    .seo-field textarea {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }
    
    .seo-field input:focus,
    .seo-field textarea:focus {
        border-color: #0073aa;
        box-shadow: 0 0 0 1px #0073aa;
        outline: none;
    }
    
    .char-counter {
        margin-top: 5px;
        font-size: 12px;
        color: #666;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .char-count {
        font-weight: 500;
    }
    
    .char-status {
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .char-status.good {
        background: #46b450;
        color: #fff;
    }
    
    .char-status.warning {
        background: #ffb900;
        color: #fff;
    }
    
    .char-status.error {
        background: #dc3232;
        color: #fff;
    }
    
    .google-preview {
        max-width: 600px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid #4285f4;
        font-family: arial, sans-serif;
    }
    
    .preview-title {
        color: #1a0dab;
        font-size: 18px;
        font-weight: normal;
        line-height: 1.3;
        margin-bottom: 2px;
        cursor: pointer;
    }
    
    .preview-title:hover {
        text-decoration: underline;
    }
    
    .preview-url {
        color: #006621;
        font-size: 14px;
        margin-bottom: 6px;
        word-break: break-all;
    }
    
    .preview-description {
        color: #545454;
        font-size: 14px;
        line-height: 1.4;
        max-height: 2.8em;
        overflow: hidden;
    }
    
    .keywords-preview {
        margin-top: 8px;
        min-height: 24px;
    }
    
    .keyword-tag {
        display: inline-block;
        background: #e3f2fd;
        color: #1565c0;
        padding: 4px 8px;
        margin: 2px 4px 2px 0;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .keyword-tag:hover {
        background: #bbdefb;
        transform: translateY(-1px);
    }
    
    .facebook-preview {
        max-width: 500px;
        border: 1px solid #dadde1;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .social-image {
        height: 200px;
        background: #f5f6f7;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .social-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .no-image {
        color: #8a8d91;
        font-size: 14px;
    }
    
    .social-content {
        padding: 12px;
        border-top: 1px solid #dadde1;
    }
    
    .social-title {
        font-weight: 600;
        color: #1d2129;
        font-size: 16px;
        line-height: 1.3;
        margin-bottom: 4px;
    }
    
    .social-description {
        color: #606770;
        font-size: 14px;
        line-height: 1.4;
        margin-bottom: 6px;
    }
    
    .social-url {
        color: #8a8d91;
        font-size: 12px;
        text-transform: uppercase;
    }
    
    .image-upload-section {
        margin-top: 10px;
    }
    
    .image-upload-section .button {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    
    .image-preview {
        margin-top: 10px;
    }
    
    .image-preview img {
        max-width: 200px;
        height: auto;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .checkbox-group {
        margin-top: 10px;
    }
    
    .checkbox-label {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        padding: 8px;
        border: 1px solid #e1e5e9;
        border-radius: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .checkbox-label:hover {
        background: #f8f9fa;
        border-color: #0073aa;
    }
    
    .checkbox-label input[type="checkbox"] {
        margin-right: 8px;
    }
    
    .checkmark {
        margin-right: 8px;
        font-size: 16px;
    }
    
    .seo-score-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: conic-gradient(#46b450 0deg, #46b450 calc(var(--score, 0) * 3.6deg), #e1e5e9 calc(var(--score, 0) * 3.6deg));
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        flex-shrink: 0;
    }
    
    .seo-score-circle::before {
        content: '';
        position: absolute;
        width: 60px;
        height: 60px;
        background: #fff;
        border-radius: 50%;
    }
    
    .score-number {
        font-size: 18px;
        font-weight: bold;
        color: #23282d;
        z-index: 1;
    }
    
    .score-label {
        font-size: 11px;
        color: #666;
        z-index: 1;
    }
    
    .seo-score-overview {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    
    .seo-status-list {
        flex: 1;
    }
    
    .seo-status-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        margin-bottom: 6px;
        border-radius: 6px;
        font-size: 13px;
    }
    
    .seo-status-item.success {
        background: #e8f5e8;
        color: #2e7d2e;
        border-left: 4px solid #46b450;
    }
    
    .seo-status-item.warning {
        background: #fff3cd;
        color: #856404;
        border-left: 4px solid #ffb900;
    }
    
    .seo-status-item.error {
        background: #f8d7da;
        color: #721c24;
        border-left: 4px solid #dc3232;
    }
    
    .seo-status-icon {
        margin-right: 8px;
        font-size: 14px;
    }
    
    /* Responsive */
    @media (max-width: 782px) {
        .carni24-seo-tabs {
            flex-wrap: wrap;
        }
        
        .carni24-seo-tabs li {
            flex: 1 1 50%;
        }
        
        .carni24-seo-tabs .tab-link {
            padding: 10px 8px;
            font-size: 12px;
        }
        
        .carni24-seo-content {
            padding: 15px;
        }
        
        .google-preview {
            font-size: 13px;
        }
        
        .preview-title {
            font-size: 16px;
        }
        
        .seo-score-overview {
            flex-direction: column;
            gap: 15px;
        }
    }
    
    /* Animacje */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .seo-tab-content.active {
        animation: fadeIn 0.3s ease;
    }
    </style>
    
    <!-- FALLBACK JAVASCRIPT - ładuje się zawsze, żeby zakładki działały -->
    <script>
    jQuery(document).ready(function($) {
        console.log('SEO Metabox fallback JS loading...');
        
        // Podstawowe przełączanie zakładek - fallback
        $('.carni24-seo-tabs .tab-link').off('click.fallback').on('click.fallback', function(e) {
            e.preventDefault();
            console.log('Fallback tab click:', $(this).data('tab'));
            
            var targetTab = $(this).data('tab');
            var $metabox = $(this).closest('.carni24-seo-metabox');
            
            // Usuń aktywne klasy
            $metabox.find('.carni24-seo-tabs .tab-link').removeClass('active');
            $metabox.find('.seo-tab-content').removeClass('active').hide();
            
            // Dodaj aktywne klasy
            $(this).addClass('active');
            $metabox.find('#' + targetTab).addClass('active').show();
            
            console.log('Tab switched to:', targetTab);
            
            // Zapisz w localStorage
            localStorage.setItem('carni24_seo_active_tab', targetTab);
        });
        
        // Upewnij się, że pierwsza zakładka jest aktywna
        if (!$('.carni24-seo-tabs .tab-link.active').length) {
            $('.carni24-seo-tabs .tab-link:first').addClass('active');
            $('.seo-tab-content:first').addClass('active').show();
            $('.seo-tab-content:not(:first)').removeClass('active').hide();
            console.log('First tab activated as fallback');
        }
        
        // Przywróć zapisaną zakładkę
        var savedTab = localStorage.getItem('carni24_seo_active_tab');
        if (savedTab && $('#' + savedTab).length) {
            $('.tab-link[data-tab="' + savedTab + '"]').click();
            console.log('Restored saved tab:', savedTab);
        }
        
        // Podstawowe liczniki znaków
        $('#seo_title').on('input', function() {
            var length = $(this).val().length;
            $('#title-char-count').text(length);
            updateCharStatus('#title-char-status', length, 30, 60, 70);
            updatePreview();
        });
        
        $('#seo_description').on('input', function() {
            var length = $(this).val().length;
            $('#desc-char-count').text(length);
            updateCharStatus('#desc-char-status', length, 120, 160, 200);
            updatePreview();
        });
        
        $('#seo_keywords').on('input', function() {
            updateKeywordsPreview();
        });
        
        $('#seo_og_title, #seo_og_description').on('input', function() {
            updateSocialPreview();
        });
        
        function updateCharStatus(selector, length, goodMin, goodMax, max) {
            var $status = $(selector);
            if (!$status.length) return;
            
            $status.removeClass('good warning error');
            
            if (length === 0) {
                $status.text('');
            } else if (length >= goodMin && length <= goodMax) {
                $status.addClass('good').text('Idealnie');
            } else if (length > 0 && length <= max) {
                $status.addClass('warning').text('OK');
            } else {
                $status.addClass('error').text(length > max ? 'Za długie' : 'Za krótkie');
            }
        }
        
        function updatePreview() {
            var title = $('#seo_title').val() || $('#seo_title').attr('placeholder') || '';
            var description = $('#seo_description').val() || $('#seo_description').attr('placeholder') || '';
            
            $('#preview-title').text(title);
            $('#preview-description').text(description);
        }
        
        function updateSocialPreview() {
            var ogTitle = $('#seo_og_title').val() || $('#seo_title').val() || $('#seo_title').attr('placeholder') || '';
            var ogDescription = $('#seo_og_description').val() || $('#seo_description').val() || $('#seo_description').attr('placeholder') || '';
            
            $('#social-preview-title').text(ogTitle);
            $('#social-preview-desc').text(ogDescription);
        }
        
        function updateKeywordsPreview() {
            var keywords = $('#seo_keywords').val();
            var $preview = $('#keywords-preview');
            
            if (!$preview.length) return;
            
            if (!keywords.trim()) {
                $preview.empty();
                return;
            }
            
            var keywordArray = keywords.split(',').map(function(k) { return k.trim(); }).filter(function(k) { return k; });
            var html = '';
            
            keywordArray.forEach(function(keyword) {
                html += '<span class="keyword-tag">' + $('<div>').text(keyword).html() + '</span>';
            });
            
            $preview.html(html);
        }
        
        // Upload obrazu - podstawowa funkcjonalność
        $('.upload-image-button').on('click', function(e) {
            e.preventDefault();
            
            if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
                alert('Media library nie jest dostępna. Odśwież stronę i spróbuj ponownie.');
                return;
            }
            
            var mediaUploader = wp.media({
                title: 'Wybierz obraz',
                button: { text: 'Użyj tego obrazu' },
                multiple: false,
                library: { type: 'image' }
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                $('#seo_og_image').val(attachment.id);
                $('#og-image-preview').html('<img src="' + attachment.url + '" alt="OG Image" />');
                $('.upload-image-button').text('🔄 Zmień obraz');
                $('.remove-image-button').show();
                $('.social-image').html('<img src="' + attachment.url + '" alt="Social preview" />');
            });
            
            mediaUploader.open();
        });
        
        // Usuń obraz
        $('.remove-image-button').on('click', function(e) {
            e.preventDefault();
            
            $('#seo_og_image').val('');
            $('#og-image-preview').empty();
            $('.upload-image-button').text('📷 Dodaj obraz');
            $(this).hide();
            $('.social-image').html('<div class="no-image">Brak obrazu</div>');
        });
        
        // Uruchom wszystkie funkcje na start
        $('#seo_title, #seo_description').trigger('input');
        updateKeywordsPreview();
        updateSocialPreview();
        
        console.log('SEO Metabox fallback JS loaded successfully');
    });
    </script>
    <?php
}

/**
 * Zapisuje dane z meta boxa SEO
 */
function carni24_save_seo_improved_meta_box($post_id) {
    if (!isset($_POST['carni24_seo_improved_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['carni24_seo_improved_meta_box_nonce'], 'carni24_seo_improved_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Zapisz wszystkie pola SEO
    $seo_fields = array(
        'seo_title' => sanitize_text_field($_POST['seo_title'] ?? ''),
        'seo_description' => sanitize_textarea_field($_POST['seo_description'] ?? ''),
        'seo_keywords' => sanitize_text_field($_POST['seo_keywords'] ?? ''),
        'seo_canonical' => esc_url_raw($_POST['seo_canonical'] ?? ''),
        'seo_og_title' => sanitize_text_field($_POST['seo_og_title'] ?? ''),
        'seo_og_description' => sanitize_textarea_field($_POST['seo_og_description'] ?? ''),
        'seo_og_image' => absint($_POST['seo_og_image'] ?? 0),
        'seo_noindex' => isset($_POST['seo_noindex']) ? 1 : 0,
        'seo_nofollow' => isset($_POST['seo_nofollow']) ? 1 : 0,
    );

    foreach ($seo_fields as $field => $value) {
        $meta_key = '_' . $field;
        if ($value) {
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}
add_action('save_post', 'carni24_save_seo_improved_meta_box');

/**
 * Funkcja sprawdzająca kompletność SEO
 */
function carni24_check_seo_completeness($post_id) {
    $meta_title = get_post_meta($post_id, '_seo_title', true);
    $meta_description = get_post_meta($post_id, '_seo_description', true);
    $meta_keywords = get_post_meta($post_id, '_seo_keywords', true);
    
    $issues = array();
    
    if (empty($meta_title)) {
        $issues[] = array(
            'type' => 'missing_title',
            'severity' => 'error',
            'message' => 'Brak tytułu SEO'
        );
    } elseif (strlen($meta_title) < 30) {
        $issues[] = array(
            'type' => 'short_title',
            'severity' => 'warning',
            'message' => 'Tytuł SEO jest za krótki (< 30 znaków)'
        );
    } elseif (strlen($meta_title) > 60) {
        $issues[] = array(
            'type' => 'long_title',
            'severity' => 'warning',
            'message' => 'Tytuł SEO może być za długi (> 60 znaków)'
        );
    }
    
    if (empty($meta_description)) {
        $issues[] = array(
            'type' => 'missing_description',
            'severity' => 'error',
            'message' => 'Brak opisu SEO'
        );
    } elseif (strlen($meta_description) < 120) {
        $issues[] = array(
            'type' => 'short_description',
            'severity' => 'warning',
            'message' => 'Opis SEO jest za krótki (< 120 znaków)'
        );
    } elseif (strlen($meta_description) > 160) {
        $issues[] = array(
            'type' => 'long_description',
            'severity' => 'warning',
            'message' => 'Opis SEO może być za długi (> 160 znaków)'
        );
    }
    
    if (empty($meta_keywords)) {
        $issues[] = array(
            'type' => 'missing_keywords',
            'severity' => 'warning',
            'message' => 'Brak słów kluczowych'
        );
    }
    
    return $issues;
}

/**
 * AJAX: Sprawdź completeness SEO
 */
function carni24_ajax_check_seo_completeness() {
    check_ajax_referer('carni24_seo_metabox', 'nonce');
    
    $post_id = intval($_POST['post_id']);
    $issues = carni24_check_seo_completeness($post_id);
    
    wp_send_json_success(array('issues' => $issues));
}
add_action('wp_ajax_carni24_check_seo_completeness', 'carni24_ajax_check_seo_completeness');