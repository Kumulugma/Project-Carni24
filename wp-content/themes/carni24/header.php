<?php
/**
 * The header for our theme - zmodyfikowany header z konfigurowalnymi menu
 * 
 * @package carni24
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- HEADER - SUB-MENU z konfigurowalnymi menu -->
<section id="sub-menu" class="bg-dark d-none d-lg-flex justify-content-between align-items-center px-4 py-2">
    <!-- Lewa strona - logo i nawigacja -->
    <div class="d-flex align-items-center">
        <!-- Logo -->
        <a href="<?= home_url('/') ?>" class="navbar-brand d-flex align-items-center me-4">
            <strong class="text-white"><?= esc_html(get_option('carni24_site_logo_text', get_bloginfo('name'))) ?></strong>
        </a>
        
        <!-- Nawigacja -->
        <nav class="navbar navbar-expand-lg p-0">
            <div class="navbar-nav">
                <?php
                // Wyświetl menu WordPress jeśli istnieje
                if (has_nav_menu('main-menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'main-menu',
                        'container' => false,
                        'menu_class' => 'navbar-nav',
                        'fallback_cb' => false,
                        'items_wrap' => '%3$s',
                        'depth' => 1,
                        'walker' => new class extends Walker_Nav_Menu {
                            function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                                $classes = empty($item->classes) ? array() : (array) $item->classes;
                                $classes[] = 'nav-link';
                                $classes[] = 'text-white';
                                $classes[] = 'px-3';
                                $classes[] = 'py-2';
                                $classes[] = 'rounded';
                                
                                // Sprawdź czy aktywna strona
                                if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
                                    $classes[] = 'bg-success';
                                }
                                
                                $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                                $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                                
                                $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
                                $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
                                $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
                                $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
                                
                                $output .= '<a' . $attributes . $class_names . '>';
                                $output .= apply_filters('the_title', $item->title, $item->ID);
                                $output .= '</a>';
                            }
                            
                            function end_el(&$output, $item, $depth = 0, $args = null) {
                                // Nic nie rób - linki nie mają zamykających tagów
                            }
                        }
                    ));
                } else {
                    // Fallback menu jeśli nie ma zdefiniowanego menu
                ?>
                    <a href="<?= home_url('/') ?>" class="nav-link text-white px-3 py-2 rounded <?= is_front_page() ? 'bg-success' : '' ?>">
                        <i class="bi bi-house me-1"></i> Strona Główna
                    </a>
                    <a href="<?= home_url('/species/') ?>" class="nav-link text-white px-3 py-2 rounded <?= is_page('species') ? 'bg-success' : '' ?>">
                        <i class="bi bi-flower1 me-1"></i> Gatunki
                    </a>
                    <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="nav-link text-white px-3 py-2 rounded <?= is_home() || is_category() || is_tag() || is_author() || is_date() || is_search() ? 'bg-success' : '' ?>">
                        <i class="bi bi-journal-text me-1"></i> Artykuły
                    </a>
                <?php } ?>
            </div>
        </nav>
    </div>
    
    <!-- Prawa strona - wyszukiwanie -->
    <div class="sub-menu-search">
        <button class="btn btn-outline-light search-trigger-btn" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
            <i class="bi bi-search me-2"></i>
            <span class="d-none d-xl-inline">Szukaj</span>
        </button>
    </div>
</section>

<!-- Mobile header -->
<section id="sub-menu-mobile" class="bg-dark d-lg-none">
    <div class="container-fluid px-3 py-2">
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            <!-- Logo mobile -->
            <a href="<?= home_url('/') ?>" class="navbar-brand">
                <strong class="text-white"><?= esc_html(get_option('carni24_site_logo_text', get_bloginfo('name'))) ?></strong>
            </a>
            
            <div class="d-flex align-items-center">
                <!-- Search button mobile -->
                <button class="btn btn-outline-light search-trigger-btn me-2" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bi bi-search"></i>
                </button>
                
                <!-- Hamburger menu -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <div class="collapse navbar-collapse" id="mobileNavigation">
                <div class="navbar-nav w-100 mt-3">
                    <?php
                    if (has_nav_menu('main-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu',
                            'container' => false,
                            'menu_class' => 'navbar-nav w-100',
                            'fallback_cb' => false,
                            'items_wrap' => '%3$s',
                            'depth' => 1,
                            'walker' => new class extends Walker_Nav_Menu {
                                function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                                    $classes = empty($item->classes) ? array() : (array) $item->classes;
                                    $classes[] = 'nav-link';
                                    $classes[] = 'text-white';
                                    $classes[] = 'py-3';
                                    $classes[] = 'border-bottom';
                                    $classes[] = 'border-secondary';
                                    
                                    if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
                                        $classes[] = 'bg-success';
                                        $classes[] = 'rounded';
                                        $classes[] = 'mb-2';
                                    }
                                    
                                    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                                    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
                                    
                                    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
                                    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
                                    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
                                    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
                                    
                                    $output .= '<a' . $attributes . $class_names . '>';
                                    $output .= apply_filters('the_title', $item->title, $item->ID);
                                    $output .= '</a>';
                                }
                                
                                function end_el(&$output, $item, $depth = 0, $args = null) {
                                    // Nic nie rób
                                }
                            }
                        ));
                    } else {
                        // Fallback dla mobile
                    ?>
                        <a href="<?= home_url('/') ?>" class="nav-link text-white py-3 border-bottom border-secondary <?= is_front_page() ? 'bg-success rounded mb-2' : '' ?>">
                            <i class="bi bi-house me-2"></i> Strona Główna
                        </a>
                        <a href="<?= home_url('/species/') ?>" class="nav-link text-white py-3 border-bottom border-secondary <?= is_page('species') ? 'bg-success rounded mb-2' : '' ?>">
                            <i class="bi bi-flower1 me-2"></i> Gatunki
                        </a>
                        <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="nav-link text-white py-3 border-bottom border-secondary <?= is_home() || is_category() || is_tag() || is_author() || is_date() || is_search() ? 'bg-success rounded mb-2' : '' ?>">
                            <i class="bi bi-journal-text me-2"></i> Artykuły
                        </a>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </div>
</section>

<!-- Dołącz overlay wyszukiwania -->
<?php get_template_part('template-parts/search-overlay'); ?>