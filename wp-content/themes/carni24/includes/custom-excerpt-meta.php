<?php
// wp-content/themes/carni24/includes/custom-excerpt-meta.php
// Dedykowane pole meta dla opisów wpisów zamiast excerpt

// Dodaj meta box dla custom excerpt
function carni24_add_custom_excerpt_meta_box() {
    add_meta_box(
        'custom_excerpt_meta_box',
        'Opis wpisu (dla kart i sliderów)',
        'carni24_custom_excerpt_meta_box_callback',
        array('post', 'species'),
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'carni24_add_custom_excerpt_meta_box');

function carni24_custom_excerpt_meta_box_callback($post) {
    wp_nonce_field('custom_excerpt_meta_box', 'custom_excerpt_meta_box_nonce');
    
    $custom_excerpt = get_post_meta($post->ID, '_custom_excerpt', true);
    $hero_description = get_post_meta($post->ID, '_hero_description', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="custom_excerpt"><strong>Opis dla kart:</strong></label></th>
            <td>
                <textarea id="custom_excerpt" name="custom_excerpt" rows="3" class="large-text" maxlength="300"><?= esc_textarea($custom_excerpt) ?></textarea>
                <p class="description">Opis wyświetlany w kartach wpisów na stronie głównej i listach. Maksymalnie 300 znaków. Obsługuje zwykły tekst bez formatowania.</p>
                <div class="char-counter">
                    <span id="custom-excerpt-counter">0</span> / 300 znaków
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="hero_description"><strong>Opis dla slidera:</strong></label></th>
            <td>
                <textarea id="hero_description" name="hero_description" rows="2" class="large-text" maxlength="200"><?= esc_textarea($hero_description) ?></textarea>
                <p class="description">Opis wyświetlany w hero sliderze. Maksymalnie 200 znaków. Krótki, atrakcyjny opis zachęcający do przeczytania.</p>
                <div class="char-counter">
                    <span id="hero-description-counter">0</span> / 200 znaków
                </div>
            </td>
        </tr>
    </table>
    
    <style>
    .char-counter {
        font-size: 12px;
        margin-top: 5px;
        font-weight: bold;
    }
    .char-counter.warning {
        color: orange;
    }
    .char-counter.error {
        color: red;
    }
    .char-counter.success {
        color: green;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        function updateCounter(textareaId, counterId, maxLength) {
            const textarea = $('#' + textareaId);
            const counter = $('#' + counterId);
            
            function update() {
                const length = textarea.val().length;
                counter.text(length);
                
                // Reset classes
                counter.removeClass('success warning error');
                
                // Add appropriate class
                if (length > maxLength) {
                    counter.addClass('error');
                } else if (length > maxLength * 0.8) {
                    counter.addClass('warning');
                } else {
                    counter.addClass('success');
                }
            }
            
            textarea.on('input', update);
            update(); // Initial update
        }
        
        updateCounter('custom_excerpt', 'custom-excerpt-counter', 300);
        updateCounter('hero_description', 'hero-description-counter', 200);
    });
    </script>
    <?php
}

function carni24_save_custom_excerpt_meta($post_id) {
    // Sprawdź nonce
    if (!isset($_POST['custom_excerpt_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['custom_excerpt_meta_box_nonce'], 'custom_excerpt_meta_box')) return;
    
    // Sprawdź autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    
    // Sprawdź uprawnienia
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Zapisz custom excerpt
    if (isset($_POST['custom_excerpt'])) {
        $custom_excerpt = sanitize_textarea_field($_POST['custom_excerpt']);
        update_post_meta($post_id, '_custom_excerpt', $custom_excerpt);
    }
    
    // Zapisz hero description
    if (isset($_POST['hero_description'])) {
        $hero_description = sanitize_textarea_field($_POST['hero_description']);
        update_post_meta($post_id, '_hero_description', $hero_description);
    }
}
add_action('save_post', 'carni24_save_custom_excerpt_meta');

// Funkcja pomocnicza do pobierania custom excerpt
function carni24_get_custom_excerpt($post_id = null, $fallback_words = 20) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $custom_excerpt = get_post_meta($post_id, '_custom_excerpt', true);
    
    if (!empty($custom_excerpt)) {
        return $custom_excerpt;
    }
    
    // Fallback do WordPress excerpt
    $excerpt = get_the_excerpt($post_id);
    if (!empty($excerpt)) {
        return $excerpt;
    }
    
    // Ostatni fallback - fragment treści bez HTML
    $content = get_post_field('post_content', $post_id);
    $content = wp_strip_all_tags($content);
    return wp_trim_words($content, $fallback_words);
}

// Funkcja pomocnicza do pobierania hero description
function carni24_get_hero_description($post_id = null, $fallback_words = 25) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $hero_description = get_post_meta($post_id, '_hero_description', true);
    
    if (!empty($hero_description)) {
        return $hero_description;
    }
    
    // Fallback do custom excerpt
    $custom_excerpt = carni24_get_custom_excerpt($post_id);
    return wp_trim_words($custom_excerpt, $fallback_words);
}

// Dodaj kolumnę w liście wpisów (opcjonalne)
function carni24_add_custom_excerpt_column($columns) {
    $columns['custom_excerpt'] = 'Opis dla kart';
    return $columns;
}
add_filter('manage_posts_columns', 'carni24_add_custom_excerpt_column');
add_filter('manage_species_posts_columns', 'carni24_add_custom_excerpt_column');

function carni24_show_custom_excerpt_column($column, $post_id) {
    if ($column == 'custom_excerpt') {
        $custom_excerpt = get_post_meta($post_id, '_custom_excerpt', true);
        if (!empty($custom_excerpt)) {
            echo '<div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' . esc_attr($custom_excerpt) . '">';
            echo esc_html(wp_trim_words($custom_excerpt, 10));
            echo '</div>';
        } else {
            echo '<span style="color: #999;">Brak opisu</span>';
        }
    }
}
add_action('manage_posts_custom_column', 'carni24_show_custom_excerpt_column', 10, 2);
add_action('manage_species_posts_custom_column', 'carni24_show_custom_excerpt_column', 10, 2);

// Dodaj bulk action do uzupełnienia opisów
function carni24_add_bulk_actions($bulk_actions) {
    $bulk_actions['generate_custom_excerpts'] = 'Wygeneruj opisy z treści';
    return $bulk_actions;
}
add_filter('bulk_actions-edit-post', 'carni24_add_bulk_actions');
add_filter('bulk_actions-edit-species', 'carni24_add_bulk_actions');

function carni24_handle_bulk_generate_excerpts($redirect_to, $doaction, $post_ids) {
    if ($doaction !== 'generate_custom_excerpts') {
        return $redirect_to;
    }
    
    $generated = 0;
    
    foreach ($post_ids as $post_id) {
        // Sprawdź czy już ma custom excerpt
        $existing = get_post_meta($post_id, '_custom_excerpt', true);
        if (!empty($existing)) {
            continue;
        }
        
        // Wygeneruj z treści
        $content = get_post_field('post_content', $post_id);
        $content = wp_strip_all_tags($content);
        $excerpt = wp_trim_words($content, 30);
        
        if (!empty($excerpt)) {
            update_post_meta($post_id, '_custom_excerpt', $excerpt);
            $generated++;
        }
    }
    
    $redirect_to = add_query_arg('bulk_generated_excerpts', $generated, $redirect_to);
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-post', 'carni24_handle_bulk_generate_excerpts', 10, 3);
add_filter('handle_bulk_actions-edit-species', 'carni24_handle_bulk_generate_excerpts', 10, 3);

// Pokaż notice po bulk action
function carni24_bulk_generate_excerpts_notice() {
    if (!empty($_REQUEST['bulk_generated_excerpts'])) {
        $generated = intval($_REQUEST['bulk_generated_excerpts']);
        printf(
            '<div id="message" class="updated notice is-dismissible"><p>Wygenerowano opisy dla %d wpisów.</p></div>',
            $generated
        );
    }
}
add_action('admin_notices', 'carni24_bulk_generate_excerpts_notice');

// Shortcode do wyświetlania custom excerpt
function carni24_custom_excerpt_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_id' => get_the_ID(),
        'type' => 'card', // 'card' lub 'hero'
        'words' => 20
    ), $atts);
    
    if ($atts['type'] === 'hero') {
        return carni24_get_hero_description($atts['post_id'], $atts['words']);
    } else {
        return carni24_get_custom_excerpt($atts['post_id'], $atts['words']);
    }
}
add_shortcode('carni24_excerpt', 'carni24_custom_excerpt_shortcode');

