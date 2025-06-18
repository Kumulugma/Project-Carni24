<?php
/**
 * Custom Excerpt Meta Box dla wszystkich typów postów
 * 
 * @package Carni24
 * @subpackage MetaBoxes
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dodaje custom excerpt meta box dla posts, pages, species i guides
 */
function carni24_add_custom_excerpt_meta_box() {
    $post_types = array('post', 'page', 'species', 'guides');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'carni24_custom_excerpt',
            '📝 Skrót artykułu (Custom Excerpt)',
            'carni24_custom_excerpt_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'carni24_add_custom_excerpt_meta_box');

/**
 * Callback funkcja dla custom excerpt meta box
 */
function carni24_custom_excerpt_callback($post) {
    // Dodaj nonce dla bezpieczeństwa
    wp_nonce_field('carni24_custom_excerpt_meta', 'carni24_custom_excerpt_nonce');
    
    // Pobierz aktualną wartość
    $custom_excerpt = get_post_meta($post->ID, '_custom_excerpt', true);
    $excerpt_length = strlen($custom_excerpt);
    $word_count = str_word_count($custom_excerpt);
    
    ?>
    <div class="carni24-excerpt-metabox">
        <div class="excerpt-field-wrapper">
            <div class="excerpt-info">
                <p class="description">
                    <strong>📄 Ten skrót zastąpi automatyczny excerpt WordPress.</strong><br>
                    Będzie wyświetlany w kartach artykułów, wynikach wyszukiwania i meta opisach.
                    <br><em>Pozostaw puste, aby używać standardowego excerpt WordPress.</em>
                </p>
            </div>
            
            <div class="excerpt-textarea-container">
                <textarea 
                    id="custom_excerpt" 
                    name="custom_excerpt" 
                    rows="4" 
                    cols="50" 
                    placeholder="Wprowadź krótki, zachęcający opis artykułu (zalecane: 120-160 znaków dla SEO)..."
                    class="large-text"><?= esc_textarea($custom_excerpt) ?></textarea>
                
                <div class="excerpt-counter">
                    <span class="char-count">Znaków: <strong id="excerpt-char-count"><?= $excerpt_length ?></strong></span>
                    <span class="word-count">Słów: <strong id="excerpt-word-count"><?= $word_count ?></strong></span>
                    <span class="seo-indicator">
                        <span id="seo-status" class="<?= ($excerpt_length >= 120 && $excerpt_length <= 160) ? 'optimal' : ($excerpt_length < 120 ? 'too-short' : 'too-long') ?>">
                            <?php if ($excerpt_length >= 120 && $excerpt_length <= 160): ?>
                                ✅ Optymalna długość dla SEO
                            <?php elseif ($excerpt_length < 120): ?>
                                ⚠️ Za krótkie dla SEO (min. 120 znaków)
                            <?php else: ?>
                                ⚠️ Za długie dla SEO (max. 160 znaków)
                            <?php endif; ?>
                        </span>
                    </span>
                </div>
            </div>
            
            <?php if (!empty($custom_excerpt)): ?>
                <div class="excerpt-preview">
                    <h4>📱 Podgląd w wynikach wyszukiwania:</h4>
                    <div class="search-preview">
                        <div class="preview-title"><?= esc_html(get_the_title($post)) ?></div>
                        <div class="preview-url"><?= esc_url(get_permalink($post)) ?></div>
                        <div class="preview-description" id="preview-description"><?= esc_html($custom_excerpt) ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
    .carni24-excerpt-metabox {
        padding: 15px 0;
    }
    
    .excerpt-field-wrapper {
        max-width: 100%;
    }
    
    .excerpt-info {
        background: #f0f6ff;
        border: 1px solid #c7d2fe;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 15px;
    }
    
    .excerpt-info .description {
        margin: 0;
        color: #1e40af;
        font-size: 13px;
        line-height: 1.5;
    }
    
    .excerpt-textarea-container {
        position: relative;
    }
    
    #custom_excerpt {
        width: 100%;
        min-height: 100px;
        resize: vertical;
        border: 2px solid #ddd;
        border-radius: 6px;
        padding: 12px;
        font-size: 14px;
        line-height: 1.5;
        transition: border-color 0.3s ease;
    }
    
    #custom_excerpt:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: none;
    }
    
    .excerpt-counter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        padding: 8px 0;
        font-size: 12px;
        color: #666;
        border-top: 1px solid #e5e7eb;
    }
    
    .char-count, .word-count {
        font-weight: 500;
    }
    
    .seo-indicator .optimal {
        color: #16a34a;
        font-weight: 600;
    }
    
    .seo-indicator .too-short {
        color: #f59e0b;
        font-weight: 600;
    }
    
    .seo-indicator .too-long {
        color: #dc2626;
        font-weight: 600;
    }
    
    .excerpt-preview {
        margin-top: 15px;
        padding: 15px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
    }
    
    .excerpt-preview h4 {
        margin: 0 0 10px 0;
        color: #374151;
        font-size: 14px;
    }
    
    .search-preview {
        background: white;
        padding: 12px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        max-width: 600px;
    }
    
    .preview-title {
        color: #1a0dab;
        font-size: 18px;
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
        margin-bottom: 4px;
    }
    
    .preview-description {
        color: #545454;
        font-size: 14px;
        line-height: 1.4;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('custom_excerpt');
        const charCount = document.getElementById('excerpt-char-count');
        const wordCount = document.getElementById('excerpt-word-count');
        const seoStatus = document.getElementById('seo-status');
        const previewDesc = document.getElementById('preview-description');
        
        if (textarea) {
            textarea.addEventListener('input', function() {
                const content = this.value;
                const chars = content.length;
                const words = content.trim() === '' ? 0 : content.trim().split(/\s+/).length;
                
                // Aktualizuj liczniki
                if (charCount) charCount.textContent = chars;
                if (wordCount) wordCount.textContent = words;
                
                // Aktualizuj status SEO
                if (seoStatus) {
                    seoStatus.className = '';
                    if (chars >= 120 && chars <= 160) {
                        seoStatus.className = 'optimal';
                        seoStatus.textContent = '✅ Optymalna długość dla SEO';
                    } else if (chars < 120) {
                        seoStatus.className = 'too-short';
                        seoStatus.textContent = '⚠️ Za krótkie dla SEO (min. 120 znaków)';
                    } else {
                        seoStatus.className = 'too-long';
                        seoStatus.textContent = '⚠️ Za długie dla SEO (max. 160 znaków)';
                    }
                }
                
                // Aktualizuj podgląd
                if (previewDesc) {
                    previewDesc.textContent = content || 'Opis artykułu będzie widoczny tutaj...';
                }
            });
        }
    });
    </script>
    <?php
}

