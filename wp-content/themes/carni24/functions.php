<?php

//Register headerAsset
include("components/headerAsset.php");
//Register Species
include("post-types/species.php");
//Register Pagination
include("includes/pagination.php");
//Register Title Separator
include("includes/titleSeparator.php");
//Register Read More
include("includes/readMore.php");
//Gallery count
include("includes/galleryCount.php");
//Menu Link Class
include("includes/menuAClass.php");
//Breadcrumbs
include("includes/breadcrumbs.php");
//Spec ID
include("includes/specID.php");
//Sitemap
include("includes/sitemap.php");
//Theme Options
include("includes/theme-options.php");

add_theme_support('post-thumbnails');

// Dodaj custom image sizes
add_action('after_setup_theme', 'carni24_image_sizes');
function carni24_image_sizes() {
    // Carousel - duże obrazy tła (16:9)
    add_image_size('carousel', 1920, 300, true);
    
    // Feature section - średnie obrazy (3:2)
    add_image_size('feature', 600, 400, true);
    
    // Card thumbnails - małe obrazy do kart (16:9)
    add_image_size('blog_thumb', 400, 225, true);
    
    // Scene - obrazy artykułów (16:9)
    add_image_size('scene', 1200, 675, true);
    
    // Tiles - bardzo małe miniaturki (1:1)
    add_image_size('tiles', 150, 150, true);
    
    // Gallery thumbs - miniaturki galerii (1:1)
    add_image_size('gallery_thumb', 300, 300, true);
    
    // News thumbnails - dla manifest sekcji (4:3)
    add_image_size('manifest_thumb', 320, 240, true);
}

add_filter('image_size_names_choose', 'carni24_custom_sizes');
function carni24_custom_sizes($sizes) {
    return array_merge($sizes, array(
        'carousel' => __('Carousel (1920x1080)'),
        'feature' => __('Feature (600x400)'),
        'blog_thumb' => __('Blog Card (400x225)'),
        'scene' => __('Scene (1200x675)'),
        'tiles' => __('Kafelek (150x150)'),
        'gallery_thumb' => __('Galeria (300x300)'),
        'manifest_thumb' => __('Manifest (320x240)')
    ));
}

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

