<?php
// wp-content/themes/carni24/includes/image-sizes.php
// Zaktualizowane rozmiary miniaturek z sensownymi wymiarami

function carni24_setup_image_sizes() {
    // === PODSTAWOWE ROZMIARY ===
    
    // Miniaturka (thumbnail) - dla list, widgetów
    update_option('thumbnail_size_w', 150);
    update_option('thumbnail_size_h', 150);
    update_option('thumbnail_crop', 1);
    
    // Średni rozmiar - dla content, artykułów
    update_option('medium_size_w', 400);
    update_option('medium_size_h', 300);
    
    // Średni-duży - dla responsive
    update_option('medium_large_size_w', 768);
    update_option('medium_large_size_h', 0);
    
    // Duży rozmiar - dla galerii, featured images
    update_option('large_size_w', 1024);
    update_option('large_size_h', 768);
    
    // === CUSTOM ROZMIARY ===
    
    // HOMEPAGE - Slider główny (16:9)
    add_image_size('homepage_slider', 1200, 675, true);
    add_image_size('homepage_slider_mobile', 800, 450, true);
    
    // HOMEPAGE - Karty wpisów (4:3)
    add_image_size('homepage_card', 400, 300, true);
    add_image_size('homepage_card_small', 300, 225, true);
    
    // HOMEPAGE - Wyróżnione wpisy (3:2)
    add_image_size('homepage_featured', 600, 400, true);
    
    // BLOG - Lista wpisów (16:10)
    add_image_size('blog_thumb', 400, 250, true);
    add_image_size('blog_thumb_large', 600, 375, true);
    
    // MANIFEST - Popularne wpisy (3:2)
    add_image_size('manifest_thumb', 350, 233, true);
    
    // SPECIES - Gatunki (1:1 kwadrat)
    add_image_size('species_thumb', 300, 300, true);
    add_image_size('species_card', 400, 400, true);
    add_image_size('species_hero', 800, 500, true);
    
    // GALLERY - Galerie zdjęć
    add_image_size('gallery_thumb', 200, 200, true);
    add_image_size('gallery_medium', 500, 375, true);
    add_image_size('gallery_large', 1000, 750, true);
    
    // ARCHIVE - Strony archiwów (16:10)
    add_image_size('archive_thumb', 350, 219, true);
    
    // WIDGET - Miniaturki w widgetach
    add_image_size('widget_thumb', 80, 80, true);
    add_image_size('widget_medium', 200, 150, true);
    
    // SOCIAL - Media społecznościowe
    add_image_size('social_facebook', 1200, 630, true);
    add_image_size('social_twitter', 1024, 512, true);
    add_image_size('social_instagram', 1080, 1080, true);
    
    // MOBILE - Dedykowane dla urządzeń mobilnych
    add_image_size('mobile_hero', 600, 400, true);
    add_image_size('mobile_card', 280, 180, true);
    
    // === RESPONSIVE BREAKPOINTS ===
    
    // Dla różnych rozdzielczości ekranu
    add_image_size('retina_small', 600, 400, true);    // 2x dla 300x200
    add_image_size('retina_medium', 800, 600, true);   // 2x dla 400x300
    add_image_size('retina_large', 1600, 1200, true);  // 2x dla 800x600
}
add_action('after_setup_theme', 'carni24_setup_image_sizes');

// === DODATKOWE USTAWIENIA OBRAZÓW ===

// Ustaw jakość JPEG
function carni24_set_image_quality($quality, $mime_type) {
    // Pobierz ustawienie z opcji motywu lub użyj domyślnej wartości
    $custom_quality = get_option('carni24_jpg_quality', 85);
    
    if ($mime_type === 'image/jpeg') {
        return $custom_quality;
    }
    
    return $quality;
}
add_filter('wp_editor_set_quality', 'carni24_set_image_quality', 10, 2);

// Dodaj wsparcie dla WebP (jeśli włączone)
function carni24_enable_webp_support() {
    $enable_webp = get_option('carni24_enable_webp', 0);
    
    if ($enable_webp) {
        add_filter('wp_generate_attachment_metadata', 'carni24_generate_webp_versions');
    }
}
add_action('init', 'carni24_enable_webp_support');

function carni24_generate_webp_versions($metadata) {
    if (!function_exists('imagewebp')) {
        return $metadata;
    }
    
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['basedir'] . '/' . $metadata['file'];
    
    // Generuj WebP dla głównego obrazu
    if (file_exists($file_path)) {
        $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file_path);
        carni24_convert_to_webp($file_path, $webp_path);
    }
    
    // Generuj WebP dla miniaturek
    if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
        foreach ($metadata['sizes'] as $size => $size_data) {
            $size_path = $upload_dir['basedir'] . '/' . dirname($metadata['file']) . '/' . $size_data['file'];
            $webp_size_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $size_path);
            
            if (file_exists($size_path)) {
                carni24_convert_to_webp($size_path, $webp_size_path);
            }
        }
    }
    
    return $metadata;
}

function carni24_convert_to_webp($source, $destination) {
    $info = getimagesize($source);
    
    if ($info === false) {
        return false;
    }
    
    $image = null;
    
    switch ($info['mime']) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            return false;
    }
    
    if ($image !== null) {
        $quality = get_option('carni24_webp_quality', 80);
        $result = imagewebp($image, $destination, $quality);
        imagedestroy($image);
        return $result;
    }
    
    return false;
}

