/**
 * Front Page JavaScript
 * Plik: assets/js/pages/front-page.js
 * Autor: Carni24 Team
 * Wersja: 3.0.0
 */

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        initCarouselEnhancements();
        initSmoothScrolling();
        initSearchBoxAnimations();
        initStatsCounter();
        initParallaxEffects();
    });

    // ===== ENHANCED CAROUSEL FUNCTIONALITY ===== //
    function initCarouselEnhancements() {
        const carousel = document.getElementById('heroSlider');
        if (!carousel) return;

        // Pause carousel on hover
        carousel.addEventListener('mouseenter', function() {
            if (window.bootstrap && window.bootstrap.Carousel) {
                const carouselInstance = window.bootstrap.Carousel.getInstance(carousel);
                if (carouselInstance) {
                    carouselInstance.pause();
                }
            }
        });
        
        // Resume carousel on mouse leave
        carousel.addEventListener('mouseleave', function() {
            if (window.bootstrap && window.bootstrap.Carousel) {
                const carouselInstance = window.bootstrap.Carousel.getInstance(carousel);
                if (carouselInstance) {
                    carouselInstance.cycle();
                }
            }
        });
        
        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Only handle if carousel is visible and focused
            if (!isElementInViewport(carousel)) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    const prevBtn = carousel.querySelector('.carousel-control-prev');
                    if (prevBtn) prevBtn.click();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    const nextBtn = carousel.querySelector('.carousel-control-next');
                    if (nextBtn) nextBtn.click();
                    break;
                case ' ':
                    // Spacebar pauses/resumes carousel
                    e.preventDefault();
                    const carouselInstance = window.bootstrap?.Carousel?.getInstance(carousel);
                    if (carouselInstance) {
                        const isPaused = carousel.classList.contains('paused');
                        if (isPaused) {
                            carouselInstance.cycle();
                            carousel.classList.remove('paused');
                        } else {
                            carouselInstance.pause();
                            carousel.classList.add('paused');
                        }
                    }
                    break;
            }
        });

        // Touch/swipe support enhancement
        initTouchSupport(carousel);
        
        // Preload next images
        preloadCarouselImages(carousel);
        
        // Auto-pause when page is not visible
        initVisibilityPause(carousel);
    }

    // ===== TOUCH/SWIPE SUPPORT ===== //
    function initTouchSupport(carousel) {
        let startX = 0;
        let endX = 0;
        let startY = 0;
        let endY = 0;
        const threshold = 50; // Minimum swipe distance
        const maxVerticalMovement = 100; // Max vertical movement to still count as horizontal swipe

        carousel.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, { passive: true });

        carousel.addEventListener('touchmove', function(e) {
            // Prevent default only if it's a horizontal swipe
            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const deltaX = Math.abs(currentX - startX);
            const deltaY = Math.abs(currentY - startY);
            
            if (deltaX > deltaY && deltaX > 10) {
                e.preventDefault();
            }
        }, { passive: false });

        carousel.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const deltaX = startX - endX;
            const deltaY = Math.abs(startY - endY);
            
            // Check if it's a valid horizontal swipe
            if (Math.abs(deltaX) < threshold || deltaY > maxVerticalMovement) return;

            if (deltaX > 0) {
                // Swipe left - next slide
                const nextBtn = carousel.querySelector('.carousel-control-next');
                if (nextBtn) nextBtn.click();
            } else {
                // Swipe right - previous slide
                const prevBtn = carousel.querySelector('.carousel-control-prev');
                if (prevBtn) prevBtn.click();
            }
        }
    }

    // ===== PRELOAD CAROUSEL IMAGES ===== //
    function preloadCarouselImages(carousel) {
        const slides = carousel.querySelectorAll('.carousel-item .hero-slide');
        slides.forEach((slide, index) => {
            const bgImage = window.getComputedStyle(slide).backgroundImage;
            if (bgImage && bgImage !== 'none') {
                const imageUrl = bgImage.slice(4, -1).replace(/"/g, "");
                const img = new Image();
                img.src = imageUrl;
            }
        });
    }

    // ===== VISIBILITY PAUSE ===== //
    function initVisibilityPause(carousel) {
        document.addEventListener('visibilitychange', function() {
            const carouselInstance = window.bootstrap?.Carousel?.getInstance(carousel);
            if (!carouselInstance) return;

            if (document.hidden) {
                carouselInstance.pause();
            } else if (!carousel.classList.contains('paused')) {
                carouselInstance.cycle();
            }
        });
    }

    // ===== SMOOTH SCROLLING ===== //
    function initSmoothScrolling() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    
                    const headerOffset = 80; // Account for fixed header
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // ===== SEARCH BOX ANIMATIONS ===== //
    function initSearchBoxAnimations() {
        const searchBoxes = document.querySelectorAll('.search-box');
        
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-in');
                    }, index * 100);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        searchBoxes.forEach(box => {
            observer.observe(box);
            
            // Add hover effects
            box.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            box.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // ===== STATS COUNTER ANIMATION ===== //
    function initStatsCounter() {
        const statNumbers = document.querySelectorAll('.stat-number, .search-count');
        
        function animateValue(element, start, end, duration) {
            const range = end - start;
            const increment = range / (duration / 16);
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= end) {
                    current = end;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 16);
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    entry.target.classList.add('animated');
                    
                    const finalValue = parseInt(entry.target.textContent.replace(/\D/g, ''));
                    if (finalValue && !isNaN(finalValue)) {
                        entry.target.textContent = '0';
                        animateValue(entry.target, 0, finalValue, 2000);
                    }
                }
            });
        }, { threshold: 0.5 });

        statNumbers.forEach(stat => {
            observer.observe(stat);
        });
    }

    // ===== PARALLAX EFFECTS ===== //
    function initParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.hero-slide');
        
        if (parallaxElements.length === 0) return;
        
        let ticking = false;

        function updateParallax() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;

            parallaxElements.forEach(element => {
                if (isElementInViewport(element)) {
                    element.style.transform = `translateY(${rate}px)`;
                }
            });

            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }

        // Throttled scroll event
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(requestTick, 10);
        }, { passive: true });
    }

    // ===== UTILITY FUNCTIONS ===== //
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
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

    // ===== LAZY LOADING ENHANCEMENT ===== //
    function initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Initialize lazy loading
    initLazyLoading();

    // ===== PERFORMANCE MONITORING ===== //
    function initPerformanceMonitoring() {
        if (typeof performance !== 'undefined' && performance.mark) {
            performance.mark('frontpage-js-end');
            
            window.addEventListener('load', function() {
                setTimeout(() => {
                    try {
                        performance.measure('frontpage-js-duration', 'frontpage-js-start', 'frontpage-js-end');
                        const measures = performance.getEntriesByName('frontpage-js-duration');
                        if (measures.length > 0) {
                            console.log('Front Page JS Load Time:', measures[0].duration + 'ms');
                        }
                    } catch (e) {
                        // Ignore measurement errors
                    }
                }, 0);
            });
        }
    }

    // Initialize performance monitoring in development
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
        initPerformanceMonitoring();
    }

    // ===== ERROR HANDLING ===== //
    window.addEventListener('error', function(e) {
        if (e.filename && e.filename.includes('front-page.js')) {
            console.warn('Front Page JS Error:', e.message);
        }
    });

    // ===== ACCESSIBILITY ENHANCEMENTS ===== //
    function initAccessibilityEnhancements() {
        // Skip to content link
        const skipLink = document.createElement('a');
        skipLink.href = '#main';
        skipLink.textContent = 'Skip to main content';
        skipLink.className = 'skip-link';
        skipLink.style.cssText = `
            position: absolute;
            top: -40px;
            left: 6px;
            background: #000;
            color: #fff;
            padding: 8px;
            text-decoration: none;
            z-index: 100;
            transition: top 0.3s;
        `;
        
        skipLink.addEventListener('focus', function() {
            this.style.top = '6px';
        });
        
        skipLink.addEventListener('blur', function() {
            this.style.top = '-40px';
        });
        
        document.body.insertBefore(skipLink, document.body.firstChild);

        // Announce carousel changes to screen readers
        const carousel = document.getElementById('heroSlider');
        if (carousel) {
            carousel.addEventListener('slid.bs.carousel', function(e) {
                const announcement = document.createElement('div');
                announcement.setAttribute('aria-live', 'polite');
                announcement.setAttribute('aria-atomic', 'true');
                announcement.className = 'sr-only';
                announcement.textContent = `Slide ${e.to + 1} of ${e.relatedTarget.parentElement.children.length}`;
                
                document.body.appendChild(announcement);
                setTimeout(() => document.body.removeChild(announcement), 1000);
            });
        }
    }

    // Initialize accessibility features
    initAccessibilityEnhancements();

})();