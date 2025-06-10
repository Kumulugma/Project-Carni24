<?php
$search_placeholder = get_option('carni24_search_placeholder', 'Wpisz czego poszukujesz...');
?>

<!-- PRZYCISK WYSZUKIWANIA W SUB-MENU -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dodaj przycisk wyszukiwania do #sub-menu
    const subMenu = document.getElementById('sub-menu');
    if (subMenu) {
        const searchButton = document.createElement('button');
        searchButton.className = 'btn btn-light search-trigger-btn ms-2';
        searchButton.innerHTML = '<i class="bi bi-search"></i> Szukaj';
        searchButton.setAttribute('type', 'button');
        searchButton.setAttribute('data-bs-toggle', 'modal');
        searchButton.setAttribute('data-bs-target', '#searchModal');
        searchButton.setAttribute('aria-label', 'Otwórz wyszukiwarkę');
        
        // Dodaj przycisk na końcu sub-menu
        subMenu.appendChild(searchButton);
    }
});
</script>

<!-- OVERLAY/MODAL WYSZUKIWARKI -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content search-modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="searchModalLabel">
                    <i class="bi bi-search text-success me-2"></i>
                    Wyszukiwarka
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
            </div>
            
            <div class="modal-body pt-2">
                <!-- FORMULARZ WYSZUKIWANIA -->
                <form class="search-overlay-form" role="search" method="get" action="<?= esc_url(home_url('/')) ?>">
                    <div class="input-group input-group-lg mb-4">
                        <input type="search" 
                               name="s" 
                               class="form-control search-overlay-input" 
                               placeholder="<?= esc_attr($search_placeholder) ?>"
                               value="<?= get_search_query() ?>"
                               autocomplete="off"
                               id="searchOverlayInput">
                        <button class="btn btn-success search-overlay-submit" type="submit">
                            <i class="bi bi-search"></i>
                            <span class="d-none d-sm-inline ms-1">Szukaj</span>
                        </button>
                    </div>
                </form>
                
                <!-- PODPOWIEDZI I WYNIKI LIVE -->
                <div class="search-suggestions" id="searchSuggestions" style="display: none;">
                    <div class="search-suggestions-header">
                        <small class="text-muted">Podpowiedzi:</small>
                    </div>
                    <div class="search-suggestions-content" id="searchSuggestionsContent">
                        <!-- Dynamicznie ładowane przez JavaScript -->
                    </div>
                </div>
                
                <!-- POPULARNE WYSZUKIWANIA -->
                <div class="popular-searches">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-fire text-warning me-1"></i>
                        Popularne wyszukiwania:
                    </h6>
                    <div class="popular-searches-tags">
                        <?php
                        // Pobierz popularne tagi/kategorie
                        $popular_tags = get_tags(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 8,
                            'hide_empty' => true
                        ));
                        
                        if ($popular_tags) :
                            foreach ($popular_tags as $tag) :
                                $search_url = add_query_arg('s', $tag->name, home_url('/'));
                                ?>
                                <a href="<?= esc_url($search_url) ?>" 
                                   class="badge bg-light text-success me-2 mb-2 popular-search-tag"
                                   data-search-term="<?= esc_attr($tag->name) ?>">
                                    <?= esc_html($tag->name) ?>
                                    <small class="text-muted">(<?= $tag->count ?>)</small>
                                </a>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
                
                <!-- OSTATNIE WYSZUKIWANIA (localStorage) -->
                <div class="recent-searches" id="recentSearches" style="display: none;">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-clock-history text-info me-1"></i>
                        Ostatnie wyszukiwania:
                    </h6>
                    <div class="recent-searches-content" id="recentSearchesContent">
                        <!-- Dynamicznie ładowane przez JavaScript -->
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 pt-0">
                <div class="search-footer-info">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Naciśnij Enter aby wyszukać lub kliknij jedną z podpowiedzi
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- STYLE CSS -->
<style>
/* ===== MODAL WYSZUKIWARKI ===== */

