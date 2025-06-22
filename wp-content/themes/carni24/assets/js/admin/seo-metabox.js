/**
 * Carni24 SEO Meta Box JavaScript - NAPRAWIONA WERSJA
 * assets/js/admin/seo-metabox.js
 */

(function($) {
    'use strict';
    
    // ===== INICJALIZACJA ===== //
    $(document).ready(function() {
        // Sprawdź czy metabox istnieje
        if ($('.carni24-seo-metabox').length) {
            console.log('SEO Metabox found, initializing...');
            initSeoMetabox();
        } else {
            console.log('SEO Metabox not found');
        }
    });
    
    function initSeoMetabox() {
        initTabs();
        initCharacterCounters();
        initLivePreview();
        initImageUpload();
        initKeywordsPreview();
        initSeoAnalysis();
        
        // Początkowa analiza SEO
        setTimeout(updateSeoAnalysis, 1000);
        
        console.log('SEO Metabox initialized successfully');
    }
    
    // ===== ZAKŁADKI - NAPRAWIONA WERSJA ===== //
    function initTabs() {
        console.log('Initializing tabs...');
        
        // Obsługa kliknięć na zakładki
        $(document).on('click', '.carni24-seo-tabs .tab-link', function(e) {
            e.preventDefault();
            console.log('Tab clicked:', $(this).data('tab'));
            
            const targetTab = $(this).data('tab');
            const $metabox = $(this).closest('.carni24-seo-metabox');
            
            // Usuń aktywne klasy z wszystkich zakładek w tym metaboxie
            $metabox.find('.carni24-seo-tabs .tab-link').removeClass('active');
            $metabox.find('.seo-tab-content').removeClass('active').hide();
            
            // Dodaj aktywne klasy
            $(this).addClass('active');
            $metabox.find('#' + targetTab).addClass('active').show();
            
            console.log('Switched to tab:', targetTab);
            
            // Zapisz aktywną zakładkę w localStorage
            localStorage.setItem('carni24_seo_active_tab', targetTab);
        });
        
        // Przywróć ostatnią aktywną zakładkę
        const savedTab = localStorage.getItem('carni24_seo_active_tab');
        if (savedTab && $('#' + savedTab).length) {
            $('.tab-link[data-tab="' + savedTab + '"]').click();
            console.log('Restored saved tab:', savedTab);
        } else {
            // Upewnij się, że pierwsza zakładka jest aktywna
            $('.carni24-seo-tabs .tab-link:first').addClass('active');
            $('.seo-tab-content:first').addClass('active').show();
            $('.seo-tab-content:not(:first)').removeClass('active').hide();
            console.log('Set first tab as active');
        }
    }
    
    // ===== LICZNIKI ZNAKÓW ===== //
    function initCharacterCounters() {
        console.log('Initializing character counters...');
        
        // Tytuł SEO
        $(document).on('input', '#seo_title', function() {
            updateCharCounter(this, '#title-char-count', '#title-char-status', {
                good: [30, 60],
                warning: [25, 70],
                max: 70
            });
            updatePreview();
        });
        
        // Opis SEO
        $(document).on('input', '#seo_description', function() {
            updateCharCounter(this, '#desc-char-count', '#desc-char-status', {
                good: [120, 160],
                warning: [100, 200],
                max: 200
            });
            updatePreview();
        });
        
        // Tytuł OG
        $(document).on('input', '#seo_og_title', function() {
            updateSocialPreview();
        });
        
        // Opis OG
        $(document).on('input', '#seo_og_description', function() {
            updateSocialPreview();
        });
        
        // Uruchom liczniki na start
        $('#seo_title, #seo_description').trigger('input');
    }
    
    function updateCharCounter(input, countSelector, statusSelector, limits) {
        const length = $(input).val().length;
        const $count = $(countSelector);
        const $status = $(statusSelector);
        
        if (!$count.length || !$status.length) return;
        
        $count.text(length);
        
        // Usuń poprzednie klasy
        $status.removeClass('good warning error');
        
        if (length === 0) {
            $status.text('').removeClass('good warning error');
        } else if (length >= limits.good[0] && length <= limits.good[1]) {
            $status.addClass('good').text('Idealnie');
        } else if (length >= limits.warning[0] && length <= limits.warning[1]) {
            $status.addClass('warning').text('OK');
        } else {
            $status.addClass('error').text(length > limits.max ? 'Za długie' : 'Za krótkie');
        }
    }
    
    // ===== PODGLĄD NA ŻYWO ===== //
    function initLivePreview() {
        updatePreview();
        updateSocialPreview();
    }
    
    function updatePreview() {
        const title = $('#seo_title').val() || $('#seo_title').attr('placeholder') || '';
        const description = $('#seo_description').val() || $('#seo_description').attr('placeholder') || '';
        
        const $previewTitle = $('#preview-title');
        const $previewDesc = $('#preview-description');
        
        if ($previewTitle.length) $previewTitle.text(title);
        if ($previewDesc.length) $previewDesc.text(description);
    }
    
    function updateSocialPreview() {
        const ogTitle = $('#seo_og_title').val() || $('#seo_title').val() || $('#seo_title').attr('placeholder') || '';
        const ogDescription = $('#seo_og_description').val() || $('#seo_description').val() || $('#seo_description').attr('placeholder') || '';
        
        const $socialTitle = $('#social-preview-title');
        const $socialDesc = $('#social-preview-desc');
        
        if ($socialTitle.length) $socialTitle.text(ogTitle);
        if ($socialDesc.length) $socialDesc.text(ogDescription);
    }
    
    // ===== UPLOAD OBRAZU ===== //
    function initImageUpload() {
        console.log('Initializing image upload...');
        
        let mediaUploader;
        
        $(document).on('click', '.upload-image-button', function(e) {
            e.preventDefault();
            console.log('Upload image button clicked');
            
            // Jeśli uploader już istnieje, otwórz go
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Sprawdź czy wp.media jest dostępne
            if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
                console.error('WordPress media library not available');
                alert('Media library nie jest dostępna. Odśwież stronę i spróbuj ponownie.');
                return;
            }
            
            // Stwórz nowy media uploader
            mediaUploader = wp.media({
                title: 'Wybierz obraz',
                button: {
                    text: 'Użyj tego obrazu'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            // Gdy wybrano obraz
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                console.log('Image selected:', attachment);
                
                $('#seo_og_image').val(attachment.id);
                
                // Aktualizuj podgląd
                $('#og-image-preview').html('<img src="' + attachment.url + '" alt="OG Image" />');
                
                // Aktualizuj przyciski
                $('.upload-image-button').text('🔄 Zmień obraz');
                $('.remove-image-button').show();
                
                // Aktualizuj podgląd social
                $('.social-image').html('<img src="' + attachment.url + '" alt="Social preview" />');
            });
            
            mediaUploader.open();
        });
        
        // Usuń obraz
        $(document).on('click', '.remove-image-button', function(e) {
            e.preventDefault();
            console.log('Remove image button clicked');
            
            $('#seo_og_image').val('');
            $('#og-image-preview').empty();
            $('.upload-image-button').text('📷 Dodaj obraz');
            $(this).hide();
            
            // Aktualizuj podgląd social
            $('.social-image').html('<div class="no-image">Brak obrazu</div>');
        });
    }
    
    // ===== PODGLĄD SŁÓW KLUCZOWYCH ===== //
    function initKeywordsPreview() {
        $(document).on('input', '#seo_keywords', function() {
            updateKeywordsPreview();
            updateSeoAnalysis();
        });
        
        updateKeywordsPreview();
    }
    
    function updateKeywordsPreview() {
        const keywords = $('#seo_keywords').val();
        const $preview = $('#keywords-preview');
        
        if (!$preview.length) return;
        
        if (!keywords.trim()) {
            $preview.empty();
            return;
        }
        
        const keywordArray = keywords.split(',').map(k => k.trim()).filter(k => k);
        let html = '';
        
        keywordArray.forEach(function(keyword) {
            html += '<span class="keyword-tag">' + escapeHtml(keyword) + '</span>';
        });
        
        $preview.html(html);
    }
    
    // ===== ANALIZA SEO ===== //
    function initSeoAnalysis() {
        // Nasłuchuj zmian we wszystkich polach SEO
        $(document).on('input', '#seo_title, #seo_description, #seo_keywords', debounce(updateSeoAnalysis, 500));
    }
    
    function updateSeoAnalysis() {
        const analysis = performSeoAnalysis();
        renderSeoAnalysis(analysis);
    }
    
    function performSeoAnalysis() {
        const title = $('#seo_title').val();
        const description = $('#seo_description').val();
        const keywords = $('#seo_keywords').val();
        
        const checks = [];
        let score = 0;
        let maxScore = 0;
        
        // Sprawdź tytuł
        maxScore += 25;
        if (title) {
            if (title.length >= 30 && title.length <= 60) {
                checks.push({
                    type: 'success',
                    icon: '✅',
                    message: 'Tytuł SEO ma odpowiednią długość'
                });
                score += 25;
            } else if (title.length > 0) {
                checks.push({
                    type: 'warning',
                    icon: '⚠️',
                    message: 'Tytuł SEO ' + (title.length < 30 ? 'za krótki' : 'za długi')
                });
                score += 15;
            }
        } else {
            checks.push({
                type: 'error',
                icon: '❌',
                message: 'Brak tytułu SEO'
            });
        }
        
        // Sprawdź opis
        maxScore += 25;
        if (description) {
            if (description.length >= 120 && description.length <= 160) {
                checks.push({
                    type: 'success',
                    icon: '✅',
                    message: 'Opis SEO ma odpowiednią długość'
                });
                score += 25;
            } else if (description.length > 0) {
                checks.push({
                    type: 'warning',
                    icon: '⚠️',
                    message: 'Opis SEO ' + (description.length < 120 ? 'za krótki' : 'za długi')
                });
                score += 15;
            }
        } else {
            checks.push({
                type: 'error',
                icon: '❌',
                message: 'Brak opisu SEO'
            });
        }
        
        // Sprawdź słowa kluczowe
        maxScore += 20;
        if (keywords) {
            const keywordArray = keywords.split(',').map(k => k.trim()).filter(k => k);
            if (keywordArray.length >= 3 && keywordArray.length <= 10) {
                checks.push({
                    type: 'success',
                    icon: '✅',
                    message: 'Odpowiednia liczba słów kluczowych (' + keywordArray.length + ')'
                });
                score += 20;
            } else if (keywordArray.length > 0) {
                checks.push({
                    type: 'warning',
                    icon: '⚠️',
                    message: keywordArray.length < 3 ? 'Za mało słów kluczowych' : 'Za dużo słów kluczowych'
                });
                score += 10;
            }
        } else {
            checks.push({
                type: 'error',
                icon: '❌',
                message: 'Brak słów kluczowych'
            });
        }
        
        // Sprawdź focus keyword w tytule
        maxScore += 15;
        if (keywords && title) {
            const focusKeyword = keywords.split(',')[0].trim().toLowerCase();
            if (title.toLowerCase().includes(focusKeyword)) {
                checks.push({
                    type: 'success',
                    icon: '✅',
                    message: 'Główne słowo kluczowe w tytule'
                });
                score += 15;
            } else {
                checks.push({
                    type: 'warning',
                    icon: '⚠️',
                    message: 'Główne słowo kluczowe nie występuje w tytule'
                });
            }
        }
        
        // Sprawdź focus keyword w opisie
        maxScore += 15;
        if (keywords && description) {
            const focusKeyword = keywords.split(',')[0].trim().toLowerCase();
            if (description.toLowerCase().includes(focusKeyword)) {
                checks.push({
                    type: 'success',
                    icon: '✅',
                    message: 'Główne słowo kluczowe w opisie'
                });
                score += 15;
            } else {
                checks.push({
                    type: 'warning',
                    icon: '⚠️',
                    message: 'Główne słowo kluczowe nie występuje w opisie'
                });
            }
        }
        
        const percentage = maxScore > 0 ? Math.round((score / maxScore) * 100) : 0;
        
        return {
            score: percentage,
            checks: checks
        };
    }
    
    function renderSeoAnalysis(analysis) {
        const $score = $('#seo-score');
        const $scoreCircle = $('.seo-score-circle');
        const $statusList = $('#seo-status-list');
        
        // Aktualizuj wynik
        if ($score.length) {
            $score.text(analysis.score);
        }
        
        if ($scoreCircle.length) {
            $scoreCircle.css('--score', analysis.score);
        }
        
        // Aktualizuj listę sprawdzeń
        if ($statusList.length) {
            let html = '';
            analysis.checks.forEach(function(check) {
                html += '<div class="seo-status-item ' + check.type + '">';
                html += '<span class="seo-status-icon">' + check.icon + '</span>';
                html += check.message;
                html += '</div>';
            });
            
            $statusList.html(html);
        }
    }
    
    // ===== FUNKCJE POMOCNICZE ===== //
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // ===== ACCESSIBILITY ===== //
    $(document).on('keydown', '.carni24-seo-tabs .tab-link', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).click();
        }
    });
    
    // ===== AUTO-SAVE DRAFT ===== //
    let autoSaveTimeout;
    $(document).on('input', '#seo_title, #seo_description, #seo_keywords', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // Trigger WordPress autosave
            if (typeof wp !== 'undefined' && wp.autosave) {
                wp.autosave.server.triggerSave();
            }
        }, 2000);
    });
    
    // ===== DEBUGGING ===== //
    window.carni24SeoDebug = function() {
        console.log('=== SEO Metabox Debug ===');
        console.log('Metabox exists:', $('.carni24-seo-metabox').length > 0);
        console.log('Tabs found:', $('.carni24-seo-tabs .tab-link').length);
        console.log('Tab contents found:', $('.seo-tab-content').length);
        console.log('Active tab:', $('.carni24-seo-tabs .tab-link.active').data('tab'));
        console.log('Active content:', $('.seo-tab-content.active').attr('id'));
    };
    
})(jQuery);
    