<?php
/**
 * The header for our theme - CZYSTY z jednym modalem search
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
                        'walker' => new Carni24_Main_Nav_Walker(), // DODANE!
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
                onclick="openSearchModal()"
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
                        onclick="openSearchModal()"
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

<!-- CUSTOM SEARCH MODAL (bez Bootstrap) -->
<div id="customSearchModal" class="custom-search-modal">
    <div class="custom-search-overlay" onclick="closeSearchModal()"></div>
    <div class="custom-search-container">
        <div class="custom-search-header">
            <h3><i class="bi bi-search me-2"></i>Wyszukiwarka</h3>
            <button class="custom-search-close" onclick="closeSearchModal()" aria-label="Zamknij">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div class="custom-search-body">
            <form class="custom-search-form" onsubmit="performSearch(event)">
                <div class="custom-search-input-group">
                    <input type="search" 
                           id="customSearchInput"
                           class="custom-search-input" 
                           placeholder="Wpisz szukane słowa..."
                           autocomplete="off">
                    <button type="submit" class="custom-search-submit">
                        <i class="bi bi-search"></i>
                        <span>Szukaj</span>
                    </button>
                </div>
            </form>
            
            <!-- Kontener na wyniki live search -->
            <div id="customSearchResults" class="custom-search-results"></div>
            
            <!-- Popularne wyszukiwania -->
            <div class="custom-search-suggestions">
                <h6><i class="bi bi-lightbulb me-1"></i>Popularne wyszukiwania:</h6>
                <div class="custom-search-tags">
                    <button onclick="searchFor('dionaea')" class="search-tag">Dionaea</button>
                    <button onclick="searchFor('nepenthes')" class="search-tag">Nepenthes</button>
                    <button onclick="searchFor('sarracenia')" class="search-tag">Sarracenia</button>
                    <button onclick="searchFor('pielęgnacja')" class="search-tag">Pielęgnacja</button>
                    <button onclick="searchFor('podlewanie')" class="search-tag">Podlewanie</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* CUSTOM SEARCH MODAL STYLES */
.custom-search-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.custom-search-modal.active {
    display: flex;
    opacity: 1;
    align-items: center;
    justify-content: center;
}

.custom-search-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.custom-search-container {
    position: relative;
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.custom-search-modal.active .custom-search-container {
    transform: scale(1);
}

.custom-search-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #e9ecef;
}

.custom-search-header h3 {
    margin: 0;
    color: #28a745;
    font-size: 1.25rem;
}

.custom-search-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #666;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.custom-search-close:hover {
    background: #f8f9fa;
    color: #000;
}

.custom-search-body {
    padding: 25px;
}

.custom-search-input-group {
    display: flex;
    margin-bottom: 20px;
}

.custom-search-input {
    flex: 1;
    padding: 15px 20px;
    border: 2px solid #e9ecef;
    border-radius: 25px 0 0 25px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s ease;
}

.custom-search-input:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
}

