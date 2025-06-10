<?php
// wp-content/themes/carni24/includes/custom-meta-fields.php
// Dedykowane pola meta zamiast excerpt

// Dodaj meta box dla custom pól
function carni24_add_custom_meta_fields() {
    add_meta_box(
        'carni24_post_meta',
        'Carni24 - Dodatkowe pola',
        'carni24_meta_fields_callback',
        array('post', 'species'),
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'carni24_add_custom_meta_fields');

function carni24_meta_fields_callback($post) {
    wp_nonce_field('carni24_meta_fields', 'carni24_meta_nonce');
    
    $card_description = get_post_meta($post->ID, '_card_description', true);
    $hero_description = get_post_meta($post->ID, '_hero_description', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="card_description"><strong>Opis dla kart:</strong></label></th>
            <td>
                <textarea id="card_description" name="card_description" rows="4" class="large-text" maxlength="250" placeholder="Krótki opis wyświetlany w kartach wpisów na stronie głównej i w listach..."><?= esc_textarea($card_description) ?></textarea>
                <p class="description">
                    Opis wyświetlany w kartach wpisów. Maksymalnie 250 znaków. 
                    <strong>Obsługuje nagłówki i formatowanie HTML</strong> - można używać tagów jak &lt;strong&gt;, &lt;em&gt;, &lt;h4&gt; itp.
                </p>
                <div class="char-counter">
                    <span id="card-desc-counter">0</span> / 250 znaków
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="hero_description"><strong>Opis dla slidera:</strong></label></th>
            <td>
                <textarea id="hero_description" name="hero_description" rows="3" class="large-text" maxlength="180" placeholder="Krótki opis dla hero slidera..."><?= esc_textarea($hero_description) ?></textarea>
                <p class="description">
                    Opis wyświetlany w hero sliderze na stronie głównej. Maksymalnie 180 znaków.
                    Zwykły tekst bez formatowania HTML.
                </p>
                <div class="char-counter">
                    <span id="hero-desc-counter">0</span> / 180 znaków
                </div>
            </td>
        </tr>
    </table>
    
    <style>
    .char-counter {
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }
    .char-counter.warning {
        color: #d63638;
        font-weight: bold;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        function updateCounter(textareaId, counterId, maxLength) {
            const textarea = $('#' + textareaId);
            const counter = $('#' + counterId);
            const parent = counter.parent();
            
            function update() {
                const length = textarea.val().length;
                counter.text(length);
                
                if (length > maxLength * 0.9) {
                    parent.addClass('warning');
                } else {
                    parent.removeClass('warning');
                }
            }
            
            textarea.on('input keyup', update);
            update(); // Initial count
        }
        
        updateCounter('card_description', 'card-desc-counter', 250);
        updateCounter('hero_description', 'hero-desc-counter', 180);
    });
    </script>
    <?php
}

// Zapisz meta pola
function carni24_save_meta_fields($post_id) {
    // Sprawdź nonce
    if (!isset($_POST['carni24_meta_nonce']) || !wp_verify_nonce($_POST['carni24_meta_nonce'], 'carni24_meta_fields')) {
        return;
    }
    
    // Sprawdź autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Sprawdź uprawnienia
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Zapisz opis dla kart (z HTML)
    if (isset($_POST['card_description'])) {
        $card_description = wp_kses_post($_POST['card_description']); // Pozwala na podstawowy HTML
        update_post_meta($post_id, '_card_description', $card_description);
    }
    
    // Zapisz opis dla hero (bez HTML)
    if (isset($_POST['hero_description'])) {
        $hero_description = sanitize_textarea_field($_POST['hero_description']);
        update_post_meta($post_id, '_hero_description', $hero_description);
    }
}
add_action('save_post', 'carni24_save_meta_fields');

// Funkcje pomocnicze do pobierania opisów
function carni24_get_card_description($post_id = null, $fallback_words = 25) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $card_description = get_post_meta($post_id, '_card_description', true);
    
    if (!empty($card_description)) {
        return $card_description;
    }
    
    // Fallback - wygeneruj z treści
    $content = get_post_field('post_content', $post_id);
    $content = wp_strip_all_tags($content);
    return wp_trim_words($content, $fallback_words, '...');
}

