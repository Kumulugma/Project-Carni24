/**
 * Naprawiony JavaScript do sortowania
 * Plik: assets/js/sorting.js
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sorting JavaScript loaded');
    
    // Elementy
    const sortSelect = document.getElementById('species-sort') || document.getElementById('blog-sort') || document.querySelector('.sort-select');
    const gridContainer = document.querySelector('.species-grid') || document.querySelector('.blog-posts-grid') || document.querySelector('.posts-grid');
    const loadingOverlay = createLoadingOverlay();
    
    if (!sortSelect) {
        console.log('Sort select not found');
        return;
    }
    
    // Funkcja tworzenia loading overlay
    function createLoadingOverlay() {
        const overlay = document.createElement('div');
        overlay.className = 'sorting-loading-overlay';
        overlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(2px);
        `;
        
        const spinner = document.createElement('div');
        spinner.innerHTML = `
            <div style="
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #16a34a;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            "></div>
            <p style="margin-top: 1rem; color: #666; font-weight: 500;">Sortowanie...</p>
        `;
        
        overlay.appendChild(spinner);
        
        // Dodaj CSS dla animacji
        if (!document.getElementById('sorting-styles')) {
            const style = document.createElement('style');
            style.id = 'sorting-styles';
            style.textContent = `
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .sorting-fade-in {
                    animation: sortingFadeIn 0.5s ease-in-out;
                }
                @keyframes sortingFadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `;
            document.head.appendChild(style);
        }
        
        return overlay;
    }
    
    // Metoda 1: Przekierowanie URL (fallback)
    function handleSortByRedirect(value) {
        console.log('Redirecting to:', value);
        showLoading();
        
        // Dodaj delay dla UX
        setTimeout(() => {
            window.location.href = value;
        }, 300);
    }
    
    // Metoda 2: AJAX sortowanie (preferowane)
    function handleSortByAjax(orderby) {
        if (!carni24_ajax) {
            console.log('AJAX not available, using redirect');
            return false;
        }
        
        console.log('AJAX sort:', orderby);
        showLoading();
        
        const postType = getPostType();
        const currentPage = getCurrentPage();
        
        const data = {
            action: 'carni24_sort_posts',
            orderby: orderby,
            post_type: postType,
            paged: currentPage,
            nonce: carni24_ajax.nonce
        };
        
        fetch(carni24_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        })
        .then(response => response.json())
        .then(result => {
            hideLoading();
            
            if (result.success) {
                updateContent(result.data.html);
                updateURL(orderby);
                updatePagination(result.data.max_pages, result.data.found_posts);
                
                // Pokazanie efektu fade-in
                gridContainer.classList.add('sorting-fade-in');
                setTimeout(() => {
                    gridContainer.classList.remove('sorting-fade-in');
                }, 500);
                
            } else {
                console.error('AJAX Error:', result.data);
                showError('Wystąpił błąd podczas sortowania');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Fetch Error:', error);
            showError('Wystąpił błąd połączenia');
        });
        
        return true;
    }
    
    // Funkcje pomocnicze
    function getPostType() {
        if (document.body.classList.contains('post-type-archive-species')) return 'species';
        if (document.body.classList.contains('page-template-page-blog')) return 'post';
        if (document.body.classList.contains('archive') || document.body.classList.contains('category')) return 'post';
        return 'post';
    }
    
    function getCurrentPage() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('paged') || 1;
    }
    
    function showLoading() {
        if (gridContainer) {
            gridContainer.style.position = 'relative';
            gridContainer.appendChild(loadingOverlay);
            loadingOverlay.style.display = 'flex';
        }
        
        if (sortSelect) {
            sortSelect.disabled = true;
        }
    }
    
    function hideLoading() {
        loadingOverlay.style.display = 'none';
        
        if (sortSelect) {
            sortSelect.disabled = false;
        }
    }
    
    function updateContent(html) {
        if (gridContainer) {
            gridContainer.innerHTML = html;
        }
    }
    
    function updateURL(orderby) {
        const url = new URL(window.location);
        if (orderby && orderby !== 'date') {
            url.searchParams.set('orderby', orderby);
        } else {
            url.searchParams.delete('orderby');
        }
        
        // Usuń paged przy zmianie sortowania
        url.searchParams.delete('paged');
        
        window.history.pushState({}, '', url);
    }
    
    function updatePagination(maxPages, foundPosts) {
        const paginationContainer = document.querySelector('.custom-pagination');
        const countContainer = document.querySelector('.species-count, .blog-count, .archive-count');
        
        if (countContainer) {
            const postType = getPostType();
            let countText = '';
            
            if (postType === 'species') {
                countText = foundPosts + ' ' + (foundPosts == 1 ? 'gatunek' : foundPosts < 5 ? 'gatunki' : 'gatunków');
            } else {
                countText = foundPosts + ' ' + (foundPosts == 1 ? 'artykuł' : foundPosts < 5 ? 'artykuły' : 'artykułów');
            }
            
            countContainer.querySelector('span').textContent = countText;
        }
        
        // Ukryj paginację jeśli tylko jedna strona
        if (paginationContainer) {
            paginationContainer.style.display = maxPages > 1 ? 'block' : 'none';
        }
    }
    
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'sorting-error';
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc2626;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            z-index: 10000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: slideInRight 0.3s ease-out;
        `;
        errorDiv.textContent = message;
        
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }
    
    // Event listener dla select
    sortSelect.addEventListener('change', function(e) {
        const selectedValue = e.target.value;
        console.log('Sort changed to:', selectedValue);
        
        // Próbuj AJAX, jeśli nie działa - użyj przekierowania
        const orderby = extractOrderbyFromUrl(selectedValue);
        
        if (!handleSortByAjax(orderby)) {
            handleSortByRedirect(selectedValue);
        }
    });
    
    // Funkcja wyciągająca orderby z URL
    function extractOrderbyFromUrl(url) {
        try {
            const urlObj = new URL(url, window.location.origin);
            return urlObj.searchParams.get('orderby') || 'date';
        } catch (e) {
            return 'date';
        }
    }
    
    // Zabezpieczenie przed przypadkowym opuszczeniem strony podczas sortowania
    let sortingInProgress = false;
    
    window.addEventListener('beforeunload', function(e) {
        if (sortingInProgress) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Debug info
    console.log('Sorting initialized for:', getPostType());
    console.log('Sort select:', sortSelect);
    console.log('Grid container:', gridContainer);
    console.log('AJAX available:', !!carni24_ajax);
});