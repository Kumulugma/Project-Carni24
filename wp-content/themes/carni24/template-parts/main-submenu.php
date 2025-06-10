<?php
// wp-content/themes/carni24/template-parts/main-submenu.php
// Poprawione sub-menu z przyciskiem wyszukiwania w jednej linii

$navigation_heading = get_option('carni24_navigation_heading', 'Poznaj świat roślin mięsożernych');
$navigation_description = get_option('carni24_navigation_description', 'Odkryj fascynujące gatunki i sposoby ich pielęgnacji');
?>

<section id="sub-menu" class="bg-dark d-none d-lg-flex justify-content-between align-items-center px-4 py-2">
    <!-- Lewa strona - nawigacja -->
    <div class="sub-menu-navigation">
        <nav class="navbar navbar-expand-lg p-0">
            <div class="navbar-nav">
                <?php
                // Menu główne
                $menu_items = wp_get_nav_menu_items('main-menu'); // Zmień na nazwę swojego menu
                
                if ($menu_items) :
                    foreach ($menu_items as $item) :
                        $is_current = ($item->object_id == get_queried_object_id());
                        $item_classes = 'nav-link text-white px-3 py-2 rounded';
                        if ($is_current) {
                            $item_classes .= ' bg-success';
                        }
                ?>
                    <a href="<?= esc_url($item->url) ?>" 
                       class="<?= $item_classes ?>"
                       <?= $item->attr_title ? 'title="' . esc_attr($item->attr_title) . '"' : '' ?>>
                        <?= esc_html($item->title) ?>
                    </a>
                <?php
                    endforeach;
                else :
                    // Fallback menu jeśli brak zdefiniowanego menu
                ?>
                    <a href="<?= home_url('/') ?>" class="nav-link text-white px-3 py-2 rounded">
                        <i class="bi bi-house me-1"></i> Strona Główna
                    </a>
                    <a href="<?= home_url('/species/') ?>" class="nav-link text-white px-3 py-2 rounded">
                        <i class="bi bi-flower1 me-1"></i> Gatunki
                    </a>
                    <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="nav-link text-white px-3 py-2 rounded">
                        <i class="bi bi-journal-text me-1"></i> Artykuły
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
    
    <!-- Prawa strona - przycisk wyszukiwania -->
    <div class="sub-menu-search">
        <button type="button" 
                class="btn btn-light search-trigger-btn" 
                data-bs-toggle="modal" 
                data-bs-target="#searchModal"
                aria-label="Otwórz wyszukiwarkę">
            <i class="bi bi-search me-1"></i>
            <span class="d-none d-xl-inline">Szukaj</span>
        </button>
    </div>
</section>

<!-- Wersja mobilna sub-menu -->
<section id="sub-menu-mobile" class="bg-dark d-lg-none">
    <div class="container-fluid">
        <div class="row">
            <!-- Nawigacja mobilna -->
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-dark p-3">
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavigation">
                        <span class="navbar-toggler-icon"></span>
                        <span class="ms-2 text-white">Menu</span>
                    </button>
                    
                    <!-- Przycisk wyszukiwania dla mobile -->
                    <button type="button" 
                            class="btn btn-light btn-sm ms-auto search-trigger-btn" 
                            data-bs-toggle="modal" 
                            data-bs-target="#searchModal"
                            aria-label="Otwórz wyszukiwarkę">
                        <i class="bi bi-search"></i>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="mobileNavigation">
                        <div class="navbar-nav w-100 mt-3">
                            <?php
                            if ($menu_items) :
                                foreach ($menu_items as $item) :
                                    $is_current = ($item->object_id == get_queried_object_id());
                                    $item_classes = 'nav-link text-white py-3 border-bottom border-secondary';
                                    if ($is_current) {
                                        $item_classes .= ' bg-success rounded mb-2';
                                    }
                            ?>
                                <a href="<?= esc_url($item->url) ?>" 
                                   class="<?= $item_classes ?>"
                                   <?= $item->attr_title ? 'title="' . esc_attr($item->attr_title) . '"' : '' ?>>
                                    <?= esc_html($item->title) ?>
                                </a>
                            <?php
                                endforeach;
                            else :
                            ?>
                                <a href="<?= home_url('/') ?>" class="nav-link text-white py-3 border-bottom border-secondary">
                                    <i class="bi bi-house me-2"></i> Strona Główna
                                </a>
                                <a href="<?= home_url('/species/') ?>" class="nav-link text-white py-3 border-bottom border-secondary">
                                    <i class="bi bi-flower1 me-2"></i> Gatunki
                                </a>
                                <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="nav-link text-white py-3 border-bottom border-secondary">
                                    <i class="bi bi-journal-text me-2"></i> Artykuły
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>

<style>
/* ===== SUB-MENU STYLES ===== */

#sub-menu {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%) !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-top: 3px solid #28a745;
    min-height: 60px;
}

/* Nawigacja */
.sub-menu-navigation .navbar-nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sub-menu-navigation .nav-link {
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    border-radius: 20px !important;
    position: relative;
    overflow: hidden;
}

