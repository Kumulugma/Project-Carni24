/**
 * JavaScript dla strony głównej
 * Plik: assets/js/pages/homepage.js
 * Autor: Carni24 Team
 */

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        initHomepageAnimations();
        initScrollEffects();
        initSearchBoxInteractions();
    });
    
    // ===== ANIMACJE STRONY GŁÓWNEJ ===== //
    function initHomepageAnimations() {
        // Animate search boxes on load
        const searchBoxes = document.querySelectorAll('.search-box');
        
        searchBoxes.forEach((box, index) => {
            box.style.animationDelay = `${(index + 1) * 0.1}s`;
        });
        
        // Animate news and manifest articles
        const articles = document.querySelectorAll('.news-article, .manifest-article');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        articles.forEach(article => {
            article.style.opacity = '0';
            article.style.transform = 'translateY(30px)';
            article.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(article);
        });
    }
    
    // ===== EFEKTY SCROLL ===== //
    function initScrollEffects() {
        const sections = ['#news', '#manifest'];
        let isScrolling = false;
        
        const handleScroll = () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    updateSectionOpacity();
                    isScrolling = false;
                });
                isScrolling = true;
            }
        };
        
        function updateSectionOpacity() {
            const windowHeight = window.innerHeight;
            const scrollTop = window.pageYOffset;
            
            sections.forEach(sectionId => {
                const section = document.querySelector(sectionId);
                if (!section) return;
                
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                const sectionBottom = sectionTop + sectionHeight;
                
                // Calculate visibility percentage
                let visibility = 0;
                
                if (scrollTop + windowHeight > sectionTop && scrollTop < sectionBottom) {
                    const visibleHeight = Math.min(
                        scrollTop + windowHeight - sectionTop,
                        sectionHeight,
                        sectionBottom - scrollTop
                    );
                    visibility = Math.max(0.3, visibleHeight / (windowHeight * 0.8));
                }
                
                if (visibility > 0) {
                    visibility = Math.min(1, visibility);
                }
                
                section.style.opacity = visibility;
            });
        }
        
        // Throttle scroll events
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(handleScroll);
                ticking = true;
                setTimeout(() => { ticking = false; }, 16);
            }
        });
        
        // Initial call
        updateSectionOpacity();
    }
    
    // ===== INTERAKCJE SEARCH BOX ===== //
    function initSearchBoxInteractions() {
        const searchBoxes = document.querySelectorAll('.search-box');
        
        searchBoxes.forEach(box => {
            // Hover effect enhancement
            box.addEventListener('mouseenter', function() {
                const droplet = this.querySelector('.droplet');
                if (droplet) {
                    droplet.style.transform = 'scale(1.1) rotate(10deg)';
                }
            });
            
            box.addEventListener('mouseleave', function() {
                const droplet = this.querySelector('.droplet');
                if (droplet) {
                    droplet.style.transform = 'scale(1) rotate(0deg)';
                }
            });
            
            // Click animation
            box.addEventListener('click', function(e) {
                // Create ripple effect
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(40, 167, 69, 0.2);
                    border-radius: 50%;
                    transform: scale(0);
                    pointer-events: none;
                    z-index: 1;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                // Animate ripple
                requestAnimationFrame(() => {
                    ripple.style.transition = 'transform 0.6s ease-out, opacity 0.6s ease-out';
                    ripple.style.transform = 'scale(2)';
                    ripple.style.opacity = '0';
                });
                
                // Remove ripple after animation
                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.parentNode.removeChild(ripple);
                    }
                }, 600);
            });
        });
    }
    
    // ===== COUNTER ANIMATIONS ===== //
    function initCounterAnimations() {
        const counters = document.querySelectorAll('.search-count');
        
        const animateCounter = (counter) => {
            const target = parseInt(counter.textContent);
            const increment = target / 100;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current);
            }, 20);
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    entry.target.classList.add('animated');
                    animateCounter(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    }
    
    // ===== PARALLAX EFFECTS ===== //
    function initParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.news-img, .manifest-img');
        
        if (parallaxElements.length === 0) return;
        
        const handleParallax = () => {
            const scrollTop = window.pageYOffset;
            
            parallaxElements.forEach(element => {
                const rect = element.getBoundingClientRect();
                const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
                
                if (isVisible) {
                    const speed = 0.3;
                    const yPos = -(scrollTop - element.offsetTop) * speed;
                    element.style.transform = `translateY(${yPos}px)`;
                }
            });
        };
        
        // Throttled scroll handler
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    handleParallax();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }
    
    // ===== PERFORMANCE OPTIMIZATION ===== //
    
    // Lazy load images that are not immediately visible
    function initLazyImages() {
        const images = document.querySelectorAll('.news-img, .manifest-img');
        
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const bgImage = element.style.backgroundImage;
                    
                    if (bgImage && bgImage.includes('data:image')) {
                        // Replace placeholder with actual image
                        const actualSrc = element.dataset.bg;
                        if (actualSrc) {
                            element.style.backgroundImage = `url(${actualSrc})`;
                        }
                    }
                    
                    imageObserver.unobserve(element);
                }
            });
        }, {
            rootMargin: '50px'
        });
        
        images.forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Initialize lazy loading if supported
    if ('IntersectionObserver' in window) {
        initLazyImages();
        initCounterAnimations();
    }
    
    // Initialize parallax only on larger screens
    if (window.innerWidth > 768 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        initParallaxEffects();
    }
    
})();