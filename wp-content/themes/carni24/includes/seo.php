<?php

// === SEO META TAGS OUTPUT ===

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
add_action('wp_head', 'carni24_seo_meta_tags', 1);

// Remove default WordPress canonical
remove_action('wp_head', 'rel_canonical');

// === TITLE TAG FUNCTIONS ===

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

// Title separator
add_filter('document_title_separator', 'carni24_document_title_separator');
function carni24_document_title_separator($sep) {
    return '|';
}

// === JSON-LD SCHEMA ===

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

// === SEO TESTING FUNCTIONS ===

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

// === DEBUG FUNCTIONS ===

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