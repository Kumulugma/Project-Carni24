/**
 * Species Archive JavaScript
 * wp-content/themes/carni24/assets/js/species-archive.js
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== VIEW TOGGLE FUNCTIONALITY =====
    const viewButtons = document.querySelectorAll('[data-view]');
    const speciesGrid = document.getElementById('speciesGrid');
    
    if (viewButtons.length > 0 && speciesGrid) {
        // Initialize view from localStorage or default to grid
        const savedView = localStorage.getItem('species-view') || 'grid';
        setView(savedView);
        
        viewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const view = this.getAttribute('data-view');
                setView(view);
                
                // Save preference
                localStorage.setItem('species-view', view);
                
                // Analytics event (if available)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'view_change', {
                        'event_category': 'species_archive',
                        'event_label': view
                    });
                }
            });
        });
    }
    
    function setView(view) {
        if (!speciesGrid) return;
        
        // Update grid data attribute
        speciesGrid.setAttribute('data-view', view);
        
        // Update button states
        viewButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-view') === view) {
                btn.classList.add('active');
            }
        });
        
        // Force layout recalculation for better browser compatibility
        speciesGrid.style.display = 'none';
        speciesGrid.offsetHeight; // Trigger reflow
        speciesGrid.style.display = '';
    }
    
    // ===== SORT FUNCTIONALITY =====
    const sortSelect = document.getElementById('species-sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const orderby = this.value;
            const url = new URL(window.location);
            
            // Update URL parameter
            if (orderby === 'date') {
                url.searchParams.delete('orderby');
            } else {
                url.searchParams.set('orderby', orderby);
            }
            
            // Remove page parameter when sorting
            url.searchParams.delete('paged');
            
            // Show loading state
            showLoadingState();
            
            // Navigate to new URL
            window.location.href = url.toString();
        });
    }
    
    // ===== LOADING STATES =====
    function showLoadingState() {
        if (speciesGrid) {
            speciesGrid.style.opacity = '0.5';
            speciesGrid.style.pointerEvents = 'none';
        }
        
        if (sortSelect) {
            sortSelect.disabled = true;
        }
        
        // Add loading spinner if desired
        const loadingSpinner = document.createElement('div');
        loadingSpinner.className = 'loading-spinner';
        loadingSpinner.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Ładowanie...';
        loadingSpinner.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #16a34a;
        `;
        
        document.body.appendChild(loadingSpinner);
        
        // Add spin animation
        const style = document.createElement('style');
        style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // ===== SMOOTH ANIMATIONS =====
    function initializeAnimations() {
        const cards = document.querySelectorAll('.species-card');
        
        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100); // Stagger animation
                    
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    }
    
    // Initialize animations after page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeAnimations);
    } else {
        initializeAnimations();
    }
    
    
    // ===== RESPONSIVE BEHAVIOR =====
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Force grid recalculation on resize
            if (speciesGrid) {
                const currentDisplay = speciesGrid.style.display;
                speciesGrid.style.display = 'none';
                speciesGrid.offsetHeight;
                speciesGrid.style.display = currentDisplay;
            }
        }, 250);
    });
    
    // ===== URL PARAMETER HANDLING =====
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }
    
    // Set initial sort value from URL
    const initialSort = getUrlParameter('orderby');
    if (initialSort && sortSelect) {
        sortSelect.value = initialSort;
    }
    
    // ===== ACCESSIBILITY IMPROVEMENTS =====
    function improveAccessibility() {
        // Add ARIA labels to view buttons
        viewButtons.forEach(btn => {
            const view = btn.getAttribute('data-view');
            const label = view === 'grid' ? 'Widok siatki' : 'Widok listy';
            btn.setAttribute('aria-label', label);
            btn.setAttribute('role', 'button');
        });
        
        // Add ARIA live region for sort changes
        if (sortSelect) {
            sortSelect.setAttribute('aria-label', 'Sortowanie gatunków');
        }
        
        // Add keyboard navigation hints
        const keyboardHints = document.createElement('div');
        keyboardHints.className = 'sr-only';
        keyboardHints.innerHTML = 'Skróty klawiszowe: V - zmiana widoku, S - focus na sortowanie';
        document.body.appendChild(keyboardHints);
    }
    
    improveAccessibility();
    
    // ===== PERFORMANCE OPTIMIZATION =====
    // Lazy loading for images (if not handled by WordPress)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // ===== ERROR HANDLING =====
    window.addEventListener('error', function(e) {
        console.error('Species Archive Error:', e.error);
        
        // Remove loading states on error
        if (speciesGrid) {
            speciesGrid.style.opacity = '';
            speciesGrid.style.pointerEvents = '';
        }
        
        if (sortSelect) {
            sortSelect.disabled = false;
        }
        
        // Remove loading spinner
        const spinner = document.querySelector('.loading-spinner');
        if (spinner) {
            spinner.remove();
        }
    });
    
    console.log('Species Archive JavaScript initialized');
});