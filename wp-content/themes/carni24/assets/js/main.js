/**
 * Główny plik JavaScript dla motywu Carni24
 * Plik: assets/js/main.js - NAPRAWIONY bez duplikatów
 */

(function() {
    'use strict';
    
    // Search variables
    let searchInput = null;
    let searchTimeout = null;
    let isSearching = false;
    
    // ===== INICJALIZACJA ===== //
    document.addEventListener('DOMContentLoaded', function() {
        initBootstrapModalSearch(); // Tylko Bootstrap modal
        initScrollAnimations();
        initSmoothScrolling();
        initAccessibility();
        console.log('Carni24 Theme loaded successfully');
    });
    
    // ===== BOOTSTRAP MODAL SEARCH ===== //
    function initBootstrapModalSearch() {
        // Znajdź input w Bootstrap modal
        searchInput = document.querySelector('#searchModal .search-overlay-input');
        
        if (!searchInput) {
            console.warn('Search input nie znaleziony w modal');
            return;
        }
        
        // Dodaj live search do istniejącego Bootstrap modal
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Wyczyść poprzedni timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            // Ustaw nowy timeout
            searchTimeout = setTimeout(() => {
                if (query.length >= 3) {
                    performLiveSearch(query);
                } else {
                    clearSearchResults();
                }
            }, 300);
        });
        
        // Enter = przejdź do wyników
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                if (query.length > 0) {
                    // Zamknij modal bezpiecznie
                    const searchModal = document.getElementById('searchModal');
                    if (searchModal) {
                        // Użyj jQuery Bootstrap jeśli dostępne
                        if (typeof $ !== 'undefined' && $.fn.modal) {
                            $(searchModal).modal('hide');
                        } else {
                            // Lub spróbuj natywny Bootstrap 5
                            try {
                                const modal = bootstrap.Modal.getInstance(searchModal) || new bootstrap.Modal(searchModal);
                                modal.hide();
                            } catch (error) {
                                console.log('Modal close error:', error);
                                // Fallback - po prostu ukryj
                                searchModal.style.display = 'none';
                                document.body.classList.remove('modal-open');
                                const backdrop = document.querySelector('.modal-backdrop');
                                if (backdrop) backdrop.remove();
                            }
                        }
                    }
                    
                    // Przejdź do strony wyników po krótkim delay
                    setTimeout(() => {
                        window.location.href = `${window.location.origin}/?s=${encodeURIComponent(query)}`;
                    }, 100);
                }
            }
        });
        
        // Focus po otwarciu modal
        const searchModal = document.getElementById('searchModal');
        if (searchModal) {
            // Bezpieczne event listenery dla Bootstrap modal
            searchModal.addEventListener('shown.bs.modal', function() {
                console.log('Modal opened');
                if (searchInput) {
                    // Focus z małym delay
                    setTimeout(() => {
                        searchInput.focus();
                    }, 100);
                }
            });
            
            // Wyczyść wyniki po zamknięciu
            searchModal.addEventListener('hidden.bs.modal', function() {
                console.log('Modal closed');
                clearSearchResults();
                if (searchInput) {
                    searchInput.value = '';
                }
            });
            
            // Dodatkowe sprawdzenie czy Bootstrap jest loaded
            if (typeof bootstrap === 'undefined') {
                console.warn('Bootstrap JavaScript nie jest załadowany');
            }
        }
    }
    
    function performLiveSearch(query) {
        if (isSearching) return;
        
        isSearching = true;
        showLoadingState();
        
        // Sprawdź czy carni24_ajax jest dostępne
        if (typeof carni24_ajax === 'undefined') {
            console.error('carni24_ajax not found');
            isSearching = false;
            showErrorState();
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
                displaySearchResults(data.data, query);
            } else {
                showNoResults(query);
            }
        })
        .catch(error => {
            isSearching = false;
            console.error('Search error:', error);
            showErrorState();
        });
    }
    
    function showLoadingState() {
        const resultsContainer = getSearchResultsContainer();
        if (!resultsContainer) return;
        
        resultsContainer.innerHTML = `
            <div class="search-loading text-center py-4">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Wyszukiwanie...</span>
                </div>
                <p class="mt-2 text-muted small">Wyszukiwanie...</p>
            </div>
        `;
    }
    
    function displaySearchResults(results, query) {
        const resultsContainer = getSearchResultsContainer();
        if (!resultsContainer) return;
        
        let html = `
            <div class="live-search-results">
                <div class="search-results-header mb-3 pb-2 border-bottom">
                    <h6 class="text-muted mb-0">Podpowiedzi (${results.length}):</h6>
                </div>
                <div class="search-results-list">
        `;
        
        results.forEach(result => {
            const thumbnail = result.thumbnail ? 
                `<img src="${result.thumbnail}" alt="${result.title}" class="search-result-thumb me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">` : 
                `<div class="search-result-thumb-placeholder me-2" style="width: 40px; height: 40px; background: #f8f9fa; border-radius: 5px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-file-text text-muted"></i></div>`;
            
            const scientificName = result.scientific_name ? 
                `<small class="text-muted d-block"><em>${result.scientific_name}</em></small>` : '';
            
            html += `
                <div class="search-result-item mb-2">
                    <a href="${result.url}" class="d-flex align-items-center text-decoration-none p-2 rounded hover-bg-light">
                        ${thumbnail}
                        <div class="flex-grow-1">
                            <div class="fw-medium text-dark">${highlightText(result.title, query)}</div>
                            ${scientificName}
                            <small class="text-muted">${highlightText(result.excerpt, query)}</small>
                        </div>
                        <small class="badge bg-light text-dark ms-2">${getPostTypeLabel(result.type)}</small>
                    </a>
                </div>
            `;
        });
        
        html += `
                </div>
                <div class="search-results-footer text-center mt-3 pt-2 border-top">
                    <a href="/?s=${encodeURIComponent(query)}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-search me-1"></i>
                        Zobacz wszystkie wyniki
                    </a>
                </div>
            </div>
        `;
        
        resultsContainer.innerHTML = html;
    }
    
    function showNoResults(query) {
        const resultsContainer = getSearchResultsContainer();
        if (!resultsContainer) return;
        
        resultsContainer.innerHTML = `
            <div class="search-no-results text-center py-3">
                <i class="bi bi-search text-muted mb-2" style="font-size: 2rem;"></i>
                <h6 class="text-muted">Brak podpowiedzi</h6>
                <p class="text-muted small mb-3">Naciśnij Enter aby wyszukać "${query}"</p>
                <a href="/?s=${encodeURIComponent(query)}" class="btn btn-success btn-sm">
                    <i class="bi bi-search me-1"></i>
                    Szukaj
                </a>
            </div>
        `;
    }
    
    function showErrorState() {
        const resultsContainer = getSearchResultsContainer();
        if (!resultsContainer) return;
        
        resultsContainer.innerHTML = `
            <div class="search-error text-center py-3">
                <i class="bi bi-exclamation-triangle text-warning mb-2" style="font-size: 2rem;"></i>
                <h6 class="text-muted">Wystąpił błąd</h6>
                <p class="text-muted small">Spróbuj ponownie</p>
            </div>
        `;
    }
    
    function clearSearchResults() {
        const resultsContainer = getSearchResultsContainer();
        if (resultsContainer) {
            resultsContainer.innerHTML = '';
        }
    }
    
    function getSearchResultsContainer() {
        // Spróbuj znaleźć kontener na wyniki w modal
        let container = document.querySelector('#searchModal .live-search-results-container');
        
        if (!container) {
            // Jeśli nie ma, utwórz go pod formularzem
            const modalBody = document.querySelector('#searchModal .modal-body');
            if (modalBody) {
                container = document.createElement('div');
                container.className = 'live-search-results-container mt-3';
                modalBody.appendChild(container);
            }
        }
        
        return container;
    }
    
    // Helper functions
    function highlightText(text, query) {
        if (!text || !query) return text;
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return text.replace(regex, '<mark class="bg-warning bg-opacity-25">$1</mark>');
    }
    
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    function getPostTypeLabel(type) {
        const labels = {
            'post': 'Artykuł',
            'page': 'Strona',
            'species': 'Gatunek',
            'guides': 'Poradnik'
        };
        return labels[type] || 'Treść';
    }
    
    // ===== SCROLL ANIMATIONS ===== //
    function initScrollAnimations() {
        // Twój istniejący kod scroll animations jeśli jest
    }
    
    // ===== SMOOTH SCROLLING ===== //
    function initSmoothScrolling() {
        // Twój istniejący kod smooth scrolling jeśli jest
    }
    
    // ===== ACCESSIBILITY ===== //
    function initAccessibility() {
        // Twój istniejący kod accessibility jeśli jest
    }
    
})();

// Dodaj CSS dla hover effect
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
        }
        .search-result-item a:hover {
            transform: translateX(2px);
            transition: transform 0.2s ease;
        }
    `;
    document.head.appendChild(style);
});