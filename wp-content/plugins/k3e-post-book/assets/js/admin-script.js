/**
 * JavaScript dla kalendarza K3e Post Book
 */

(function() {
    'use strict';
    
    // Zmienne globalne
    var currentMonth = window.k3eCurrentMonth || new Date().getMonth() + 1;
    var currentYear = window.k3eCurrentYear || new Date().getFullYear();
    
    // Czekamy na jQuery i DOM
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function($) {
            
            // Modal functions w jQuery
            window.openPostsModal = function(day, month, year, posts) {
                var monthNames = [
                    '', 'Stycznia', 'Lutego', 'Marca', 'Kwietnia', 'Maja', 'Czerwca',
                    'Lipca', 'Sierpnia', 'Września', 'Października', 'Listopada', 'Grudnia'
                ];
                
                var modalTitle = day + ' ' + monthNames[month] + ' ' + year;
                var postsHtml = '';
                
                if (posts && posts.length > 0) {
                    posts.forEach(function(post) {
                        postsHtml += '<div class="k3e-post-item">';
                        postsHtml += '<a href="' + post.edit_url + '" class="k3e-post-title" target="_blank">' + post.title + '</a>';
                        postsHtml += '<div class="k3e-post-meta">';
                        postsHtml += '<span class="k3e-post-type k3e-type-' + post.type.toLowerCase() + '">' + post.type + '</span>';
                        postsHtml += '<span class="k3e-post-status ' + post.status.toLowerCase() + '">' + post.status + '</span>';
                        if (post.author) {
                            postsHtml += '<span class="k3e-post-author">Autor: ' + post.author + '</span>';
                        }
                        postsHtml += '</div>';
                        postsHtml += '<div class="k3e-post-actions">';
                        postsHtml += '<a href="' + post.edit_url + '" class="k3e-post-action" target="_blank">Edytuj</a>';
                        
                        if (post.status.toLowerCase() === 'publish' && post.view_url) {
                            postsHtml += '<a href="' + post.view_url + '" class="k3e-post-action" target="_blank">Zobacz</a>';
                        }
                        
                        postsHtml += '</div>';
                        postsHtml += '</div>';
                    });
                } else {
                    postsHtml = '<div class="k3e-no-posts">Brak wpisów w tym dniu.</div>';
                }
                
                $('#k3e-modal-title').text(modalTitle);
                $('#k3e-modal-posts-list').html(postsHtml);
                $('#k3e-posts-modal').fadeIn(300);
                
                $('body').addClass('k3e-modal-open');
            };
            
            window.closePostsModal = function() {
                $('#k3e-posts-modal').fadeOut(300);
                $('body').removeClass('k3e-modal-open');
            };
            
            // AJAX function
            window.loadCalendar = function(month, year) {
                var $calendarContainer = $('#k3e-post-book-widget .k3e-calendar-container');
                var $loading = $('#k3e-post-book-widget .k3e-loading');
                
                $loading.show();
                $calendarContainer.hide();
                
                $.ajax({
                    url: k3ePostBook.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'k3e_get_calendar_data',
                        month: month,
                        year: year,
                        nonce: k3ePostBook.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            currentMonth = response.data.month;
                            currentYear = response.data.year;
                            
                            $('#k3e-month-select').val(currentMonth);
                            $('#k3e-year-select').val(currentYear);
                            
                            $loading.hide();
                            $calendarContainer.html(response.data.calendar).show();
                            
                            addEventListeners();
                            
                        } else {
                            $loading.hide();
                            $calendarContainer.html('<div class="k3e-error"><p>Błąd ładowania kalendarza.</p></div>').show();
                        }
                    },
                    error: function() {
                        $loading.hide();
                        $calendarContainer.html('<div class="k3e-error"><p>Błąd połączenia.</p></div>').show();
                    }
                });
            };
            
            // Inicjalizacja
            setTimeout(function() {
                addEventListeners();
            }, 200);
        });
    }
    
    // Event listeners
    function addEventListeners() {
        removeEventListeners();
        
        // Submit button
        var submitBtn = document.getElementById('k3e-date-submit');
        if (submitBtn) {
            submitBtn.addEventListener('click', handleSubmitClick);
        }
        
        // Enter w selectach
        var monthSelect = document.getElementById('k3e-month-select');
        var yearSelect = document.getElementById('k3e-year-select');
        if (monthSelect) monthSelect.addEventListener('keydown', handleSelectKeydown);
        if (yearSelect) yearSelect.addEventListener('keydown', handleSelectKeydown);
        
        // Kliknięcia w dni z wpisami
        var hasPostsElements = document.querySelectorAll('.k3e-has-posts');
        hasPostsElements.forEach(function(element) {
            element.addEventListener('click', handleDayClick);
        });
        
        // Modal close
        var modalClose = document.querySelector('.k3e-modal-close');
        var modalOverlay = document.querySelector('.k3e-modal-overlay');
        if (modalClose) modalClose.addEventListener('click', handleModalClose);
        if (modalOverlay) modalOverlay.addEventListener('click', handleModalClose);
        
        // Escape key
        document.addEventListener('keydown', handleKeydown);
    }
    
    function removeEventListeners() {
        var submitBtn = document.getElementById('k3e-date-submit');
        if (submitBtn) submitBtn.removeEventListener('click', handleSubmitClick);
        
        var monthSelect = document.getElementById('k3e-month-select');
        var yearSelect = document.getElementById('k3e-year-select');
        if (monthSelect) monthSelect.removeEventListener('keydown', handleSelectKeydown);
        if (yearSelect) yearSelect.removeEventListener('keydown', handleSelectKeydown);
        
        var hasPostsElements = document.querySelectorAll('.k3e-has-posts');
        hasPostsElements.forEach(function(element) {
            element.removeEventListener('click', handleDayClick);
        });
        
        var modalClose = document.querySelector('.k3e-modal-close');
        var modalOverlay = document.querySelector('.k3e-modal-overlay');
        if (modalClose) modalClose.removeEventListener('click', handleModalClose);
        if (modalOverlay) modalOverlay.removeEventListener('click', handleModalClose);
        
        document.removeEventListener('keydown', handleKeydown);
    }
    
    // Event handlers
    function handleSubmitClick(e) {
        e.preventDefault();
        
        var newMonth = parseInt(document.getElementById('k3e-month-select').value);
        var newYear = parseInt(document.getElementById('k3e-year-select').value);
        
        if (newMonth !== currentMonth || newYear !== currentYear) {
            currentMonth = newMonth;
            currentYear = newYear;
            window.loadCalendar(currentMonth, currentYear);
        }
    }
    
    function handleSelectKeydown(e) {
        if (e.which === 13 || e.keyCode === 13) {
            e.preventDefault();
            var submitBtn = document.getElementById('k3e-date-submit');
            if (submitBtn) submitBtn.click();
        }
    }
    
    function handleDayClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var dayElement = this.querySelector('.k3e-day-number');
        var day = dayElement ? dayElement.textContent : '';
        var postsData = this.getAttribute('data-tooltip');
        
        if (postsData) {
            try {
                postsData = postsData.replace(/&quot;/g, '"').replace(/&amp;/g, '&');
                var posts = JSON.parse(postsData);
                window.openPostsModal(day, currentMonth, currentYear, posts);
            } catch(error) {
                // Silent fail
            }
        }
    }
    
    function handleModalClose(e) {
        e.preventDefault();
        window.closePostsModal();
    }
    
    function handleKeydown(e) {
        if (e.which === 27 || e.keyCode === 27) {
            var modal = document.getElementById('k3e-posts-modal');
            if (modal && modal.style.display !== 'none') {
                window.closePostsModal();
            }
        }
    }
    
})();