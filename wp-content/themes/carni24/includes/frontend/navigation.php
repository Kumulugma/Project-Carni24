<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_register_nav_menus() {
    register_nav_menus(array(
        'main-menu'   => __('Menu główne', 'carni24'),
        'footer-menu' => __('Menu stopki', 'carni24'),
        'mobile-menu' => __('Menu mobilne', 'carni24'),
    ));
}
add_action('init', 'carni24_register_nav_menus');

class Carni24_Bootstrap_Nav_Walker extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }
    
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'dropdown';
        }
        
        if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
            $classes[] = 'active';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="nav-item ' . esc_attr($class_names) . '"' : ' class="nav-item"';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $link_classes = 'nav-link';
        if (in_array('menu-item-has-children', $classes)) {
            $link_classes .= ' dropdown-toggle';
            $attributes .= ' data-bs-toggle="dropdown" aria-expanded="false"';
        }
        
        if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
            $link_classes .= ' active';
        }
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a class="' . $link_classes . '"' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

function carni24_main_navigation() {
    wp_nav_menu(array(
        'theme_location'  => 'main-menu',
        'menu_class'      => 'navbar-nav me-auto mb-2 mb-lg-0',
        'container'       => false,
        'fallback_cb'     => 'carni24_fallback_menu',
        'walker'          => new Carni24_Bootstrap_Nav_Walker(),
        'depth'           => 2,
    ));
}


function carni24_mobile_menu_toggle() {
    ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
            data-bs-target="#navbarNav" aria-controls="navbarNav" 
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <?php
}

function carni24_footer_navigation() {
    wp_nav_menu(array(
        'theme_location'  => 'footer-menu',
        'menu_class'      => 'footer-nav list-unstyled d-flex flex-wrap',
        'container'       => 'nav',
        'container_class' => 'footer-navigation',
        'fallback_cb'     => 'carni24_footer_fallback_menu',
        'depth'           => 1,
        'walker'          => new Carni24_Footer_Nav_Walker(),
    ));
}

class Carni24_Footer_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = '<li class="footer-nav-item me-3 mb-2">';
        $item_output .= '<a class="footer-nav-link text-muted"' . $attributes . '>';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= '</a>';
        $item_output .= '</li>';
        
        $output .= $item_output;
    }
}

function carni24_footer_fallback_menu() {
    echo '<nav class="footer-navigation">';
    echo '<ul class="footer-nav list-unstyled d-flex flex-wrap">';
    echo '<li class="footer-nav-item me-3 mb-2"><a href="' . home_url() . '" class="footer-nav-link text-muted">Strona główna</a></li>';
    echo '<li class="footer-nav-item me-3 mb-2"><a href="' . home_url('/gatunki/') . '" class="footer-nav-link text-muted">Gatunki</a></li>';
    echo '<li class="footer-nav-item me-3 mb-2"><a href="' . home_url('/poradniki/') . '" class="footer-nav-link text-muted">Poradniki</a></li>';
    echo '<li class="footer-nav-item me-3 mb-2"><a href="' . home_url('/polityka-prywatnosci/') . '" class="footer-nav-link text-muted">Polityka prywatności</a></li>';
    echo '<li class="footer-nav-item me-3 mb-2"><a href="' . home_url('/kontakt/') . '" class="footer-nav-link text-muted">Kontakt</a></li>';
    echo '</ul>';
    echo '</nav>';
}

function carni24_breadcrumbs_nav() {
    if (function_exists('carni24_breadcrumbs')) {
        carni24_breadcrumbs();
    }
}

function carni24_nav_menu_css_class($classes, $item, $args) {
    if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
        $classes[] = 'active';
    }
    
    if ($item->title === 'Gatunki') {
        $classes[] = 'menu-species';
    } elseif ($item->title === 'Poradniki') {
        $classes[] = 'menu-guides';
    } elseif ($item->title === 'Blog') {
        $classes[] = 'menu-blog';
    } elseif ($item->title === 'Kontakt') {
        $classes[] = 'menu-contact';
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'carni24_nav_menu_css_class', 10, 3);

function carni24_nav_menu_item_title($title, $item, $args, $depth) {
    if (isset($args->theme_location) && $args->theme_location === 'main-menu' && $depth === 0) {
        $icons = array(
            'Strona główna' => '<i class="bi bi-house-fill me-2"></i>',
            'Gatunki'       => '<i class="bi bi-flower1 me-2"></i>',
            'Poradniki'     => '<i class="bi bi-book me-2"></i>',
            'Blog'          => '<i class="bi bi-journal-text me-2"></i>',
            'Galeria'       => '<i class="bi bi-images me-2"></i>',
            'Kontakt'       => '<i class="bi bi-envelope me-2"></i>',
            'O nas'         => '<i class="bi bi-info-circle me-2"></i>',
        );
        
        if (isset($icons[$title])) {
            $title = $icons[$title] . $title;
        }
    }
    
    return $title;
}
add_filter('nav_menu_item_title', 'carni24_nav_menu_item_title', 10, 4);

