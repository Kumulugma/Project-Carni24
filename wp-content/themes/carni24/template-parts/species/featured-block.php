<?php
// wp-content/themes/carni24/template-parts/species/featured-block.php
// Blok z wyróżnionymi/najnowszymi gatunkami do wyświetlenia na stronie głównej
?>

<section class="species-block py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title h1 mb-3">
                    <i class="bi bi-flower2 me-2"></i>
                    Gatunki roślin
                </h2>
                <p class="section-subtitle text-muted">
                    Poznaj fascynujący świat roślin mięsożernych
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            // Query dla najnowszych gatunków
            $species_query = new WP_Query(array(
                'post_type' => 'species',
                'posts_per_page' => 8,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => array(
                    array(
                        'key' => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    )
                )
            ));
            
            if ($species_query->have_posts()) :
                while ($species_query->have_posts()) : $species_query->the_post();
                    $species_image = get_the_post_thumbnail_url(get_the_ID(), 'species_card');
                    if (!$species_image) {
                        $species_image = get_template_directory_uri() . '/assets/images/default-species.jpg';
                    }
                    
                    $species_excerpt = get_the_excerpt();
                    if (empty($species_excerpt)) {
                        $species_excerpt = wp_trim_words(get_the_content(), 15);
                    }
                    
                    // Pobierz kategorię gatunku (jeśli używasz taxonomii)
                    $species_categories = get_the_category();
                    $species_category = $species_categories ? $species_categories[0]->name : '';
                    
                    // Pobierz meta dane gatunku (jeśli masz custom fields)
                    $latin_name = get_post_meta(get_the_ID(), 'latin_name', true);
                    $family = get_post_meta(get_the_ID(), 'plant_family', true);
                    $difficulty = get_post_meta(get_the_ID(), 'care_difficulty', true);
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <article class="species-card h-100">
                            <div class="card h-100 shadow-sm">
                                <div class="species-image-container">
                                    <img src="<?= esc_url($species_image) ?>" 
                                         class="card-img-top species-image" 
                                         alt="<?= esc_attr(get_the_title()) ?>"
                                         loading="lazy">
                                    
                                    <?php if ($difficulty): ?>
                                    <div class="difficulty-badge">
                                        <?php
                                        $difficulty_class = '';
                                        $difficulty_text = '';
                                        switch ($difficulty) {
                                            case 'easy':
                                                $difficulty_class = 'bg-success';
                                                $difficulty_text = 'Łatwy';
                                                break;
                                            case 'medium':
                                                $difficulty_class = 'bg-warning';
                                                $difficulty_text = 'Średni';
                                                break;
                                            case 'hard':
                                                $difficulty_class = 'bg-danger';
                                                $difficulty_text = 'Trudny';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $difficulty_class ?>"><?= $difficulty_text ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="species-overlay">
                                        <a href="<?= esc_url(get_permalink()) ?>" class="species-link">
                                            <i class="bi bi-eye-fill"></i>
                                            <span>Zobacz szczegóły</span>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <?php if ($species_category): ?>
                                    <div class="species-category mb-2">
                                        <span class="badge bg-primary"><?= esc_html($species_category) ?></span>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="card-title h5 mb-2">
                                        <a href="<?= esc_url(get_permalink()) ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <?php if ($latin_name): ?>
                                    <p class="species-latin text-muted fst-italic mb-2">
                                        <?= esc_html($latin_name) ?>
                                    </p>
                                    <?php endif; ?>
                                    
                                    <p class="card-text text-muted flex-grow-1">
                                        <?= esc_html(wp_trim_words($species_excerpt, 12)) ?>
                                    </p>
                                    
                                    <div class="species-meta mt-auto pt-3 border-top">
                                        <div class="row text-center">
                                            <?php if ($family): ?>
                                            <div class="col-6">
                                                <small class="text-muted d-block">Rodzina</small>
                                                <small class="fw-bold"><?= esc_html($family) ?></small>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <div class="col-<?= $family ? '6' : '12' ?>">
                                                <small class="text-muted d-block">Dodano</small>
                                                <small class="fw-bold"><?= get_the_date('d.m.Y') ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <h4>Brak gatunków do wyświetlenia</h4>
                        <p>Nie znaleźliśmy żadnych opublikowanych gatunków z obrazami.</p>
                        <?php if (current_user_can('edit_posts')): ?>
                        <a href="<?= admin_url('post-new.php?post_type=species') ?>" class="btn btn-primary">
                            Dodaj pierwszy gatunek
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Przycisk "Zobacz wszystkie" -->
        <?php if ($species_query->found_posts > 0): ?>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="<?= get_post_type_archive_link('species') ?>" 
                   class="btn btn-outline-success btn-lg px-5">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    Zobacz wszystkie gatunki
                    <span class="badge bg-success ms-2"><?= carni24_species_count() ?></span>
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Species Block Styles */
.species-block {
    background: linear-gradient(135deg, #f0f8f0 0%, #ffffff 100%);
}

.species-block .section-title {
    color: #2d5016;
    font-weight: 700;
    position: relative;
}

.species-block .section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(45deg, #28a745, #20c997);
    border-radius: 2px;
}

/* Species Cards */
.species-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.species-card:hover {
    transform: translateY(-8px);
}

.species-card .card {
    border: none;
    overflow: hidden;
    border-radius: 12px;
    transition: box-shadow 0.3s ease;
}

.species-card:hover .card {
    box-shadow: 0 20px 40px rgba(40, 167, 69, 0.15);
}

.species-image-container {
    position: relative;
    overflow: hidden;
    height: 250px;
    background: #f8f9fa;
}

.species-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.species-card:hover .species-image {
    transform: scale(1.05);
}

.difficulty-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 10;
}

.difficulty-badge .badge {
    font-size: 0.7rem;
    padding: 0.4em 0.8em;
    border-radius: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.species-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(40, 167, 69, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.species-card:hover .species-overlay {
    opacity: 1;
}

.species-link i {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.5rem;
}

.species-link span {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.species-category .badge {
    font-size: 0.7rem;
    padding: 0.4em 0.8em;
    border-radius: 15px;
}

.species-latin {
    font-size: 0.85rem;
    line-height: 1.3;
}

.species-meta {
    font-size: 0.8rem;
}

.species-meta small.fw-bold {
    color: #28a745;
}

/* Responsive Design */
@media (max-width: 768px) {
    .species-image-container {
        height: 200px;
    }
    
    .species-block .section-title {
        font-size: 1.75rem !important;
    }
    
    .difficulty-badge {
        top: 8px;
        right: 8px;
    }
    
    .species-link i {
        font-size: 1.5rem;
    }
    
    .species-link span {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .species-image-container {
        height: 180px;
    }
    
    .species-meta .row {
        text-align: left !important;
    }
    
    .species-meta .col-6 {
        margin-bottom: 0.5rem;
    }
}

/* Animation for cards */
.species-card {
    animation: fadeInUp 0.6s ease-out;
}

.species-card:nth-child(2) { animation-delay: 0.1s; }
.species-card:nth-child(3) { animation-delay: 0.2s; }
.species-card:nth-child(4) { animation-delay: 0.3s; }
.species-card:nth-child(5) { animation-delay: 0.4s; }
.species-card:nth-child(6) { animation-delay: 0.5s; }
.species-card:nth-child(7) { animation-delay: 0.6s; }
.species-card:nth-child(8) { animation-delay: 0.7s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading state */
.species-card.loading {
    opacity: 0.7;
}

.species-card.loading .species-image {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Custom scrollbar for species block */
.species-block::-webkit-scrollbar {
    width: 6px;
}

.species-block::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.species-block::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 3px;
}

.species-block::-webkit-scrollbar-thumb:hover {
    background: #20c997;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading images with intersection observer
    const speciesImages = document.querySelectorAll('.species-image[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.remove('loading');
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px 0px',
            threshold: 0.1
        });
        
        speciesImages.forEach(img => {
            img.classList.add('loading');
            imageObserver.observe(img);
        });
    }
    
    // Enhanced hover effects
    const speciesCards = document.querySelectorAll('.species-card');
    
    speciesCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Add subtle animation to other cards
            speciesCards.forEach(otherCard => {
                if (otherCard !== card) {
                    otherCard.style.opacity = '0.8';
                }
            });
        });
        
        card.addEventListener('mouseleave', function() {
            // Reset all cards
            speciesCards.forEach(otherCard => {
                otherCard.style.opacity = '1';
            });
        });
    });
    
    // Keyboard navigation support
    const speciesLinks = document.querySelectorAll('.species-card a');
    
    speciesLinks.forEach(link => {
        link.addEventListener('focus', function() {
            this.closest('.species-card').style.transform = 'translateY(-8px)';
            this.closest('.species-card').style.boxShadow = '0 20px 40px rgba(40, 167, 69, 0.15)';
        });
        
        link.addEventListener('blur', function() {
            this.closest('.species-card').style.transform = '';
            this.closest('.species-card').style.boxShadow = '';
        });
    });
});
