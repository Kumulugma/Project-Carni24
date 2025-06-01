<?php

// === CAROUSEL META BOXES ===

// Meta boxes for carousel
function add_carousel_meta_boxes() {
    add_meta_box(
        'carousel_settings',
        'Ustawienia karuzeli',
        'carousel_meta_box_callback',
        'page'
    );
}
add_action('add_meta_boxes', 'add_carousel_meta_boxes');

function carousel_meta_box_callback($post) {
    wp_nonce_field('carousel_meta_box', 'carousel_meta_box_nonce');
    
    echo '<table class="form-table">';
    
    for ($i = 1; $i <= 5; $i++) {
        $title = get_post_meta($post->ID, "carousel_title_$i", true);
        $interval = get_post_meta($post->ID, "carousel_interval_$i", true);
        $image = get_post_meta($post->ID, "carousel_image_$i", true);
        
        echo '<tr>';
        echo '<th><label for="carousel_title_' . $i . '">Slajd ' . $i . ' - Tytuł:</label></th>';
        echo '<td><input type="text" id="carousel_title_' . $i . '" name="carousel_title_' . $i . '" value="' . esc_attr($title) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th><label for="carousel_interval_' . $i . '">Slajd ' . $i . ' - Interwał (ms):</label></th>';
        echo '<td><input type="number" id="carousel_interval_' . $i . '" name="carousel_interval_' . $i . '" value="' . esc_attr($interval) . '" class="small-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th><label for="carousel_image_' . $i . '">Slajd ' . $i . ' - Obraz:</label></th>';
        echo '<td>';
        echo '<input type="hidden" id="carousel_image_' . $i . '" name="carousel_image_' . $i . '" value="' . esc_attr($image) . '" />';
        echo '<button type="button" class="button" onclick="openMediaUploader(' . $i . ')">Wybierz obraz</button>';
        if ($image) {
            echo '<div id="image_preview_' . $i . '">';
            echo wp_get_attachment_image($image, 'thumbnail');
            echo '</div>';
        }
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    
    // JavaScript for media uploader
    ?>
    <script>
    function openMediaUploader(slideNumber) {
        var mediaUploader = wp.media({
            title: 'Wybierz obraz dla slajdu ' + slideNumber,
            button: {text: 'Użyj tego obrazu'},
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById('carousel_image_' + slideNumber).value = attachment.id;
            document.getElementById('image_preview_' + slideNumber).innerHTML = '<img src="' + attachment.sizes.thumbnail.url + '" />';
        });
        
        mediaUploader.open();
    }
    </script>
    <?php
}

function save_carousel_meta($post_id) {
    if (!isset($_POST['carousel_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['carousel_meta_box_nonce'], 'carousel_meta_box')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    for ($i = 1; $i <= 5; $i++) {
        if (isset($_POST["carousel_title_$i"])) {
            update_post_meta($post_id, "carousel_title_$i", sanitize_text_field($_POST["carousel_title_$i"]));
        }
        
        if (isset($_POST["carousel_interval_$i"])) {
            update_post_meta($post_id, "carousel_interval_$i", absint($_POST["carousel_interval_$i"]));
        }
        
        if (isset($_POST["carousel_image_$i"])) {
            update_post_meta($post_id, "carousel_image_$i", absint($_POST["carousel_image_$i"]));
        }
    }
}
add_action('save_post', 'save_carousel_meta');

// === FEATURE META BOXES ===

// Meta boxes for feature section
function add_feature_meta_boxes() {
    add_meta_box(
        'feature_settings',
        'Ustawienia sekcji feature',
        'feature_meta_box_callback',
        'page'
    );
}
add_action('add_meta_boxes', 'add_feature_meta_boxes');

function feature_meta_box_callback($post) {
    wp_nonce_field('feature_meta_box', 'feature_meta_box_nonce');
    
    $title = get_post_meta($post->ID, 'feature_title', true);
    $content = get_post_meta($post->ID, 'feature_content', true);
    $image = get_post_meta($post->ID, 'feature_image', true);
    
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="feature_title">Tytuł:</label></th>';
    echo '<td><input type="text" id="feature_title" name="feature_title" value="' . esc_attr($title) . '" class="regular-text" /></td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="feature_content">Treść:</label></th>';
    echo '<td><textarea id="feature_content" name="feature_content" rows="5" class="large-text">' . esc_textarea($content) . '</textarea></td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="feature_image">Obraz:</label></th>';
    echo '<td>';
    echo '<input type="hidden" id="feature_image" name="feature_image" value="' . esc_attr($image) . '" />';
    echo '<button type="button" class="button" onclick="openFeatureMediaUploader()">Wybierz obraz</button>';
    if ($image) {
        echo '<div id="feature_image_preview">';
        echo wp_get_attachment_image($image, 'thumbnail');
        echo '</div>';
    }
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    
    ?>
    <script>
    function openFeatureMediaUploader() {
        var mediaUploader = wp.media({
            title: 'Wybierz obraz feature',
            button: {text: 'Użyj tego obrazu'},
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById('feature_image').value = attachment.id;
            document.getElementById('feature_image_preview').innerHTML = '<img src="' + attachment.sizes.thumbnail.url + '" />';
        });
        
        mediaUploader.open();
    }
    </script>
    <?php
}

function save_feature_meta($post_id) {
    if (!isset($_POST['feature_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['feature_meta_box_nonce'], 'feature_meta_box')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['feature_title'])) {
        update_post_meta($post_id, 'feature_title', sanitize_text_field($_POST['feature_title']));
    }
    
    if (isset($_POST['feature_content'])) {
        update_post_meta($post_id, 'feature_content', wp_kses_post($_POST['feature_content']));
    }
    
    if (isset($_POST['feature_image'])) {
        update_post_meta($post_id, 'feature_image', absint($_POST['feature_image']));
    }
}
add_action('save_post', 'save_feature_meta');

// === SEO META BOXES ===

// Add SEO meta boxes to posts, pages and species
function add_seo_meta_boxes() {
    $post_types = array('post', 'page', 'species');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'seo_settings',
            'Ustawienia SEO',
            'seo_meta_box_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'add_seo_meta_boxes');

function seo_meta_box_callback($post) {
    wp_nonce_field('seo_meta_box', 'seo_meta_box_nonce');
    
    $meta_title = get_post_meta($post->ID, '_seo_title', true);
    $meta_description = get_post_meta($post->ID, '_seo_description', true);
    $meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);
    $canonical_url = get_post_meta($post->ID, '_seo_canonical', true);
    $noindex = get_post_meta($post->ID, '_seo_noindex', true);
    $nofollow = get_post_meta($post->ID, '_seo_nofollow', true);
    $og_title = get_post_meta($post->ID, '_seo_og_title', true);
    $og_description = get_post_meta($post->ID, '_seo_og_description', true);
    $og_image = get_post_meta($post->ID, '_seo_og_image', true);
    
    echo '<table class="form-table">';
    
    echo '<tr>';
    echo '<th><label for="seo_title">Meta Title:</label></th>';
    echo '<td>';
    echo '<input type="text" id="seo_title" name="seo_title" value="' . esc_attr($meta_title) . '" class="large-text" maxlength="60" />';
    echo '<p class="description">Pozostaw puste, aby użyć domyślnego tytułu. Maksymalnie 60 znaków.</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="seo_description">Meta Description:</label></th>';
    echo '<td>';
    echo '<textarea id="seo_description" name="seo_description" rows="3" class="large-text" maxlength="160">' . esc_textarea($meta_description) . '</textarea>';
    echo '<p class="description">Opis strony w wynikach wyszukiwania. Maksymalnie 160 znaków.</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="seo_keywords">Meta Keywords:</label></th>';
    echo '<td>';
    echo '<input type="text" id="seo_keywords" name="seo_keywords" value="' . esc_attr($meta_keywords) . '" class="large-text" />';
    echo '<p class="description">Słowa kluczowe oddzielone przecinkami (opcjonalne).</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="seo_canonical">Canonical URL:</label></th>';
    echo '<td>';
    echo '<input type="url" id="seo_canonical" name="seo_canonical" value="' . esc_attr($canonical_url) . '" class="large-text" />';
    echo '<p class="description">Pozostaw puste, aby użyć domyślnego URL.</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th>Ustawienia robotów:</th>';
    echo '<td>';
    echo '<label><input type="checkbox" name="seo_noindex" value="1"' . checked($noindex, 1, false) . '> No Index (nie indeksuj tej strony)</label><br>';
    echo '<label><input type="checkbox" name="seo_nofollow" value="1"' . checked($nofollow, 1, false) . '> No Follow (nie podążaj za linkami)</label>';
    echo '</td>';
    echo '</tr>';
    
    echo '</table>';
    
    echo '<h3>Open Graph (Facebook/Social)</h3>';
    echo '<table class="form-table">';
    
    echo '<tr>';
    echo '<th><label for="seo_og_title">OG Title:</label></th>';
    echo '<td>';
    echo '<input type="text" id="seo_og_title" name="seo_og_title" value="' . esc_attr($og_title) . '" class="large-text" />';
    echo '<p class="description">Tytuł dla mediów społecznościowych. Pozostaw puste, aby użyć meta title.</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="seo_og_description">OG Description:</label></th>';
    echo '<td>';
    echo '<textarea id="seo_og_description" name="seo_og_description" rows="3" class="large-text">' . esc_textarea($og_description) . '</textarea>';
    echo '<p class="description">Opis dla mediów społecznościowych. Pozostaw puste, aby użyć meta description.</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="seo_og_image">OG Image:</label></th>';
    echo '<td>';
    echo '<input type="hidden" id="seo_og_image" name="seo_og_image" value="' . esc_attr($og_image) . '" />';
    echo '<button type="button" class="button" onclick="openOgImageUploader()">Wybierz obraz OG</button>';
    echo '<button type="button" class="button" onclick="clearOgImage()" style="margin-left: 10px;">Usuń</button>';
    if ($og_image) {
        echo '<div id="og_image_preview" style="margin-top: 10px;">';
        echo wp_get_attachment_image($og_image, 'thumbnail');
        echo '</div>';
    } else {
        echo '<div id="og_image_preview" style="margin-top: 10px;"></div>';
    }
    echo '<p class="description">Obraz dla mediów społecznościowych. Pozostaw puste, aby użyć featured image.</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '</table>';
    
    ?>
    <script>
    function openOgImageUploader() {
        var mediaUploader = wp.media({
            title: 'Wybierz obraz OG',
            button: {text: 'Użyj tego obrazu'},
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            document.getElementById('seo_og_image').value = attachment.id;
            document.getElementById('og_image_preview').innerHTML = '<img src="' + attachment.sizes.thumbnail.url + '" />';
        });
        
        mediaUploader.open();
    }
    
    function clearOgImage() {
        document.getElementById('seo_og_image').value = '';
        document.getElementById('og_image_preview').innerHTML = '';
    }
    </script>
    <?php
}

function save_seo_meta($post_id) {
    if (!isset($_POST['seo_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['seo_meta_box_nonce'], 'seo_meta_box')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $seo_fields = array(
        'seo_title' => 'sanitize_text_field',
        'seo_description' => 'sanitize_textarea_field',
        'seo_keywords' => 'sanitize_text_field',
        'seo_canonical' => 'esc_url_raw',
        'seo_og_title' => 'sanitize_text_field',
        'seo_og_description' => 'sanitize_textarea_field'
    );
    
    foreach ($seo_fields as $field => $sanitize_function) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_function($_POST[$field]));
        }
    }
    
    // Checkboxes
    update_post_meta($post_id, '_seo_noindex', isset($_POST['seo_noindex']) ? 1 : 0);
    update_post_meta($post_id, '_seo_nofollow', isset($_POST['seo_nofollow']) ? 1 : 0);
    
    // OG Image
    if (isset($_POST['seo_og_image'])) {
        update_post_meta($post_id, '_seo_og_image', absint($_POST['seo_og_image']));
    }
}
add_action('save_post', 'save_seo_meta');