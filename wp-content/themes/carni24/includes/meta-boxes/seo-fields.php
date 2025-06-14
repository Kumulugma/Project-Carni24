<?php
/**
 * Carni24 SEO Meta Boxes
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
 * Dodaje meta boxy SEO do postów, stron i species
 */
function carni24_add_seo_meta_boxes() {
    $post_types = array('post', 'page', 'species', 'guides');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'carni24_seo_settings',
            'Ustawienia SEO',
            'carni24_seo_meta_box_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'carni24_add_seo_meta_boxes');

/**
 * Callback dla meta boxa SEO
 */
function carni24_seo_meta_box_callback($post) {
    wp_nonce_field('carni24_seo_meta_box', 'carni24_seo_meta_box_nonce');
    
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
    
    ?>
    <div class="carni24-seo-meta-box">
        <style>
        .carni24-seo-meta-box { padding: 10px 0; }
        .carni24-seo-section { margin-bottom: 25px; border-bottom: 1px solid #e0e0e0; padding-bottom: 20px; }
        .carni24-seo-section:last-child { border-bottom: none; margin-bottom: 0; }
        .carni24-seo-section h4 { margin: 0 0 15px; color: #23282d; font-size: 14px; font-weight: 600; }
        .carni24-field { margin-bottom: 15px; }
        .carni24-field label { display: block; margin-bottom: 5px; font-weight: 600; color: #23282d; }
        .carni24-field input[type="text"], .carni24-field input[type="url"], .carni24-field textarea { width: 100%; }
        .carni24-field .description { margin-top: 5px; color: #666; font-size: 13px; }
        .carni24-char-counter { text-align: right; font-size: 12px; color: #666; margin-top: 5px; }
        .carni24-char-counter.over-limit { color: #d63638; font-weight: bold; }
        .carni24-og-image-preview { margin-top: 10px; }
        .carni24-og-image-preview img { max-width: 200px; height: auto; border: 1px solid #ddd; }
        .carni24-button-group { margin-top: 10px; }
        .carni24-button-group button { margin-right: 10px; }
        </style>
        
        <!-- Podstawowe SEO -->
        <div class="carni24-seo-section">
            <h4>🎯 Podstawowe SEO</h4>
            
            <div class="carni24-field">
                <label for="carni24_seo_title">Meta Title</label>
                <input type="text" id="carni24_seo_title" name="seo_title" 
                       value="<?php echo esc_attr($meta_title); ?>" 
                       maxlength="60" onkeyup="carni24UpdateCharCounter('carni24_seo_title', 'title-counter', 60)" />
                <div class="carni24-char-counter">
                    <span id="title-counter"><?php echo strlen($meta_title); ?></span> / 60 znaków
                </div>
                <p class="description">Tytuł strony w wynikach wyszukiwania. Pozostaw puste dla automatycznego.</p>
            </div>
            
            <div class="carni24-field">
                <label for="carni24_seo_description">Meta Description</label>
                <textarea id="carni24_seo_description" name="seo_description" rows="3" 
                          maxlength="160" onkeyup="carni24UpdateCharCounter('carni24_seo_description', 'desc-counter', 160)"><?php echo esc_textarea($meta_description); ?></textarea>
                <div class="carni24-char-counter">
                    <span id="desc-counter"><?php echo strlen($meta_description); ?></span> / 160 znaków
                </div>
                <p class="description">Opis strony w wynikach wyszukiwania Google.</p>
            </div>
            
            <div class="carni24-field">
                <label for="carni24_seo_keywords">Meta Keywords</label>
                <input type="text" id="carni24_seo_keywords" name="seo_keywords" 
                       value="<?php echo esc_attr($meta_keywords); ?>" />
                <p class="description">Słowa kluczowe oddzielone przecinkami (opcjonalne).</p>
            </div>
        </div>
        
        <!-- Ustawienia zaawansowane -->
        <div class="carni24-seo-section">
            <h4>⚙️ Ustawienia zaawansowane</h4>
            
            <div class="carni24-field">
                <label for="carni24_seo_canonical">Canonical URL</label>
                <input type="url" id="carni24_seo_canonical" name="seo_canonical" 
                       value="<?php echo esc_attr($canonical_url); ?>" />
                <p class="description">Pozostaw puste dla automatycznego URL.</p>
            </div>
            
            <div class="carni24-field">
                <label>
                    <input type="checkbox" name="seo_noindex" value="1" <?php checked($noindex, 1); ?> />
                    No Index (nie indeksuj tej strony)
                </label>
                <br>
                <label style="margin-top: 8px; display: inline-block;">
                    <input type="checkbox" name="seo_nofollow" value="1" <?php checked($nofollow, 1); ?> />
                    No Follow (nie podążaj za linkami)
                </label>
            </div>
        </div>
        
        <!-- Open Graph / Social Media -->
        <div class="carni24-seo-section">
            <h4>📱 Media społecznościowe (Open Graph)</h4>
            
            <div class="carni24-field">
                <label for="carni24_seo_og_title">OG Title</label>
                <input type="text" id="carni24_seo_og_title" name="seo_og_title" 
                       value="<?php echo esc_attr($og_title); ?>" />
                <p class="description">Tytuł dla Facebook, Twitter itp. Pozostaw puste dla meta title.</p>
            </div>
            
            <div class="carni24-field">
                <label for="carni24_seo_og_description">OG Description</label>
                <textarea id="carni24_seo_og_description" name="seo_og_description" rows="3"><?php echo esc_textarea($og_description); ?></textarea>
                <p class="description">Opis dla mediów społecznościowych. Pozostaw puste dla meta description.</p>
            </div>
            
            <div class="carni24-field">
                <label>OG Image</label>
                <input type="hidden" id="carni24_seo_og_image" name="seo_og_image" value="<?php echo esc_attr($og_image); ?>" />
                <div class="carni24-button-group">
                    <button type="button" class="button" onclick="carni24OpenOgImageUploader()">Wybierz obraz</button>
                    <button type="button" class="button" onclick="carni24ClearOgImage()">Usuń</button>
                </div>
                <div id="carni24-og-image-preview" class="carni24-og-image-preview">
                    <?php if ($og_image): ?>
                        <?php echo wp_get_attachment_image($og_image, 'medium'); ?>
                    <?php endif; ?>
                </div>
                <p class="description">Obraz dla mediów społecznościowych (1200x630px). Pozostaw puste dla featured image.</p>
            </div>
        </div>
    </div>
    
    <script>
    // Character counters
    function carni24UpdateCharCounter(fieldId, counterId, limit) {
        const field = document.getElementById(fieldId);
        const counter = document.getElementById(counterId);
        const length = field.value.length;
        
        counter.textContent = length;
        counter.parentElement.className = length > limit ? 'carni24-char-counter over-limit' : 'carni24-char-counter';
    }
    
    // Media uploader
    function carni24OpenOgImageUploader() {
        const mediaUploader = wp.media({
            title: 'Wybierz obraz OG',
            button: { text: 'Użyj tego obrazu' },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById('carni24_seo_og_image').value = attachment.id;
            document.getElementById('carni24-og-image-preview').innerHTML = 
                '<img src="' + attachment.sizes.medium.url + '" alt="" />';
        });
        
        mediaUploader.open();
    }
    
    function carni24ClearOgImage() {
        document.getElementById('carni24_seo_og_image').value = '';
        document.getElementById('carni24-og-image-preview').innerHTML = '';
    }
    
    // Initialize character counters
    jQuery(document).ready(function($) {
        carni24UpdateCharCounter('carni24_seo_title', 'title-counter', 60);
        carni24UpdateCharCounter('carni24_seo_description', 'desc-counter', 160);
    });
    </script>
    <?php
}

/**
 * Zapisuje meta dane SEO
 */
function carni24_save_seo_meta($post_id) {
    // Sprawdzenie nonce
    if (!isset($_POST['carni24_seo_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['carni24_seo_meta_box_nonce'], 'carni24_seo_meta_box')) return;
    
    // Sprawdzenie autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // Sprawdzenie uprawnień
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
add_action('save_post', 'carni24_save_seo_meta');