// Enqueue media uploader
function enqueue_media_uploader() {
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enqueue_media_uploader');

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

// === SEO OUTPUT ===

function carni24_seo_meta_tags() {
    global $post;
    
    $meta_title = '';
    $meta_description = '';
    $meta_keywords = '';
    $canonical_url = '';
    $noindex = false;
    $nofollow = false;
    $og_title = '';
    $og_description = '';
    $og_image = '';
    $og_url = '';
    
    // Tylko dla pojedynczych postów/stron pobieramy meta z wpisu
    if (is_singular()) {
        $meta_title = get_post_meta($post->ID, '_seo_title', true);
        $meta_description = get_post_meta($post->ID, '_seo_description', true);
        $meta_keywords = get_post_meta($post->ID, '_seo_keywords', true);
        $canonical_url = get_post_meta($post->ID, '_seo_canonical', true);
        $noindex = get_post_meta($post->ID, '_seo_noindex', true);
        $nofollow = get_post_meta($post->ID, '_seo_nofollow', true);
        $og_title = get_post_meta($post->ID, '_seo_og_title', true);
        $og_description = get_post_meta($post->ID, '_seo_og_description', true);
        $og_image_id = get_post_meta($post->ID, '_seo_og_image', true);
        
        if ($og_image_id) {
            $og_image = wp_get_attachment_image_url($og_image_id, 'large');
        }
        
        $og_url = get_permalink($post->ID);
    }
    
    // === FALLBACKS - najpierw ustawienia globalne, potem WordPress ===
    
    // Meta Title
    if (empty($meta_title)) {
        if (is_front_page() || is_home()) {
            // Strona główna - najpierw sprawdź ustawienia globalne
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $site_description = carni24_get_option('site_description', get_bloginfo('description'));
            if (!empty($site_name) && !empty($site_description)) {
                $meta_title = $site_name . ' - ' . $site_description;
            } else {
                $meta_title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
            }
        } elseif (is_singular()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = get_the_title() . ' - ' . $site_name;
        } elseif (is_category()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = single_cat_title('', false) . ' - ' . $site_name;
        } elseif (is_tag()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = single_tag_title('', false) . ' - ' . $site_name;
        } elseif (is_search()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $meta_title = 'Wyniki wyszukiwania: ' . get_search_query() . ' - ' . $site_name;
        } else {
            $meta_title = wp_get_document_title();
        }
    }
    
    // Meta Description
    if (empty($meta_description)) {
        if (is_front_page() || is_home()) {
            // Strona główna - najpierw ustawienia globalne
            $meta_description = carni24_get_option('default_meta_description', '');
            if (empty($meta_description)) {
                $meta_description = carni24_get_option('site_description', get_bloginfo('description'));
            }
        } elseif (is_singular() && has_excerpt()) {
            $meta_description = get_the_excerpt();
        } elseif (is_singular()) {
            $meta_description = wp_trim_words(strip_shortcodes(get_the_content()), 25);
        } elseif (is_category()) {
            $meta_description = category_description();
            if (empty($meta_description)) {
                $meta_description = 'Kategoria: ' . single_cat_title('', false);
            }
        } elseif (is_tag()) {
            $meta_description = tag_description();
            if (empty($meta_description)) {
                $meta_description = 'Tag: ' . single_tag_title('', false);
            }
        } else {
            // Fallback do globalnych ustawień
            $meta_description = carni24_get_option('default_meta_description', '');
            if (empty($meta_description)) {
                $meta_description = carni24_get_option('site_description', get_bloginfo('description'));
            }
        }
    }
    
    // Meta Keywords
    if (empty($meta_keywords)) {
        $meta_keywords = carni24_get_option('default_meta_keywords', '');
    }
    
    // Canonical URL
    if (empty($canonical_url)) {
        if (is_front_page() || is_home()) {
            $canonical_url = home_url('/');
        } elseif (is_singular()) {
            $canonical_url = get_permalink();
        } else {
            $canonical_url = home_url(add_query_arg(array(), $GLOBALS['wp']->request));
        }
    }
    
    // Open Graph Title
    if (empty($og_title)) {
        $og_title = $meta_title;
    }
    
    // Open Graph Description
    if (empty($og_description)) {
        $og_description = $meta_description;
    }
    
    // Open Graph Image
    if (empty($og_image)) {
        if (is_singular() && has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url($post->ID, 'large');
        } else {
            // Fallback do globalnego obrazu OG
            $default_og_image_id = carni24_get_option('default_og_image', '');
            if ($default_og_image_id) {
                $og_image = wp_get_attachment_image_url($default_og_image_id, 'large');
            }
        }
    }
    
    // Open Graph URL
    if (empty($og_url)) {
        $og_url = $canonical_url;
    }
    
    // === OUTPUT META TAGS ===
    echo "\n<!-- Carni24 SEO -->\n";
    
    if ($meta_title) {
        echo '<meta name="title" content="' . esc_attr($meta_title) . '">' . "\n";
    }
    
    if ($meta_description) {
        echo '<meta name="description" content="' . esc_attr(wp_trim_words($meta_description, 25)) . '">' . "\n";
    }
    
    if ($meta_keywords) {
        echo '<meta name="keywords" content="' . esc_attr($meta_keywords) . '">' . "\n";
    }
    
    if ($canonical_url) {
        echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
    }
    
    // Robots meta
    $robots = array();
    if ($noindex) $robots[] = 'noindex';
    if ($nofollow) $robots[] = 'nofollow';
    
    if (!empty($robots)) {
        echo '<meta name="robots" content="' . implode(', ', $robots) . '">' . "\n";
    }
    
    // Open Graph
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr(wp_trim_words($og_description, 25)) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
    echo '<meta property="og:type" content="' . (is_singular() ? 'article' : 'website') . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(carni24_get_option('site_name', get_bloginfo('name'))) . '">' . "\n";
    
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        echo '<meta property="og:image:width" content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
    }
    
    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr(wp_trim_words($og_description, 25)) . '">' . "\n";
    
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '">' . "\n";
    }
    
    echo "<!-- /Carni24 SEO -->\n\n";
}

