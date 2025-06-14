<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_json_ld_schema() {
    if (is_admin()) return;
    
    $schema = array();
    
    if (is_front_page() || is_home()) {
        $schema = carni24_get_website_schema();
    } elseif (is_singular('post')) {
        $schema = carni24_get_article_schema();
    } elseif (is_singular('species')) {
        $schema = carni24_get_species_schema();
    } elseif (is_singular('guides')) {
        $schema = carni24_get_guide_schema();
    } elseif (is_page()) {
        $schema = carni24_get_webpage_schema();
    } elseif (is_author()) {
        $schema = carni24_get_person_schema();
    } elseif (is_archive()) {
        $schema = carni24_get_collection_schema();
    }
    
    if (empty($schema)) {
        return;
    }
    
    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
}
add_action('wp_head', 'carni24_json_ld_schema', 5);

function carni24_get_website_schema() {
    $site_name = carni24_get_option('site_name', get_bloginfo('name'));
    $site_description = carni24_get_option('site_description', get_bloginfo('description'));
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $site_name,
        'description' => $site_description,
        'url' => home_url(),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}')
            ),
            'query-input' => 'required name=search_term_string'
        ),
        'publisher' => carni24_get_organization_schema()
    );
    
    return $schema;
}

function carni24_get_organization_schema() {
    $site_name = carni24_get_option('site_name', get_bloginfo('name'));
    $logo_id = carni24_get_option('default_og_image', '');
    
    $organization = array(
        '@type' => 'Organization',
        'name' => $site_name,
        'url' => home_url(),
        'sameAs' => array()
    );
    
    if ($logo_id) {
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        if ($logo_url) {
            $organization['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $logo_url
            );
        }
    }
    
    $social_links = array();
    $facebook = carni24_get_option('facebook_url', '');
    $instagram = carni24_get_option('instagram_url', '');
    $youtube = carni24_get_option('youtube_url', '');
    
    if ($facebook) $social_links[] = $facebook;
    if ($instagram) $social_links[] = $instagram;
    if ($youtube) $social_links[] = $youtube;
    
    if (!empty($social_links)) {
        $organization['sameAs'] = $social_links;
    }
    
    return $organization;
}

function carni24_get_article_schema() {
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'description' => carni24_get_custom_excerpt($post->ID, 30),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name'),
            'url' => get_author_posts_url(get_the_author_meta('ID'))
        ),
        'publisher' => carni24_get_organization_schema(),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        )
    );
    
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        $image_meta = wp_get_attachment_metadata($image_id);
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => isset($image_meta['width']) ? $image_meta['width'] : 1200,
            'height' => isset($image_meta['height']) ? $image_meta['height'] : 630
        );
    }
    
    $categories = get_the_category();
    if (!empty($categories)) {
        $schema['articleSection'] = $categories[0]->name;
    }
    
    $tags = get_the_tags();
    if (!empty($tags)) {
        $keywords = array();
        foreach ($tags as $tag) {
            $keywords[] = $tag->name;
        }
        $schema['keywords'] = implode(', ', $keywords);
    }
    
    $word_count = str_word_count(strip_tags($post->post_content));
    if ($word_count > 0) {
        $schema['wordCount'] = $word_count;
    }
    
    return $schema;
}

function carni24_get_species_schema() {
    global $post;
    
    $scientific_name = get_post_meta($post->ID, '_species_scientific_name', true);
    $origin = get_post_meta($post->ID, '_species_origin', true);
    $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'description' => carni24_get_custom_excerpt($post->ID, 30),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name')
        ),
        'publisher' => carni24_get_organization_schema(),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        ),
        'about' => array(
            '@type' => 'Thing',
            'name' => get_the_title(),
            'description' => 'Roślina mięsożerna'
        )
    );
    
    if ($scientific_name) {
        $schema['about']['alternateName'] = $scientific_name;
    }
    
    if ($origin) {
        $schema['about']['spatialCoverage'] = $origin;
    }
    
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url
        );
        
        $schema['about']['image'] = $image_url;
    }
    
    $categories = get_the_terms($post->ID, 'species_category');
    if ($categories && !is_wp_error($categories)) {
        $schema['articleSection'] = $categories[0]->name;
    }
    
    return $schema;
}

