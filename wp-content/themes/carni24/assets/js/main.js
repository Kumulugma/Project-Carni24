/**
 * Główny plik JavaScript dla motywu Carni24
 * Plik: assets/js/main.js
 * Autor: Carni24 Team
 */

(function() {
    'use strict';
    
    // ===== INICJALIZACJA ===== //
    document.addEventListener('DOMContentLoaded', function() {
        initSearchOverlay();
        initScrollAnimations();
        initSmoothScrolling();
        initAccessibility();
        console.log('Carni24 Theme loaded successfully');
    });
    
    // ===== SEARCH OVERLAY ===== //
    function initSearchOverlay() {
        const searchTriggers = document.querySelectorAll('.search-trigger-btn');
        const searchOverlay = document.querySelector('.search-overlay');
        const searchClose = document.querySelector('.search-overlay .search-close');
        const searchInput = document.querySelector('.search-overlay .search-input');
        
        if (!searchOverlay) return;
        
        // Otwórz overlay
        searchTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                openSearchOverlay();
            });
        });
        
        // Zamknij overlay
        if (searchClose) {
            searchClose.addEventListener('click', closeSearchOverlay);
        }
        
        // Zamknij overlay przy kliknięciu tła
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                closeSearchOverlay();
            }
        });
        
        // Zamknij overlay przy ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                closeSearchOverlay();
            }
        });
        
        function openSearchOverlay() {
            searchOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 300);
            }
        }
        
        function closeSearchOverlay() {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
            if (searchInput) {
                searchInput.value = '';
            }
        }
    }
    
    // ===== SCROLL ANIMATIONS ===== //
    function initScrollAnimations() {
        // Intersection Observer dla animacji scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                }
            });
        }, observerOptions);
        
        // Obserwuj elementy z klasami animacji scroll
        const animatedElements = document.querySelectorAll('.scroll-fade, .scroll-slide-left, .scroll-slide-right, .fade-in-up, .fade-in-left, .fade-in-right');
        
        animatedElements.forEach(el => {
            observer.observe(el);
        });
        
        // Staggered animations dla kontenerów
        const staggerContainers = document.querySelectorAll('.stagger-fade-in');
        staggerContainers.forEach(container => {
            observer.observe(container);
        });
    }
    
    // ===== SMOOTH SCROLLING ===== //
    function initSmoothScrolling() {
        // Smooth scroll dla linków z hash
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href^="#"]');
            if (!link) return;
            
            const targetId = link.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (!targetElement) return;
            
            e.preventDefault();
            
            const headerHeight = document.querySelector('#sub-menu')?.offsetHeight || 0;
            const targetPosition = targetElement.offsetTop - headerHeight - 20;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
            
            // Dodaj focus do elementu docelowego (accessibility)
            targetElement.setAttribute('tabindex', '-1');
            targetElement.focus();
        });
    }
    
    // ===== ACCESSIBILITY ===== //
    function initAccessibility() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-to-content');
        if (skipLink) {
            skipLink.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.setAttribute('tabindex', '-1');
                    target.focus();
                }
            });
        }
        
        // Keyboard navigation dla dropdown menu (mobile)
        const navToggle = document.querySelector('.navbar-toggler');
        const navCollapse = document.querySelector('.navbar-collapse');
        
        if (navToggle && navCollapse) {
            navToggle.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        }
        
        // Focus management dla modal overlay
        document.addEventListener('keydown', function(e) {
            const activeModal = document.querySelector('.search-overlay.active');
            if (!activeModal) return;
            
            if (e.key === 'Tab') {
                trapFocus(activeModal, e);
            }
        });
    }
    
    // ===== HELPER FUNCTIONS ===== //
    
    // Focus trap dla modal
    function trapFocus(container, event) {
        const focusableElements = container.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (event.shiftKey) {
            if (document.activeElement === firstElement) {
                lastElement.focus();
                event.preventDefault();
            }
        } else {
            if (document.activeElement === lastElement) {
                firstElement.focus();
                event.preventDefault();
            }
        }
    }
    
    // Debounce function
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
    
    // Throttle function
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
    
    // ===== LAZY LOADING ===== //
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }
    
    // ===== PARALLAX EFFECT ===== //
    function initParallax() {
        const parallaxElements = document.querySelectorAll('.parallax');
        if (parallaxElements.length === 0) return;
        
        const handleParallax = throttle(() => {
            const scrollTop = window.pageYOffset;
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.parallaxSpeed || 0.5;
                const yPos = -(scrollTop * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
        }, 10);
        
        window.addEventListener('scroll', handleParallax);
    }
    
    // ===== FORM ENHANCEMENTS ===== //
    function initFormEnhancements() {
        // Floating labels
        const formInputs = document.querySelectorAll('.form-floating input, .form-floating textarea');
        
        formInputs.forEach(input => {
            // Check initial state
            toggleFloatingLabel(input);
            
            // Listen for changes
            input.addEventListener('blur', () => toggleFloatingLabel(input));
            input.addEventListener('focus', () => toggleFloatingLabel(input));
            input.addEventListener('input', () => toggleFloatingLabel(input));
        });
        
        function toggleFloatingLabel(input) {
            const hasValue = input.value.trim() !== '';
            const label = input.nextElementSibling;
            
            if (hasValue || input === document.activeElement) {
                label?.classList.add('active');
            } else {
                label?.classList.remove('active');
            }
        }
    }

    
    // ===== GLOBAL UTILITIES ===== //
    
    // Expose utilities globally
    window.Carni24 = {
        debounce,
        throttle,
        trapFocus
    };
    
    // Performance monitoring
    if (window.performance) {
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loadTime = window.performance.timing.loadEventEnd - window.performance.timing.navigationStart;
                console.log(`Page loaded in ${loadTime}ms`);
            }, 0);
        });
    }
    
})();