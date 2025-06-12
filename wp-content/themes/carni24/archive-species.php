<?php
/**
 * Archive template for Species post type
 * Plik: archive-species.php
 * Autor: Carni24 Team
 */

get_header(); ?>

<main class="species-archive-main">
    
    <!-- Header sekcji -->
    <section class="species-archive-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="species-archive-title">
                        <i class="bi bi-flower1 me-3"></i>
                        Gatunki roślin mięsożernych
                    </h1>
                    <p class="species-archive-description lead">
                        Poznaj fascynujący świat roślin mięsożernych. Odkryj różnorodność gatunków, ich unikalne cechy i sposoby pielęgnacji.
                    </p>
                    
                    <?php if (have_posts()) : ?>
                        <div class="species-archive-meta">
                            <span class="badge bg-white text-success fs-6">
                                <i class="bi bi-collection me-1"></i>
                                <?php
                                global $wp_query;
                                echo $wp_query->found_posts . ' ';
                                if ($wp_query->found_posts == 1) {
                                    echo 'gatunek';
                                } elseif ($wp_query->found_posts % 10 >= 2 && $wp_query->found_posts % 10 <= 4 && ($wp_query->found_posts % 100 < 10 || $wp_query->found_posts % 100 >= 20)) {
                                    echo 'gatunki';
                                } else {
                                    echo 'gatunków';
                                }
                                echo ' w kolekcji';
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Zawartość -->
    <section class="species-archive-content py-5">
        <div class="container">
            <?php if (have_posts()) : ?>
                
                <!-- Filtry/Sortowanie -->
                <div class="species-filters mb-5">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="species-view-toggle">
                                <span class="text-muted me-3">Widok:</span>
                                <div class="btn-group" role="group">
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
                            
                            // Meta dane
                            $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                            $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                            $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                            $light_requirements = get_post_meta(get_the_ID(), '_species_light', true);
                            $water_requirements = get_post_meta(get_the_ID(), '_species_water', true);
                        ?>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 species-item">
                                <article class="species-card h-100">
                                    <a href="<?= esc_url(get_permalink()) ?>" class="species-card-link">
                                        
                                        <!-- Obrazek -->
                                        <div class="species-card-image-wrapper">
                                            <div class="species-card-image">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?php the_post_thumbnail('medium', [
                                                        'alt' => get_the_title(),
                                                        'class' => 'species-thumbnail'
                                                    ]); ?>
                                                <?php else : ?>
                                                    <div class="species-placeholder">
                                                        <i class="bi bi-flower1"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Badge trudności -->
                                            <?php if ($difficulty) : ?>
                                                <div class="species-difficulty-badge difficulty-<?= esc_attr($difficulty) ?>">
                                                    <?php
                                                    switch ($difficulty) {
                                                        case 'easy':
                                                            echo '<i class="bi bi-1-circle me-1"></i>Łatwy';
                                                            break;
                                                        case 'medium':
                                                            echo '<i class="bi bi-2-circle me-1"></i>Średni';
                                                            break;
                                                        case 'hard':
                                                            echo '<i class="bi bi-3-circle me-1"></i>Trudny';
                                                            break;
                                                        default:
                                                            echo '<i class="bi bi-question-circle me-1"></i>Nieznany';
                                                    }
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Treść -->
                                        <div class="species-card-content">
                                            <!-- Nazwa gatunku -->
                                            <h3 class="species-card-title"><?php the_title(); ?></h3>
                                            
                                            <!-- Nazwa naukowa -->
                                            <?php if ($scientific_name) : ?>
                                                <p class="species-scientific-name">
                                                    <em><?= esc_html($scientific_name) ?></em>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <!-- Krótki opis -->
                                            <p class="species-card-excerpt">
                                                <?php
                                                $excerpt = get_the_excerpt();
                                                if (empty($excerpt)) {
                                                    $excerpt = wp_trim_words(get_the_content(), 20);
                                                }
                                                echo wp_trim_words($excerpt, 15, '...');
                                                ?>
                                            </p>
                                            
                                            <!-- Meta informacje -->
                                            <div class="species-card-meta">
                                                <?php if ($origin) : ?>
                                                    <div class="species-origin">
                                                        <small class="text-muted">
                                                            <i class="bi bi-geo-alt me-1"></i>
                                                            <?= esc_html($origin) ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($light_requirements) : ?>
                                                    <div class="species-light">
                                                        <small class="text-muted">
                                                            <i class="bi bi-sun me-1"></i>
                                                            Światło: <?= esc_html($light_requirements) ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($water_requirements) : ?>
                                                    <div class="species-water">
                                                        <small class="text-muted">
                                                            <i class="bi bi-droplet me-1"></i>
                                                            Woda: <?= esc_html($water_requirements) ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Kategorie/Tagi -->
                                            <?php 
                                            $terms = get_the_terms(get_the_ID(), 'species_category');
                                            if ($terms && !is_wp_error($terms)) :
                                            ?>
                                                <div class="species-categories">
                                                    <?php foreach (array_slice($terms, 0, 2) as $term) : ?>
                                                        <span class="species-category-tag">
                                                            <?= esc_html($term->name) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Data dodania -->
                                            <div class="species-date">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    Dodano: <?= get_the_date('d.m.Y') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                
                <!-- Paginacja -->
                <?php if ($wp_query->max_num_pages > 1) : ?>
                    <div class="species-pagination mt-5 d-flex justify-content-center">
                        <?php
                        echo paginate_links(array(
                            'total' => $wp_query->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                            'format' => '?paged=%#%',
                            'show_all' => false,
                            'end_size' => 1,
                            'mid_size' => 2,
                            'prev_next' => true,
                            'prev_text' => '<i class="bi bi-chevron-left"></i> Poprzednia',
                            'next_text' => 'Następna <i class="bi bi-chevron-right"></i>',
                            'add_args' => false,
                            'add_fragment' => '',
                            'type' => 'plain'
                        ));
                        ?>
                    </div>
                <?php endif; ?>
                
            <?php else : ?>
                
                <!-- Brak gatunków -->
                <div class="no-species-found text-center py-5">
                    <div class="no-species-icon mb-4">
                        <i class="bi bi-flower1 display-1 text-muted"></i>
                    </div>
                    <h3 class="no-species-title">Brak gatunków</h3>
                    <p class="no-species-description text-muted">
                        Nie znaleziono żadnych gatunków do wyświetlenia.
                        <br>Sprawdź później lub przeglądaj inne sekcje witryny.
                    </p>
                </div>
                
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Sekcja powiązanych artykułów -->
    <?php
    $related_articles = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => '_post_related_to_species',
                'value' => '1',
                'compare' => '='
            )
        )
    ));
    
    if ($related_articles->have_posts()) :
    ?>
        <section class="related-articles py-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="related-articles-title">
                            <i class="bi bi-journal-text me-2"></i>
                            Powiązane artykuły
                        </h2>
                        <p class="related-articles-subtitle text-muted">
                            Przeczytaj więcej o pielęgnacji i uprawie roślin mięsożernych
                        </p>
                    </div>
                </div>
                <div class="row g-4">
                    <?php while ($related_articles->have_posts()) : $related_articles->the_post(); ?>
                        <div class="col-lg-4 col-md-6">
                            <article class="related-article-card h-100">
                                <a href="<?= esc_url(get_permalink()) ?>" class="related-article-link">
                                    
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="related-article-thumbnail-wrapper">
                                            <img src="<?= esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')) ?>" 
                                                 alt="<?= esc_attr(get_the_title()) ?>" 
                                                 class="related-article-thumbnail">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="related-article-content">
                                        <h4 class="related-article-title">
                                            <?= esc_html(get_the_title()) ?>
                                        </h4>
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
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<style>
/* ===== SPECIES ARCHIVE STYLES ===== */

