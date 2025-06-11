<?php
$search_placeholder = get_option('carni24_search_placeholder', 'Wpisz czego poszukujesz...');
?>

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
                
                <!-- PODPOWIEDZI WYSZUKIWANIA -->
                <div class="search-suggestions">
                    <h6 class="search-suggestions-header text-muted">
                        <i class="bi bi-lightbulb me-1"></i>
                        Popularne wyszukiwania:
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php
                        $popular_searches = array(
                            'Dionaea muscipula' => 'species',
                            'Nepenthes' => 'species',
                            'Sarracenia' => 'species',
                            'Drosera' => 'species',
                            'Pielęgnacja' => 'post'
                        );
                        
                        foreach ($popular_searches as $term => $type) :
                            $search_url = add_query_arg('s', $term, home_url('/'));
                            if ($type === 'species') {
                                $search_url = add_query_arg('post_type', 'species', $search_url);
                            }
                        ?>
                            <a href="<?= esc_url($search_url) ?>" 
                               class="popular-search-tag text-decoration-none bg-light text-dark px-3 py-2 rounded-pill">
                                <?= esc_html($term) ?>
                                <small class="text-muted">(<?= $type === 'species' ? 'gatunek' : 'artykuł' ?>)</small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- OSTATNIE WYSZUKIWANIA -->
                <div class="recent-searches" id="recentSearches" style="display: none;">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-clock-history me-1"></i>
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

@media (max-width: 768px) {
    .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .search-overlay-input {
        font-size: 16px;
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

.modal.fade .modal-dialog {
    transition: transform 0.4s ease, opacity 0.4s ease;
    transform: translate(0, -50px) scale(0.9);
}

.modal.show .modal-dialog {
    transform: translate(0, 0) scale(1);
}
</style>

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOverlayInput');
    const searchModal = document.getElementById('searchModal');
    const recentSearches = document.getElementById('recentSearches');
    const recentSearchesContent = document.getElementById('recentSearchesContent');
    
    // Focus na input po otwarciu modala
    if (searchModal) {
        searchModal.addEventListener('shown.bs.modal', function() {
            if (searchInput) {
                searchInput.focus();
            }
        });
    }
    
    // Obsługa ostatnich wyszukiwań
    function loadRecentSearches() {
        const recent = JSON.parse(localStorage.getItem('carni24_recent_searches') || '[]');
        if (recent.length > 0) {
            recentSearches.style.display = 'block';
            recentSearchesContent.innerHTML = recent.map(term => 
                `<span class="recent-search-item" data-term="${term}">
                    ${term}
                    <button type="button" class="recent-search-remove" data-term="${term}">×</button>
                </span>`
            ).join('');
        }
    }
    
    function saveRecentSearch(term) {
        if (!term.trim()) return;
        
        let recent = JSON.parse(localStorage.getItem('carni24_recent_searches') || '[]');
        recent = recent.filter(item => item !== term);
        recent.unshift(term);
        recent = recent.slice(0, 5);
        
        localStorage.setItem('carni24_recent_searches', JSON.stringify(recent));
    }
    
    // Zapisz wyszukiwanie po submit
    if (searchInput) {
        searchInput.closest('form').addEventListener('submit', function() {
            saveRecentSearch(searchInput.value);
        });
    }
    
    // Obsługa kliknięć w ostatnie wyszukiwania
    if (recentSearchesContent) {
        recentSearchesContent.addEventListener('click', function(e) {
            if (e.target.classList.contains('recent-search-item')) {
                const term = e.target.dataset.term;
                searchInput.value = term;
                searchInput.closest('form').submit();
            } else if (e.target.classList.contains('recent-search-remove')) {
                const term = e.target.dataset.term;
                let recent = JSON.parse(localStorage.getItem('carni24_recent_searches') || '[]');
                recent = recent.filter(item => item !== term);
                localStorage.setItem('carni24_recent_searches', JSON.stringify(recent));
                loadRecentSearches();
            }
        });
    }
    
    // Załaduj ostatnie wyszukiwania przy otwarciu
    loadRecentSearches();
});
</script>