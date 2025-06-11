/**
 * Search Overlay Component
 * Plik: assets/js/components/search-overlay.js
 * Autor: Carni24 Team
 */

(function() {
    'use strict';
    
    let searchOverlay = null;
    let searchInput = null;
    let searchResults = null;
    let currentResults = [];
    let selectedIndex = -1;
    let searchTimeout = null;
    let isSearching = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        initSearchOverlay();
        createSearchOverlay();
        bindEvents();
    });
    
    // ===== INICJALIZACJA ===== //
    function initSearchOverlay() {
        searchOverlay = document.querySelector('.search-overlay');
        if (searchOverlay) {
            searchInput = searchOverlay.querySelector('.search-input');
            searchResults = searchOverlay.querySelector('.search-results');
        }
    }
    
    function createSearchOverlay() {
        if (searchOverlay) return; // Already exists
        
        const overlayHTML = `
            <div class="search-overlay" id="searchOverlay">
                <div class="search-overlay-content">
                    <button class="search-close" aria-label="Zamknij wyszukiwarkę">
                        <i class="bi bi-x"></i>
                    </button>
                    
                    <div class="search-header">
                        <h2>Przeszukaj naszą stronę</h2>
                        <p>Znajdź interesujące Cię treści</p>
                    </div>
                    
                    <form class="search-form" role="search">
                        <div class="search-input-wrapper">
                            <input type="search" 
                                   class="search-input" 
                                   placeholder="Wpisz szukane słowa..."
                                   autocomplete="off"
                                   aria-label="Pole wyszukiwania">
                            <button type="submit" class="search-submit" aria-label="Szukaj">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="search-results" role="region" aria-label="Wyniki wyszukiwania" aria-live="polite">
                        <div class="search-suggestions">
                            <h3>Popularne wyszukiwania:</h3>
                            <div class="suggestion-tags">
                                <button class="suggestion-tag" data-query="rośliny mięsożerne">rośliny mięsożerne</button>
                                <button class="suggestion-tag" data-query="muchołówka">muchołówka</button>
                                <button class="suggestion-tag" data-query="dzbanecznik">dzbanecznik</button>
                                <button class="suggestion-tag" data-query="pielęgnacja">pielęgnacja</button>
                                <button class="suggestion-tag" data-query="podlewanie">podlewanie</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="search-footer">
                        <div class="search-tips">
                            <h4>Wskazówki wyszukiwania:</h4>
                            <ul>
                                <li>Użyj konkretnych słów kluczowych</li>
                                <li>Spróbuj synonimy jeśli nie znajdziesz wyników</li>
                                <li>Użyj cudzysłowów dla dokładnej frazy</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', overlayHTML);
        initSearchOverlay();
    }
    
    // ===== EVENT BINDING ===== //
    function bindEvents() {
        if (!searchOverlay) return;
        
        const searchTriggers = document.querySelectorAll('.search-trigger-btn, [data-search-trigger]');
        const searchClose = searchOverlay.querySelector('.search-close');
        const searchForm = searchOverlay.querySelector('.search-form');
        const suggestionTags = searchOverlay.querySelectorAll('.suggestion-tag');
        
        // Open search overlay
        searchTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                openSearchOverlay();
            });
        });
        
        // Close search overlay
        if (searchClose) {
            searchClose.addEventListener('click', closeSearchOverlay);
        }
        
        // Close on overlay background click
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                closeSearchOverlay();
            }
        });
        
        // Search form submission
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
            });
        }
        
        // Search input events
        if (searchInput) {
            searchInput.addEventListener('input', handleSearchInput);
            searchInput.addEventListener('keydown', handleSearchKeydown);
            searchInput.addEventListener('focus', showSuggestions);
        }
        
        // Suggestion tags
        suggestionTags.forEach(tag => {
            tag.addEventListener('click', function() {
                const query = this.dataset.query;
                searchInput.value = query;
                performSearch();
            });
        });
        
        // Global keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K to open search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                openSearchOverlay();
            }
            
            // Escape to close search
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearchOverlay();
            }
        });
    }
    
    // ===== SEARCH OVERLAY CONTROL ===== //
    function openSearchOverlay() {
        if (!searchOverlay) return;
        
        searchOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Focus search input with delay for animation
        setTimeout(() => {
            if (searchInput) {
                searchInput.focus();
            }
        }, 300);
        
        // Reset search state
        selectedIndex = -1;
        showSuggestions();
        
        // Track analytics if available
        if (typeof gtag !== 'undefined') {
            gtag('event', 'search_overlay_open', {
                event_category: 'search',
                event_label: 'overlay_opened'
            });
        }
    }
    
    function closeSearchOverlay() {
        if (!searchOverlay) return;
        
        searchOverlay.classList.remove('active');
        document.body.style.overflow = '';
        
        // Clear search if no results
        if (currentResults.length === 0 && searchInput) {
            searchInput.value = '';
        }
        
        // Reset selection
        selectedIndex = -1;
        clearSearchTimeout();
    }
    
    // ===== SEARCH FUNCTIONALITY ===== //
    function handleSearchInput(e) {
        const query = e.target.value.trim();
        
        clearSearchTimeout();
        
        if (query.length === 0) {
            showSuggestions();
            return;
        }
        
        if (query.length < 2) {
            return;
        }
        
        // Debounce search
        searchTimeout = setTimeout(() => {
            performInstantSearch(query);
        }, 300);
    }
    
    function handleSearchKeydown(e) {
        const resultItems = searchResults.querySelectorAll('.search-result-item, .suggestion-tag');
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, resultItems.length - 1);
                updateSelection(resultItems);
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(resultItems);
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && resultItems[selectedIndex]) {
                    const selectedItem = resultItems[selectedIndex];
                    if (selectedItem.classList.contains('suggestion-tag')) {
                        selectedItem.click();
                    } else {
                        const link = selectedItem.querySelector('a');
                        if (link) {
                            window.location.href = link.href;
                        }
                    }
                } else {
                    performSearch();
                }
                break;
                
            case 'Escape':
                closeSearchOverlay();
                break;
        }
    }
    
    function performInstantSearch(query) {
        if (isSearching) return;
        
        isSearching = true;
        showLoadingState();
        
        // Use AJAX to search
        fetch(`${carni24_ajax.ajax_url}?action=carni24_search`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                search_term: query,
                nonce: carni24_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            isSearching = false;
            if (data.success) {
                currentResults = data.data;
                displaySearchResults(currentResults, query);
            } else {
                displayNoResults(query);
            }
        })
        .catch(error => {
            isSearching = false;
            console.error('Search error:', error);
            displayErrorState();
        });
    }
    
    function performSearch() {
        const query = searchInput.value.trim();
        if (query.length === 0) return;
        
        // Navigate to search results page
        const searchUrl = `${window.location.origin}/?s=${encodeURIComponent(query)}`;
        window.location.href = searchUrl;
    }
    
    // ===== DISPLAY FUNCTIONS ===== //
    function showSuggestions() {
        if (!searchResults) return;
        
        const suggestionsHtml = `
            <div class="search-suggestions">
                <h3>Popularne wyszukiwania:</h3>
                <div class="suggestion-tags">
                    <button class="suggestion-tag" data-query="rośliny mięsożerne">rośliny mięsożerne</button>
                    <button class="suggestion-tag" data-query="muchołówka">muchołówka</button>
                    <button class="suggestion-tag" data-query="dzbanecznik">dzbanecznik</button>
                    <button class="suggestion-tag" data-query="pielęgnacja">pielęgnacja</button>
                    <button class="suggestion-tag" data-query="podlewanie">podlewanie</button>
                    <button class="suggestion-tag" data-query="nasiona">nasiona</button>
                </div>
            </div>
            
            <div class="recent-searches" style="display: none;">
                <h3>Ostatnie wyszukiwania:</h3>
                <div class="recent-list"></div>
            </div>
        `;
        
        searchResults.innerHTML = suggestionsHtml;
        
        // Rebind suggestion events
        const suggestionTags = searchResults.querySelectorAll('.suggestion-tag');
        suggestionTags.forEach(tag => {
            tag.addEventListener('click', function() {
                const query = this.dataset.query;
                searchInput.value = query;
                performSearch();
            });
        });
        
        // Show recent searches if available
        showRecentSearches();
    }
    
    function displaySearchResults(results, query) {
        if (!searchResults) return;
        
        if (results.length === 0) {
            displayNoResults(query);
            return;
        }
        
        let resultsHtml = `
            <div class="search-results-header">
                <h3>Wyniki wyszukiwania dla: "<em>${escapeHtml(query)}</em>"</h3>
                <span class="results-count">${results.length} ${getPolishPlural(results.length, 'wynik', 'wyniki', 'wyników')}</span>
            </div>
            <div class="search-results-list">
        `;
        
        results.forEach(result => {
            resultsHtml += `
                <div class="search-result-item">
                    <div class="result-content">
                        <h4><a href="${result.url}">${highlightSearchTerms(result.title, query)}</a></h4>
                        <p>${highlightSearchTerms(result.excerpt, query)}</p>
                        <div class="result-meta">
                            <span class="result-url">${result.url}</span>
                            ${result.thumbnail ? `<img src="${result.thumbnail}" alt="${result.title}" class="result-thumbnail">` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        resultsHtml += `
            </div>
            <div class="search-results-footer">
                <button class="view-all-results btn btn-primary" onclick="window.location.href='/?s=${encodeURIComponent(query)}'">
                    Zobacz wszystkie wyniki
                </button>
            </div>
        `;
        
        searchResults.innerHTML = resultsHtml;
        selectedIndex = -1;
        
        // Save to recent searches
        saveRecentSearch(query);
    }
    
    function displayNoResults(query) {
        if (!searchResults) return;
        
        const noResultsHtml = `
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h3>Brak wyników dla: "${escapeHtml(query)}"</h3>
                <p>Nie znaleźliśmy niczego pasującego do Twojego wyszukiwania.</p>
                
                <div class="search-suggestions">
                    <h4>Spróbuj:</h4>
                    <ul>
                        <li>Sprawdź pisownię słów kluczowych</li>
                        <li>Użyj innych słów kluczowych</li>
                        <li>Użyj bardziej ogólnych terminów</li>
                    </ul>
                </div>
                
                <div class="popular-suggestions">
                    <h4>Popularne tematy:</h4>
                    <div class="suggestion-tags">
                        <button class="suggestion-tag" data-query="rośliny mięsożerne">rośliny mięsożerne</button>
                        <button class="suggestion-tag" data-query="pielęgnacja">pielęgnacja</button>
                        <button class="suggestion-tag" data-query="gatunki">gatunki</button>
                    </div>
                </div>
            </div>
        `;
        
        searchResults.innerHTML = noResultsHtml;
        
        // Rebind suggestion events
        const suggestionTags = searchResults.querySelectorAll('.suggestion-tag');
        suggestionTags.forEach(tag => {
            tag.addEventListener('click', function() {
                const query = this.dataset.query;
                searchInput.value = query;
                performInstantSearch(query);
            });
        });
    }
    
    function showLoadingState() {
        if (!searchResults) return;
        
        const loadingHtml = `
            <div class="search-loading">
                <div class="loading-spinner"></div>
                <p>Wyszukiwanie...</p>
            </div>
        `;
        
        searchResults.innerHTML = loadingHtml;
    }
    
    function displayErrorState() {
        if (!searchResults) return;
        
        const errorHtml = `
            <div class="search-error">
                <div class="error-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <h3>Wystąpił błąd</h3>
                <p>Nie udało się wykonać wyszukiwania. Spróbuj ponownie.</p>
                <button class="retry-search btn btn-primary" onclick="location.reload()">
                    Spróbuj ponownie
                </button>
            </div>
        `;
        
        searchResults.innerHTML = errorHtml;
    }
    
    // ===== UTILITY FUNCTIONS ===== //
    function updateSelection(items) {
        // Remove previous selection
        items.forEach(item => item.classList.remove('selected'));
        
        // Add selection to current item
        if (selectedIndex >= 0 && items[selectedIndex]) {
            items[selectedIndex].classList.add('selected');
            items[selectedIndex].scrollIntoView({ block: 'nearest' });
        }
    }
    
    function highlightSearchTerms(text, query) {
        if (!text || !query) return text;
        
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    function clearSearchTimeout() {
        if (searchTimeout) {
            clearTimeout(searchTimeout);
            searchTimeout = null;
        }
    }
    
    function getPolishPlural(count, singular, few, many) {
        if (count === 1) return singular;
        if (count >= 2 && count <= 4) return few;
        return many;
    }
    
    // ===== RECENT SEARCHES ===== //
    function saveRecentSearch(query) {
        if (!query || query.length < 2) return;
        
        let recentSearches = getRecentSearches();
        
        // Remove if already exists
        recentSearches = recentSearches.filter(search => search !== query);
        
        // Add to beginning
        recentSearches.unshift(query);
        
        // Keep only 5 recent searches
        recentSearches = recentSearches.slice(0, 5);
        
        // Save to localStorage
        try {
            localStorage.setItem('carni24_recent_searches', JSON.stringify(recentSearches));
        } catch (e) {
            console.warn('Could not save recent searches:', e);
        }
    }
    
    function getRecentSearches() {
        try {
            const stored = localStorage.getItem('carni24_recent_searches');
            return stored ? JSON.parse(stored) : [];
        } catch (e) {
            return [];
        }
    }
    
    function showRecentSearches() {
        const recentSearches = getRecentSearches();
        const recentContainer = searchResults.querySelector('.recent-searches');
        
        if (recentSearches.length === 0 || !recentContainer) return;
        
        const recentList = recentContainer.querySelector('.recent-list');
        let recentHtml = '';
        
        recentSearches.forEach(search => {
            recentHtml += `
                <button class="recent-search-item suggestion-tag" data-query="${escapeHtml(search)}">
                    <i class="bi bi-clock-history"></i> ${escapeHtml(search)}
                </button>
            `;
        });
        
        recentList.innerHTML = recentHtml;
        recentContainer.style.display = 'block';
        
        // Bind events
        recentList.querySelectorAll('.recent-search-item').forEach(item => {
            item.addEventListener('click', function() {
                const query = this.dataset.query;
                searchInput.value = query;
                performInstantSearch(query);
            });
        });
    }
    
    // ===== PUBLIC API ===== //
    window.SearchOverlay = {
        open: openSearchOverlay,
        close: closeSearchOverlay,
        search: function(query) {
            if (searchInput) {
                searchInput.value = query;
                openSearchOverlay();
                performInstantSearch(query);
            }
        }
    };
    
})();