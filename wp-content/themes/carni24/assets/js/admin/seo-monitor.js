/**
 * Carni24 SEO Monitor JavaScript
 * assets/js/admin/seo-monitor.js
 */

function carni24InitSeoMonitor() {
    if (typeof jQuery === 'undefined' || !jQuery('.carni24-seo-monitor').length) {
        return;
    }
    
    const $ = jQuery;
    const $monitor = $('.carni24-seo-monitor');
    
    // ===== INICJALIZACJA ===== //
    initTabs();
    initFilters();
    initIgnoreButtons();
    initLoadMore();
    initAutoRefresh();
    
    // ===== ZAKŁADKI ===== //
    function initTabs() {
        $('.seo-tab-btn').on('click', function() {
            const $btn = $(this);
            const tab = $btn.data('tab');
            
            // Usuń aktywne klasy
            $('.seo-tab-btn').removeClass('active');
            $('.seo-tab-content').removeClass('active');
            
            // Dodaj aktywne klasy
            $btn.addClass('active');
            $(`#tab-${tab}`).addClass('active');
            
            // Zapisz aktywną zakładkę
            localStorage.setItem('carni24_seo_monitor_tab', tab);
            
            // Załaduj zawartość jeśli nie jest to overview
            if (tab !== 'overview') {
                loadTabContent(tab);
            }
        });
        
        // Przywróć ostatnią aktywną zakładkę
        const savedTab = localStorage.getItem('carni24_seo_monitor_tab');
        if (savedTab && $(`.seo-tab-btn[data-tab="${savedTab}"]`).length) {
            $(`.seo-tab-btn[data-tab="${savedTab}"]`).click();
        }
    }
    
    // ===== FILTRY ===== //
    function initFilters() {
        $('#seo-post-type-filter, #seo-issue-filter').on('change', function() {
            refreshContent();
        });
        
        $('#refresh-seo-monitor').on('click', function() {
            refreshContent();
        });
    }
    
    function refreshContent() {
        const activeTab = $('.seo-tab-btn.active').data('tab') || 'overview';
        const postType = $('#seo-post-type-filter').val();
        const issueType = $('#seo-issue-filter').val();
        
        showLoading();
        
        $.ajax({
            url: carni24SeoMonitor.ajaxurl,
            type: 'POST',
            data: {
                action: 'carni24_refresh_seo_monitor',
                nonce: carni24SeoMonitor.refreshNonce,
                tab: activeTab,
                post_type: postType,
                issue_type: issueType
            },
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    $(`#tab-${activeTab}`).html(response.data.html);
                    
                    // Reinicjalizuj funkcje dla nowej zawartości
                    initIgnoreButtons();
                    initLoadMore();
                    
                    // Pokazuj komunikat jeśli brak wyników
                    if (!response.data.html.trim()) {
                        $(`#tab-${activeTab}`).html(`
                            <div class="seo-no-issues">
                                <div class="no-issues-icon">🔍</div>
                                <h3>Brak wyników</h3>
                                <p>Nie znaleziono wpisów spełniających wybrane kryteria.</p>
                            </div>
                        `);
                    }
                } else {
                    showError(carni24SeoMonitor.strings.error);
                }
            },
            error: function() {
                hideLoading();
                showError(carni24SeoMonitor.strings.error);
            }
        });
    }
    
    // ===== ŁADOWANIE ZAWARTOŚCI ZAKŁADEK ===== //
    function loadTabContent(tab) {
        const $tabContent = $(`#tab-${tab}`);
        
        // Jeśli zawartość już została załadowana, nie ładuj ponownie
        if ($tabContent.children().length > 0) {
            return;
        }
        
        $tabContent.html('<div class="seo-loading">Ładowanie...</div>');
        
        const postType = $('#seo-post-type-filter').val();
        const issueType = $('#seo-issue-filter').val();
        
        $.ajax({
            url: carni24SeoMonitor.ajaxurl,
            type: 'POST',
            data: {
                action: 'carni24_refresh_seo_monitor',
                nonce: carni24SeoMonitor.refreshNonce,
                tab: tab,
                post_type: postType,
                issue_type: issueType
            },
            success: function(response) {
                if (response.success) {
                    $tabContent.html(response.data.html);
                    
                    // Reinicjalizuj funkcje
                    initIgnoreButtons();
                    initLoadMore();
                } else {
                    $tabContent.html(`
                        <div class="seo-no-issues">
                            <div class="no-issues-icon">❌</div>
                            <h3>Błąd ładowania</h3>
                            <p>${carni24SeoMonitor.strings.error}</p>
                        </div>
                    `);
                }
            },
            error: function() {
                $tabContent.html(`
                    <div class="seo-no-issues">
                        <div class="no-issues-icon">❌</div>
                        <h3>Błąd ładowania</h3>
                        <p>${carni24SeoMonitor.strings.error}</p>
                    </div>
                `);
            }
        });
    }
    
    // ===== IGNOROWANIE WPISÓW ===== //
    function initIgnoreButtons() {
        $('.seo-ignore-post').off('click').on('click', function(e) {
            e.preventDefault();
            
            const $btn = $(this);
            const postId = $btn.data('post-id');
            const nonce = $btn.data('nonce');
            
            if (!confirm(carni24SeoMonitor.strings.confirm_ignore)) {
                return;
            }
            
            $btn.prop('disabled', true).text('Ignorowanie...');
            
            $.ajax({
                url: carni24SeoMonitor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'carni24_toggle_seo_ignore',
                    post_id: postId,
                    ignore: '1',
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Usuń wpis z listy z animacją
                        $btn.closest('.seo-post-item').fadeOut(300, function() {
                            $(this).remove();
                            
                            // Sprawdź czy lista jest pusta
                            checkEmptyList();
                        });
                    } else {
                        $btn.prop('disabled', false).text('🙈 Ignoruj');
                        showError('Nie udało się zignorować wpisu.');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).text('🙈 Ignoruj');
                    showError(carni24SeoMonitor.strings.error);
                }
            });
        });
    }
    
    // ===== ŁADOWANIE WIĘCEJ ===== //
    function initLoadMore() {
        $('#load-more-seo-posts').off('click').on('click', function(e) {
            e.preventDefault();
            
            const $btn = $(this);
            const offset = parseInt($btn.data('offset'));
            const limit = 10;
            
            $btn.addClass('loading').prop('disabled', true);
            
            $.ajax({
                url: carni24SeoMonitor.ajaxurl,
                type: 'POST',
                data: {
                    action: 'carni24_load_more_seo_posts',
                    offset: offset,
                    limit: limit,
                    nonce: carni24SeoMonitor.loadMoreNonce
                },
                success: function(response) {
                    $btn.removeClass('loading').prop('disabled', false);
                    
                    if (response.success) {
                        // Dodaj nowe wpisy
                        $('.seo-posts-list').append(response.data.html);
                        
                        // Reinicjalizuj funkcje dla nowych wpisów
                        initIgnoreButtons();
                        
                        if (response.data.has_more) {
                            // Aktualizuj offset
                            $btn.data('offset', offset + limit);
                            $btn.text(`Pokaż więcej (${response.data.remaining} pozostałych)`);
                        } else {
                            // Usuń przycisk jeśli nie ma więcej
                            $btn.closest('.seo-load-more').fadeOut();
                        }
                    } else {
                        showError(carni24SeoMonitor.strings.error);
                    }
                },
                error: function() {
                    $btn.removeClass('loading').prop('disabled', false);
                    showError(carni24SeoMonitor.strings.error);
                }
            });
        });
    }
    
    // ===== AUTO-REFRESH ===== //
    function initAutoRefresh() {
        // Odświeżaj co 5 minut
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                refreshContent();
            }
        }, 300000); // 5 minut
    }
    
    // ===== FUNKCJE POMOCNICZE ===== //
    function showLoading() {
        const activeTab = $('.seo-tab-btn.active').data('tab') || 'overview';
        $(`#tab-${activeTab}`).html('<div class="seo-loading">Ładowanie danych SEO...</div>');
    }
    
    function hideLoading() {
        $('.seo-loading').hide();
    }
    
    function showError(message) {
        const activeTab = $('.seo-tab-btn.active').data('tab') || 'overview';
        $(`#tab-${activeTab}`).html(`
            <div class="seo-no-issues">
                <div class="no-issues-icon">❌</div>
                <h3>Wystąpił błąd</h3>
                <p>${message}</p>
                <button type="button" class="button" onclick="location.reload()">Odśwież stronę</button>
            </div>
        `);
    }
    
    function checkEmptyList() {
        const $postsList = $('.seo-posts-list');
        
        if ($postsList.children('.seo-post-item').length === 0) {
            $postsList.html(`
                <div class="seo-no-issues">
                    <div class="no-issues-icon">🎉</div>
                    <h3>Lista pusta!</h3>
                    <p>Wszystkie wpisy zostały zoptymalizowane lub zignorowane.</p>
                </div>
            `);
        }
    }
    
    // ===== KEYBOARD NAVIGATION ===== //
    $(document).on('keydown', '.seo-tab-btn', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).click();
        }
        
        if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            e.preventDefault();
            const $tabs = $('.seo-tab-btn');
            const currentIndex = $tabs.index(this);
            let newIndex;
            
            if (e.key === 'ArrowLeft') {
                newIndex = currentIndex > 0 ? currentIndex - 1 : $tabs.length - 1;
            } else {
                newIndex = currentIndex < $tabs.length - 1 ? currentIndex + 1 : 0;
            }
            
            $tabs.eq(newIndex).focus().click();
        }
    });
    
    // ===== TOOLTIPS ===== //
    function initTooltips() {
        $('[data-tooltip]').hover(
            function() {
                const tooltip = $(this).data('tooltip');
                const $tooltip = $('<div class="seo-tooltip-popup"></div>').text(tooltip);
                $('body').append($tooltip);
                
                const offset = $(this).offset();
                $tooltip.css({
                    position: 'absolute',
                    top: offset.top - $tooltip.outerHeight() - 5,
                    left: offset.left + ($(this).outerWidth() / 2) - ($tooltip.outerWidth() / 2),
                    background: '#23282d',
                    color: '#fff',
                    padding: '6px 8px',
                    borderRadius: '4px',
                    fontSize: '12px',
                    whiteSpace: 'nowrap',
                    zIndex: 1000
                });
            },
            function() {
                $('.seo-tooltip-popup').remove();
            }
        );
    }
    
    // Inicjalizuj tooltips
    initTooltips();
    
    // ===== STATISTIKY UPDATE ===== //
    function updateStatistics() {
        $.ajax({
            url: carni24SeoMonitor.ajaxurl,
            type: 'POST',
            data: {
                action: 'carni24_get_seo_stats',
                nonce: carni24SeoMonitor.refreshNonce
            },
            success: function(response) {
                if (response.success && response.data.stats) {
                    updateStatsDisplay(response.data.stats);
                }
            }
        });
    }
    
    function updateStatsDisplay(stats) {
        $('.seo-stats-grid .seo-stat-item').each(function(index) {
            if (stats[index]) {
                const $item = $(this);
                $item.find('.stat-number').text(stats[index].count);
                $item.removeClass('good warning error').addClass(stats[index].status);
            }
        });
    }
    
    // ===== EXPORT FUNCTIONALITY ===== //
    function initExport() {
        if ($('#export-seo-report').length) {
            $('#export-seo-report').on('click', function() {
                const activeTab = $('.seo-tab-btn.active').data('tab') || 'overview';
                
                // Zbierz dane do eksportu
                const exportData = {
                    tab: activeTab,
                    posts: []
                };
                
                $('.seo-post-item').each(function() {
                    const $item = $(this);
                    exportData.posts.push({
                        id: $item.data('post-id'),
                        title: $item.find('.seo-post-title a').text(),
                        issues: $item.find('.seo-issue').map(function() {
                            return $(this).find('.issue-message').text();
                        }).get(),
                        score: $item.find('.score-number').text()
                    });
                });
                
                // Konwertuj do CSV
                const csv = convertToCSV(exportData);
                downloadCSV(csv, `seo-report-${activeTab}-${new Date().toISOString().split('T')[0]}.csv`);
            });
        }
    }
    
    function convertToCSV(data) {
        const headers = ['ID', 'Tytuł', 'Wynik SEO', 'Problemy'];
        const rows = [headers];
        
        data.posts.forEach(post => {
            rows.push([
                post.id,
                `"${post.title.replace(/"/g, '""')}"`,
                post.score,
                `"${post.issues.join('; ').replace(/"/g, '""')}"`
            ]);
        });
        
        return rows.map(row => row.join(',')).join('\n');
    }
    
    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
    
    // Inicjalizuj export jeśli istnieje
    initExport();
    
    // ===== BULK ACTIONS ===== //
    function initBulkActions() {
        if ($('#bulk-actions-selector').length) {
            $('#bulk-actions-selector').on('change', function() {
                const action = $(this).val();
                const $applyBtn = $('#bulk-apply');
                
                if (action) {
                    $applyBtn.prop('disabled', false);
                } else {
                    $applyBtn.prop('disabled', true);
                }
            });
            
            $('#bulk-apply').on('click', function() {
                const action = $('#bulk-actions-selector').val();
                const selectedPosts = [];
                
                $('.seo-post-checkbox:checked').each(function() {
                    selectedPosts.push($(this).val());
                });
                
                if (selectedPosts.length === 0) {
                    alert('Wybierz przynajmniej jeden wpis.');
                    return;
                }
                
                if (action === 'ignore') {
                    bulkIgnorePosts(selectedPosts);
                } else if (action === 'edit') {
                    bulkEditPosts(selectedPosts);
                }
            });
        }
    }
    
    function bulkIgnorePosts(postIds) {
        if (!confirm(`Czy na pewno chcesz zignorować ${postIds.length} wpisów?`)) {
            return;
        }
        
        // Implementacja bulk ignore
        // ... kod do ignorowania wielu wpisów
    }
    
    function bulkEditPosts(postIds) {
        // Otwórz wpisy w nowych zakładkach
        postIds.forEach(postId => {
            const editUrl = `/wp-admin/post.php?post=${postId}&action=edit`;
            window.open(editUrl, '_blank');
        });
    }
    
    // Inicjalizuj bulk actions jeśli istnieją
    initBulkActions();
}

// Global funkcja dostępna dla inline handlers
window.carni24InitSeoMonitor = carni24InitSeoMonitor;
