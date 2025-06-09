<?php
// wp-content/themes/carni24/template-parts/homepage/ajax-search.php
// Elegancka sekcja wyszukiwania z AJAX
?>

<section id="ajax-search" class="search-section py-4">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="search-container">
                    <div class="search-header text-center mb-4">
                        <h2 class="search-title h3 mb-3">Czego szukasz?</h2>
                        <p class="search-subtitle text-muted">Przeszukaj nasze artykuły o roślinach mięsożernych</p>
                    </div>
                    
                    <form id="ajax-search-form" class="search-form" method="get" action="<?= esc_url(home_url('/')) ?>">
                        <div class="search-input-group">
                            <div class="search-input-wrapper">
                                <input type="search" 
                                       id="search-input" 
                                       name="s" 
                                       class="search-input form-control" 
                                       placeholder="Wpisz czego poszukujesz..." 
                                       value="<?= esc_attr(get_search_query()) ?>"
                                       autocomplete="off">
                                <div class="search-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                    </svg>
                                </div>
                                <button type="submit" class="search-submit btn btn-carni-green">
                                    Szukaj
                                </button>
                            </div>
                            
                            <!-- Loader -->
                            <div id="search-loader" class="search-loader" style="display: none;">
                                <div class="spinner"></div>
                            </div>
                        </div>
                        
                        <!-- Search filters -->
                        <div class="search-filters mt-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="filter-label">Szukaj w:</label>
                                    <div class="filter-options">
                                        <label class="filter-option">
                                            <input type="checkbox" name="search_types[]" value="post" checked>
                                            <span class="checkmark"></span>
                                            Artykuły
                                        </label>
                                        <label class="filter-option">
                                            <input type="checkbox" name="search_types[]" value="species">
                                            <span class="checkmark"></span>
                                            Gatunki
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select name="search_category" class="form-select search-category">
                                        <option value="">Wszystkie kategorie</option>
                                        <?php
                                        $categories = get_categories(array(
                                            'hide_empty' => true,
                                            'orderby' => 'name',
                                            'order' => 'ASC'
                                        ));
                                        foreach ($categories as $category) {
                                            echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . ' (' . $category->count . ')</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Search Results -->
                    <div id="search-results" class="search-results mt-4" style="display: none;">
                        <div class="results-header">
                            <h3 class="results-title">Wyniki wyszukiwania</h3>
                            <span class="results-count"></span>
                        </div>
                        <div class="results-content"></div>
                        <div class="results-pagination"></div>
                    </div>
                    
                    <!-- Popular searches suggestions -->
                    <div class="popular-searches mt-4">
                        <h4 class="popular-title">Popularne wyszukiwania:</h4>
                        <div class="popular-tags">
                            <?php
                            $popular_terms = array(
                                'pielęgnacja', 'uprawa', 'nawożenie', 'podlewanie', 
                                'dionaea', 'nepenthes', 'drosera', 'sarracenia'
                            );
                            
                            foreach ($popular_terms as $term) {
                                echo '<button type="button" class="popular-tag" data-search="' . esc_attr($term) . '">' . esc_html($term) . '</button>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Search Section Styles */
.search-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.search-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid #f0f0f0;
}

.search-title {
    color: var(--carni-green);
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.search-subtitle {
    font-size: 1rem;
    margin-bottom: 0;
}

/* Search Input Group */
.search-input-group {
    position: relative;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border-radius: 50px;
    padding: 4px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.search-input-wrapper:focus-within {
    border-color: var(--carni-green);
    box-shadow: 0 0 0 3px rgba(45, 80, 22, 0.1);
}

.search-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 12px 20px 12px 50px;
    font-size: 1.1rem;
    border-radius: 50px;
    outline: none;
    box-shadow: none;
}

.search-input::placeholder {
    color: #6c757d;
    font-style: italic;
}

.search-icon {
    position: absolute;
    left: 18px;
    color: #6c757d;
    pointer-events: none;
    z-index: 2;
}

.search-submit {
    border-radius: 50px;
    padding: 12px 24px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    transition: all 0.3s ease;
}

.search-submit:hover {
    transform: translateX(-2px);
    box-shadow: 0 5px 15px rgba(45, 80, 22, 0.3);
}

/* Search Loader */
.search-loader {
    position: absolute;
    top: 50%;
    right: 80px;
    transform: translateY(-50%);
    z-index: 10;
}

.spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid var(--carni-green);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Search Filters */
.search-filters {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1rem;
    border: 1px solid #e9ecef;
}

.filter-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.9rem;
}

.filter-options {
    display: flex;
    gap: 1rem;
}

.filter-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    color: #495057;
    margin: 0;
}

