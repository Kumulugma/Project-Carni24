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
                        'walker' => new Carni24_Bootstrap_Nav_Walker(),
                    ));
                } else {
                    // Fallback menu jeśli nie ma zdefiniowanego menu
                    carni24_fallback_menu();
                }
                ?>
            </div>
        </nav>
    </div>
    
    <!-- Prawa strona - przycisk wyszukiwania -->
    <div class="d-flex align-items-center">
        <button class="btn search-trigger-btn d-flex align-items-center" 
                type="button" 
                data-bs-toggle="modal" 
                data-bs-target="#searchModal"
                aria-label="<?php esc_attr_e('Otwórz wyszukiwarkę', 'carni24'); ?>">
            <i class="bi bi-search me-2"></i>
            <span class="d-none d-xl-inline"><?php esc_html_e('Szukaj', 'carni24'); ?></span>
        </button>
    </div>
</section>

<!-- MOBILE HEADER - SUB-MENU MOBILE -->
<section id="sub-menu-mobile" class="bg-dark d-lg-none">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-dark p-0" role="navigation" aria-label="<?php esc_attr_e('Nawigacja mobilna', 'carni24'); ?>">
            <!-- Logo -->
            <a href="<?= home_url('/') ?>" class="navbar-brand text-white">
                <strong><?= esc_html(get_theme_mod('carni24_site_logo_text', get_bloginfo('name'))) ?></strong>
            </a>
            
            <!-- Przycisk wyszukiwania mobile -->
            <button class="btn search-trigger-btn d-lg-none me-2" 
                    type="button" 
                    data-bs-toggle="modal" 
                    data-bs-target="#searchModal"
                    aria-label="<?php esc_attr_e('Otwórz wyszukiwarkę', 'carni24'); ?>">
                <i class="bi bi-search"></i>
            </button>
            
            <!-- Hamburger button -->
            <button class="navbar-toggler" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarNavMobile" 
                    aria-controls="navbarNavMobile" 
                    aria-expanded="false" 
                    aria-label="<?php esc_attr_e('Przełącz nawigację', 'carni24'); ?>">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Collapse menu -->
            <div class="collapse navbar-collapse" id="navbarNavMobile">
                <div class="navbar-nav w-100">
                    <?php
                    // Menu mobilne
                    if (has_nav_menu('mobile-menu')) {
                        wp_nav_menu(array(
                            'theme_location' => 'mobile-menu',
                            'container' => false,
                            'menu_class' => 'navbar-nav w-100',
                            'fallback_cb' => 'carni24_mobile_fallback_menu',
                            'items_wrap' => '%3$s',
                            'depth' => 1,
                            'walker' => new Carni24_Bootstrap_Nav_Walker(),
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
                            'walker' => new Carni24_Bootstrap_Nav_Walker(),
                        ));
                    } else {
                        // Fallback menu mobilne
                        carni24_mobile_fallback_menu();
                    }
                    ?>
                </div>
            </div>
        </nav>
    </div>
</section>

<!-- SEARCH MODAL -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content search-modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="searchModalLabel"><?php esc_html_e('Wyszukaj na stronie', 'carni24'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Zamknij', 'carni24'); ?>"></button>
            </div>
            <div class="modal-body pt-2">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="input-group">
                        <input type="search" 
                               class="form-control search-overlay-input" 
                               placeholder="<?php esc_attr_e('Wpisz szukane słowo...', 'carni24'); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s" 
                               autocomplete="off"
                               aria-label="<?php esc_attr_e('Wyszukaj', 'carni24'); ?>">
                        <button class="btn btn-success search-overlay-submit" type="submit">
                            <i class="bi bi-search me-2"></i>
                            <?php esc_html_e('Szukaj', 'carni24'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Funkcje pomocnicze dla menu
if (!function_exists('carni24_fallback_menu')) {
    function carni24_fallback_menu() {
        echo '<a href="' . home_url() . '" class="nav-link">Strona główna</a>';
        echo '<a href="' . home_url('/gatunki/') . '" class="nav-link">Gatunki</a>';
        echo '<a href="' . home_url('/poradniki/') . '" class="nav-link">Poradniki</a>';
        echo '<a href="' . home_url('/blog/') . '" class="nav-link">Blog</a>';
        echo '<a href="' . home_url('/kontakt/') . '" class="nav-link">Kontakt</a>';
    }
}

if (!function_exists('carni24_mobile_fallback_menu')) {
    function carni24_mobile_fallback_menu() {
        echo '<a href="' . home_url() . '" class="nav-link">Strona główna</a>';
        echo '<a href="' . home_url('/gatunki/') . '" class="nav-link">Gatunki</a>';
        echo '<a href="' . home_url('/poradniki/') . '" class="nav-link">Poradniki</a>';
        echo '<a href="' . home_url('/blog/') . '" class="nav-link">Blog</a>';
        echo '<a href="' . home_url('/kontakt/') . '" class="nav-link">Kontakt</a>';
    }
}
?>