.sub-menu-navigation .nav-link:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.sub-menu-navigation .nav-link:hover:before {
    left: 100%;
}

.sub-menu-navigation .nav-link:hover {
    background-color: rgba(40, 167, 69, 0.8) !important;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.sub-menu-navigation .nav-link.bg-success {
    background-color: #28a745 !important;
    color: #ffffff !important;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.4);
}

/* Przycisk wyszukiwania */
.sub-menu-search .search-trigger-btn {
    font-size: 14px;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    color: #28a745 !important;
    background-color: #ffffff;
}

.sub-menu-search .search-trigger-btn:hover {
    color: #1e7e34 !important;
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: rgba(40, 167, 69, 0.2);
}

.sub-menu-search .search-trigger-btn:focus {
    color: #1e7e34 !important;
    background-color: #ffffff;
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
}

/* Mobile sub-menu */
#sub-menu-mobile {
    background: linear-gradient(135deg, #343a40 0%, #495057 100%) !important;
    border-top: 3px solid #28a745;
}

#sub-menu-mobile .navbar-toggler {
    padding: 8px 12px;
    font-size: 14px;
    border-radius: 20px;
    background: rgba(255,255,255,0.1);
}

#sub-menu-mobile .navbar-toggler:hover {
    background: rgba(255,255,255,0.2);
}

#sub-menu-mobile .navbar-toggler:focus {
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
}

#sub-menu-mobile .search-trigger-btn {
    padding: 6px 12px;
    border-radius: 15px;
    color: #28a745 !important;
}

#sub-menu-mobile .nav-link {
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

#sub-menu-mobile .nav-link:hover {
    background-color: rgba(40, 167, 69, 0.1) !important;
    color: #ffffff !important;
    padding-left: 2rem !important;
}

#sub-menu-mobile .nav-link.bg-success {
    background-color: #28a745 !important;
    border-radius: 10px !important;
    margin-bottom: 0.5rem;
    border: none !important;
}

/* Responsive adjustments */
@media (max-width: 1399px) {
    .sub-menu-navigation .nav-link {
        padding: 0.5rem 0.75rem !important;
        font-size: 13px;
    }
    
    .sub-menu-search .search-trigger-btn {
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .sub-menu-search .search-trigger-btn .d-none {
        display: none !important;
    }
}

@media (max-width: 1199px) {
    .sub-menu-navigation .nav-link {
        padding: 0.5rem !important;
        font-size: 12px;
    }
}

/* Accessibility improvements */
.sub-menu-navigation .nav-link:focus-visible,
.sub-menu-search .search-trigger-btn:focus-visible {
    outline: 2px solid #ffffff;
    outline-offset: 2px;
}

/* Loading states */
.sub-menu-navigation.loading .nav-link {
    opacity: 0.6;
    pointer-events: none;
}

.search-trigger-btn.loading {
    opacity: 0.6;
    pointer-events: none;
}

.search-trigger-btn.loading::after {
    content: '';
    width: 12px;
    height: 12px;
    margin-left: 8px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    display: inline-block;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Breadcrumb integration */
.breadcrumb-wrapper + #sub-menu {
    border-top: none;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    #sub-menu {
        background: linear-gradient(135deg, #1a1d23 0%, #2d3748 100%) !important;
        border-top-color: #48bb78;
    }
    
    .sub-menu-navigation .nav-link:hover {
        background-color: rgba(72, 187, 120, 0.8) !important;
    }
    
    .sub-menu-navigation .nav-link.bg-success {
        background-color: #48bb78 !important;
    }
    
    .sub-menu-search .search-trigger-btn {
        background-color: #2d3748;
        color: #48bb78 !important;
        border-color: #4a5568;
    }
    
    .sub-menu-search .search-trigger-btn:hover {
        background-color: #4a5568;
        color: #68d391 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Podświetl aktywny link na podstawie URL
    const currentUrl = window.location.pathname;
    const navLinks = document.querySelectorAll('.sub-menu-navigation .nav-link, #sub-menu-mobile .nav-link');
    
    navLinks.forEach(link => {
        const linkUrl = new URL(link.href).pathname;
        if (linkUrl === currentUrl || (currentUrl !== '/' && linkUrl.includes(currentUrl))) {
            link.classList.add('bg-success');
        }
    });
    
    // Animacja loading dla przycisku wyszukiwania
    const searchButtons = document.querySelectorAll('.search-trigger-btn');
    searchButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.classList.add('loading');
            
            // Usuń loading po otwarciu modala
            setTimeout(() => {
                this.classList.remove('loading');
            }, 500);
        });
    });
    
    // Collapse navbar on link click (mobile)
    const mobileNavLinks = document.querySelectorAll('#mobileNavigation .nav-link');
    const mobileNavCollapse = document.getElementById('mobileNavigation');
    
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (mobileNavCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(mobileNavCollapse);
                bsCollapse.hide();
            }
        });
    });
});
</script>