function carni24_get_guide_schema() {
    global $post;
    
    $difficulty_level = get_post_meta($post->ID, '_guide_difficulty_level', true);
    $estimated_time = get_post_meta($post->ID, '_guide_estimated_time', true);
    $required_tools = get_post_meta($post->ID, '_guide_required_tools', true);
    $materials_needed = get_post_meta($post->ID, '_guide_materials_needed', true);
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'HowTo',
        'name' => get_the_title(),
        'description' => carni24_get_custom_excerpt($post->ID, 30),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name')
        ),
        'publisher' => carni24_get_organization_schema()
    );
    
    if ($estimated_time) {
        $schema['totalTime'] = 'PT' . strtoupper(str_replace(' ', '', $estimated_time));
    }
    
    if ($difficulty_level) {
        $difficulty_labels = array(
            'beginner' => 'Początkujący',
            'intermediate' => 'Średniozaawansowany', 
            'advanced' => 'Zaawansowany',
            'expert' => 'Ekspert'
        );
        
        if (isset($difficulty_labels[$difficulty_level])) {
            $schema['difficulty'] = $difficulty_labels[$difficulty_level];
        }
    }
    
    $tools = array();
    if ($required_tools) {
        $tool_lines = explode("\n", $required_tools);
        foreach ($tool_lines as $tool) {
            $tool = trim($tool);
            if (!empty($tool)) {
                $tools[] = array(
                    '@type' => 'HowToTool',
                    'name' => $tool
                );
            }
        }
    }
    
    if (!empty($tools)) {
        $schema['tool'] = $tools;
    }
    
    $supplies = array();
    if ($materials_needed) {
        $material_lines = explode("\n", $materials_needed);
        foreach ($material_lines as $material) {
            $material = trim($material);
            if (!empty($material)) {
                $supplies[] = array(
                    '@type' => 'HowToSupply',
                    'name' => $material
                );
            }
        }
    }
    
    if (!empty($supplies)) {
        $schema['supply'] = $supplies;
    }
    
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url
        );
    }
    
    return $schema;
}

function carni24_get_webpage_schema() {
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => get_the_title(),
        'description' => carni24_get_custom_excerpt($post->ID, 30),
        'url' => get_permalink(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'publisher' => carni24_get_organization_schema(),
        'mainEntity' => array(
            '@type' => 'WebPageElement',
            '@id' => get_permalink() . '#main'
        )
    );
    
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        
        $schema['primaryImageOfPage'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url
        );
    }
    
    return $schema;
}

function carni24_get_person_schema() {
    $author_id = get_queried_object_id();
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $author_name,
        'url' => $author_url,
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => $author_url
        )
    );
    
    if ($author_description) {
        $schema['description'] = $author_description;
    }
    
    $avatar_url = get_avatar_url($author_id, array('size' => 300));
    if ($avatar_url) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $avatar_url
        );
    }
    
    $author_website = get_the_author_meta('user_url', $author_id);
    if ($author_website) {
        $schema['sameAs'] = array($author_website);
    }
    
    return $schema;
}

function carni24_get_collection_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => get_the_archive_title(),
        'description' => get_the_archive_description(),
        'url' => get_pagenum_link(),
        'publisher' => carni24_get_organization_schema()
    );
    
    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        if ($term) {
            $schema['about'] = array(
                '@type' => 'Thing',
                'name' => $term->name,
                'description' => $term->description
            );
        }
    }
    
    return $schema;
}

function carni24_get_breadcrumb_schema() {
    if (is_front_page()) return array();
    
    $breadcrumbs = array();
    $position = 1;
    
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => 'Strona główna',
        'item' => home_url()
    );
    
    if (is_singular('species')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Gatunki',
            'item' => home_url('/gatunki/')
        );
        
        $categories = get_the_terms(get_the_ID(), 'species_category');
        if ($categories && !is_wp_error($categories)) {
            $category = reset($categories);
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $category->name,
                'item' => get_term_link($category)
            );
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    } elseif (is_singular('guides')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Poradniki',
            'item' => home_url('/poradniki/')
        );
        
        $categories = get_the_terms(get_the_ID(), 'guide_category');
        if ($categories && !is_wp_error($categories)) {
            $category = reset($categories);
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $category->name,
                'item' => get_term_link($category)
            );
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    } elseif (is_singular()) {
        $post_type = get_post_type();
        $post_type_object = get_post_type_object($post_type);
        
        if ($post_type_object && $post_type_object->has_archive) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $post_type_object->labels->name,
                'item' => get_post_type_archive_link($post_type)
            );
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    }
    
    if (empty($breadcrumbs)) {
        return array();
    }
    
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbs
    );
}

function carni24_output_breadcrumb_schema() {
    $schema = carni24_get_breadcrumb_schema();
    
    if (empty($schema)) {
        return;
    }
    
    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    echo "\n" . '</script>' . "\n";
}
add_action('wp_head', 'carni24_output_breadcrumb_schema', 6);