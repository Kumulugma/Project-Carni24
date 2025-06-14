<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_breadcrumbs() {
    if (is_front_page()) return;
    
    echo '<nav aria-label="breadcrumb" class="breadcrumbs-nav">';
    echo '<div class="container">';
    echo '<ol class="breadcrumb">';
    
    echo '<li class="breadcrumb-item">';
    echo '<a href="' . home_url() . '">';
    echo '<i class="bi bi-house-fill me-1"></i>';
    echo 'Strona główna';
    echo '</a>';
    echo '</li>';
    
    if (is_singular('species')) {
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . home_url('/gatunki/') . '">Gatunki</a>';
        echo '</li>';
        
        $categories = get_the_terms(get_the_ID(), 'species_category');
        if ($categories && !is_wp_error($categories)) {
            $category = reset($categories);
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo get_the_title();
        echo '</li>';
        
    } elseif (is_singular('guides')) {
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . home_url('/poradniki/') . '">Poradniki</a>';
        echo '</li>';
        
        $categories = get_the_terms(get_the_ID(), 'guide_category');
        if ($categories && !is_wp_error($categories)) {
            $category = reset($categories);
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_term_link($category) . '">' . esc_html($category->name) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo get_the_title();
        echo '</li>';
        
    } elseif (is_singular('post')) {
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . home_url('/blog/') . '">Blog</a>';
        echo '</li>';
        
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = reset($categories);
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_category_link($category->term_id) . '">' . esc_html($category->name) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo get_the_title();
        echo '</li>';
        
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor_id) {
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_permalink($ancestor_id) . '">' . get_the_title($ancestor_id) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo get_the_title();
        echo '</li>';
        
    } elseif (is_post_type_archive('species')) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo 'Gatunki';
        echo '</li>';
        
    } elseif (is_post_type_archive('guides')) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo 'Poradniki';
        echo '</li>';
        
    } elseif (is_category()) {
        $category = get_queried_object();
        
        if ($category->parent) {
            $parent_cats = get_category_parents($category->parent, true, '</li><li class="breadcrumb-item">', false);
            if ($parent_cats) {
                echo '<li class="breadcrumb-item">' . $parent_cats . '</li>';
            }
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo single_cat_title('', false);
        echo '</li>';
        
    } elseif (is_tag()) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo 'Tag: ' . single_tag_title('', false);
        echo '</li>';
        
    } elseif (is_tax('species_category')) {
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . home_url('/gatunki/') . '">Gatunki</a>';
        echo '</li>';
        
        $term = get_queried_object();
        if ($term->parent) {
            $parent_term = get_term($term->parent, 'species_category');
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_term_link($parent_term) . '">' . esc_html($parent_term->name) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo single_term_title('', false);
        echo '</li>';
        
    } elseif (is_tax('guide_category')) {
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . home_url('/poradniki/') . '">Poradniki</a>';
        echo '</li>';
        
        $term = get_queried_object();
        if ($term->parent) {
            $parent_term = get_term($term->parent, 'guide_category');
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_term_link($parent_term) . '">' . esc_html($parent_term->name) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo single_term_title('', false);
        echo '</li>';
        
    } elseif (is_author()) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo 'Autor: ' . get_the_author();
        echo '</li>';
        
    } elseif (is_date()) {
        if (is_year()) {
            echo '<li class="breadcrumb-item active" aria-current="page">';
            echo get_the_date('Y');
            echo '</li>';
        } elseif (is_month()) {
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_year_link(get_the_date('Y')) . '">' . get_the_date('Y') . '</a>';
            echo '</li>';
            echo '<li class="breadcrumb-item active" aria-current="page">';
            echo get_the_date('F');
            echo '</li>';
        } elseif (is_day()) {
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_year_link(get_the_date('Y')) . '">' . get_the_date('Y') . '</a>';
            echo '</li>';
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_month_link(get_the_date('Y'), get_the_date('m')) . '">' . get_the_date('F') . '</a>';
            echo '</li>';
            echo '<li class="breadcrumb-item active" aria-current="page">';
            echo get_the_date('j');
            echo '</li>';
        }
        
    } elseif (is_search()) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo 'Wyniki wyszukiwania: "' . get_search_query() . '"';
        echo '</li>';
        
    } elseif (is_404()) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo 'Strona nie została znaleziona';
        echo '</li>';
        
    } else {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo get_the_title();
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</div>';
    echo '</nav>';
}

function carni24_breadcrumb_json_ld() {
    if (is_front_page()) return;
    
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
        
    } elseif (is_singular('post')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Blog',
            'item' => home_url('/blog/')
        );
        
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = reset($categories);
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $category->name,
                'item' => get_category_link($category->term_id)
            );
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
        
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor_id) {
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title($ancestor_id),
                'item' => get_permalink($ancestor_id)
            );
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
        
    } elseif (is_category()) {
        $category = get_queried_object();
        
        if ($category->parent) {
            $parent_categories = get_category_parents($category->parent, false, '|||');
            $parent_cats = explode('|||', $parent_categories);
            
            foreach ($parent_cats as $parent_cat) {
                if (!empty($parent_cat)) {
                    $parent_cat_obj = get_category_by_slug(sanitize_title($parent_cat));
                    if ($parent_cat_obj) {
                        $breadcrumbs[] = array(
                            '@type' => 'ListItem',
                            'position' => $position++,
                            'name' => $parent_cat_obj->name,
                            'item' => get_category_link($parent_cat_obj->term_id)
                        );
                    }
                }
            }
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $category->name,
            'item' => get_category_link($category->term_id)
        );
        
    } elseif (is_tax()) {
        $term = get_queried_object();
        $taxonomy = get_taxonomy($term->taxonomy);
        
        if ($taxonomy) {
            if ($term->taxonomy === 'species_category') {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => 'Gatunki',
                    'item' => home_url('/gatunki/')
                );
            } elseif ($term->taxonomy === 'guide_category') {
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => 'Poradniki',
                    'item' => home_url('/poradniki/')
                );
            }
            
            if ($term->parent) {
                $parent_term = get_term($term->parent, $term->taxonomy);
                $breadcrumbs[] = array(
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $parent_term->name,
                    'item' => get_term_link($parent_term)
                );
            }
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $term->name,
            'item' => get_term_link($term)
        );
    }
    
    if (count($breadcrumbs) > 1) {
        $json_ld = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'carni24_breadcrumb_json_ld', 2);

function carni24_breadcrumbs_microdata() {
    if (is_front_page()) return;
    
    echo '<nav aria-label="breadcrumb" class="breadcrumbs-nav" itemscope itemtype="https://schema.org/BreadcrumbList">';
    echo '<div class="container">';
    echo '<ol class="breadcrumb">';
    
    $position = 1;
    
    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a href="' . home_url() . '" itemprop="item">';
    echo '<span itemprop="name">Strona główna</span>';
    echo '</a>';
    echo '<meta itemprop="position" content="' . $position++ . '" />';
    echo '</li>';
    
    if (is_singular()) {
        $post_type = get_post_type();
        
        if ($post_type === 'species') {
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a href="' . home_url('/gatunki/') . '" itemprop="item">';
            echo '<span itemprop="name">Gatunki</span>';
            echo '</a>';
            echo '<meta itemprop="position" content="' . $position++ . '" />';
            echo '</li>';
        } elseif ($post_type === 'guides') {
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a href="' . home_url('/poradniki/') . '" itemprop="item">';
            echo '<span itemprop="name">Poradniki</span>';
            echo '</a>';
            echo '<meta itemprop="position" content="' . $position++ . '" />';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</div>';
    echo '</nav>';
}