.search-trigger-btn {
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 20px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.search-trigger-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.search-modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    backdrop-filter: blur(10px);
}

.search-overlay-input {
    border: 2px solid #e9ecef;
    border-radius: 25px 0 0 25px;
    padding: 15px 20px;
    font-size: 18px;
    transition: all 0.3s ease;
    background: #fff;
}

.search-overlay-input:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
    background: #fff;
}

.search-overlay-submit {
    border-radius: 0 25px 25px 0;
    padding: 15px 20px;
    font-size: 18px;
    border: 2px solid #28a745;
    background: #28a745;
    color: #ffffff !important;
    transition: all 0.3s ease;
}

.search-overlay-submit:hover {
    background: #218838;
    border-color: #1e7e34;
    color: #ffffff !important;
    transform: translateX(2px);
}

/* ===== PODPOWIEDZI ===== */

.search-suggestions {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

.search-suggestions-header {
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #dee2e6;
}

.search-suggestion-item {
    display: block;
    padding: 8px 12px;
    color: #495057;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
    margin-bottom: 5px;
}

.search-suggestion-item:hover {
    background: #28a745;
    color: #ffffff;
    text-decoration: none;
    transform: translateX(5px);
}

.search-suggestion-item:last-child {
    margin-bottom: 0;
}

/* ===== POPULARNE WYSZUKIWANIA ===== */

.popular-searches {
    margin-bottom: 20px;
}

.popular-search-tag {
    text-decoration: none;
    font-size: 13px;
    padding: 8px 12px;
    border-radius: 20px;
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
    display: inline-block;
}

.popular-search-tag:hover {
    background: #28a745 !important;
    color: #ffffff !important;
    border-color: #28a745;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
}

.popular-search-tag small {
    opacity: 0.7;
}

/* ===== OSTATNIE WYSZUKIWANIA ===== */

.recent-searches {
    margin-bottom: 15px;
}

.recent-search-item {
    display: inline-block;
    background: #e9ecef;
    color: #495057;
    padding: 6px 12px;
    border-radius: 15px;
    text-decoration: none;
    font-size: 13px;
    margin-right: 8px;
    margin-bottom: 8px;
    transition: all 0.2s ease;
    position: relative;
}

.recent-search-item:hover {
    background: #28a745;
    color: #ffffff;
    text-decoration: none;
    transform: translateY(-1px);
}

.recent-search-remove {
    background: none;
    border: none;
    color: #6c757d;
    font-size: 12px;
    margin-left: 5px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.recent-search-remove:hover {
    color: #dc3545;
}

/* ===== RESPONSYWNOŚĆ ===== */

@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .search-overlay-input {
        font-size: 16px; /* Zapobiega zoom na iOS */
        padding: 12px 15px;
    }
    
    .search-overlay-submit {
        font-size: 16px;
        padding: 12px 15px;
    }
    
    .popular-search-tag {
        font-size: 12px;
        padding: 6px 10px;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    
    .search-trigger-btn {
        font-size: 13px;
        padding: 6px 10px;
    }
    
    .search-trigger-btn .d-sm-none {
        display: none !important;
    }
}

@media (max-width: 576px) {
    .modal-header {
        padding: 15px;
    }
    
    .modal-body {
        padding: 0 15px 15px;
    }
    
    .modal-footer {
        padding: 0 15px 15px;
    }
    
    .input-group-lg .form-control {
        font-size: 16px;
    }
    
    .input-group-lg .btn {
        font-size: 16px;
    }
}

/* ===== ANIMACJE ===== */

.modal.fade .modal-dialog {
    transition: transform 0.4s ease, opacity 0.4s ease;
    transform: translate(0, -50px) scale(0.9);
}

.modal.show .modal-dialog {
    transform: translate(0, 0) scale(1);
}

.search-suggestions {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.popular-search-tag,
.recent-search-item {
    animation: fadeInUp 0.3s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== LOADING STATE ===== */

.search-loading {
    display: none;
    text-align: center;
    padding: 20px;
}

.search-loading.active {
    display: block;
}

.search-spinner {
    width: 2rem;
    height: 2rem;
    border: 3px solid #e9ecef;
    border-top: 3px solid #28a745;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ===== DARK MODE SUPPORT ===== */

@media (prefers-color-scheme: dark) {
    .search-modal-content {
        background: #2d3748;
        color: #e2e8f0;
    }
    
    .search-overlay-input {
        background: #4a5568;
        border-color: #718096;
        color: #e2e8f0;
    }
    
    .search-overlay-input:focus {
        background: #4a5568;
        border-color: #48bb78;
        color: #e2e8f0;
    }
    
    .search-suggestions {
        background: #4a5568;
        border-color: #718096;
    }
    
    .popular-search-tag {
        background: #4a5568;
        color: #e2e8f0;
        border-color: #718096;
    }
    
    .recent-search-item {
        background: #4a5568;
        color: #e2e8f0;
    }
}
</style>

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchModal = document.getElementById('searchModal');
    const searchInput = document.getElementById('searchOverlayInput');
    const suggestionsContainer = document.getElementById('searchSuggestions');
    const suggestionsContent = document.getElementById('searchSuggestionsContent');
    const recentSearches = document.getElementById('recentSearches');
    const recentSearchesContent = document.getElementById('recentSearchesContent');
    
    let searchTimeout;
    let recentSearchesData = [];
    
    // Załaduj ostatnie wyszukiwania z localStorage (uwaga: może nie działać w wszystkich środowiskach)
    try {
        const saved = localStorage.getItem('carni24_recent_searches');
        if (saved) {
            recentSearchesData = JSON.parse(saved);
            displayRecentSearches();
        }
    } catch (e) {
        console.log('localStorage not available');
    }
    
    // Focus na input po otwarciu modala
    searchModal.addEventListener('shown.bs.modal', function() {
        searchInput.focus();
    });
    
    // Wyczyść pole po zamknięciu modala
    searchModal.addEventListener('hidden.bs.modal', function() {
        searchInput.value = '';
        hideSuggestions();
    });
    
    // Live search z debounce
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length >= 2) {
            searchTimeout = setTimeout(() => {
                fetchSuggestions(query);
            }, 300);
        } else {
            hideSuggestions();
        }
    });
    
    // Submit formularza
    document.querySelector('.search-overlay-form').addEventListener('submit', function(e) {
        const query = searchInput.value.trim();
        if (query) {
            saveRecentSearch(query);
        }
    });
    
    // Kliknięcie w popularny tag
    document.querySelectorAll('.popular-search-tag').forEach(tag => {
        tag.addEventListener('click', function(e) {
            e.preventDefault();
            const term = this.getAttribute('data-search-term');
            searchInput.value = term;
            saveRecentSearch(term);
            this.closest('form').submit();
        });
    });
    
    // Funkcja pobierania podpowiedzi (może wymagać AJAX endpoint)
    function fetchSuggestions(query) {
        showLoading();
        
        // Symulacja AJAX (możesz zastąpić prawdziwym endpointem)
        setTimeout(() => {
            const suggestions = generateMockSuggestions(query);
            displaySuggestions(suggestions);
            hideLoading();
        }, 200);
    }
    
    // Generowanie mock podpowiedzi (zastąp prawdziwą funkcją)
    function generateMockSuggestions(query) {
        const mockSuggestions = [
            'rośliny mięsożerne',
            'muchołówka amerykańska',
            'rosiczka okrągłolistna',
            'dzbanecznik',
            'pinguicula',
            'sarracenia',
            'nepenthes',
            'drosera'
        ];
        
        return mockSuggestions
            .filter(item => item.toLowerCase().includes(query.toLowerCase()))
            .slice(0, 5);
    }
    
    // Wyświetlanie podpowiedzi
    function displaySuggestions(suggestions) {
        if (suggestions.length === 0) {
            hideSuggestions();
            return;
        }
        
        let html = '';
        suggestions.forEach(suggestion => {
            const searchUrl = `${window.location.origin}/?s=${encodeURIComponent(suggestion)}`;
            html += `
                <a href="${searchUrl}" class="search-suggestion-item" data-suggestion="${suggestion}">
                    <i class="bi bi-search me-2"></i>
                    ${suggestion}
                </a>
            `;
        });
        
        suggestionsContent.innerHTML = html;
        suggestionsContainer.style.display = 'block';
        
        // Dodaj event listenery do podpowiedzi
        suggestionsContent.querySelectorAll('.search-suggestion-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const suggestion = this.getAttribute('data-suggestion');
                searchInput.value = suggestion;
                saveRecentSearch(suggestion);
                window.location.href = this.href;
            });
        });
    }
    
    // Ukryj podpowiedzi
    function hideSuggestions() {
        suggestionsContainer.style.display = 'none';
    }
    
    // Loading state
    function showLoading() {
        suggestionsContent.innerHTML = `
            <div class="search-loading active">
                <div class="search-spinner"></div>
                <small class="text-muted">Wyszukiwanie...</small>
            </div>
        `;
        suggestionsContainer.style.display = 'block';
    }
    
    function hideLoading() {
        document.querySelector('.search-loading')?.classList.remove('active');
    }
    
    // Zapisz ostatnie wyszukiwanie
    function saveRecentSearch(query) {
        if (!query || query.length < 2) return;
        
        try {
            // Usuń jeśli już istnieje
            recentSearchesData = recentSearchesData.filter(item => item !== query);
            
            // Dodaj na początek
            recentSearchesData.unshift(query);
            
            // Ogranicz do 5 ostatnich
            recentSearchesData = recentSearchesData.slice(0, 5);
            
            // Zapisz do localStorage
            localStorage.setItem('carni24_recent_searches', JSON.stringify(recentSearchesData));
            
            displayRecentSearches();
        } catch (e) {
            console.log('Cannot save to localStorage');
        }
    }
    
    // Wyświetl ostatnie wyszukiwania
    function displayRecentSearches() {
        if (recentSearchesData.length === 0) {
            recentSearches.style.display = 'none';
            return;
        }
        
        let html = '';
        recentSearchesData.forEach((term, index) => {
            const searchUrl = `${window.location.origin}/?s=${encodeURIComponent(term)}`;
            html += `
                <a href="${searchUrl}" class="recent-search-item" data-term="${term}">
                    ${term}
                    <button type="button" class="recent-search-remove" data-index="${index}" title="Usuń">×</button>
                </a>
            `;
        });
        
        recentSearchesContent.innerHTML = html;
        recentSearches.style.display = 'block';
        
        // Event listenery
        recentSearchesContent.querySelectorAll('.recent-search-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.classList.contains('recent-search-remove')) {
                    e.preventDefault();
                    const index = parseInt(e.target.getAttribute('data-index'));
                    removeRecentSearch(index);
                } else {
                    e.preventDefault();
                    const term = this.getAttribute('data-term');
                    searchInput.value = term;
                    window.location.href = this.href;
                }
            });
        });
    }
    
    // Usuń ostatnie wyszukiwanie
    function removeRecentSearch(index) {
        try {
            recentSearchesData.splice(index, 1);
            localStorage.setItem('carni24_recent_searches', JSON.stringify(recentSearchesData));
            displayRecentSearches();
        } catch (e) {
            console.log('Cannot remove from localStorage');
        }
    }
    
    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const suggestions = suggestionsContent.querySelectorAll('.search-suggestion-item');
        
        if (e.key === 'ArrowDown' && suggestions.length > 0) {
            e.preventDefault();
            suggestions[0].focus();
        }
    });
    
    // ESC key closes modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchModal.classList.contains('show')) {
            bootstrap.Modal.getInstance(searchModal).hide();
        }
    });
});
</script>