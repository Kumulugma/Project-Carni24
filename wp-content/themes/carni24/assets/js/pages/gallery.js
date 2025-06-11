/**
 * JavaScript dla galerii
 * Plik: assets/js/pages/gallery.js
 * Autor: Carni24 Team
 */

(function() {
    'use strict';
    
    let currentImageIndex = 0;
    let galleryImages = [];
    let isLightboxOpen = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        initGalleryFilters();
        initLightbox();
        initLoadMore();
        initMasonry();
        initImageLazyLoading();
    });
    
    // ===== FILTRY GALERII ===== //
    function initGalleryFilters() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const searchInput = document.querySelector('.search-gallery input');
        const galleryGrid = document.querySelector('.gallery-grid');
        
        if (!filterButtons.length || !galleryGrid) return;
        
        // Filter by category
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter images
                filterGalleryItems(filter);
            });
        });
        
        // Search functionality
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase();
                    searchGalleryItems(searchTerm);
                }, 300);
            });
        }
        
        function filterGalleryItems(filter) {
            const items = document.querySelectorAll('.gallery-item');
            
            items.forEach(item => {
                const category = item.dataset.category;
                
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    
                    setTimeout(() => {
                        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
            
            // Update masonry layout after filtering
            setTimeout(() => {
                updateMasonryLayout();
            }, 350);
        }
        
        function searchGalleryItems(searchTerm) {
            const items = document.querySelectorAll('.gallery-item');
            
            items.forEach(item => {
                const title = item.querySelector('.gallery-title-item')?.textContent.toLowerCase() || '';
                const caption = item.querySelector('.gallery-caption')?.textContent.toLowerCase() || '';
                
                if (title.includes(searchTerm) || caption.includes(searchTerm) || searchTerm === '') {
                    item.style.display = 'block';
                    item.style.opacity = '1';
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        }
    }
    
    // ===== LIGHTBOX ===== //
    function initLightbox() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        const lightbox = document.querySelector('.gallery-lightbox');
        
        if (!lightbox) {
            createLightbox();
        }
        
        // Zbierz wszystkie obrazy
        galleryImages = Array.from(galleryItems).map((item, index) => {
            const img = item.querySelector('.gallery-image');
            const title = item.querySelector('.gallery-title-item')?.textContent || '';
            const caption = item.querySelector('.gallery-caption')?.textContent || '';
            
            return {
                src: img.src || img.dataset.src,
                title: title,
                caption: caption,
                index: index
            };
        });
        
        // Add click handlers
        galleryItems.forEach((item, index) => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                openLightbox(index);
            });
            
            // Keyboard navigation
            item.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openLightbox(index);
                }
            });
        });
        
        // Lightbox controls
        document.addEventListener('keydown', handleLightboxKeyboard);
    }
    
    function createLightbox() {
        const lightboxHTML = `
            <div class="gallery-lightbox">
                <div class="lightbox-content">
                    <div class="lightbox-counter"></div>
                    <button class="lightbox-close" aria-label="Zamknij lightbox">
                        <i class="bi bi-x"></i>
                    </button>
                    <button class="lightbox-nav lightbox-prev" aria-label="Poprzedni obraz">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="lightbox-nav lightbox-next" aria-label="Następny obraz">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <img class="lightbox-image" alt="">
                    <div class="lightbox-info">
                        <div class="lightbox-title"></div>
                        <div class="lightbox-caption"></div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', lightboxHTML);
        
        // Add event listeners
        const lightbox = document.querySelector('.gallery-lightbox');
        const closeBtn = lightbox.querySelector('.lightbox-close');
        const prevBtn = lightbox.querySelector('.lightbox-prev');
        const nextBtn = lightbox.querySelector('.lightbox-next');
        
        closeBtn.addEventListener('click', closeLightbox);
        prevBtn.addEventListener('click', () => navigateLightbox('prev'));
        nextBtn.addEventListener('click', () => navigateLightbox('next'));
        
        // Close on background click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }
    
    function openLightbox(index) {
        currentImageIndex = index;
        isLightboxOpen = true;
        
        const lightbox = document.querySelector('.gallery-lightbox');
        const image = lightbox.querySelector('.lightbox-image');
        const title = lightbox.querySelector('.lightbox-title');
        const caption = lightbox.querySelector('.lightbox-caption');
        const counter = lightbox.querySelector('.lightbox-counter');
        
        const currentImage = galleryImages[currentImageIndex];
        
        // Update content
        image.src = currentImage.src;
        image.alt = currentImage.title;
        title.textContent = currentImage.title;
        caption.textContent = currentImage.caption;
        counter.textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;
        
        // Show lightbox
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Focus management
        lightbox.querySelector('.lightbox-close').focus();
    }
    
    function closeLightbox() {
        const lightbox = document.querySelector('.gallery-lightbox');
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
        isLightboxOpen = false;
        
        // Return focus to gallery item
        const galleryItems = document.querySelectorAll('.gallery-item');
        if (galleryItems[currentImageIndex]) {
            galleryItems[currentImageIndex].focus();
        }
    }
    
    function navigateLightbox(direction) {
        if (direction === 'next') {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        } else {
            currentImageIndex = currentImageIndex === 0 ? galleryImages.length - 1 : currentImageIndex - 1;
        }
        
        openLightbox(currentImageIndex);
    }
    
    function handleLightboxKeyboard(e) {
        if (!isLightboxOpen) return;
        
        switch(e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                navigateLightbox('prev');
                break;
            case 'ArrowRight':
                navigateLightbox('next');
                break;
        }
    }
    
    // ===== LOAD MORE ===== //
    function initLoadMore() {
        const loadMoreBtn = document.querySelector('.load-more-btn');
        if (!loadMoreBtn) return;
        
        loadMoreBtn.addEventListener('click', function() {
            if (this.classList.contains('loading')) return;
            
            this.classList.add('loading');
            this.disabled = true;
            
            // Simulate loading
            setTimeout(() => {
                loadMoreImages();
                this.classList.remove('loading');
                this.disabled = false;
            }, 1500);
        });
    }
    
    function loadMoreImages() {
        const galleryGrid = document.querySelector('.gallery-grid');
        if (!galleryGrid) return;
        
        // Simulate loading new images
        const newImages = generatePlaceholderImages(6);
        
        newImages.forEach((imageData, index) => {
            const imageElement = createImageElement(imageData);
            galleryGrid.appendChild(imageElement);
            
            // Animate in
            setTimeout(() => {
                imageElement.style.opacity = '1';
                imageElement.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Update gallery images array
        const newGalleryItems = galleryGrid.querySelectorAll('.gallery-item');
        updateGalleryImages(newGalleryItems);
        
        // Update masonry layout
        setTimeout(() => {
            updateMasonryLayout();
        }, 600);
    }
    
    function generatePlaceholderImages(count) {
        const categories = ['rośliny', 'kwiaty', 'drzewa', 'liście'];
        const images = [];
        
        for (let i = 0; i < count; i++) {
            images.push({
                src: `https://picsum.photos/400/300?random=${Date.now() + i}`,
                title: `Nowy obraz ${i + 1}`,
                caption: `Opis nowego obrazu ${i + 1}`,
                category: categories[Math.floor(Math.random() * categories.length)]
            });
        }
        
        return images;
    }
    
    function createImageElement(imageData) {
        const element = document.createElement('div');
        element.className = 'gallery-item';
        element.dataset.category = imageData.category;
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        element.innerHTML = `
            <img class="gallery-image" src="${imageData.src}" alt="${imageData.title}">
            <div class="gallery-overlay">
                <div class="gallery-title-item">${imageData.title}</div>
                <div class="gallery-caption">${imageData.caption}</div>
                <div class="gallery-meta">
                    <span class="gallery-category">${imageData.category}</span>
                    <button class="gallery-zoom" aria-label="Powiększ obraz">
                        <i class="bi bi-zoom-in"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Add click handler
        element.addEventListener('click', function(e) {
            e.preventDefault();
            const index = Array.from(document.querySelectorAll('.gallery-item')).indexOf(this);
            openLightbox(index);
        });
        
        return element;
    }
    
    function updateGalleryImages(galleryItems) {
        galleryImages = Array.from(galleryItems).map((item, index) => {
            const img = item.querySelector('.gallery-image');
            const title = item.querySelector('.gallery-title-item')?.textContent || '';
            const caption = item.querySelector('.gallery-caption')?.textContent || '';
            
            return {
                src: img.src || img.dataset.src,
                title: title,
                caption: caption,
                index: index
            };
        });
    }
    
    // ===== MASONRY LAYOUT ===== //
    function initMasonry() {
        // Simple masonry effect using CSS Grid
        updateMasonryLayout();
        
        window.addEventListener('resize', debounce(updateMasonryLayout, 250));
    }
    
    function updateMasonryLayout() {
        const galleryGrid = document.querySelector('.gallery-grid');
        if (!galleryGrid) return;
        
        const items = galleryGrid.querySelectorAll('.gallery-item');
        
        // Reset any previous masonry styles
        items.forEach(item => {
            item.style.gridRowEnd = 'auto';
        });
        
        // Calculate grid row spans based on image height
        setTimeout(() => {
            items.forEach(item => {
                if (item.style.display === 'none') return;
                
                const img = item.querySelector('.gallery-image');
                if (img && img.complete) {
                    const aspectRatio = img.naturalHeight / img.naturalWidth;
                    const spans = Math.ceil(aspectRatio * 10);
                    item.style.gridRowEnd = `span ${Math.max(spans, 10)}`;
                }
            });
        }, 100);
    }
    
    // ===== LAZY LOADING ===== //
    function initImageLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    }
                });
            }, {
                rootMargin: '50px'
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }
    
    // ===== TOUCH GESTURES (Mobile) ===== //
    function initTouchGestures() {
        if (!('ontouchstart' in window)) return;
        
        const lightbox = document.querySelector('.gallery-lightbox');
        if (!lightbox) return;
        
        let startX = 0;
        let startY = 0;
        let threshold = 50;
        
        lightbox.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        lightbox.addEventListener('touchend', function(e) {
            if (!isLightboxOpen) return;
            
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            const diffX = startX - endX;
            const diffY = startY - endY;
            
            // Horizontal swipe
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > threshold) {
                if (diffX > 0) {
                    navigateLightbox('next');
                } else {
                    navigateLightbox('prev');
                }
            }
            
            // Vertical swipe down to close
            if (diffY < -threshold && Math.abs(diffY) > Math.abs(diffX)) {
                closeLightbox();
            }
        });
    }
    
    // ===== UTILITY FUNCTIONS ===== //
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
    
    // ===== KEYBOARD SHORTCUTS ===== //
    function initKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            if (isLightboxOpen) return; // Lightbox handles its own keys
            
            // F - toggle filters
            if (e.key === 'f' || e.key === 'F') {
                const firstFilterBtn = document.querySelector('.filter-btn');
                if (firstFilterBtn) {
                    firstFilterBtn.focus();
                }
            }
            
            // S - focus search
            if (e.key === 's' || e.key === 'S') {
                e.preventDefault();
                const searchInput = document.querySelector('.search-gallery input');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    }
    
    // ===== PERFORMANCE MONITORING ===== //
    function initPerformanceMonitoring() {
        // Monitor image loading performance
        const images = document.querySelectorAll('.gallery-image');
        let loadedImages = 0;
        
        const startTime = performance.now();
        
        images.forEach(img => {
            if (img.complete) {
                loadedImages++;
            } else {
                img.addEventListener('load', function() {
                    loadedImages++;
                    
                    if (loadedImages === images.length) {
                        const endTime = performance.now();
                        console.log(`Gallery loaded in ${endTime - startTime}ms`);
                    }
                });
            }
        });
    }
    
    // ===== AUTO SLIDESHOW (Optional) ===== //
    function initAutoSlideshow() {
        const autoplayBtn = document.querySelector('.autoplay-btn');
        if (!autoplayBtn) return;
        
        let slideshowInterval;
        let isAutoplay = false;
        
        autoplayBtn.addEventListener('click', function() {
            if (isAutoplay) {
                stopSlideshow();
            } else {
                startSlideshow();
            }
        });
        
        function startSlideshow() {
            isAutoplay = true;
            autoplayBtn.textContent = 'Zatrzymaj pokaz';
            autoplayBtn.classList.add('active');
            
            slideshowInterval = setInterval(() => {
                if (isLightboxOpen) {
                    navigateLightbox('next');
                } else {
                    // Open first image if lightbox is closed
                    openLightbox(0);
                }
            }, 3000);
        }
        
        function stopSlideshow() {
            isAutoplay = false;
            autoplayBtn.textContent = 'Uruchom pokaz';
            autoplayBtn.classList.remove('active');
            
            if (slideshowInterval) {
                clearInterval(slideshowInterval);
            }
        }
        
        // Stop slideshow when user interacts
        document.addEventListener('click', function(e) {
            if (isAutoplay && !e.target.closest('.lightbox-nav')) {
                stopSlideshow();
            }
        });
    }
    
    // ===== STATISTICS COUNTER ===== //
    function initStatsCounter() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        const animateCounter = (element) => {
            const target = parseInt(element.textContent);
            const increment = target / 60; // 1 second animation at 60fps
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 16);
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    entry.target.classList.add('animated');
                    animateCounter(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        statNumbers.forEach(stat => {
            observer.observe(stat);
        });
    }
    
    // ===== IMAGE ZOOM ON HOVER ===== //
    function initImageHoverZoom() {
        const galleryItems = document.querySelectorAll('.gallery-item');
        
        galleryItems.forEach(item => {
            const img = item.querySelector('.gallery-image');
            
            item.addEventListener('mouseenter', function() {
                if (window.innerWidth > 768) { // Only on desktop
                    img.style.transform = 'scale(1.1)';
                }
            });
            
            item.addEventListener('mouseleave', function() {
                img.style.transform = 'scale(1)';
            });
        });
    }
    
    // ===== SOCIAL SHARING ===== //
    function initSocialSharing() {
        // Add share buttons to lightbox
        const lightboxInfo = document.querySelector('.lightbox-info');
        if (!lightboxInfo) return;
        
        const shareButtons = document.createElement('div');
        shareButtons.className = 'lightbox-share';
        shareButtons.innerHTML = `
            <button class="share-btn" data-platform="facebook" aria-label="Udostępnij na Facebook">
                <i class="bi bi-facebook"></i>
            </button>
            <button class="share-btn" data-platform="twitter" aria-label="Udostępnij na Twitter">
                <i class="bi bi-twitter"></i>
            </button>
            <button class="share-btn" data-platform="pinterest" aria-label="Udostępnij na Pinterest">
                <i class="bi bi-pinterest"></i>
            </button>
            <button class="share-btn" data-platform="download" aria-label="Pobierz obraz">
                <i class="bi bi-download"></i>
            </button>
        `;
        
        lightboxInfo.appendChild(shareButtons);
        
        // Handle share clicks
        shareButtons.addEventListener('click', function(e) {
            const btn = e.target.closest('.share-btn');
            if (!btn) return;
            
            const platform = btn.dataset.platform;
            const currentImage = galleryImages[currentImageIndex];
            const url = window.location.href;
            const title = currentImage.title;
            const imageUrl = currentImage.src;
            
            switch(platform) {
                case 'facebook':
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
                    break;
                case 'twitter':
                    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`, '_blank');
                    break;
                case 'pinterest':
                    window.open(`https://pinterest.com/pin/create/button/?url=${encodeURIComponent(url)}&media=${encodeURIComponent(imageUrl)}&description=${encodeURIComponent(title)}`, '_blank');
                    break;
                case 'download':
                    downloadImage(imageUrl, title);
                    break;
            }
        });
    }
    
    function downloadImage(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename || 'image';
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    // ===== INITIALIZATION ===== //
    
    // Initialize additional features
    if (window.innerWidth > 768) {
        initImageHoverZoom();
    }
    
    if ('IntersectionObserver' in window) {
        initStatsCounter();
    }
    
    initTouchGestures();
    initKeyboardShortcuts();
    initAutoSlideshow();
    initSocialSharing();
    
    if (window.performance) {
        initPerformanceMonitoring();
    }
    
    // ===== PUBLIC API ===== //
    window.GalleryAPI = {
        openLightbox,
        closeLightbox,
        navigateLightbox,
        updateMasonryLayout,
        filterGalleryItems: function(filter) {
            const filterBtn = document.querySelector(`[data-filter="${filter}"]`);
            if (filterBtn) {
                filterBtn.click();
            }
        }
    };
    
})();