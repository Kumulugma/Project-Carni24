<?php
/**
 * Enhanced Breadcrumbs for Species and Guides
 * File: wp-content/themes/carni24/includes/breadcrumbs.php
 * Updated to support CPT species and guides
 */

// Enhanced breadcrumbs function
function get_breadcrumb() {
    echo '<div class="breadcrumbs-wrapper">';
    echo '<a href="' . home_url() . '" rel="nofollow" class="breadcrumb-home">
            <i class="bi bi-house-fill"></i> Strona główna
          </a>';
    
    // For regular categories and posts (not species)
    if ((is_category() || is_single()) && !is_singular('species') && !is_singular('guides')) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        the_category(' &bull; ');
        if (is_single()) {
            echo '<span class="breadcrumb-separator">&#187;</span>';
            echo '<span class="breadcrumb-current">' . get_the_title() . '</span>';
        }
    } 
    // For species CPT
    elseif (is_singular('species')) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<a href="' . home_url('/gatunki/') . '" class="breadcrumb-link">Gatunki</a>';
        
        global $post;
        $categories = get_the_category($post->ID);
        if (!empty($categories)) {
            $category = reset($categories);
            $name = $category->name;
            $slug = $category->slug;
            echo '<span class="breadcrumb-separator">&#187;</span>';
            echo '<a href="/kategoria-gatunku/?spec=' . esc_attr($slug) . '" class="breadcrumb-link">' . esc_html($name) . '</a>';
        }
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">' . get_the_title() . '</span>';
    }
    // For guides CPT
    elseif (is_singular('guides')) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<a href="' . home_url('/poradniki/') . '" class="breadcrumb-link">Poradniki</a>';
        
        // Get guide categories
        $guide_categories = get_the_terms(get_the_ID(), 'guide_category');
        if ($guide_categories && !is_wp_error($guide_categories)) {
            $category = reset($guide_categories);
            echo '<span class="breadcrumb-separator">&#187;</span>';
            echo '<a href="' . esc_url(get_term_link($category)) . '" class="breadcrumb-link">' . esc_html($category->name) . '</a>';
        }
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">' . get_the_title() . '</span>';
    }
    // For species archive
    elseif (is_post_type_archive('species')) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">Gatunki</span>';
    }
    // For guides archive
    elseif (is_post_type_archive('guides')) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">Poradniki</span>';
    }
    // For taxonomy archives - guide categories
    elseif (is_tax('guide_category')) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<a href="' . home_url('/poradniki/') . '" class="breadcrumb-link">Poradniki</a>';
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">' . single_term_title('', false) . '</span>';
    }
    // For taxonomy archives - guide tags
    elseif (is_tax('guide_tag')) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<a href="' . home_url('/poradniki/') . '" class="breadcrumb-link">Poradniki</a>';
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">Tag: ' . single_term_title('', false) . '</span>';
    }
    // For regular pages
    elseif (is_page()) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">' . get_the_title() . '</span>';
    } 
    // For search results
    elseif (is_search()) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">Wyniki wyszukiwania dla: "';
        echo '<em>' . get_search_query() . '</em>"</span>';
    }
    // For 404 pages
    elseif (is_404()) {
        echo '<span class="breadcrumb-separator">&#187;</span>';
        echo '<span class="breadcrumb-current">Strona nie została znaleziona</span>';
    }
    
    echo '</div>';
}

// JSON-LD structured data for breadcrumbs
function carni24_breadcrumb_json_ld() {
    if (is_front_page()) return;
    
    $breadcrumbs = array();
    $breadcrumbs[] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Strona główna',
        'item' => home_url()
    );
    
    $position = 2;
    
    if (is_singular('species')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Gatunki',
            'item' => home_url('/gatunki/')
        );
        
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = reset($categories);
            $breadcrumbs[] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $category->name,
                'item' => home_url('/kategoria-gatunku/?spec=' . $category->slug)
            );
        }
        
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    }
    elseif (is_singular('guides')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => $position++,
            'name' => 'Poradniki',
            'item' => home_url('/poradniki/')
        );
        
        $guide_categories = get_the_terms(get_the_ID(), 'guide_category');
        if ($guide_categories && !is_wp_error($guide_categories)) {
            $category = reset($guide_categories);
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
    }
    
    if (!empty($breadcrumbs)) {
        $json_ld = array(
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        );
        
        echo '<script type="application/ld+json">' . json_encode($json_ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
}

// Add structured data to head
add_action('wp_head', 'carni24_breadcrumb_json_ld');

// Alternative breadcrumb function with microdata
function carni24_breadcrumbs_microdata() {
    if (is_front_page()) return;
    
    echo '<nav aria-label="breadcrumb" class="breadcrumbs-nav">';
    echo '<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    // Home
    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a href="' . home_url() . '" itemprop="item"><span itemprop="name">Strona główna</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';
    
    $position = 2;
    
    if (is_singular('species')) {
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<a href="' . home_url('/gatunki/') . '" itemprop="item"><span itemprop="name">Gatunki</span></a>';
        echo '<meta itemprop="position" content="' . $position++ . '" />';
        echo '</li>';
        
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = reset($categories);
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a href="/kategoria-gatunku/?spec=' . esc_attr($category->slug) . '" itemprop="item">';
            echo '<span itemprop="name">' . esc_html($category->name) . '</span></a>';
            echo '<meta itemprop="position" content="' . $position++ . '" />';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    elseif (is_singular('guides')) {
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<a href="' . home_url('/poradniki/') . '" itemprop="item"><span itemprop="name">Poradniki</span></a>';
        echo '<meta itemprop="position" content="' . $position++ . '" />';
        echo '</li>';
        
        $guide_categories = get_the_terms(get_the_ID(), 'guide_category');
        if ($guide_categories && !is_wp_error($guide_categories)) {
            $category = reset($guide_categories);
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a href="' . esc_url(get_term_link($category)) . '" itemprop="item">';
            echo '<span itemprop="name">' . esc_html($category->name) . '</span></a>';
            echo '<meta itemprop="position" content="' . $position++ . '" />';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

// CSS for enhanced breadcrumbs
function carni24_breadcrumbs_styles() {
    ?>
    <style>
    .breadcrumbs-wrapper {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .breadcrumb-home,
    .breadcrumb-link {
        color: #268155;
        text-decoration: none;
        transition: color 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .breadcrumb-home:hover,
    .breadcrumb-link:hover {
        color: #1e6b47;
        text-decoration: underline;
    }
    
    .breadcrumb-separator {
        color: #6c757d;
        margin: 0 0.25rem;
        font-weight: normal;
    }
    
    .breadcrumb-current {
        color: #495057;
        font-weight: 500;
    }
    
    .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        padding: 0;
        margin: 0;
        list-style: none;
        background-color: transparent;
    }
    
    .breadcrumb-item {
        display: flex;
        align-items: center;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #6c757d;
        margin: 0 0.5rem;
        font-weight: normal;
    }
    
    .breadcrumb-item.active {
        color: #495057;
        font-weight: 500;
    }
    
    .breadcrumb-item a {
        color: #268155;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #1e6b47;
        text-decoration: underline;
    }
    
    @media (max-width: 767.98px) {
        .breadcrumbs-wrapper,
        .breadcrumb {
            font-size: 0.8125rem;
        }
        
        .breadcrumb-separator {
            margin: 0 0.125rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            margin: 0 0.25rem;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'carni24_breadcrumbs_styles');
?>