// === FUNKCJE POMOCNICZE ===

/**
 * Pobiera URL obrazu w najlepszym dostępnym rozmiarze
 */
function carni24_get_responsive_image_url($attachment_id, $base_size, $retina = false) {
    if ($retina) {
        // Sprawdź czy istnieje wersja retina
        $retina_size = 'retina_' . $base_size;
        $retina_url = wp_get_attachment_image_url($attachment_id, $retina_size);
        
        if ($retina_url) {
            return $retina_url;
        }
    }
    
    return wp_get_attachment_image_url($attachment_id, $base_size);
}

/**
 * Generuje responsive srcset dla obrazu
 */
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

/**
 * Dodaje lazy loading do obrazów (jeśli włączone)
 */
function carni24_add_lazy_loading($attr, $attachment, $size) {
    $lazy_loading = get_option('carni24_lazy_loading', 1);
    
    if ($lazy_loading && !is_admin()) {
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'carni24_add_lazy_loading', 10, 3);

// === LISTA WSZYSTKICH ROZMIARÓW ===

/**
 * Zwraca listę wszystkich dostępnych rozmiarów obrazów
 */
function carni24_get_all_image_sizes() {
    global $_wp_additional_image_sizes;
    
    $sizes = array();
    
    // WordPress domyślne rozmiary
    $default_sizes = array('thumbnail', 'medium', 'medium_large', 'large');
    
    foreach ($default_sizes as $size) {
        $sizes[$size] = array(
            'width'  => get_option($size . '_size_w'),
            'height' => get_option($size . '_size_h'),
            'crop'   => get_option($size . '_crop')
        );
    }
    
    // Custom rozmiary
    if (isset($_wp_additional_image_sizes)) {
        $sizes = array_merge($sizes, $_wp_additional_image_sizes);
    }
    
    return $sizes;
}

/**
 * Wyświetla informacje o rozmiarach obrazów (dla admina)
 */
function carni24_display_image_sizes_info() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $sizes = carni24_get_all_image_sizes();
    
    echo '<div class="carni24-image-sizes-info">';
    echo '<h3>Aktualnie zdefiniowane rozmiary obrazów:</h3>';
    echo '<table class="wp-list-table widefat">';
    echo '<thead><tr><th>Nazwa</th><th>Wymiary</th><th>Przycinanie</th><th>Zastosowanie</th></tr></thead>';
    echo '<tbody>';
    
    $descriptions = array(
        'thumbnail' => 'Miniaturki, listy, widgety',
        'medium' => 'Artykuły, treści',
        'medium_large' => 'Responsive, tablet',
        'large' => 'Galerie, featured images',
        'homepage_slider' => 'Slider główny (desktop)',
        'homepage_slider_mobile' => 'Slider główny (mobile)',
        'homepage_card' => 'Karty wpisów na stronie głównej',
        'homepage_featured' => 'Wyróżnione wpisy',
        'blog_thumb' => 'Lista wpisów w blogu',
        'species_thumb' => 'Miniaturki gatunków',
        'species_card' => 'Karty gatunków',
        'gallery_thumb' => 'Miniaturki galerii',
        'social_facebook' => 'Facebook Open Graph',
        'social_twitter' => 'Twitter Cards'
    );
    
    foreach ($sizes as $name => $data) {
        $crop_text = !empty($data['crop']) ? 'Tak' : 'Nie';
        $description = isset($descriptions[$name]) ? $descriptions[$name] : '';
        
        echo '<tr>';
        echo '<td><code>' . $name . '</code></td>';
        echo '<td>' . $data['width'] . ' × ' . $data['height'] . ' px</td>';
        echo '<td>' . $crop_text . '</td>';
        echo '<td>' . $description . '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    echo '</div>';
}

// === ZALECENIA JAKOŚCI ===

/*
ZALECENIA ROZMIARÓW I JAKOŚCI:

1. HOMEPAGE SLIDER: 1200×675 (16:9)
   - Duże obrazy hero, dobra jakość (85-90%)
   - WebP: zalecane dla szybkości

2. KARTY WPISÓW: 400×300 (4:3)
   - Uniwersalny format, dobry dla większości treści
   - Jakość: 80-85%

3. GATUNKI: 400×400 (1:1)
   - Kwadratowe dla spójności botanicznej
   - Wysoka jakość dla detali (85-90%)

4. GALERIE: 200×200 (thumb), 1000×750 (large)
   - Małe thumb dla szybkości, duże dla lightbox
   - Thumb: 75%, Large: 90%

5. MOBILE: Oddzielne rozmiary dla lepszej wydajności
   - 600×400 dla hero mobile
   - 280×180 dla kart mobile

6. SOCIAL MEDIA: Zgodne ze standardami platform
   - Facebook: 1200×630
   - Twitter: 1024×512
   - Instagram: 1080×1080

WSKAZÓWKI:
- Używaj WebP gdy to możliwe (30-50% mniej miejsca)
- Lazy loading dla obrazów poniżej fold
- Retina (@2x) dla urządzeń wysokiej rozdzielczości
- Przycinaj zawsze gdy potrzebujesz spójnych wymiarów
- Jakość 80-85% to dobry kompromis rozmiar/jakość
*/
?>