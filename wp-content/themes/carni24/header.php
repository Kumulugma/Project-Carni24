<?php
/**
 * The header for our theme - z pełną integracją menu WordPress
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

<!-- HEADER - SUB-MENU z pełną konfiguracją WordPress -->
<section id="sub-menu" class="bg-dark d-none d-lg-flex justify-content-between align-items-center px-4 py-2">
    <!-- Lewa strona - logo i nawigacja -->
    <div class="d-flex align-items-center">
        <!-- Logo -->
        <a href="<?= home_url('/') ?>" class="navbar-brand d-flex align-items-center me-4" 
           data-menu-item="logo">
            <strong class="text-white"><?= esc_html(get_theme_mod('carni24_site_logo_text', get_bloginfo('name'))) ?></strong>
        </a>
        
        <!-- Nawigacja główna -->
        <nav class="navbar navbar-expand-lg p-0" role="navigation" aria-label="<?php esc_attr_e('Nawigacja główna', 'carni24'); ?>">
            <div class="navbar-nav">
                <?php
                // Sprawdź czy istnieje zdefiniowane menu
                if (has_nav_menu('main-menu')) {
                    // Wyświetl menu WordPress z custom walkerem
                    wp_nav_menu(array(
                        'theme_location' => 'main-menu',
                        'container' => false,
                        'menu_class' => 'navbar-nav',
                        'fallback_cb' => 'carni24_fallback_menu',
                        'items_wrap' => '%3$s',
                        'depth' => 1,
                    ));
                } else {
                    // Fallback menu jeśli nie ma zdefiniowanego menu
                    carni24_fallback_menu();
                }
                ?>
            </div>
        </nav>
    </div>
    
    <!-- Prawa strona - wyszukiwanie -->
    <div class="sub-menu-search">
        <button class="btn btn-outline-light search-trigger-btn"
                style="background: #ffffd7;"
                type="button" 
                data-bs-toggle="modal" 
                data-bs-target="#searchModal"
                aria-label="<?php esc_attr_e('Otwórz wyszukiwarkę', 'carni24'); ?>">
            <i class="bi bi-search me-2" aria-hidden="true"></i>
            <span class="d-none d-xl-inline"><?php esc_html_e('Szukaj', 'carni24'); ?></span>
        </button>
    </div>
</section>

<!-- Mobile header -->
<section id="sub-menu-mobile" class="bg-dark d-lg-none">
    <div class="container-fluid px-3 py-2">
        <nav class="navbar navbar-expand-lg navbar-dark p-0" role="navigation" aria-label="<?php esc_attr_e('Nawigacja mobilna', 'carni24'); ?>">
            <!-- Logo mobile -->
            <a href="<?= home_url('/') ?>" class="navbar-brand" data-menu-item="logo-mobile">
                <strong class="text-white"><?= esc_html(get_theme_mod('carni24_site_logo_text', get_bloginfo('name'))) ?></strong>
            </a>
            
            <div class="d-flex align-items-center">
                <!-- Search button mobile -->
                <button class="btn btn-outline-light search-trigger-btn me-2" 
                        style="background: #ffffd7;"
                        type="button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#searchModal"
                        aria-label="<?php esc_attr_e('Otwórz wyszukiwarkę', 'carni24'); ?>">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </button>
                
                <!-- Hamburger menu -->
                <button class="navbar-toggler border-0" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#mobileNavigation"
                        aria-controls="mobileNavigation"
                        aria-expanded="false"
                        aria-label="<?php esc_attr_e('Przełącz nawigację', 'carni24'); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            
            <!-- Mobile navigation menu -->
            <div class="collapse navbar-collapse" id="mobileNavigation">
                <div class="navbar-nav w-100 mt-3">
                    <?php
                    if (has_nav_menu('mobile-menu')) {
                        // Użyj dedykowanego menu mobilnego jeśli istnieje
                        wp_nav_menu(array(
                            'theme_location' => 'mobile-menu',
                            'container' => false,
                            'menu_class' => 'navbar-nav w-100',
                            'fallback_cb' => 'carni24_mobile_fallback_menu',
                            'items_wrap' => '%3$s',
                            'depth' => 2,
                        ));
                    } elseif (has_nav_menu('main-menu')) {
                        // Użyj głównego menu jako fallback
                        wp_nav_menu(array(
                            'theme_location' => 'main-menu',
                            'container' => false,
                            'menu_class' => 'navbar-nav w-100',
                            'fallback_cb' => 'carni24_mobile_fallback_menu',
                            'items_wrap' => '%3$s',
                            'depth' => 1,
                        ));
                    } else {
                        // Fallback dla mobile
                        carni24_mobile_fallback_menu();
                    }
                    ?>
                </div>
            </div>
        </nav>
    </div>
</section>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content search-modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="searchModalLabel">
                    <i class="bi bi-search me-2"></i>
                    <?php esc_html_e('Wyszukiwarka', 'carni24'); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Zamknij', 'carni24'); ?>"></button>
            </div>
            <div class="modal-body pt-2">
                <form role="search" method="get" action="<?= esc_url(home_url('/')); ?>" class="search-form">
                    <div class="input-group">
                        <input type="search" 
                               class="form-control search-overlay-input" 
                               placeholder="<?= esc_attr(get_theme_mod('carni24_search_placeholder', 'Wpisz czego poszukujesz...')); ?>" 
                               value="<?= get_search_query(); ?>" 
                               name="s" 
                               aria-label="<?php esc_attr_e('Szukaj', 'carni24'); ?>">
                        <button class="btn btn-success search-overlay-submit" type="submit">
                            <i class="bi bi-search me-1"></i>
                            <?php esc_html_e('Szukaj', 'carni24'); ?>
                        </button>
                    </div>
                </form>
                
                <?php if (is_search() && have_posts()): ?>
                <div class="mt-3">
                    <small class="text-muted">
                        <?php
                        printf(
                            esc_html__('Znaleziono %d wyników dla: %s', 'carni24'),
                            $wp_query->found_posts,
                            '<strong>' . get_search_query() . '</strong>'
                        );
                        ?>
                    </small>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
// Funkcje fallback dla menu
function carni24_fallback_menu() {
    ?>
    <a href="<?= home_url('/') ?>" class="nav-link text-white px-3 py-2 rounded <?= is_front_page() ? 'bg-success active' : '' ?>" data-menu-item="home">
        <?php if (get_theme_mod('carni24_menu_icons', true)): ?>
            <i class="bi bi-house me-1" aria-hidden="true"></i>
        <?php endif; ?>
        <?php esc_html_e('Strona Główna', 'carni24'); ?>
    </a>
    
    <?php if (get_option('page_for_posts')): ?>
    <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="nav-link text-white px-3 py-2 rounded <?= is_home() || is_category() || is_tag() || is_author() || is_date() || is_search() ? 'bg-success active' : '' ?>" data-menu-item="blog">
        <?php if (get_theme_mod('carni24_menu_icons', true)): ?>
            <i class="bi bi-journal-text me-1" aria-hidden="true"></i>
        <?php endif; ?>
        <?php esc_html_e('Blog', 'carni24'); ?>
    </a>
    <?php endif; ?>
    
    <?php
    // Dodaj automatycznie strony główne
    $pages = get_pages(array(
        'sort_column' => 'menu_order',
        'parent' => 0,
        'number' => 5
    ));
    
    foreach ($pages as $page):
        if ($page->ID == get_option('page_for_posts') || $page->ID == get_option('page_on_front')) continue;
    ?>
    <a href="<?= get_permalink($page->ID) ?>" class="nav-link text-white px-3 py-2 rounded <?= is_page($page->ID) ? 'bg-success active' : '' ?>" data-menu-item="<?= esc_attr($page->post_name) ?>">
        <?php if (get_theme_mod('carni24_menu_icons', true)): ?>
            <i class="bi bi-circle me-1" aria-hidden="true"></i>
        <?php endif; ?>
        <?= esc_html($page->post_title) ?>
    </a>
    <?php endforeach; ?>
    <?php
}

function carni24_mobile_fallback_menu() {
    ?>
    <a href="<?= home_url('/') ?>" class="nav-link text-white py-2 <?= is_front_page() ? 'bg-success active' : '' ?>" data-menu-item="home-mobile">
        <?php if (get_theme_mod('carni24_menu_icons', true)): ?>
            <i class="bi bi-house me-2" aria-hidden="true"></i>
        <?php endif; ?>
        <?php esc_html_e('Strona Główna', 'carni24'); ?>
    </a>
    
    <?php if (get_option('page_for_posts')): ?>
    <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="nav-link text-white py-2 <?= is_home() || is_category() || is_tag() || is_author() || is_date() || is_search() ? 'bg-success active' : '' ?>" data-menu-item="blog-mobile">
        <?php if (get_theme_mod('carni24_menu_icons', true)): ?>
            <i class="bi bi-journal-text me-2" aria-hidden="true"></i>
        <?php endif; ?>
        <?php esc_html_e('Blog', 'carni24'); ?>
    </a>
    <?php endif; ?>
    
    <?php
    // Dodaj strony dla mobile
    $pages = get_pages(array(
        'sort_column' => 'menu_order',
        'parent' => 0,
        'number' => 5
    ));
    
    foreach ($pages as $page):
        if ($page->ID == get_option('page_for_posts') || $page->ID == get_option('page_on_front')) continue;
    ?>
    <a href="<?= get_permalink($page->ID) ?>" class="nav-link text-white py-2 <?= is_page($page->ID) ? 'bg-success active' : '' ?>" data-menu-item="<?= esc_attr($page->post_name) ?>-mobile">
        <?php if (get_theme_mod('carni24_menu_icons', true)): ?>
            <i class="bi bi-circle me-2" aria-hidden="true"></i>
        <?php endif; ?>
        <?= esc_html($page->post_title) ?>
    </a>
    <?php endforeach; ?>
    <?php
}