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
                                        <div class="species-card-image-wrapper">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="species-card-image">
                                                    <?php the_post_thumbnail('medium', [
                                                        'alt' => get_the_title(),
                                                        'class' => 'species-thumbnail'
                                                    ]); ?>
                                                </div>
                                            <?php else : ?>
                                                <div class="species-card-image species-placeholder">
                                                    <i class="bi bi-flower1"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Poziom trudności -->
                                            <?php 
                                            $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                                            if ($difficulty) :
                                                $difficulty_class = '';
                                                switch(strtolower($difficulty)) {
                                                    case 'łatwy':
                                                        $difficulty_class = 'bg-success';
                                                        break;
                                                    case 'średni':
                                                        $difficulty_class = 'bg-warning';
                                                        break;
                                                    case 'trudny':
                                                        $difficulty_class = 'bg-danger';
                                                        break;
                                                    default:
                                                        $difficulty_class = 'bg-secondary';
                                                }
                                            ?>
                                                <div class="species-difficulty-badge">
                                                    <span class="badge <?= $difficulty_class ?>">
                                                        <?= esc_html($difficulty) ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="species-card-content">
                                            <!-- Nazwa gatunku -->
                                            <h3 class="species-card-title"><?php the_title(); ?></h3>
                                            
                                            <!-- Nazwa naukowa -->
                                            <?php 
                                            $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                                            if ($scientific_name) :
                                            ?>
                                                <p class="species-scientific-name">
                                                    <em><?= esc_html($scientific_name) ?></em>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <!-- Krótki opis -->
                                            <p class="species-card-excerpt">
                                                <?= wp_trim_words(get_the_excerpt(), 15, '...') ?>
                                            </p>
                                            
                                            <!-- Meta informacje -->
                                            <div class="species-card-meta">
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
                            $big = 999999999; // potrzebne dla str_replace
                            $pagination_links = paginate_links([
                                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'format' => '?paged=%#%',
                                'current' => max(1, get_query_var('paged')),
                                'total' => $wp_query->max_num_pages,
                                'prev_text' => '<i class="bi bi-chevron-left me-1"></i>Poprzednia',
                                'next_text' => 'Następna<i class="bi bi-chevron-right ms-1"></i>',
                                'type' => 'array',
                                'show_all' => false,
                                'mid_size' => 2,
                                'end_size' => 1,
                            ]);

                            if ($pagination_links) :
                            ?>
                                <nav aria-label="Paginacja gatunków" class="d-flex justify-content-center">
                                    <ul class="pagination">
                                        <?php foreach ($pagination_links as $link) : ?>
                                            <li class="page-item <?= strpos($link, 'current') !== false ? 'active' : '' ?>">
                                                <?= str_replace('page-numbers', 'page-link', $link) ?>
                                            </li>
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
                    <div class="no-species-icon mb-4">
                        <i class="bi bi-search display-1 text-muted"></i>
                    </div>
                    <h2 class="no-species-title h4 mb-3">Brak gatunków do wyświetlenia</h2>
                    <p class="no-species-description text-muted mb-4">
                        Nie znaleźliśmy żadnych gatunków roślin mięsożernych. 
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
                    $related_posts = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'meta_query' => [
                            [
                                'key' => '_related_to_species',
                                'value' => '1',
                                'compare' => '='
                            ]
                        ],
                        'orderby' => 'rand'
                    ]);
                    
                    if ($related_posts->have_posts()) :
                        while ($related_posts->have_posts()) : $related_posts->the_post();
                    ?>
                            <div class="col-lg-4">
                                <article class="related-article-card h-100">
                                    <a href="<?= esc_url(get_permalink()) ?>" class="text-decoration-none d-block h-100">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="related-article-thumbnail-wrapper">
                                                <?php the_post_thumbnail('medium', [
                                                    'class' => 'related-article-thumbnail',
                                                    'alt' => get_the_title()
                                                ]); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="related-article-content">
                                            <h3 class="related-article-title"><?php the_title(); ?></h3>
                                            
                                            <div class="related-article-meta mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <?= get_the_date('d.m.Y') ?>
                                                </small>
                                            </div>
                                            
                                            <p class="related-article-excerpt">
                                                <?= wp_trim_words(get_the_excerpt(), 15, '...') ?>
                                            </p>
                                        </div>
                                    </a>
                                </article>
                            </div>
                    <?php 
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Jeśli brak powiązanych, pokaż najnowsze artykuły
                        $latest_posts = new WP_Query([
                            'post_type' => 'post',
                            'posts_per_page' => 3,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ]);
                        
                        if ($latest_posts->have_posts()) :
                            while ($latest_posts->have_posts()) : $latest_posts->the_post();
                        ?>
                                <div class="col-lg-4">
                                    <article class="related-article-card h-100">
                                        <a href="<?= esc_url(get_permalink()) ?>" class="text-decoration-none d-block h-100">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="related-article-thumbnail-wrapper">
                                                    <?php the_post_thumbnail('medium', [
                                                        'class' => 'related-article-thumbnail',
                                                        'alt' => get_the_title()
                                                    ]); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="related-article-content">
                                                <h3 class="related-article-title"><?php the_title(); ?></h3>
                                                
                                                <div class="related-article-meta mb-2">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        <?= get_the_date('d.m.Y') ?>
                                                    </small>
                                                </div>
                                                
                                                <p class="related-article-excerpt">
                                                    <?= wp_trim_words(get_the_excerpt(), 15, '...') ?>
                                                </p>
                                            </div>
                                        </a>
                                    </article>
                                </div>
                        <?php 
                            endwhile;
                            wp_reset_postdata();
                        endif;
                    endif; 
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<style>
/* Dodatkowe style dla archive-species */
.species-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.species-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #28a745;
}

