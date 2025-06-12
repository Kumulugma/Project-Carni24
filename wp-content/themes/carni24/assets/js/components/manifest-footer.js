/**
 * JavaScript dla sekcji Manifest i Footer
 * Plik: assets/js/components/manifest-footer.js
 * Autor: Carni24 Team
 */

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        initManifestAnimations();
        initFooterFunctionality();
        initNewsletterForms();
        initBackToTop();
        initScrollAnimations();
    });

    // ===== ANIMACJE SEKCJI MANIFEST ===== //
    function initManifestAnimations() {
        const manifestSection = document.querySelector('#manifest');
        if (!manifestSection) return;

        // Intersection Observer dla animacji
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const manifestObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('aos-animate');
                    }, index * 100);
                }
            });
        }, observerOptions);

        // Obserwuj artykuły
        const manifestArticles = document.querySelectorAll('.manifest-article[data-aos]');
        manifestArticles.forEach(article => {
            manifestObserver.observe(article);
        });

        // Animacja licznika wyświetleń
        animateCounters();
    }

    // ===== ANIMACJA LICZNIKÓW ===== //
    function animateCounters() {
        const badges = document.querySelectorAll('.manifest-badge');
        
        badges.forEach(badge => {
            const targetValue = parseInt(badge.textContent.replace(/[^\d]/g, ''));
            if (isNaN(targetValue)) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateValue(badge, 0, targetValue, 1500);
                        observer.unobserve(entry.target);
                    }
                });
            });

            observer.observe(badge);
        });
    }

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
            
            const icon = element.querySelector('i');
            const iconClass = icon ? icon.outerHTML : '';
            element.innerHTML = iconClass + ' ' + Math.floor(current).toLocaleString();
        }, 16);
    }

    // ===== FUNKCJONALNOŚĆ FOOTER ===== //
    function initFooterFunctionality() {
        // Animacje hover dla social links
        const socialLinks = document.querySelectorAll('.social-link');
        socialLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px) scale(1.1)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Parallax effect dla background elements
        initFooterParallax();
    }

    function initFooterParallax() {
        const bgElements = document.querySelectorAll('.bg-element');
        if (bgElements.length === 0) return;

        let ticking = false;

        function updateParallax() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;

            bgElements.forEach((element, index) => {
                const speed = 0.5 + (index * 0.2);
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px) rotate(${scrolled * 0.1}deg)`;
            });

            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }

        // Throttle scroll events
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(requestTick, 10);
        });
    }

    // ===== FORMULARZE NEWSLETTER ===== //
    function initNewsletterForms() {
        const newsletterForms = document.querySelectorAll('.newsletter-form, .newsletter-form-footer');
        
        newsletterForms.forEach(form => {
            form.addEventListener('submit', handleNewsletterSubmit);
        });
    }

    function handleNewsletterSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const emailInput = form.querySelector('input[type="email"]');
        const submitBtn = form.querySelector('button[type="submit"]');
        const email = emailInput.value.trim();

        // Walidacja email
        if (!isValidEmail(email)) {
            showMessage('Podaj prawidłowy adres email', 'error', form);
            return;
        }

        // Disable button and show loading
        const originalBtnContent = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Wysyłanie...';

        // Symulacja wysyłania (tu powinna być integracja z rzeczywistym API)
        setTimeout(() => {
            // Success simulation
            showMessage('Dziękujemy! Sprawdź swoją skrzynkę mailową.', 'success', form);
            emailInput.value = '';
            
            // Restore button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnContent;
        }, 2000);
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showMessage(message, type, container) {
        // Remove existing messages
        const existingMessage = container.querySelector('.newsletter-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Create new message
        const messageEl = document.createElement('div');
        messageEl.className = `newsletter-message alert alert-${type === 'error' ? 'danger' : 'success'} mt-3`;
        messageEl.innerHTML = `
            <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2"></i>
            ${message}
        `;

        container.appendChild(messageEl);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageEl.parentNode) {
                messageEl.remove();
            }
        }, 5000);
    }

    // ===== BACK TO TOP - PŁYWAJĄCY PRZYCISK ===== //
    function initBackToTop() {
        const backToTopBtn = document.getElementById('backToTop');
        if (!backToTopBtn) return;

        // Show/hide on scroll
        let isVisible = false;

        function toggleBackToTop() {
            const scrolled = window.pageYOffset;
            const shouldShow = scrolled > 300;

            if (shouldShow && !isVisible) {
                backToTopBtn.classList.add('visible');
                isVisible = true;
            } else if (!shouldShow && isVisible) {
                backToTopBtn.classList.remove('visible');
                isVisible = false;
            }
        }

        // Throttled scroll listener
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(toggleBackToTop, 100);
        });

        // Smooth scroll to top
        backToTopBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const duration = 800;
            const start = window.pageYOffset;
            const startTime = performance.now();

            function animateScroll(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function
                const easeInOutCubic = progress < 0.5 
                    ? 4 * progress * progress * progress 
                    : (progress - 1) * (2 * progress - 2) * (2 * progress - 2) + 1;

                window.scrollTo(0, start * (1 - easeInOutCubic));

                if (progress < 1) {
                    requestAnimationFrame(animateScroll);
                }
            }

            requestAnimationFrame(animateScroll);
        });
    }

    // ===== SCROLL ANIMATIONS ===== //
    function initScrollAnimations() {
        // Fade in elements on scroll
        const fadeElements = document.querySelectorAll('.footer-section');
        
        const fadeObserver = new IntersectionObserver((entries) => {
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

        fadeElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            fadeObserver.observe(element);
        });
    }

    // ===== UTILITIES ===== //
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

    // ===== ERROR HANDLING ===== //
    window.addEventListener('error', function(e) {
        if (e.filename && e.filename.includes('manifest-footer.js')) {
            console.warn('Manifest/Footer JS Error:', e.message);
        }
    });

    // ===== PERFORMANCE MONITORING ===== //
    if (typeof performance !== 'undefined' && performance.mark) {
        performance.mark('manifest-footer-js-end');
        
        window.addEventListener('load', function() {
            setTimeout(() => {
                try {
                    performance.measure('manifest-footer-js-duration', 'manifest-footer-js-start', 'manifest-footer-js-end');
                } catch (e) {
                    // Ignore measurement errors
                }
            }, 0);
        });
    }

})();