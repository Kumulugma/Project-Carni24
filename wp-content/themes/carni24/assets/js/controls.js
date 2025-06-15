/**
 * Kontrolki widoków - Species i Blog - NAPRAWIONY
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Controls script loading...');
    
    // Funkcja do inicjalizacji kontrolek dla danego typu
    function initializeControls(gridSelector, controlsSelector) {
        const grid = document.querySelector(gridSelector);
        const controls = document.querySelector(controlsSelector);
        
        if (!grid || !controls) {
            console.log(`Grid (${gridSelector}) or controls (${controlsSelector}) not found`);
            return;
        }
        
        console.log(`Initializing controls for ${gridSelector}`);
        
        // Znajdź przyciski toggle w kontrolkach
        const toggleBtns = controls.querySelectorAll('.view-toggle-btn, [data-view]');
        
        if (toggleBtns.length === 0) {
            console.log('No toggle buttons found');
            return;
        }
        
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const view = this.getAttribute('data-view');
                if (!view) return;
                
                console.log(`Switching to view: ${view}`);
                
                // Usuń active z wszystkich przycisków w tej sekcji
                toggleBtns.forEach(b => {
                    b.classList.remove('active');
                    // Dla Bootstrap buttons
                    if (b.classList.contains('btn')) {
                        b.classList.remove('btn-success');
                        b.classList.add('btn-outline-success');
                    }
                });
                
                // Dodaj active do klikniętego przycisku
                this.classList.add('active');
                // Dla Bootstrap buttons
                if (this.classList.contains('btn')) {
                    this.classList.remove('btn-outline-success');
                    this.classList.add('btn-success');
                }
                
                // Zmień widok gridu
                grid.setAttribute('data-view', view);
                grid.classList.remove('grid-view', 'list-view');
                grid.classList.add(view + '-view');
                
                // Zapisz preferencje
                localStorage.setItem(`${gridSelector.replace('#', '')}_view`, view);
                
                console.log(`View changed to: ${view}`);
            });
        });
        
        // Przywróć zapisany widok
        const savedView = localStorage.getItem(`${gridSelector.replace('#', '')}_view`);
        if (savedView) {
            const targetBtn = controls.querySelector(`[data-view="${savedView}"]`);
            if (targetBtn) {
                targetBtn.click();
            }
        }
    }
    
    // Inicjalizuj dla różnych typów stron
    initializeControls('#speciesGrid', '.species-controls');
    initializeControls('#blogGrid', '.blog-controls');
    initializeControls('#archiveGrid', '.archive-controls');
    
    // Dodaj cursor pointer do klikanych kart
    const clickableCards = document.querySelectorAll('.species-card[onclick], .blog-card[onclick], .archive-card[onclick]');
    clickableCards.forEach(card => {
        card.style.cursor = 'pointer';
    });
    
    // Obsługa sortowania
    const sortSelects = document.querySelectorAll('.sort-select, #species-sort, #blog-sort, #archive-sort');
    sortSelects.forEach(select => {
        select.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('orderby', this.value);
            url.searchParams.delete('paged'); // Reset paginacji
            window.location.href = url.toString();
        });
    });
    
    console.log('Controls script initialized successfully');
});

// Funkcja pomocnicza dla responsive view toggle
function handleResponsiveViewToggle() {
    const isMobile = window.innerWidth <= 768;
    const toggleBtns = document.querySelectorAll('.view-toggle-btn .btn-text');
    
    toggleBtns.forEach(textSpan => {
        if (textSpan) {
            textSpan.style.display = isMobile ? 'none' : 'inline';
        }
    });
}

// Dodaj listener dla zmiany rozmiaru okna
window.addEventListener('resize', handleResponsiveViewToggle);
handleResponsiveViewToggle(); // Wywołaj na starcie