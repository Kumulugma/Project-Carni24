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

function carni24_nav_menu_link_attributes($atts, $item, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'main-menu') {
        $atts['data-menu-item'] = sanitize_title($item->title);
        
        if (in_array('current-menu-item', $item->classes) || in_array('current-menu-ancestor', $item->classes)) {
            $atts['aria-current'] = 'page';
        }
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'carni24_nav_menu_link_attributes', 10, 3);

function carni24_get_menu_items_by_location($location, $args = array()) {
    $locations = get_nav_menu_locations();
    
    if (!isset($locations[$location])) {
        return array();
    }
    
    $menu = wp_get_nav_menu_object($locations[$location]);
    
    if (!$menu) {
        return array();
    }
    
    $menu_items = wp_get_nav_menu_items($menu->term_id, $args);
    
    return $menu_items ? $menu_items : array();
}

function carni24_has_custom_menu_walker() {
    return class_exists('Carni24_Bootstrap_Nav_Walker');
}

function carni24_mobile_menu_toggle_button($menu_id = 'navbarNavMobile') {
    return sprintf(
        '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#%s" aria-controls="%s" aria-expanded="false" aria-label="%s">
            <span class="navbar-toggler-icon"></span>
        </button>',
        esc_attr($menu_id),
        esc_attr($menu_id),
        esc_attr__('Przełącz nawigację', 'carni24')
    );
}

function carni24_search_button($mobile = false) {
    $classes = $mobile ? 'btn search-trigger-btn d-lg-none me-2' : 'btn search-trigger-btn d-flex align-items-center';
    $show_text = !$mobile;
    
    return sprintf(
        '<button class="%s" type="button" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="%s">
            <i class="bi bi-search%s"></i>
            %s
        </button>',
        esc_attr($classes),
        esc_attr__('Otwórz wyszukiwarkę', 'carni24'),
        $show_text ? ' me-2' : '',
        $show_text ? '<span class="d-none d-xl-inline">' . esc_html__('Szukaj', 'carni24') . '</span>' : ''
    );
}

function carni24_get_active_menu_item_class($item) {
    $classes = array();
    
    if (in_array('current-menu-item', $item->classes)) {
        $classes[] = 'active';
        $classes[] = 'current';
    }
    
    if (in_array('current-menu-ancestor', $item->classes)) {
        $classes[] = 'active';
        $classes[] = 'ancestor';
    }
    
    if (in_array('current-menu-parent', $item->classes)) {
        $classes[] = 'active';
        $classes[] = 'parent';
    }
    
    return implode(' ', $classes);
}

// Fallback menu functions
function carni24_fallback_menu() {
    echo '<a href="' . home_url() . '" class="nav-link">Strona główna</a>';
    echo '<a href="' . home_url('/gatunki/') . '" class="nav-link">Gatunki</a>';
    echo '<a href="' . home_url('/poradniki/') . '" class="nav-link">Poradniki</a>';
    echo '<a href="' . home_url('/blog/') . '" class="nav-link">Blog</a>';
    echo '<a href="' . home_url('/kontakt/') . '" class="nav-link">Kontakt</a>';
}

function carni24_mobile_fallback_menu() {
    echo '<a href="' . home_url() . '" class="nav-link">Strona główna</a>';
    echo '<a href="' . home_url('/gatunki/') . '" class="nav-link">Gatunki</a>';
    echo '<a href="' . home_url('/poradniki/') . '" class="nav-link">Poradniki</a>';
    echo '<a href="' . home_url('/blog/') . '" class="nav-link">Blog</a>';
    echo '<a href="' . home_url('/kontakt/') . '" class="nav-link">Kontakt</a>';
}

function carni24_navigation_accessibility_improvements() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Улучшение доступности для навигации
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(function(link) {
            link.addEventListener('focus', function() {
                this.style.outline = '2px solid #ffffff';
                this.style.outlineOffset = '2px';
            });
            
            link.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
            });
        });
        
        // Закрытие мобильного меню при клике на ссылку
        const mobileNavLinks = document.querySelectorAll('#navbarNavMobile .nav-link');
        const navbarCollapse = document.getElementById('navbarNavMobile');
        
        mobileNavLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                        toggle: false
                    });
                    bsCollapse.hide();
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'carni24_navigation_accessibility_improvements');

?>