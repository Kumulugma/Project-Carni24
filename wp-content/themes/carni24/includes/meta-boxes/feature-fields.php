<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_add_feature_meta_boxes() {
    $post_types = array('post', 'page', 'species', 'guides');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'carni24_feature_settings',
            'Ustawienia wyróżnienia',
            'carni24_feature_meta_box_callback',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'carni24_add_feature_meta_boxes');

function carni24_feature_meta_box_callback($post) {
    wp_nonce_field('carni24_feature_meta_box', 'carni24_feature_meta_box_nonce');
    
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
    ?>
    
    <style>
    .feature-meta-box { padding: 5px 0; }
    .feature-field { margin-bottom: 15px; }
    .feature-field label { display: block; margin-bottom: 5px; font-weight: 600; }
    .feature-field input[type="text"], .feature-field input[type="number"], .feature-field select, .feature-field textarea { width: 100%; }
    .feature-field textarea { height: 60px; resize: vertical; }
    .feature-color-preview { width: 30px; height: 30px; border: 1px solid #ddd; border-radius: 3px; display: inline-block; margin-left: 10px; vertical-align: middle; }
    .feature-image-preview { margin-top: 10px; }
    .feature-image-preview img { max-width: 100%; height: auto; border: 1px solid #ddd; }
    .feature-checkbox-group { background: #f9f9f9; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    .feature-checkbox-group label { font-weight: normal; margin-bottom: 5px; }
    .feature-layout-preview { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; margin-top: 5px; }
    .layout-option { padding: 5px; border: 2px solid #ddd; border-radius: 3px; text-align: center; cursor: pointer; font-size: 11px; }
    .layout-option.selected { border-color: #0073aa; background: #e5f3ff; }
    </style>
    
    <div class="feature-meta-box">
        <div class="feature-checkbox-group">
            <label>
                <input type="checkbox" name="is_featured" value="1" <?php checked($is_featured, 1); ?> />
                <strong>Wyróżnij ten wpis</strong>
            </label>
            <label>
                <input type="checkbox" name="show_on_homepage" value="1" <?php checked($show_on_homepage, 1); ?> />
                Pokaż na stronie głównej
            </label>
            <label>
                <input type="checkbox" name="show_in_slider" value="1" <?php checked($show_in_slider, 1); ?> />
                Dodaj do slidera
            </label>
        </div>
        
        <div class="feature-field">
            <label for="feature_order">Kolejność wyświetlania</label>
            <input type="number" id="feature_order" name="feature_order" value="<?php echo esc_attr($feature_order); ?>" min="0" max="100" />
            <small>0 = najwyższy priorytet</small>
        </div>
        
        <div class="feature-field">
            <label for="feature_title">Tytuł wyróżnienia</label>
            <input type="text" id="feature_title" name="feature_title" value="<?php echo esc_attr($feature_title); ?>" placeholder="Zostaw puste dla domyślnego" />
        </div>
        
        <div class="feature-field">
            <label for="feature_subtitle">Podtytuł</label>
            <input type="text" id="feature_subtitle" name="feature_subtitle" value="<?php echo esc_attr($feature_subtitle); ?>" placeholder="Opcjonalny podtytuł" />
        </div>
        
        <div class="feature-field">
            <label for="feature_excerpt">Opis wyróżnienia</label>
            <textarea id="feature_excerpt" name="feature_excerpt" placeholder="Zostaw puste dla domyślnego"><?php echo esc_textarea($feature_excerpt); ?></textarea>
        </div>
        
        <div class="feature-field">
            <label for="feature_badge">Znaczek</label>
            <select id="feature_badge" name="feature_badge">
                <option value="">Brak znaczka</option>
                <option value="new" <?php selected($feature_badge, 'new'); ?>>🆕 Nowość</option>
                <option value="hot" <?php selected($feature_badge, 'hot'); ?>>🔥 Popularne</option>
                <option value="trending" <?php selected($feature_badge, 'trending'); ?>>📈 Trending</option>
                <option value="featured" <?php selected($feature_badge, 'featured'); ?>>⭐ Wyróżnione</option>
                <option value="recommended" <?php selected($feature_badge, 'recommended'); ?>>👍 Polecane</option>
                <option value="premium" <?php selected($feature_badge, 'premium'); ?>>💎 Premium</option>
            </select>
        </div>
        
        <div class="feature-field">
            <label for="feature_color">Kolor motywu</label>
            <input type="color" id="feature_color" name="feature_color" value="<?php echo esc_attr($feature_color ?: '#198754'); ?>" />
            <span class="feature-color-preview" style="background-color: <?php echo esc_attr($feature_color ?: '#198754'); ?>"></span>
        </div>
        
        <div class="feature-field">
            <label>Layout wyróżnienia</label>
            <select id="feature_layout" name="feature_layout">
                <option value="default" <?php selected($feature_layout, 'default'); ?>>Domyślny</option>
                <option value="large" <?php selected($feature_layout, 'large'); ?>>Duży</option>
                <option value="wide" <?php selected($feature_layout, 'wide'); ?>>Szeroki</option>
                <option value="compact" <?php selected($feature_layout, 'compact'); ?>>Kompaktowy</option>
                <option value="overlay" <?php selected($feature_layout, 'overlay'); ?>>Z nakładką</option>
            </select>
        </div>
        
        <div class="feature-field">
            <label>Obraz wyróżnienia</label>
            <input type="hidden" id="feature_image" name="feature_image" value="<?php echo esc_attr($feature_image); ?>" />
            <button type="button" class="button" onclick="carni24OpenFeatureImageUploader()">Wybierz obraz</button>
            <button type="button" class="button" onclick="carni24ClearFeatureImage()">Usuń</button>
            <div id="feature-image-preview" class="feature-image-preview">
                <?php if ($feature_image): ?>
                    <?php echo wp_get_attachment_image($feature_image, 'medium'); ?>
                <?php endif; ?>
            </div>
            <small>Pozostaw puste aby użyć featured image</small>
        </div>
    </div>
    
    <script>
    function carni24OpenFeatureImageUploader() {
        const mediaUploader = wp.media({
            title: 'Wybierz obraz wyróżnienia',
            button: { text: 'Użyj tego obrazu' },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById('feature_image').value = attachment.id;
            document.getElementById('feature-image-preview').innerHTML = 
                '<img src="' + attachment.sizes.medium.url + '" alt="" />';
        });
        
        mediaUploader.open();
    }
    
    function carni24ClearFeatureImage() {
        document.getElementById('feature_image').value = '';
        document.getElementById('feature-image-preview').innerHTML = '';
    }
    
    document.getElementById('feature_color').addEventListener('change', function() {
        document.querySelector('.feature-color-preview').style.backgroundColor = this.value;
    });
    </script>
    <?php
}

function carni24_save_feature_meta($post_id) {
    if (!isset($_POST['carni24_feature_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['carni24_feature_meta_box_nonce'], 'carni24_feature_meta_box')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $fields = array(
        'is_featured' => 'checkbox',
        'show_on_homepage' => 'checkbox', 
        'show_in_slider' => 'checkbox',
        'feature_order' => 'number',
        'feature_title' => 'text',
        'feature_subtitle' => 'text',
        'feature_excerpt' => 'textarea',
        'feature_badge' => 'text',
        'feature_color' => 'text',
        'feature_layout' => 'text',
        'feature_image' => 'number'
    );
    
    foreach ($fields as $field => $type) {
        if ($type === 'checkbox') {
            update_post_meta($post_id, '_' . $field, isset($_POST[$field]) ? 1 : 0);
        } elseif ($type === 'number') {
            $value = isset($_POST[$field]) ? absint($_POST[$field]) : 0;
            update_post_meta($post_id, '_' . $field, $value);
        } elseif ($type === 'textarea') {
            $value = isset($_POST[$field]) ? sanitize_textarea_field($_POST[$field]) : '';
            update_post_meta($post_id, '_' . $field, $value);
        } else {
            $value = isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '';
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post', 'carni24_save_feature_meta');

function carni24_get_featured_posts($args = array()) {
    $defaults = array(
        'post_type' => array('post', 'species', 'guides'),
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'meta_query' => array(
            array(
                'key' => '_is_featured',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'meta_value_num date',
        'meta_key' => '_feature_order',
        'order' => 'ASC'
    );
    
    $args = wp_parse_args($args, $defaults);
    
    return new WP_Query($args);
}

function carni24_get_homepage_featured_posts($limit = 4) {
    return carni24_get_featured_posts(array(
        'posts_per_page' => $limit,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_is_featured',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => '_show_on_homepage',
                'value' => '1',
                'compare' => '='
            )
        )
    ));
}

function carni24_get_slider_posts($limit = 5) {
    return carni24_get_featured_posts(array(
        'posts_per_page' => $limit,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_is_featured',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => '_show_in_slider',
                'value' => '1',
                'compare' => '='
            )
        )
    ));
}

function carni24_is_featured_post($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    return get_post_meta($post_id, '_is_featured', true) == '1';
}

function carni24_get_feature_data($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    return array(
        'is_featured' => get_post_meta($post_id, '_is_featured', true),
        'order' => get_post_meta($post_id, '_feature_order', true),
        'title' => get_post_meta($post_id, '_feature_title', true),
        'subtitle' => get_post_meta($post_id, '_feature_subtitle', true),
        'excerpt' => get_post_meta($post_id, '_feature_excerpt', true),
        'image' => get_post_meta($post_id, '_feature_image', true),
        'color' => get_post_meta($post_id, '_feature_color', true),
        'layout' => get_post_meta($post_id, '_feature_layout', true),
        'badge' => get_post_meta($post_id, '_feature_badge', true),
        'show_on_homepage' => get_post_meta($post_id, '_show_on_homepage', true),
        'show_in_slider' => get_post_meta($post_id, '_show_in_slider', true)
    );
}

function carni24_feature_admin_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        if ($key === 'title') {
            $new_columns['featured_status'] = '⭐ Wyróżnione';
        }
    }
    
    return $new_columns;
}
add_filter('manage_posts_columns', 'carni24_feature_admin_columns');
add_filter('manage_pages_columns', 'carni24_feature_admin_columns');

function carni24_feature_admin_columns_content($column, $post_id) {
    if ($column === 'featured_status') {
        $is_featured = get_post_meta($post_id, '_is_featured', true);
        $show_on_homepage = get_post_meta($post_id, '_show_on_homepage', true);
        $show_in_slider = get_post_meta($post_id, '_show_in_slider', true);
        $feature_order = get_post_meta($post_id, '_feature_order', true);
        $badge = get_post_meta($post_id, '_feature_badge', true);
        
        if ($is_featured) {
            echo '<div style="font-size: 12px;">';
            echo '<span style="color: #d63638; font-weight: bold;">⭐ WYRÓŻNIONE</span><br>';
            
            if ($feature_order) {
                echo 'Kolejność: ' . $feature_order . '<br>';
            }
            
            if ($badge) {
                $badges = array(
                    'new' => '🆕 Nowość',
                    'hot' => '🔥 Popularne', 
                    'trending' => '📈 Trending',
                    'featured' => '⭐ Wyróżnione',
                    'recommended' => '👍 Polecane',
                    'premium' => '💎 Premium'
                );
                echo $badges[$badge] . '<br>';
            }
            
            $locations = array();
            if ($show_on_homepage) $locations[] = 'Homepage';
            if ($show_in_slider) $locations[] = 'Slider';
            
            if (!empty($locations)) {
                echo '<small>Lokalizacje: ' . implode(', ', $locations) . '</small>';
            }
            echo '</div>';
        } else {
            echo '—';
        }
    }
}
add_action('manage_posts_custom_column', 'carni24_feature_admin_columns_content', 10, 2);
add_action('manage_pages_custom_column', 'carni24_feature_admin_columns_content', 10, 2);

function carni24_feature_admin_styles() {
    echo '<style>
        .column-featured_status {
            width: 150px;
        }
        
        tr.featured-post {
            background-color: #fff8e1 !important;
        }
        
        .featured-post .row-title {
            font-weight: bold;
        }
    </style>';
}
add_action('admin_head', 'carni24_feature_admin_styles');

function carni24_highlight_featured_posts($classes, $post_id) {
    if (carni24_is_featured_post($post_id)) {
        $classes .= ' featured-post';
    }
    
    return $classes;
}
add_filter('post_class', 'carni24_highlight_featured_posts', 10, 2);

function carni24_feature_quick_edit() {
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label>
                <input type="checkbox" name="is_featured" value="1" />
                <span class="checkbox-title">Wyróżnione</span>
            </label>
        </div>
    </fieldset>
    
    <script>
    jQuery(document).ready(function($) {
        $('a.editinline').on('click', function() {
            var post_id = $(this).closest('tr').attr('id').replace('post-', '');
            var $inline_row = $('#edit-' + post_id);
            var is_featured = $('#post-' + post_id + ' .column-featured_status').text().includes('WYRÓŻNIONE');
            
            $inline_row.find('input[name="is_featured"]').prop('checked', is_featured);
        });
    });
    </script>
    <?php
}
add_action('quick_edit_custom_box', 'carni24_feature_quick_edit', 10, 2);