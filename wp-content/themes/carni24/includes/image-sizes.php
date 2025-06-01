<?php

// Dodaj custom image sizes
add_action('after_setup_theme', 'carni24_image_sizes');
function carni24_image_sizes() {
    // Carousel - duże obrazy tła (16:9)
    add_image_size('carousel', 2320, 300, true);
    
    // Feature section - średnie obrazy (3:2)
    add_image_size('feature', 600, 400, true);
    
    // Card thumbnails - małe obrazy do kart (16:9)
    add_image_size('blog_thumb', 600, 475, true);
    
    // Scene - obrazy artykułów (16:9)
    add_image_size('scene', 1200, 675, true);
    
    // Tiles - bardzo małe miniaturki (1:1)
    add_image_size('tiles', 150, 150, true);
    
    // Gallery thumbs - miniaturki galerii (1:1)
    add_image_size('gallery_thumb', 300, 300, true);
    
    // News thumbnails - dla manifest sekcji (4:3)
    add_image_size('manifest_thumb', 320, 340, true);
}

add_filter('image_size_names_choose', 'carni24_custom_sizes');
function carni24_custom_sizes($sizes) {
    return array_merge($sizes, array(
        'carousel' => __('Carousel (1920x300)'),
        'feature' => __('Feature (600x400)'),
        'blog_thumb' => __('Blog Card (400x225)'),
        'scene' => __('Scene (1200x675)'),
        'tiles' => __('Kafelek (150x150)'),
        'gallery_thumb' => __('Galeria (300x300)'),
        'manifest_thumb' => __('Manifest (320x240)')
    ));
}