// Widget dla custom excerpt (opcjonalnie)
class Carni24_Custom_Excerpt_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_custom_excerpt_widget',
            'Carni24 - Custom Excerpt',
            array('description' => 'Wyświetla custom excerpt z aktualnego wpisu')
        );
    }
    
    public function widget($args, $instance) {
        if (!is_singular()) return;
        
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $excerpt_type = !empty($instance['type']) ? $instance['type'] : 'card';
        $words = !empty($instance['words']) ? intval($instance['words']) : 20;
        
        if ($excerpt_type === 'hero') {
            echo '<p>' . carni24_get_hero_description(get_the_ID(), $words) . '</p>';
        } else {
            echo '<p>' . carni24_get_custom_excerpt(get_the_ID(), $words) . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $type = !empty($instance['type']) ? $instance['type'] : 'card';
        $words = !empty($instance['words']) ? $instance['words'] : 20;
        ?>
        <p>
            <label for="<?= $this->get_field_id('title') ?>">Tytuł:</label>
            <input class="widefat" id="<?= $this->get_field_id('title') ?>" name="<?= $this->get_field_name('title') ?>" type="text" value="<?= esc_attr($title) ?>">
        </p>
        <p>
            <label for="<?= $this->get_field_id('type') ?>">Typ:</label>
            <select class="widefat" id="<?= $this->get_field_id('type') ?>" name="<?= $this->get_field_name('type') ?>">
                <option value="card" <?php selected($type, 'card'); ?>>Opis dla kart</option>
                <option value="hero" <?php selected($type, 'hero'); ?>>Opis dla slidera</option>
            </select>
        </p>
        <p>
            <label for="<?= $this->get_field_id('words') ?>">Maksymalna liczba słów:</label>
            <input class="widefat" id="<?= $this->get_field_id('words') ?>" name="<?= $this->get_field_name('words') ?>" type="number" value="<?= esc_attr($words) ?>" min="5" max="100">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['type'] = (!empty($new_instance['type'])) ? sanitize_text_field($new_instance['type']) : 'card';
        $instance['words'] = (!empty($new_instance['words'])) ? absint($new_instance['words']) : 20;
        
        return $instance;
    }
}

// Rejestracja widgetu
function carni24_register_custom_excerpt_widget() {
    register_widget('Carni24_Custom_Excerpt_Widget');
}
add_action('widgets_init', 'carni24_register_custom_excerpt_widget');
?>