// Dodaj funkcję debugowania SEO (opcjonalnie)
function carni24_debug_seo() {
    if (current_user_can('manage_options') && isset($_GET['seo_debug'])) {
        echo "\n<!-- SEO DEBUG -->\n";
        echo "<!-- Site Name: " . carni24_get_option('site_name', 'BRAK') . " -->\n";
        echo "<!-- Site Description: " . carni24_get_option('site_description', 'BRAK') . " -->\n";
        echo "<!-- Default Meta Description: " . carni24_get_option('default_meta_description', 'BRAK') . " -->\n";
        echo "<!-- Default Meta Keywords: " . carni24_get_option('default_meta_keywords', 'BRAK') . " -->\n";
        echo "<!-- Default OG Image ID: " . carni24_get_option('default_og_image', 'BRAK') . " -->\n";
        echo "<!-- Is Front Page: " . (is_front_page() ? 'TAK' : 'NIE') . " -->\n";
        echo "<!-- Is Home: " . (is_home() ? 'TAK' : 'NIE') . " -->\n";
        echo "<!-- /SEO DEBUG -->\n\n";
    }
}
add_action('wp_head', 'carni24_debug_seo', 0);

// Upewnij się, że funkcja carni24_get_option istnieje
if (!function_exists('carni24_get_option')) {
    function carni24_get_option($option_name, $default = '') {
        return get_option('carni24_' . $option_name, $default);
    }
}
add_action('wp_head', 'carni24_seo_meta_tags', 1);

// Remove default WordPress canonical
remove_action('wp_head', 'rel_canonical');

// JSON-LD Schema
function carni24_json_ld_schema() {
    if (!is_singular()) return;
    
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => wp_trim_words(strip_shortcodes(get_the_content()), 25),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author()
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url()
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        )
    );
    
    if (has_post_thumbnail()) {
        $schema['image'] = get_the_post_thumbnail_url($post->ID, 'large');
    }
    
    if (get_post_type() === 'species') {
        $schema['@type'] = 'Article';
        $schema['about'] = array(
            '@type' => 'Thing',
            'name' => get_the_title()
        );
    }
    
    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "\n" . '</script>' . "\n";
}
add_action('wp_head', 'carni24_json_ld_schema');

