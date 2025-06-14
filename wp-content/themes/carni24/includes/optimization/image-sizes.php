<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_setup_image_sizes() {
    update_option('thumbnail_size_w', 150);
    update_option('thumbnail_size_h', 150);
    update_option('thumbnail_crop', 1);
    
    update_option('medium_size_w', 400);
    update_option('medium_size_h', 300);
    
    update_option('medium_large_size_w', 768);
    update_option('medium_large_size_h', 0);
    
    update_option('large_size_w', 1024);
    update_option('large_size_h', 768);
    
    add_image_size('homepage_slider', 1200, 675, true);
    add_image_size('homepage_slider_mobile', 800, 450, true);
    
    add_image_size('homepage_card', 400, 300, true);
    add_image_size('homepage_card_small', 300, 225, true);
    
    add_image_size('homepage_featured', 600, 400, true);
    
    add_image_size('blog_thumb', 400, 250, true);
    add_image_size('blog_thumb_large', 600, 375, true);
    
    add_image_size('manifest_thumb', 350, 233, true);
    
    add_image_size('species_thumb', 300, 300, true);
    add_image_size('species_card', 400, 400, true);
    add_image_size('species_hero', 800, 500, true);
    
    add_image_size('gallery_thumb', 200, 200, true);
    add_image_size('gallery_medium', 500, 375, true);
    add_image_size('gallery_large', 1000, 750, true);
    
    add_image_size('archive_thumb', 350, 219, true);
    
    add_image_size('widget_thumb', 80, 80, true);
    add_image_size('widget_medium', 200, 150, true);
    
    add_image_size('social_facebook', 1200, 630, true);
    add_image_size('social_twitter', 1024, 512, true);
    add_image_size('social_instagram', 1080, 1080, true);
    
    add_image_size('mobile_hero', 600, 400, true);
    add_image_size('mobile_card', 280, 180, true);
    
    add_image_size('retina_small', 300, 300, true);
    add_image_size('retina_medium', 800, 600, true);
    add_image_size('retina_large', 1600, 1200, true);
    
    add_image_size('guides_hero', 1200, 400, true);
    add_image_size('guides_thumb', 350, 200, true);
    
    add_image_size('author_avatar', 150, 150, true);
    add_image_size('author_header', 800, 300, true);
}
add_action('after_setup_theme', 'carni24_setup_image_sizes');

function carni24_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'homepage_slider' => 'Homepage Slider (1200x675)',
        'homepage_card' => 'Homepage Card (400x300)',
        'blog_thumb' => 'Blog Thumbnail (400x250)',
        'species_thumb' => 'Species Thumbnail (300x300)',
        'gallery_thumb' => 'Gallery Thumbnail (200x200)',
        'widget_thumb' => 'Widget Thumbnail (80x80)',
        'social_facebook' => 'Facebook OG (1200x630)',
        'mobile_hero' => 'Mobile Hero (600x400)',
        'guides_thumb' => 'Guides Thumbnail (350x200)',
    ));
}
add_filter('image_size_names_choose', 'carni24_custom_image_sizes');

function carni24_get_retina_image_url($attachment_id, $base_size) {
    $retina_map = array(
        'thumbnail' => 'retina_small',
        'medium' => 'retina_medium',
        'large' => 'retina_large'
    );
    
    if (isset($retina_map[$base_size])) {
        $retina_size = $retina_map[$base_size];
        $retina_url = wp_get_attachment_image_url($attachment_id, $retina_size);
        
        if ($retina_url) {
            return $retina_url;
        }
    }
    
    return wp_get_attachment_image_url($attachment_id, $base_size);
}

function carni24_get_responsive_srcset($attachment_id, $sizes = array()) {
    $srcset = array();
    
    foreach ($sizes as $size => $descriptor) {
        $url = wp_get_attachment_image_url($attachment_id, $size);
        if ($url) {
            $srcset[] = $url . ' ' . $descriptor;
        }
    }
    
    return implode(', ', $srcset);
}

