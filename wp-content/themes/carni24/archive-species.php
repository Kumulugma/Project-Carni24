<?php
/**
 * Archive template for Species post type
 * wp-content/themes/carni24/archive-species.php
 */

get_header(); ?>

<!-- Dodaj overlay wyszukiwarki -->
<?php get_template_part('template-parts/search-overlay'); ?>

<main class="species-archive-main">    
    <!-- Breadcrumbs -->
    <div class="container py-3">
        <?php if (function_exists('carni24_breadcrumbs')) carni24_breadcrumbs(); ?>
    </div>
    
    <!-- Header sekcji -->
    <section class="species-archive-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="species-archive-title">
                        <i class="bi bi-flower1 text-success me-3"></i>
                        Gatunki roślin mięsożernych
                    </h1>
                    <p class="species-archive-description lead">
                        Poznaj fascynujący świat roślin mięsożernych. Odkryj różnorodność gatunków, ich unikalne cechy i sposoby pielęgnacji.
                    </p>
                    
                    <?php if (have_posts()) : ?>
                        <div class="species-archive-meta">
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-collection me-1"></i>
                                <?= $wp_query->found_posts ?> <?= _n('gatunek', 'gatunki', $wp_query->found_posts, 'carni24') ?> w kolekcji
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Lista gatunków -->
    <section class="species-archive-content pb-5">
        <div class="container">
            <?php if (have_posts()) : ?>
                
                <!-- Filtry/Sortowanie -->
                <div class="species-filters mb-5">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="species-view-toggle">
                                <span class="text-muted me-3">Widok:</span>
                                <div class="btn-group" role="group" aria-label="Przełącznik widoku">
                                    <button type="button" class="btn btn-outline-success btn-sm active" data-view="grid">
                                        <i class="bi bi-grid-3x3-gap"></i> 
                                        <span class="d-none d-sm-inline ms-1">Siatka</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" data-view="list">
                                        <i class="bi bi-list"></i> 
                                        <span class="d-none d-sm-inline ms-1">Lista</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="species-sort">
                                <label for="species-sort-select" class="text-muted me-2">Sortuj:</label>
                                <select id="species-sort-select" class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="date">Najnowsze</option>
                                    <option value="title">Alfabetycznie A-Z</option>
                                    <option value="title-desc">Alfabetycznie Z-A</option>
                                    <option value="difficulty">Poziom trudności</option>
                                    <option value="popularity">Popularność</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Siatka gatunków -->
                <div class="species-grid" id="speciesGrid" data-view="grid">
                    <div class="row g-4">
                        <?php 
                        $post_count = 0;
                        while (have_posts()) : the_post(); 
                            $post_count++;
                        ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 species-item" data-aos="fade-up" data-aos-delay="<?= ($post_count * 100) ?>">
                                <article class="species-card h-100">
                                    <a href="<?= get_permalink() ?>" class="species-card-link">
                                        <!-- Okrągły obrazek -->
                                        <div class="species-card-image">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?= get_the_post_thumbnail(get_the_ID(), 'species_card', array(
                                                    'class' => 'species-image',
                                                    'loading' => 'lazy',
                                                    'alt' => get_the_title()
                                                )) ?>
                                            <?php else : ?>
                                                <div class="species-image-placeholder">
                                                    <i class="bi bi-flower1"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Badge kategorii -->
                                            <?php
                                            $species_categories = get_the_terms(get_the_ID(), 'species_category');
                                            if ($species_categories && !is_wp_error($species_categories)) :
                                                $primary_category = $species_categories[0];
                                            ?>
                                                <div class="species-category-badge">
                                                    <span class="badge bg-success">
                                                        <?= esc_html($primary_category->name) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Overlay na hover -->
                                            <div class="species-image-overlay">
                                                <i class="bi bi-eye-fill"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Treść karty -->
                                        <div class="species-card-content">
                                            <h3 class="species-card-title">
                                                <?= get_the_title() ?>
                                            </h3>
                                            
                                            <!-- Nazwa łacińska -->
                                            <?php 
                                            $latin_name = get_post_meta(get_the_ID(), '_species_latin_name', true);
                                            if ($latin_name) :
                                            ?>
                                                <div class="species-latin-name">
                                                    <em><?= esc_html($latin_name) ?></em>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="species-card-excerpt">
                                                <?php
                                                $description = function_exists('carni24_get_card_description') 
                                                    ? carni24_get_card_description(get_the_ID(), 15)
                                                    : wp_trim_words(get_the_excerpt(), 15, '...');
                                                echo $description;
                                                ?>
                                            </div>
                                            
                                            <!-- Meta info -->
                                            <div class="species-card-meta">
                                                <!-- Poziom trudności -->
                                                <div class="species-difficulty">
                                                    <?php
                                                    $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                                                    $difficulty_levels = array(
                                                        'easy' => array('icon' => '🟢', 'label' => 'Łatwy', 'class' => 'easy'),
                                                        'medium' => array('icon' => '🟡', 'label' => 'Średni', 'class' => 'medium'),
                                                        'hard' => array('icon' => '🔴', 'label' => 'Trudny', 'class' => 'hard')
                                                    );
                                                    
                                                    if ($difficulty && isset($difficulty_levels[$difficulty])) :
                                                        $level = $difficulty_levels[$difficulty];
                                                    ?>
                                                        <span class="difficulty-badge difficulty-<?= $level['class'] ?>" 
                                                              title="Poziom trudności uprawy: <?= $level['label'] ?>">
                                                            <?= $level['icon'] ?> <?= $level['label'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Pochodzenie -->
                                                <div class="species-origin">
                                                    <?php
                                                    $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                                                    if ($origin) :
                                                    ?>
                                                        <small class="text-muted">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            <?= esc_html($origin) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Rozmiar -->
                                                <div class="species-size">
                                                    <?php
                                                    $size = get_post_meta(get_the_ID(), '_species_size', true);
                                                    if ($size) :
                                                    ?>
                                                        <small class="text-muted">
                                                            <i class="bi bi-arrows-angle-expand me-1"></i>
                                                            <?= esc_html($size) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <!-- Cechy specjalne -->
                                            <?php
                                            $special_features = get_post_meta(get_the_ID(), '_species_features', true);
                                            if ($special_features && is_array($special_features)) :
                                            ?>
                                                <div class="species-features">
                                                    <?php foreach (array_slice($special_features, 0, 3) as $feature) : ?>
                                                        <span class="feature-tag"><?= esc_html($feature) ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Przycisk CTA -->
                                            <div class="species-card-action">
                                                <span class="btn btn-light btn-sm">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Poznaj gatunek
                                                    <i class="bi bi-arrow-right ms-1"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <!-- Paginacja -->
                <div class="species-pagination mt-5">
                    <div class="row">
                        <div class="col-12">
                            <?php
                            $pagination = paginate_links(array(
                                'prev_text' => '<i class="bi bi-chevron-left"></i> <span class="d-none d-sm-inline">Poprzednie</span>',
                                'next_text' => '<span class="d-none d-sm-inline">Następne</span> <i class="bi bi-chevron-right"></i>',
                                'type' => 'array',
                                'current' => max(1, get_query_var('paged')),
                                'total' => $wp_query->max_num_pages
                            ));
                            
                            if ($pagination) :
                            ?>
                                <nav aria-label="Nawigacja stron gatunków">
                                    <ul class="pagination justify-content-center">
                                        <?php foreach ($pagination as $page) : ?>
                                            <li class="page-item"><?= $page ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
            <?php else : ?>
                
                <!-- Brak gatunków -->
                <div class="no-species-found text-center py-5">
                    <div class="no-species-animation mb-4">
                        <i class="bi bi-flower1 display-1 text-muted"></i>
                        <div class="floating-leaves">
                            <span>🍃</span>
                            <span>🌿</span>
                            <span>🍃</span>
                        </div>
                    </div>
                    <h3 class="text-muted mb-3">Brak gatunków</h3>
                    <p class="text-muted mb-4">
                        Nie znaleziono żadnych gatunków roślin mięsożernych. 
                        <br>Sprawdź później lub <a href="<?= home_url('/kontakt/') ?>">skontaktuj się z nami</a>.
                    </p>
                    <div class="no-species-actions">
                        <a href="<?= home_url() ?>" class="btn btn-success me-3">
                            <i class="bi bi-house me-2"></i>
                            Strona główna
                        </a>
                        <a href="<?= get_permalink(get_option('page_for_posts')) ?>" class="btn btn-outline-success">
                            <i class="bi bi-journal-text me-2"></i>
                            Czytaj artykuły
                        </a>
                    </div>
                </div>
                
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Sekcja dodatkowa - polecane artykuły -->
    <?php if (have_posts()) : ?>
        <section class="related-articles bg-light py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <h2 class="h3">
                            <i class="bi bi-journal-bookmark text-success me-2"></i>
                            Przydatne artykuły o uprawie
                        </h2>
                    </div>
                </div>
                <div class="row g-4">
                    <?php
                    // Pobierz powiązane artykuły
                    $related_posts = new WP_Query(array(
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'meta_query' => array(
                            array(
                                'key' => '_related_to_species',
                                'value' => '1',
                                'compare' => '='
                            )
                        ),
                        'orderby' => 'rand'
                    ));
                    
                    if ($related_posts->have_posts()) :
                        while ($related_posts->have_posts()) : $related_posts->the_post();
                    ?>
                        <div class="col-md-4">
                            <article class="related-article-card">
                                <a href="<?= get_permalink() ?>" class="text-decoration-none">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="related-article-image">
                                            <?= get_the_post_thumbnail(get_the_ID(), 'medium', array(
                                                'class' => 'img-fluid',
                                                'loading' => 'lazy'
                                            )) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="related-article-content">
                                        <h4 class="related-article-title"><?= get_the_title() ?></h4>
                                        <p class="related-article-excerpt">
                                            <?= wp_trim_words(get_the_excerpt(), 12, '...') ?>
                                        </p>
                                        <div class="related-article-meta">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?= get_the_date() ?>
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<!-- JavaScript dla funkcjonalności -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle
    const viewButtons = document.querySelectorAll('[data-view]');
    const speciesGrid = document.getElementById('speciesGrid');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.getAttribute('data-view');
            
            // Usuń active z wszystkich przycisków
            viewButtons.forEach(btn => btn.classList.remove('active'));
            
            // Dodaj active do klikniętego
            this.classList.add('active');
            
            // Zmień widok z animacją
            speciesGrid.style.opacity = '0.7';
            speciesGrid.style.transform = 'scale(0.98)';
            
            setTimeout(() => {
                speciesGrid.setAttribute('data-view', view);
                speciesGrid.style.opacity = '1';
                speciesGrid.style.transform = 'scale(1)';
            }, 200);
            
            // Zapisz preferencję w localStorage (jeśli dostępne)
            try {
                localStorage.setItem('species_view_preference', view);
            } catch (e) {
                console.log('localStorage not available');
            }
        });
    });
    
    // Przywróć zapisaną preferencję widoku
    try {
        const savedView = localStorage.getItem('species_view_preference');
        if (savedView) {
            const button = document.querySelector(`[data-view="${savedView}"]`);
            if (button) {
                button.click();
            }
        }
    } catch (e) {
        console.log('localStorage not available');
    }
    
    // Sort functionality
    const sortSelect = document.getElementById('species-sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            const items = Array.from(document.querySelectorAll('.species-item'));
            const container = document.querySelector('.species-grid .row');
            
            // Animacja fade out
            items.forEach(item => {
                item.style.opacity = '0.5';
                item.style.transform = 'translateY(10px)';
            });
            
            setTimeout(() => {
                // Sortuj elementy
                items.sort((a, b) => {
                    const titleA = a.querySelector('.species-card-title').textContent.trim();
                    const titleB = b.querySelector('.species-card-title').textContent.trim();
                    
                    switch (sortBy) {
                        case 'title':
                            return titleA.localeCompare(titleB);
                        case 'title-desc':
                            return titleB.localeCompare(titleA);
                        case 'difficulty':
                            const diffA = a.querySelector('.difficulty-badge')?.textContent || 'zzz';
                            const diffB = b.querySelector('.difficulty-badge')?.textContent || 'zzz';
                            return diffA.localeCompare(diffB);
                        case 'date':
                        default:
                            return 0;
                    }
                });
                
                // Usuń wszystkie elementy i dodaj w nowej kolejności
                items.forEach(item => container.removeChild(item));
                items.forEach(item => container.appendChild(item));
                
                // Animacja fade in
                setTimeout(() => {
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, index * 50);
                    });
                }, 100);
            }, 300);
        });
    }
    
    // Animacja licznika w header
    const countBadge = document.querySelector('.species-archive-meta .badge');
    if (countBadge) {
        const countText = countBadge.textContent;
        const countMatch = countText.match(/(\d+)/);
        
        if (countMatch) {
            const finalCount = parseInt(countMatch[1]);
            let currentCount = 0;
            const increment = Math.ceil(finalCount / 30);
            
            const countAnimation = setInterval(() => {
                currentCount += increment;
                if (currentCount >= finalCount) {
                    currentCount = finalCount;
                    clearInterval(countAnimation);
                }
                
                countBadge.innerHTML = countBadge.innerHTML.replace(/\d+/, currentCount);
            }, 50);
        }
    }
    
    // Smooth scroll dla paginacji
    const paginationLinks = document.querySelectorAll('.species-pagination .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.href && this.href.includes('#')) return;
            
            // Smooth scroll do góry po kliknięciu
            setTimeout(() => {
                window.scrollTo({
                    top: document.querySelector('.species-archive-header').offsetTop - 100,
                    behavior: 'smooth'
                });
            }, 100);
        });
    });
});
</script>

<?php get_footer(); ?>