/**
 * Zapisywanie custom excerpt
 */
function carni24_save_custom_excerpt_meta($post_id) {
    // Sprawdzenia bezpieczeństwa
    if (!isset($_POST['carni24_custom_excerpt_nonce']) || 
        !wp_verify_nonce($_POST['carni24_custom_excerpt_nonce'], 'carni24_custom_excerpt_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Zapisz custom excerpt
    if (isset($_POST['custom_excerpt'])) {
        $custom_excerpt = sanitize_textarea_field($_POST['custom_excerpt']);
        
        if (!empty($custom_excerpt)) {
            update_post_meta($post_id, '_custom_excerpt', $custom_excerpt);
        } else {
            delete_post_meta($post_id, '_custom_excerpt');
        }
    }
}
add_action('save_post', 'carni24_save_custom_excerpt_meta');

/**
 * Modyfikuje funkcję carni24_get_custom_excerpt w utils.php
 * aby priorytetowo używała nowego pola _custom_excerpt
 */
function carni24_override_get_custom_excerpt($post_id = null, $fallback_words = 20) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    // 1. PRIORYTET: Sprawdź nowe pole _custom_excerpt
    $custom_excerpt = get_post_meta($post_id, '_custom_excerpt', true);
    if (!empty($custom_excerpt)) {
        return $custom_excerpt;
    }
    
    // 2. WordPress excerpt
    $excerpt = get_the_excerpt($post_id);
    if (!empty($excerpt)) {
        return $excerpt;
    }
    
    // 3. Fragment treści jako fallback
    $content = get_post_field('post_content', $post_id);
    $content = wp_strip_all_tags($content);
    return wp_trim_words($content, $fallback_words, '...');
}

/**
 * Filtr dla get_the_excerpt - używa custom excerpt jeśli dostępny
 */
function carni24_filter_excerpt($excerpt, $post = null) {
    if (!$post) {
        global $post;
    }
    
    if (!$post) return $excerpt;
    
    $custom_excerpt = get_post_meta($post->ID, '_custom_excerpt', true);
    
    if (!empty($custom_excerpt)) {
        return $custom_excerpt;
    }
    
    return $excerpt;
}
add_filter('get_the_excerpt', 'carni24_filter_excerpt', 10, 2);

/**
 * Dodaje kolumnę custom excerpt w admin tables
 */
function carni24_add_excerpt_column($columns) {
    // Sprawdź czy kolumna już istnieje (unikaj duplikacji)
    if (isset($columns['custom_excerpt'])) {
        return $columns;
    }
    
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        // Dodaj po kolumnie tytułu, ale przed kolumnami specyficznymi dla CPT
        if ($key === 'title') {
            $new_columns['custom_excerpt'] = '📝 Skrót';
        }
    }
    
    return $new_columns;
}


function carni24_fill_excerpt_column($column, $post_id) {
    if ($column === 'custom_excerpt') {
        $custom_excerpt = get_post_meta($post_id, '_custom_excerpt', true);
        
        if (!empty($custom_excerpt)) {
            $excerpt_preview = wp_trim_words($custom_excerpt, 8, '...');
            echo '<span style="color: #16a34a; font-weight: 500;">✅ ' . esc_html($excerpt_preview) . '</span>';
        } else {
            echo '<span style="color: #6b7280;">—</span>';
        }
    }
}

// Dodaj kolumny dla wszystkich typów postów
add_filter('manage_posts_columns', 'carni24_add_excerpt_column');
add_filter('manage_pages_columns', 'carni24_add_excerpt_column');
add_filter('manage_guides_posts_columns', 'carni24_add_excerpt_column');

add_action('manage_posts_custom_column', 'carni24_fill_excerpt_column', 10, 2);
add_action('manage_pages_custom_column', 'carni24_fill_excerpt_column', 10, 2);
add_action('manage_guides_posts_custom_column', 'carni24_fill_excerpt_column', 10, 2);