.filter-option input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    margin-right: 8px;
    position: relative;
    transition: all 0.3s ease;
}

.filter-option input[type="checkbox"]:checked + .checkmark {
    background-color: var(--carni-green);
    border-color: var(--carni-green);
}

.filter-option input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.search-category {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: border-color 0.3s ease;
}

.search-category:focus {
    border-color: var(--carni-green);
    box-shadow: 0 0 0 3px rgba(45, 80, 22, 0.1);
    outline: none;
}

/* Search Results */
.search-results {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    animation: fadeInUp 0.5s ease;
}

.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #dee2e6;
}

.results-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--carni-green);
    margin: 0;
}

.results-count {
    font-size: 0.9rem;
    color: #6c757d;
    background: white;
    padding: 4px 12px;
    border-radius: 15px;
    border: 1px solid #dee2e6;
}

.results-content {
    min-height: 100px;
}

.result-item {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.result-item:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.result-item:last-child {
    margin-bottom: 0;
}

.result-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.result-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.result-title a:hover {
    color: var(--carni-green);
}

.result-excerpt {
    color: #6c757d;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 0.5rem;
}

.result-meta {
    font-size: 0.85rem;
    color: #adb5bd;
}

.result-type {
    background: var(--carni-green);
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.75rem;
    margin-right: 0.5rem;
}

/* Popular Searches */
.popular-searches {
    text-align: center;
}

.popular-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
}

.popular-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
}