function carni24_add_lazy_loading($attr, $attachment, $size) {
    $lazy_loading = carni24_get_option('lazy_loading', 1);
    
    if ($lazy_loading && !is_admin()) {
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'carni24_add_lazy_loading', 10, 3);

function carni24_get_all_image_sizes() {
    global $_wp_additional_image_sizes;
    
    $sizes = array();
    
    $default_sizes = array('thumbnail', 'medium', 'medium_large', 'large');
    
    foreach ($default_sizes as $size) {
        $sizes[$size] = array(
            'width'  => get_option($size . '_size_w'),
            'height' => get_option($size . '_size_h'),
            'crop'   => get_option($size . '_crop')
        );
    }
    
    if (isset($_wp_additional_image_sizes)) {
        $sizes = array_merge($sizes, $_wp_additional_image_sizes);
    }
    
    return $sizes;
}

function carni24_optimize_image_quality($quality, $mime_type) {
    if ($mime_type === 'image/jpeg') {
        return 85;
    }
    
    if ($mime_type === 'image/png') {
        return 90;
    }
    
    if ($mime_type === 'image/webp') {
        return 80;
    }
    
    return $quality;
}
add_filter('wp_editor_set_quality', 'carni24_optimize_image_quality', 10, 2);
add_filter('jpeg_quality', 'carni24_optimize_image_quality', 10, 2);

function carni24_add_webp_support($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'carni24_add_webp_support');

function carni24_enable_svg_support($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'carni24_enable_svg_support');

function carni24_fix_svg_display() {
    echo '<style>
        .attachment-266x266, .thumbnail img {
            width: 100% !important;
            height: auto !important;
        }
        
        .media-icon img[src$=".svg"] {
            width: 100%;
            height: auto;
        }
    </style>';
}
add_action('admin_head', 'carni24_fix_svg_display');

function carni24_get_optimized_image($attachment_id, $size = 'medium', $mobile_size = null) {
    if (wp_is_mobile() && $mobile_size) {
        return wp_get_attachment_image_url($attachment_id, $mobile_size);
    }
    
    return wp_get_attachment_image_url($attachment_id, $size);
}

function carni24_add_image_preload() {
    if (is_front_page()) {
        $hero_image_id = carni24_get_option('hero_image_id', '');
        if ($hero_image_id) {
            $hero_url = wp_get_attachment_image_url($hero_image_id, 'homepage_slider');
            if ($hero_url) {
                echo '<link rel="preload" as="image" href="' . esc_url($hero_url) . '">' . "\n";
            }
        }
    }
}
add_action('wp_head', 'carni24_add_image_preload', 1);

function carni24_responsive_images_sizes($sizes, $size) {
    if ($size === 'homepage_card') {
        return '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
    }
    
    if ($size === 'blog_thumb') {
        return '(max-width: 768px) 100vw, 400px';
    }
    
    if ($size === 'species_thumb') {
        return '(max-width: 768px) 50vw, 300px';
    }
    
    return $sizes;
}
add_filter('wp_calculate_image_sizes', 'carni24_responsive_images_sizes', 10, 2);

function carni24_disable_image_sizes($sizes) {
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'carni24_disable_image_sizes');

function carni24_limit_image_sizes($sizes, $metadata) {
    $allowed_sizes = array(
        'thumbnail',
        'medium', 
        'medium_large',
        'large',
        'homepage_slider',
        'homepage_card',
        'blog_thumb',
        'species_thumb',
        'widget_thumb',
        'social_facebook'
    );
    
    return array_intersect_key($sizes, array_flip($allowed_sizes));
}
add_filter('intermediate_image_sizes_advanced', 'carni24_limit_image_sizes', 10, 2);

function carni24_image_downsize($downsize, $id, $size) {
    if (!wp_attachment_is_image($id)) {
        return false;
    }
    
    if (is_array($size) && count($size) === 2) {
        $width = (int) $size[0];
        $height = (int) $size[1];
        
        if ($width > 2000 || $height > 2000) {
            $max_width = 1600;
            $max_height = 1200;
            
            $ratio = min($max_width / $width, $max_height / $height);
            
            if ($ratio < 1) {
                $new_width = (int) ($width * $ratio);
                $new_height = (int) ($height * $ratio);
                
                $size = array($new_width, $new_height);
            }
        }
    }
    
    return $downsize;
}
add_filter('image_downsize', 'carni24_image_downsize', 10, 3);

function carni24_clean_image_filenames($filename) {
    $filename = sanitize_file_name($filename);
    $filename = remove_accents($filename);
    $filename = strtolower($filename);
    $filename = preg_replace('/[^a-z0-9\-\.]/', '-', $filename);
    $filename = preg_replace('/-+/', '-', $filename);
    $filename = trim($filename, '-');
    
    return $filename;
}
add_filter('sanitize_file_name', 'carni24_clean_image_filenames');

function carni24_auto_alt_text($attr, $attachment, $size) {
    if (empty($attr['alt'])) {
        $alt_text = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        
        if (empty($alt_text)) {
            $alt_text = $attachment->post_title;
            $alt_text = sanitize_text_field($alt_text);
        }
        
        $attr['alt'] = $alt_text;
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'carni24_auto_alt_text', 10, 3);

function carni24_regenerate_image_sizes() {
    $attachment_ids = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'numberposts' => -1,
        'fields' => 'ids'
    ));
    
    foreach ($attachment_ids as $attachment_id) {
        $file_path = get_attached_file($attachment_id);
        if ($file_path && file_exists($file_path)) {
            $metadata = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $metadata);
        }
    }
}

function carni24_get_image_placeholder($width = 400, $height = 300, $text = '') {
    $placeholder_url = 'data:image/svg+xml;base64,' . base64_encode(
        '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">' .
        '<rect width="100%" height="100%" fill="#f8f9fa"/>' .
        '<text x="50%" y="50%" font-family="Arial" font-size="16" fill="#6c757d" text-anchor="middle" dy=".3em">' .
        ($text ?: $width . 'x' . $height) .
        '</text>' .
        '</svg>'
    );
    
    return $placeholder_url;
}

function carni24_optimize_thumbnails_on_upload($metadata, $attachment_id) {
    if (!wp_attachment_is_image($attachment_id)) {
        return $metadata;
    }
    
    $file_path = get_attached_file($attachment_id);
    
    if (!$file_path || !file_exists($file_path)) {
        return $metadata;
    }
    
    $image_editor = wp_get_image_editor($file_path);
    
    if (is_wp_error($image_editor)) {
        return $metadata;
    }
    
    $image_editor->set_quality(85);
    
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'carni24_optimize_thumbnails_on_upload', 10, 2);