function carni24_get_hero_description($post_id = null, $fallback_words = 20) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $hero_description = get_post_meta($post_id, '_hero_description', true);
    
    if (!empty($hero_description)) {
        return $hero_description;
    }
    
    // Fallback do card description (bez HTML)
    $card_description = carni24_get_card_description($post_id);
    return wp_trim_words(wp_strip_all_tags($card_description), $fallback_words, '...');
}

// Dodaj kolumny w admin
function carni24_add_meta_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        
        if ($key === 'title') {
            $new_columns['card_description'] = 'Opis dla kart';
        }
    }
    
    return $new_columns;
}
add_filter('manage_posts_columns', 'carni24_add_meta_columns');
add_filter('manage_species_posts_columns', 'carni24_add_meta_columns');

function carni24_show_meta_columns($column, $post_id) {
    if ($column === 'card_description') {
        $description = get_post_meta($post_id, '_card_description', true);
        if (!empty($description)) {
            $preview = wp_strip_all_tags($description);
            echo '<div style="max-width: 200px; font-size: 12px; color: #666;" title="' . esc_attr($preview) . '">';
            echo esc_html(wp_trim_words($preview, 8));
            echo '</div>';
        } else {
            echo '<span style="color: #999; font-style: italic;">Brak opisu</span>';
        }
    }
}
add_action('manage_posts_custom_column', 'carni24_show_meta_columns', 10, 2);
add_action('manage_species_posts_custom_column', 'carni24_show_meta_columns', 10, 2);

// Bulk action - wygeneruj opisy
function carni24_add_bulk_generate_descriptions($bulk_actions) {
    $bulk_actions['generate_descriptions'] = 'Wygeneruj opisy z treści';
    return $bulk_actions;
}
add_filter('bulk_actions-edit-post', 'carni24_add_bulk_generate_descriptions');
add_filter('bulk_actions-edit-species', 'carni24_add_bulk_generate_descriptions');

function carni24_handle_bulk_generate_descriptions($redirect_to, $doaction, $post_ids) {
    if ($doaction !== 'generate_descriptions') {
        return $redirect_to;
    }
    
    $generated = 0;
    
    foreach ($post_ids as $post_id) {
        // Sprawdź czy już ma opis
        $existing = get_post_meta($post_id, '_card_description', true);
        if (!empty($existing)) {
            continue;
        }
        
        // Wygeneruj z treści
        $content = get_post_field('post_content', $post_id);
        if (!empty($content)) {
            $clean_content = wp_strip_all_tags($content);
            $card_desc = wp_trim_words($clean_content, 35, '...');
            $hero_desc = wp_trim_words($clean_content, 25, '...');
            
            update_post_meta($post_id, '_card_description', $card_desc);
            update_post_meta($post_id, '_hero_description', $hero_desc);
            $generated++;
        }
    }
    
    $redirect_to = add_query_arg('bulk_generated_descriptions', $generated, $redirect_to);
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-post', 'carni24_handle_bulk_generate_descriptions', 10, 3);
add_filter('handle_bulk_actions-edit-species', 'carni24_handle_bulk_generate_descriptions', 10, 3);

// Notice po bulk action
function carni24_bulk_generate_descriptions_notice() {
    if (!empty($_REQUEST['bulk_generated_descriptions'])) {
        $generated = intval($_REQUEST['bulk_generated_descriptions']);
        printf(
            '<div class="updated notice is-dismissible"><p>Wygenerowano opisy dla %d wpisów.</p></div>',
            $generated
        );
    }
}
add_action('admin_notices', 'carni24_bulk_generate_descriptions_notice');

// Shortcode do wyświetlania opisów
function carni24_description_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_id' => get_the_ID(),
        'type' => 'card', // 'card' lub 'hero'
        'words' => null
    ), $atts);
    
    if ($atts['type'] === 'hero') {
        $description = carni24_get_hero_description($atts['post_id']);
        if ($atts['words']) {
            $description = wp_trim_words($description, $atts['words'], '...');
        }
        return esc_html($description);
    } else {
        $description = carni24_get_card_description($atts['post_id']);
        if ($atts['words']) {
            $description = wp_trim_words(wp_strip_all_tags($description), $atts['words'], '...');
            return esc_html($description);
        }
        return $description; // Zachowaj HTML dla kart
    }
}
add_shortcode('carni24_description', 'carni24_description_shortcode');
?>