.popular-tag {
    background: white;
    border: 2px solid #e9ecef;
    color: #495057;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.popular-tag:hover {
    background: var(--carni-green);
    color: white;
    border-color: var(--carni-green);
    transform: translateY(-2px);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.no-results-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-results-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.no-results-text {
    font-size: 0.95rem;
}

/* Responsive */
@media (max-width: 768px) {
    .search-container {
        padding: 1.5rem;
        border-radius: 15px;
    }
    
    .search-input-wrapper {
        flex-direction: column;
        border-radius: 15px;
        padding: 8px;
    }
    
    .search-input {
        padding: 12px 20px;
        margin-bottom: 8px;
        border-radius: 15px;
        background: white;
        border: 1px solid #e9ecef;
    }
    
    .search-icon {
        position: absolute;
        top: 12px;
        left: 18px;
    }
    
    .search-submit {
        width: 100%;
        border-radius: 15px;
    }
    
    .search-loader {
        top: 20px;
        right: 20px;
    }
    
    .search-filters .row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .filter-options {
        justify-content: center;
    }
    
    .results-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .popular-tags {
        gap: 0.3rem;
    }
    
    .popular-tag {
        font-size: 0.8rem;
        padding: 4px 12px;
    }
}

@media (max-width: 576px) {
    .search-container {
        padding: 1rem;
        margin: 0 1rem;
    }
    
    .filter-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.result-item {
    animation: fadeInUp 0.5s ease;
}

.result-item:nth-child(2) { animation-delay: 0.1s; }
.result-item:nth-child(3) { animation-delay: 0.2s; }
.result-item:nth-child(4) { animation-delay: 0.3s; }
.result-item:nth-child(5) { animation-delay: 0.4s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('ajax-search-form');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const searchLoader = document.getElementById('search-loader');
    const resultsContent = document.querySelector('.results-content');
    const resultsCount = document.querySelector('.results-count');
    const popularTags = document.querySelectorAll('.popular-tag');
    
    let searchTimeout;
    let currentPage = 1;
    
    // Live search on input
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length >= 3) {
            searchTimeout = setTimeout(() => {
                performSearch(query, 1);
            }, 500);
        } else if (query.length === 0) {
            hideResults();
        }
    });
    
    // Form submit
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query.length >= 1) {
            performSearch(query, 1);
        }
    });
    
    // Popular tags click
    popularTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const searchTerm = this.getAttribute('data-search');
            searchInput.value = searchTerm;
            performSearch(searchTerm, 1);
        });
    });
    
    // Search type checkboxes change
    const searchTypeCheckboxes = document.querySelectorAll('input[name="search_types[]"]');
    searchTypeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const query = searchInput.value.trim();
            if (query.length >= 3) {
                performSearch(query, 1);
            }
        });
    });
    
    // Category select change
    const categorySelect = document.querySelector('.search-category');
    categorySelect.addEventListener('change', function() {
        const query = searchInput.value.trim();
        if (query.length >= 3) {
            performSearch(query, 1);
        }
    });
    
    function performSearch(query, page = 1) {
        showLoader();
        currentPage = page;
        
        const formData = new FormData();
        formData.append('action', 'carni24_ajax_search');
        formData.append('query', query);
        formData.append('page', page);
        formData.append('nonce', carni24Ajax.nonce);
        
        // Get selected search types
        const selectedTypes = [];
        searchTypeCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedTypes.push(checkbox.value);
            }
        });
        formData.append('search_types', selectedTypes.join(','));
        
        // Get selected category
        const selectedCategory = categorySelect.value;
        if (selectedCategory) {
            formData.append('category', selectedCategory);
        }
        
        fetch(carni24Ajax.ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            hideLoader();
            
            if (data.success) {
                displayResults(data.data, query);
            } else {
                displayError(data.data || 'Wystąpił błąd podczas wyszukiwania');
            }
        })
        .catch(error => {
            hideLoader();
            displayError('Wystąpił błąd połączenia');
            console.error('Search error:', error);
        });
    }
    
    function displayResults(data, query) {
        showResults();
        
        if (data.posts && data.posts.length > 0) {
            resultsCount.textContent = `Znaleziono ${data.total} wyników dla "${query}"`;
            
            let html = '';
            data.posts.forEach(post => {
                html += `
                    <div class="result-item">
                        <div class="result-title">
                            <a href="${post.permalink}">${post.title}</a>
                        </div>
                        <div class="result-excerpt">${post.excerpt}</div>
                        <div class="result-meta">
                            <span class="result-type">${post.type_label}</span>
                            ${post.date} | ${post.categories}
                        </div>
                    </div>
                `;
            });
            
            resultsContent.innerHTML = html;
            
            // Add pagination if needed
            if (data.total_pages > 1) {
                displayPagination(data.total_pages, query);
            }
            
        } else {
            displayNoResults(query);
        }
    }
    
    function displayNoResults(query) {
        resultsCount.textContent = `Brak wyników dla "${query}"`;
        resultsContent.innerHTML = `
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <div class="no-results-title">Nie znaleziono wyników</div>
                <div class="no-results-text">
                    Spróbuj użyć innych słów kluczowych lub sprawdź popularne wyszukiwania poniżej.
                </div>
            </div>
        `;
    }
    
    function displayError(message) {
        showResults();
        resultsCount.textContent = 'Błąd wyszukiwania';
        resultsContent.innerHTML = `
            <div class="no-results">
                <div class="no-results-icon">⚠️</div>
                <div class="no-results-title">Wystąpił błąd</div>
                <div class="no-results-text">${message}</div>
            </div>
        `;
    }
    
    function displayPagination(totalPages, query) {
        // Implementation for pagination if needed
        // This would add pagination controls to load more results
    }
    
    function showLoader() {
        searchLoader.style.display = 'block';
    }
    
    function hideLoader() {
        searchLoader.style.display = 'none';
    }
    
    function showResults() {
        searchResults.style.display = 'block';
    }
    
    function hideResults() {
        searchResults.style.display = 'none';
    }
    
    // Clear results when input is empty
    searchInput.addEventListener('keyup', function() {
        if (this.value.trim() === '') {
            hideResults();
        }
    });
});
</script>