.species-archive-main {
    background: #f8f9fa;
    min-height: 100vh;
}

/* Header */
.species-archive-header {
    background: linear-gradient(135deg, #268155 0%, #1a5f3f 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

.species-archive-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="80" cy="30" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
    animation: floatPattern 25s linear infinite;
}

.species-archive-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.species-archive-description {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.species-archive-meta .badge {
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

/* Filters */
.species-filters {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #e9ecef;
}

.species-view-toggle .btn {
    border-color: #268155;
    color: #268155;
}

.species-view-toggle .btn.active {
    background: #268155;
    color: white;
}

/* Species Grid */
.species-grid .species-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.species-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    border-color: #268155;
    text-decoration: none;
    color: inherit;
}

.species-card-image-wrapper {
    position: relative;
    padding: 20px;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.species-card-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto;
    overflow: hidden;
    border: 3px solid #e9ecef;
    position: relative;
}

.species-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.species-card:hover .species-thumbnail {
    transform: scale(1.1);
}

.species-placeholder {
    background: linear-gradient(135deg, #268155, #1a5f3f);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    width: 100%;
    height: 100%;
}

.species-difficulty-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.difficulty-easy {
    background: #d4edda;
    color: #155724;
}

.difficulty-medium {
    background: #fff3cd;
    color: #856404;
}

.difficulty-hard {
    background: #f8d7da;
    color: #721c24;
}

.species-card-content {
    padding: 1.5rem;
    text-align: center;
}

.species-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.species-scientific-name {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 1rem;
    font-style: italic;
}

.species-card-excerpt {
    color: #6b7280;
    font-size: 0.85rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.species-card-meta {
    margin-bottom: 1rem;
    text-align: left;
}

.species-card-meta > div {
    margin-bottom: 0.25rem;
}

.species-categories {
    margin-bottom: 1rem;
}

.species-category-tag {
    display: inline-block;
    background: #e8f5f0;
    color: #268155;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    margin: 0.125rem;
}

.species-date {
    border-top: 1px solid #e5e7eb;
    padding-top: 0.75rem;
}

/* List View */
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
    align-items: stretch;
    text-align: left;
    max-width: 100%;
    width: 100%;
}

.species-grid[data-view="list"] .species-card-image-wrapper {
    flex: 0 0 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.species-grid[data-view="list"] .species-card-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
}

.species-grid[data-view="list"] .species-card-content {
    flex: 1;
    text-align: left;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.species-grid[data-view="list"] .species-card-title {
    font-size: 1.3rem;
    text-align: left;
    margin-bottom: 0.5rem;
}

.species-grid[data-view="list"] .species-scientific-name {
    text-align: left;
    margin-bottom: 1rem;
}

.species-grid[data-view="list"] .species-card-excerpt {
    text-align: left;
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.species-grid[data-view="list"] .species-card-meta {
    text-align: left;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.species-grid[data-view="list"] .species-card-meta > div {
    margin-bottom: 0;
}

.species-grid[data-view="list"] .species-categories {
    text-align: left;
    margin-bottom: 0.5rem;
}

.species-grid[data-view="list"] .species-date {
    text-align: left;
    border-top: 1px solid #e5e7eb;
    padding-top: 0.5rem;
    margin-top: auto;
}

.species-grid[data-view="list"] .species-difficulty-badge {
    position: relative;
    top: auto;
    right: auto;
    margin-bottom: 1rem;
    align-self: flex-start;
}

/* Pagination */
.species-pagination a,
.species-pagination span {
    display: inline-block;
    padding: 10px 16px;
    margin: 0 4px;
    color: #268155;
    text-decoration: none;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
    min-width: 45px;
    text-align: center;
}

.species-pagination a:hover {
    background: #268155;
    color: white;
    border-color: #268155;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(38, 129, 85, 0.3);
}

.species-pagination .current {
    background: #268155;
    color: white;
    border-color: #268155;
    font-weight: 600;
}

.species-pagination .prev,
.species-pagination .next {
    font-weight: 600;
    padding: 10px 20px;
}

/* Remove any Bootstrap list styles that might interfere */
.species-pagination ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    flex-wrap: wrap !important;
}

.species-pagination li {
    margin: 0 !important;
    padding: 0 !important;
}

/* No Species */
.no-species-found {
    background: white;
    border-radius: 15px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Related Articles */
.related-articles {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.related-articles-title {
    color: #268155;
    font-weight: 700;
}

.related-article-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.related-article-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    border-color: #268155;
    text-decoration: none;
    color: inherit;
}

.related-article-thumbnail-wrapper {
    overflow: hidden;
    height: 200px;
}

.related-article-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-article-card:hover .related-article-thumbnail {
    transform: scale(1.05);
}

.related-article-content {
    padding: 1.5rem;
}

.related-article-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.related-article-excerpt {
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Animations */
@keyframes floatPattern {
    0% { transform: translateX(-50px); }
    100% { transform: translateX(calc(100vw + 50px)); }
}

/* Mobile Responsive */
@media (max-width: 767.98px) {
    .species-archive-title {
        font-size: 2rem;
    }
    
    .species-filters {
        padding: 1rem;
    }
    
    .species-view-toggle,
    .species-sort {
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .species-grid[data-view="list"] .species-card {
        flex-direction: column;
    }
    
    .species-grid[data-view="list"] .species-card-image-wrapper {
        flex: none;
    }
    
    .species-grid[data-view="list"] .species-card-content {
        text-align: center;
    }
}
</style>

<script>
// View toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('[data-view]');
    const speciesGrid = document.getElementById('speciesGrid');
    
    if (viewButtons.length > 0 && speciesGrid) {
        viewButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const view = this.getAttribute('data-view');
                
                // Update buttons
                viewButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Update grid
                speciesGrid.setAttribute('data-view', view);
                
                // Force re-render for better compatibility
                speciesGrid.style.display = 'none';
                speciesGrid.offsetHeight; // trigger reflow
                speciesGrid.style.display = 'block';
            });
        });
    }
    
    // Sort functionality (basic)
    const sortSelect = document.getElementById('species-sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            // This would require AJAX to work properly
            // For now, just reload with parameter
            const url = new URL(window.location);
            url.searchParams.set('orderby', this.value);
            window.location.href = url.toString();
        });
    }
});
</script>

<?php get_footer(); ?>