.species-card-image-wrapper {
    position: relative;
    padding: 20px;
    text-align: center;
}

.species-card-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto;
    overflow: hidden;
    border: 3px solid #f8f9fa;
}

.species-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.species-placeholder {
    background: linear-gradient(135deg, #28a745, #20c997);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.species-difficulty-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}

.species-card-content {
    padding: 0 20px 20px;
    text-align: center;
}

.species-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.species-scientific-name {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 12px;
}

.species-card-excerpt {
    font-size: 0.85rem;
    color: #666;
    line-height: 1.4;
    margin-bottom: 15px;
}

.species-card-meta {
    margin-bottom: 15px;
}

.species-card-meta small {
    display: block;
    margin-bottom: 5px;
}

.species-features {
    margin-bottom: 15px;
}

.feature-tag {
    display: inline-block;
    background: #e9f7ef;
    color: #28a745;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    margin: 2px;
}

.species-card-action .btn {
    transition: all 0.3s ease;
}

.species-card:hover .species-card-action .btn {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

/* Lista view */
.species-grid[data-view="list"] .row {
    flex-direction: column;
}

.species-grid[data-view="list"] .species-item {
    max-width: 100%;
    flex: 0 0 100%;
}

.species-grid[data-view="list"] .species-card {
    display: flex;
    flex-direction: row;
    align-items: center;
    text-align: left;
}

.species-grid[data-view="list"] .species-card-image-wrapper {
    flex: 0 0 150px;
    padding: 15px;
}

.species-grid[data-view="list"] .species-card-content {
    flex: 1;
    text-align: left;
    padding: 20px;
}

/* Related articles styles */
.related-article-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.related-article-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.related-article-thumbnail-wrapper {
    overflow: hidden;
}

.related-article-thumbnail {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-article-card:hover .related-article-thumbnail {
    transform: scale(1.05);
}

.related-article-content {
    padding: 20px;
}

.related-article-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.3;
}

.related-article-excerpt {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .species-grid[data-view="list"] .species-card {
        flex-direction: column;
        text-align: center;
    }
    
    .species-grid[data-view="list"] .species-card-content {
        text-align: center;
    }
    
    .species-card-image {
        width: 100px;
        height: 100px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Przełączanie widoku
    const viewButtons = document.querySelectorAll('[data-view]');
    const speciesGrid = document.getElementById('speciesGrid');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Update grid view
            speciesGrid.dataset.view = view;
        });
    });
    
    // Sortowanie (prosty przykład - w prawdziwej implementacji użyj AJAX)
    const sortSelect = document.getElementById('species-sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            // Tutaj możesz dodać logikę AJAX do sortowania
            console.log('Sortowanie według:', sortValue);
            
            // Przykład: odświeżenie strony z parametrem sortowania
            const url = new URL(window.location);
            url.searchParams.set('orderby', sortValue);
            // window.location = url; // Odkomentuj aby włączyć przekierowanie
        });
    }
    
    // Animacje AOS (jeśli używasz)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true
        });
    }
});
</script>

<?php get_footer(); ?>