function carni24_search_form() {
    $search_query = get_search_query();
    ?>
    <form class="search-form d-flex" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input class="form-control me-2" type="search" placeholder="Szukaj..." 
               aria-label="Szukaj" name="s" value="<?php echo esc_attr($search_query); ?>">
        <button class="btn btn-outline-success" type="submit">
            <i class="bi bi-search"></i>
        </button>
    </form>
    <?php
}

function carni24_mobile_search_toggle() {
    ?>
    <button class="btn btn-outline-success me-2" type="button" data-bs-toggle="collapse" 
            data-bs-target="#mobileSearch" aria-expanded="false" aria-controls="mobileSearch">
        <i class="bi bi-search"></i>
    </button>
    <?php
}

function carni24_archive_filters() {
    if (!is_post_type_archive('species') && !is_post_type_archive('guides')) {
        return;
    }
    
    $current_difficulty = isset($_GET['difficulty']) ? sanitize_text_field($_GET['difficulty']) : '';
    $current_origin = isset($_GET['origin']) ? sanitize_text_field($_GET['origin']) : '';
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
    
    ?>
    <div class="archive-filters mb-4">
        <form method="get" class="row g-3">
            <?php if (is_post_type_archive('species')): ?>
                <div class="col-md-3">
                    <select name="difficulty" class="form-select">
                        <option value="">Wszystkie trudności</option>
                        <option value="easy" <?php selected($current_difficulty, 'easy'); ?>>Łatwe</option>
                        <option value="medium" <?php selected($current_difficulty, 'medium'); ?>>Średnie</option>
                        <option value="hard" <?php selected($current_difficulty, 'hard'); ?>>Trudne</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="origin" class="form-control" 
                           placeholder="Pochodzenie" value="<?php echo esc_attr($current_origin); ?>">
                </div>
                <div class="col-md-3">
                    <select name="orderby" class="form-select">
                        <option value="">Sortuj według</option>
                        <option value="title" <?php selected($current_orderby, 'title'); ?>>Nazwa A-Z</option>
                        <option value="date" <?php selected($current_orderby, 'date'); ?>>Najnowsze</option>
                        <option value="popular" <?php selected($current_orderby, 'popular'); ?>>Popularne</option>
                        <option value="difficulty" <?php selected($current_orderby, 'difficulty'); ?>>Trudność</option>
                    </select>
                </div>
            <?php elseif (is_post_type_archive('guides')): ?>
                <div class="col-md-4">
                    <select name="difficulty" class="form-select">
                        <option value="">Wszystkie poziomy</option>
                        <option value="beginner" <?php selected($current_difficulty, 'beginner'); ?>>Początkujący</option>
                        <option value="intermediate" <?php selected($current_difficulty, 'intermediate'); ?>>Średniozaawansowany</option>
                        <option value="advanced" <?php selected($current_difficulty, 'advanced'); ?>>Zaawansowany</option>
                        <option value="expert" <?php selected($current_difficulty, 'expert'); ?>>Ekspert</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="orderby" class="form-select">
                        <option value="">Sortuj według</option>
                        <option value="date" <?php selected($current_orderby, 'date'); ?>>Najnowsze</option>
                        <option value="title" <?php selected($current_orderby, 'title'); ?>>Nazwa A-Z</option>
                        <option value="popular" <?php selected($current_orderby, 'popular'); ?>>Popularne</option>
                    </select>
                </div>
            <?php endif; ?>
            
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Filtruj
                </button>
                <?php if (!empty($current_difficulty) || !empty($current_origin) || !empty($current_orderby)): ?>
                    <a href="<?php echo get_post_type_archive_link(get_post_type()); ?>" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-x-circle me-2"></i>Wyczyść
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php
}

function carni24_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max = intval($wp_query->max_num_pages);
    
    echo '<nav aria-label="Nawigacja stron" class="pagination-nav mt-5">';
    echo '<ul class="pagination justify-content-center">';
    
    if ($paged > 1) {
        echo '<li class="page-item">';
        echo '<a class="page-link" href="' . get_pagenum_link($paged - 1) . '" aria-label="Poprzednia">';
        echo '<span aria-hidden="true">&laquo;</span>';
        echo '</a>';
        echo '</li>';
    }
    
    for ($i = 1; $i <= $max; $i++) {
        if ($i == $paged) {
            echo '<li class="page-item active" aria-current="page">';
            echo '<span class="page-link">' . $i . '</span>';
            echo '</li>';
        } else {
            echo '<li class="page-item">';
            echo '<a class="page-link" href="' . get_pagenum_link($i) . '">' . $i . '</a>';
            echo '</li>';
        }
    }
    
    if ($paged < $max) {
        echo '<li class="page-item">';
        echo '<a class="page-link" href="' . get_pagenum_link($paged + 1) . '" aria-label="Następna">';
        echo '<span aria-hidden="true">&raquo;</span>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</nav>';
}

function carni24_back_to_top_button() {
    ?>
    <button id="back-to-top" class="btn btn-primary position-fixed" style="bottom: 20px; right: 20px; z-index: 1050; display: none;">
        <i class="bi bi-arrow-up"></i>
    </button>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('back-to-top');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'carni24_back_to_top_button');