.custom-search-submit {
    padding: 15px 25px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 0 25px 25px 0;
    cursor: pointer;
    transition: background 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.custom-search-submit:hover {
    background: #218838;
}

.custom-search-results {
    margin-bottom: 20px;
    max-height: 300px;
    overflow-y: auto;
}

.custom-search-suggestions h6 {
    color: #666;
    margin-bottom: 15px;
    font-size: 14px;
}

.custom-search-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.search-tag {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    color: #495057;
    padding: 8px 15px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.2s ease;
}

.search-tag:hover {
    background: #28a745;
    color: white;
    border-color: #28a745;
    transform: translateY(-1px);
}

/* Loading spinner */
.search-loading {
    text-align: center;
    padding: 20px;
}

.search-spinner {
    width: 30px;
    height: 30px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #28a745;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Search results */
.search-result-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 8px;
    text-decoration: none;
    color: inherit;
    border: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.search-result-item:hover {
    background: #f8f9fa;
    transform: translateX(5px);
    text-decoration: none;
    color: inherit;
}

.search-result-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 15px;
}

.search-result-placeholder {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
}

.search-result-content {
    flex: 1;
}

.search-result-title {
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 14px;
}

.search-result-excerpt {
    font-size: 12px;
    color: #666;
    line-height: 1.4;
}

.search-result-badge {
    background: #e9ecef;
    color: #495057;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .custom-search-container {
        width: 95%;
        margin: 10px;
    }
    
    .custom-search-header,
    .custom-search-body {
        padding: 15px;
    }
    
    .custom-search-input {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .custom-search-submit span {
        display: none;
    }
}
</style>

<script>
// CUSTOM SEARCH MODAL JAVASCRIPT
let searchTimeout = null;
let isSearching = false;

function openSearchModal() {
    const modal = document.getElementById('customSearchModal');
    const input = document.getElementById('customSearchInput');
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Focus input after animation
    setTimeout(() => {
        input.focus();
    }, 300);
}

function closeSearchModal() {
    const modal = document.getElementById('customSearchModal');
    const input = document.getElementById('customSearchInput');
    const results = document.getElementById('customSearchResults');
    
    modal.classList.remove('active');
    document.body.style.overflow = '';
    
    // Clear input and results
    input.value = '';
    results.innerHTML = '';
}

function performSearch(event) {
    event.preventDefault();
    const input = document.getElementById('customSearchInput');
    const query = input.value.trim();
    
    if (query.length > 0) {
        closeSearchModal();
        window.location.href = `${window.location.origin}/?s=${encodeURIComponent(query)}`;
    }
}

function searchFor(term) {
    const input = document.getElementById('customSearchInput');
    input.value = term;
    performLiveSearch(term);
}

function performLiveSearch(query) {
    if (isSearching || query.length < 3) return;
    
    isSearching = true;
    const results = document.getElementById('customSearchResults');
    
    // Show loading
    results.innerHTML = `
        <div class="search-loading">
            <div class="search-spinner"></div>
            <p>Wyszukiwanie...</p>
        </div>
    `;
    
    // Check if AJAX is available
    if (typeof carni24_ajax === 'undefined') {
        results.innerHTML = `
            <div class="search-loading">
                <p>Naciśnij Enter aby wyszukać</p>
            </div>
        `;
        isSearching = false;
        return;
    }
    
    // AJAX request
    const formData = new FormData();
    formData.append('action', 'carni24_live_search');
    formData.append('query', query);
    formData.append('nonce', carni24_ajax.nonce);
    
    fetch(carni24_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        isSearching = false;
        
        if (data.success && data.data.length > 0) {
            displayResults(data.data, query);
        } else {
            showNoResults(query);
        }
    })
    .catch(error => {
        isSearching = false;
        console.error('Search error:', error);
        results.innerHTML = `
            <div class="search-loading">
                <p>Wystąpił błąd. Naciśnij Enter aby wyszukać.</p>
            </div>
        `;
    });
}

function displayResults(results, query) {
    const container = document.getElementById('customSearchResults');
    let html = `<div class="search-results-header mb-3"><h6>Podpowiedzi (${results.length}):</h6></div>`;
    
    results.forEach(result => {
        const thumb = result.thumbnail ? 
            `<img src="${result.thumbnail}" alt="${result.title}" class="search-result-thumb">` :
            `<div class="search-result-placeholder"><i class="bi bi-file-text"></i></div>`;
        
        const scientificName = result.scientific_name ? 
            `<br><small><em>${result.scientific_name}</em></small>` : '';
        
        html += `
            <a href="${result.url}" class="search-result-item" onclick="closeSearchModal()">
                ${thumb}
                <div class="search-result-content">
                    <div class="search-result-title">${highlightText(result.title, query)}</div>
                    <div class="search-result-excerpt">${highlightText(result.excerpt, query)}${scientificName}</div>
                </div>
                <div class="search-result-badge">${getTypeLabel(result.type)}</div>
            </a>
        `;
    });
    
    html += `
        <div style="text-align: center; margin-top: 15px;">
            <a href="/?s=${encodeURIComponent(query)}" onclick="closeSearchModal()" 
               style="color: #28a745; text-decoration: none; font-weight: 500;">
                <i class="bi bi-search me-1"></i>Zobacz wszystkie wyniki
            </a>
        </div>
    `;
    
    container.innerHTML = html;
}

function showNoResults(query) {
    const container = document.getElementById('customSearchResults');
    container.innerHTML = `
        <div style="text-align: center; padding: 20px; color: #666;">
            <i class="bi bi-search" style="font-size: 2rem; margin-bottom: 10px;"></i>
            <p>Brak podpowiedzi dla "${query}"</p>
            <p><small>Naciśnij Enter aby wyszukać</small></p>
        </div>
    `;
}

function highlightText(text, query) {
    if (!text || !query) return text;
    const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return text.replace(regex, '<mark style="background: #fff3cd;">$1</mark>');
}

function getTypeLabel(type) {
    const labels = {
        'post': 'Artykuł',
        'page': 'Strona', 
        'species': 'Gatunek',
        'guides': 'Poradnik'
    };
    return labels[type] || 'Treść';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('customSearchInput');
    
    if (input) {
        input.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            searchTimeout = setTimeout(() => {
                if (query.length >= 3) {
                    performLiveSearch(query);
                } else {
                    document.getElementById('customSearchResults').innerHTML = '';
                }
            }, 300);
        });
    }
    
    // ESC to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSearchModal();
        }
    });
    
    // Prevent modal close when clicking inside container
    const container = document.querySelector('.custom-search-container');
    if (container) {
        container.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
</script>