// Enqueue admin styles and scripts for theme options page
function carni24_admin_enqueue_scripts($hook) {
    // Only load on our theme options page
    if ($hook !== 'appearance_page_carni24-theme-options') {
        return;
    }
    
    // Enqueue WordPress media uploader
    wp_enqueue_media();
    
    // Enqueue our custom admin CSS
    wp_enqueue_style(
        'carni24-admin-style',
        get_template_directory_uri() . '/assets/admin/css/admin-theme-options.css',
        array(),
        '2024.1'
    );
    
    // Enqueue our custom admin JS
    wp_enqueue_script(
        'carni24-admin-script',
        get_template_directory_uri() . '/assets/admin/js/admin-theme-options.js',
        array('jquery', 'wp-media'),
        '2024.1',
        true
    );
    
    // Pass data to JavaScript
    wp_localize_script('carni24-admin-script', 'carni24_admin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_admin_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'carni24_admin_enqueue_scripts');

// Enhanced notice styles for all admin pages
function carni24_admin_notice_styles() {
    ?>
    <style>
    .notice.notice-success .notice-content {
        display: flex;
        align-items: center;
        padding: 5px 0;
    }
    .notice.notice-success .notice-icon {
        margin-right: 10px;
        font-size: 16px;
    }
    .notice.notice-success .notice-text {
        flex: 1;
    }
    </style>
    <?php
}
add_action('admin_head', 'carni24_admin_notice_styles');

function carni24_add_seo_test_section() {
    ?>
    <div class="carni24-widget">
        <div class="carni24-widget-header">
            <h3><span class="icon">🔍</span> Test SEO</h3>
        </div>
        <div class="carni24-widget-content">
            <p><strong>Sprawdź jak działają Twoje ustawienia SEO:</strong></p>
            
            <div class="carni24-seo-test">
                <a href="<?= home_url('/?seo_debug=1') ?>" target="_blank" class="carni24-btn carni24-btn-primary carni24-btn-block">
                    <span class="dashicons dashicons-search"></span>
                    Sprawdź SEO strony głównej
                </a>
                
                <div class="carni24-seo-preview" id="seo-preview">
                    <h4>Podgląd w wyszukiwarce:</h4>
                    <div class="seo-preview-box">
                        <div class="seo-title" id="preview-title">
                            <?= esc_html(carni24_get_option('site_name', get_bloginfo('name'))) ?> - <?= esc_html(carni24_get_option('site_description', get_bloginfo('description'))) ?>
                        </div>
                        <div class="seo-url"><?= esc_url(home_url('/')) ?></div>
                        <div class="seo-description" id="preview-description">
                            <?= esc_html(carni24_get_option('default_meta_description', carni24_get_option('site_description', get_bloginfo('description')))) ?>
                        </div>
                    </div>
                </div>
                
                <div class="carni24-seo-status">
                    <h4>Status ustawień:</h4>
                    <div class="status-item">
                        <span class="status-label">Nazwa witryny:</span>
                        <span class="status-value <?= carni24_get_option('site_name', '') ? 'ok' : 'missing' ?>">
                            <?= carni24_get_option('site_name', '') ? '✅ Ustawiona' : '❌ Brak' ?>
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Opis witryny:</span>
                        <span class="status-value <?= carni24_get_option('site_description', '') ? 'ok' : 'missing' ?>">
                            <?= carni24_get_option('site_description', '') ? '✅ Ustawiony' : '❌ Brak' ?>
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Meta Description:</span>
                        <span class="status-value <?= carni24_get_option('default_meta_description', '') ? 'ok' : 'missing' ?>">
                            <?= carni24_get_option('default_meta_description', '') ? '✅ Ustawiony' : '❌ Brak' ?>
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Meta Keywords:</span>
                        <span class="status-value <?= carni24_get_option('default_meta_keywords', '') ? 'ok' : 'missing' ?>">
                            <?= carni24_get_option('default_meta_keywords', '') ? '✅ Ustawione' : '❌ Brak' ?>
                        </span>
                    </div>
                    <div class="status-item">
                        <span class="status-label">Obraz OG:</span>
                        <span class="status-value <?= carni24_get_option('default_og_image', '') ? 'ok' : 'missing' ?>">
                            <?= carni24_get_option('default_og_image', '') ? '✅ Ustawiony' : '❌ Brak' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    .carni24-seo-test {
        font-size: 14px;
    }
    
    .seo-preview-box {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        background: #f8f9fa;
        margin: 10px 0;
        font-family: arial, sans-serif;
    }
    
    .seo-title {
        color: #1a0dab;
        font-size: 18px;
        line-height: 1.3;
        margin-bottom: 5px;
        cursor: pointer;
    }
    
    .seo-title:hover {
        text-decoration: underline;
    }
    
    .seo-url {
        color: #006621;
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    .seo-description {
        color: #545454;
        font-size: 14px;
        line-height: 1.4;
    }
    
    .carni24-seo-status {
        margin-top: 15px;
    }
    
    .status-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .status-item:last-child {
        border-bottom: none;
    }
    
    .status-label {
        font-weight: 500;
    }
    
    .status-value.ok {
        color: #28a745;
    }
    
    .status-value.missing {
        color: #dc3545;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Update preview when form fields change
        function updateSeoPreview() {
            const siteName = $('#site_name').val() || '<?= esc_js(get_bloginfo('name')) ?>';
            const siteDesc = $('#site_description').val() || '<?= esc_js(get_bloginfo('description')) ?>';
            const metaDesc = $('#default_meta_description').val() || siteDesc;
            
            $('#preview-title').text(siteName + ' - ' + siteDesc);
            $('#preview-description').text(metaDesc);
        }
        
        $('#site_name, #site_description, #default_meta_description').on('input', updateSeoPreview);
    });
    </script>
    <?php
}

// Funkcja do szybkiego testu SEO
function carni24_quick_seo_test() {
    $results = array();
    
    // Test ustawień globalnych
    $results['site_name'] = !empty(carni24_get_option('site_name', ''));
    $results['site_description'] = !empty(carni24_get_option('site_description', ''));
    $results['default_meta_description'] = !empty(carni24_get_option('default_meta_description', ''));
    $results['default_meta_keywords'] = !empty(carni24_get_option('default_meta_keywords', ''));
    $results['default_og_image'] = !empty(carni24_get_option('default_og_image', ''));
    
    // Test czy funkcja SEO jest podpięta
    $results['seo_function_hooked'] = has_action('wp_head', 'carni24_seo_meta_tags');
    
    return $results;
}

// Ajax endpoint do sprawdzania SEO
function carni24_ajax_check_seo() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    $test_results = carni24_quick_seo_test();
    
    wp_send_json_success($test_results);
}
add_action('wp_ajax_carni24_check_seo', 'carni24_ajax_check_seo');

// Dodaj powiadomienie w panelu admina jeśli SEO nie jest skonfigurowane
function carni24_seo_admin_notice() {
    $screen = get_current_screen();
    
    // Pokaż tylko na dashboard i stronie ustawień motywu
    if ($screen && ($screen->id === 'dashboard' || $screen->id === 'appearance_page_carni24-theme-options')) {
        $test_results = carni24_quick_seo_test();
        
        $missing_seo = array();
        if (!$test_results['site_name']) $missing_seo[] = 'Nazwa witryny';
        if (!$test_results['default_meta_description']) $missing_seo[] = 'Meta Description';
        if (!$test_results['default_og_image']) $missing_seo[] = 'Obraz OG';
        
        if (!empty($missing_seo) && $screen->id === 'dashboard') {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong>Carni24 SEO:</strong> Brakuje niektórych ustawień SEO: <?= implode(', ', $missing_seo) ?>. 
                    <a href="<?= admin_url('themes.php?page=carni24-theme-options#seo-settings') ?>">Skonfiguruj teraz</a>
                </p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'carni24_seo_admin_notice');

// Włącz obsługę title-tag w motywie
add_theme_support('title-tag');

// Filtruj tytuł dokumentu
add_filter('document_title_parts', 'carni24_document_title_parts');

function carni24_document_title_parts($title_parts) {
    global $post;
    
    // Dla pojedynczych postów/stron sprawdź czy jest custom SEO title
    if (is_singular()) {
        $custom_title = get_post_meta($post->ID, '_seo_title', true);
        if (!empty($custom_title)) {
            // Jeśli jest custom title, użyj go zamiast domyślnego
            $title_parts['title'] = $custom_title;
            // Usuń site name żeby nie dublować
            unset($title_parts['site']);
            return $title_parts;
        }
    }
    
    // Dla strony głównej
    if (is_front_page() || is_home()) {
        $site_name = carni24_get_option('site_name', get_bloginfo('name'));
        $site_description = carni24_get_option('site_description', get_bloginfo('description'));
        
        if (!empty($site_name)) {
            $title_parts['title'] = $site_name;
        }
        
        if (!empty($site_description)) {
            $title_parts['tagline'] = $site_description;
        }
        
        return $title_parts;
    }
    
    // Dla kategorii
    if (is_category()) {
        $site_name = carni24_get_option('site_name', get_bloginfo('name'));
        if (!empty($site_name)) {
            $title_parts['site'] = $site_name;
        }
        return $title_parts;
    }
    
    // Dla tagów
    if (is_tag()) {
        $site_name = carni24_get_option('site_name', get_bloginfo('name'));
        if (!empty($site_name)) {
            $title_parts['site'] = $site_name;
        }
        return $title_parts;
    }
    
    // Dla wyszukiwania
    if (is_search()) {
        $site_name = carni24_get_option('site_name', get_bloginfo('name'));
        if (!empty($site_name)) {
            $title_parts['site'] = $site_name;
        }
        return $title_parts;
    }
    
    // Dla pojedynczych postów/stron (bez custom title)
    if (is_singular()) {
        $site_name = carni24_get_option('site_name', get_bloginfo('name'));
        if (!empty($site_name)) {
            $title_parts['site'] = $site_name;
        }
        return $title_parts;
    }
    
    return $title_parts;
}

// Alternatywne podejście - bezpośrednia kontrola nad całym tytułem
add_filter('pre_get_document_title', 'carni24_custom_document_title');

function carni24_custom_document_title($title) {
    global $post;
    
    // Tylko jeśli WordPress nie ma jeszcze tytułu
    if (empty($title)) {
        
        // Dla pojedynczych postów/stron
        if (is_singular()) {
            $custom_title = get_post_meta($post->ID, '_seo_title', true);
            if (!empty($custom_title)) {
                return $custom_title;
            }
            
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            return get_the_title() . ' - ' . $site_name;
        }
        
        // Dla strony głównej
        if (is_front_page() || is_home()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            $site_description = carni24_get_option('site_description', get_bloginfo('description'));
            
            if (!empty($site_name) && !empty($site_description)) {
                return $site_name . ' - ' . $site_description;
            } elseif (!empty($site_name)) {
                return $site_name;
            }
        }
        
        // Dla kategorii
        if (is_category()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            return single_cat_title('', false) . ' - ' . $site_name;
        }
        
        // Dla tagów
        if (is_tag()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            return single_tag_title('', false) . ' - ' . $site_name;
        }
        
        // Dla wyszukiwania
        if (is_search()) {
            $site_name = carni24_get_option('site_name', get_bloginfo('name'));
            return 'Wyniki wyszukiwania: ' . get_search_query() . ' - ' . $site_name;
        }
    }
    
    return $title;
}

// Upewnij się, że theme obsługuje title-tag
function carni24_setup_theme_support() {
    // Dodaj obsługę title-tag
    add_theme_support('title-tag');
    
    // Dodaj obsługę innych funkcji
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'carni24_setup_theme_support');

// Usuń stary separator jeśli istnieje i dodaj nowy
remove_filter('document_title_separator', 'theme_document_title_separator');

add_filter('document_title_separator', 'carni24_document_title_separator');
function carni24_document_title_separator($sep) {
    return '|';
}

// Debug funkcja do sprawdzania title
function carni24_debug_title() {
    if (current_user_can('manage_options') && isset($_GET['title_debug'])) {
        echo "\n<!-- TITLE DEBUG -->\n";
        echo "<!-- wp_get_document_title(): " . wp_get_document_title() . " -->\n";
        echo "<!-- is_front_page(): " . (is_front_page() ? 'true' : 'false') . " -->\n";
        echo "<!-- is_home(): " . (is_home() ? 'true' : 'false') . " -->\n";
        echo "<!-- site_name option: " . carni24_get_option('site_name', 'BRAK') . " -->\n";
        echo "<!-- site_description option: " . carni24_get_option('site_description', 'BRAK') . " -->\n";
        
        if (is_singular()) {
            global $post;
            echo "<!-- custom seo_title: " . get_post_meta($post->ID, '_seo_title', true) . " -->\n";
        }
        
        echo "<!-- /TITLE DEBUG -->\n";
    }
}
add_action('wp_head', 'carni24_debug_title', 1);

// Sprawdź czy w header.php nie ma hardcoded <title>
function carni24_check_hardcoded_title() {
    if (current_user_can('manage_options') && isset($_GET['check_title'])) {
        $header_content = file_get_contents(get_template_directory() . '/header.php');
        
        if (strpos($header_content, '<title>') !== false) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p><strong>Carni24:</strong> Znaleziono hardcoded tag &lt;title&gt; w header.php. Usuń go aby WordPress mógł kontrolować tytuł.</p></div>';
            });
        }
    }
}
add_action('init', 'carni24_check_hardcoded_title');