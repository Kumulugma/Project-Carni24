<?php
// wp-content/themes/carni24/template-parts/homepage/hero-slider.php
// Zaktualizowany hero slider z nowymi polami meta

$slider_posts = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => '_hero_featured',
            'value' => '1',
            'compare' => '='
        ),
        array(
            'key' => '_hero_featured',
            'compare' => 'NOT EXISTS'
        )
    )
));

if ($slider_posts->have_posts()) :
?>

<section class="hero-slider-section">
    <div class="container-fluid p-0">
        <div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="7000">
            <div class="carousel-indicators">
                <?php
                $slide_index = 0;
                while ($slider_posts->have_posts()) : $slider_posts->the_post();
                ?>
                    <button type="button" 
                            data-bs-target="#heroSlider" 
                            data-bs-slide-to="<?= $slide_index ?>"
                            <?= $slide_index === 0 ? 'class="active" aria-current="true"' : '' ?>
                            aria-label="Slide <?= $slide_index + 1 ?>">
                    </button>
                <?php
                $slide_index++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            
            <div class="carousel-inner">
                <?php
                $slide_index = 0;
                $slider_posts->rewind_posts();
                
                while ($slider_posts->have_posts()) : $slider_posts->the_post();
                    $slide_image = get_the_post_thumbnail_url(get_the_ID(), 'homepage_slider');
                    if (!$slide_image) {
                        $slide_image = get_template_directory_uri() . '/assets/images/default-hero.jpg';
                    }
                    
                    // Użyj nowego pola meta dla hero
                    $hero_description = carni24_get_hero_description(get_the_ID());
                    
                    $slide_categories = get_the_category();
                    $primary_category = !empty($slide_categories) ? $slide_categories[0] : null;
                    
                    $slide_active = $slide_index === 0 ? ' active' : '';
                    ?>
                    
                    <div class="carousel-item<?= $slide_active ?>">
                        <div class="hero-slide" style="background-image: url('<?= esc_url($slide_image) ?>');">
                            <div class="hero-slide-overlay"></div>
                            
                            <div class="container">
                                <div class="row h-100 align-items-center">
                                    <div class="col-lg-8 col-xl-7">
                                        <div class="hero-slide-content">
                                            <?php if ($primary_category) : ?>
                                                <div class="hero-slide-category mb-3">
                                                    <a href="<?= get_category_link($primary_category->term_id) ?>" 
                                                       class="badge bg-success fs-6">
                                                        <i class="bi bi-tag-fill me-1"></i>
                                                        <?= esc_html($primary_category->name) ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <h1 class="hero-slide-title">
                                                <?= get_the_title() ?>
                                            </h1>
                                            
                                            <p class="hero-slide-excerpt">
                                                <?= esc_html($hero_description) ?>
                                            </p>
                                            
                                            <div class="hero-slide-meta mb-4">
                                                <span class="text-white-50">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <?= get_the_date() ?>
                                                </span>
                                                
                                                <span class="text-white-50 ms-3">
                                                    <i class="bi bi-person me-1"></i>
                                                    <?= get_the_author() ?>
                                                </span>
                                                
                                                <?php
                                                $reading_time = carni24_estimate_reading_time(get_the_content());
                                                if ($reading_time > 0) :
                                                ?>
                                                    <span class="text-white-50 ms-3">
                                                        <i class="bi bi-clock me-1"></i>
                                                        <?= $reading_time ?> min
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="hero-slide-actions">
                                                <a href="<?= get_permalink() ?>" 
                                                   class="btn btn-success btn-lg me-3">
                                                    Czytaj artykuł
                                                    <i class="bi bi-arrow-right ms-2"></i>
                                                </a>
                                                
                                                <?php if ($primary_category) : ?>
                                                    <a href="<?= get_category_link($primary_category->term_id) ?>" 
                                                       class="btn btn-light btn-lg">
                                                        Więcej z kategorii
                                                        <i class="bi bi-collection ms-2"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    $slide_index++;
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            
            <!-- Kontrolki -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Poprzedni</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Następny</span>
            </button>
        </div>
    </div>
</section>

<style>
/* ===== HERO SLIDER STYLES ===== */

.hero-slider-section {
    position: relative;
    height: 80vh;
    min-height: 600px;
    max-height: 800px;
    overflow: hidden;
}

.carousel-item {
    height: 80vh;
    min-height: 600px;
    max-height: 800px;
}

.hero-slide {
    position: relative;
    height: 100%;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
}

.hero-slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        135deg, 
        rgba(0,0,0,0.7) 0%, 
        rgba(0,0,0,0.4) 50%, 
        rgba(0,0,0,0.6) 100%
    );
    z-index: 1;
}

.hero-slide-content {
    position: relative;
    z-index: 2;
    color: #ffffff;
    max-width: 600px;
}

.hero-slide-category .badge {
    font-size: 14px;
    padding: 8px 16px;
    border-radius: 25px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.hero-slide-title {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.hero-slide-excerpt {
    font-size: 1.25rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    color: rgba(255,255,255,0.9);
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.hero-slide-meta {
    font-size: 14px;
    display: flex;
    flex-wrap: wrap;
    gap: 0;
}

.hero-slide-actions .btn {
    font-size: 16px;
    padding: 12px 24px;
    border-radius: 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.hero-slide-actions .btn-success {
    background: #28a745;
    border-color: #28a745;
    color: #ffffff !important;
}

.hero-slide-actions .btn-success:hover {
    background: #218838;
    border-color: #1e7e34;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
}

.hero-slide-actions .btn-light {
    background: rgba(255,255,255,0.1);
    border: 2px solid rgba(255,255,255,0.3);
    color: #28a745 !important;
    backdrop-filter: blur(10px);
}

.hero-slide-actions .btn-light:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
    color: #1e7e34 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,255,255,0.2);
}

/* Carousel Controls */
.carousel-control-prev,
.carousel-control-next {
    width: 80px;
    height: 80px;
    background: rgba(0,0,0,0.3);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.3s ease;
}

.carousel-control-prev {
    left: 30px;
}

.carousel-control-next {
    right: 30px;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background: rgba(40, 167, 69, 0.8);
    transform: translateY(-50%) scale(1.1);
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 30px;
    height: 30px;
}

/* Carousel Indicators */
.carousel-indicators {
    bottom: 30px;
    margin-bottom: 0;
}

.carousel-indicators [data-bs-target] {
    width: 50px;
    height: 4px;
    border-radius: 2px;
    background-color: rgba(255,255,255,0.4);
    border: none;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.carousel-indicators [data-bs-target].active {
    background-color: #28a745;
    transform: scale(1.2);
}

/* ===== RESPONSYWNOŚĆ ===== */

@media (max-width: 992px) {
    .hero-slider-section,
    .carousel-item {
        height: 70vh;
        min-height: 500px;
    }
    
    .hero-slide-title {
        font-size: 2.5rem;
    }
    
    .hero-slide-excerpt {
        font-size: 1.1rem;
    }
    
    .hero-slide-actions .btn {
        font-size: 14px;
        padding: 10px 20px;
    }
}

@media (max-width: 768px) {
    .hero-slider-section,
    .carousel-item {
        height: 60vh;
        min-height: 400px;
    }
    
    .hero-slide-content {
        max-width: 100%;
    }
    
    .hero-slide-title {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .hero-slide-excerpt {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .hero-slide-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .hero-slide-actions .btn {
        width: 100%;
        margin: 0 !important;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 60px;
        height: 60px;
    }
    
    .carousel-control-prev {
        left: 15px;
    }
    
    .carousel-control-next {
        right: 15px;
    }
    
    .hero-slide-meta {
        flex-direction: column;
        gap: 5px;
    }
}

@media (max-width: 576px) {
    .hero-slider-section,
    .carousel-item {
        height: 50vh;
        min-height: 350px;
    }
    
    .hero-slide-title {
        font-size: 1.5rem;
    }
    
    .hero-slide-excerpt {
        font-size: 0.9rem;
    }
    
    .hero-slide-category .badge {
        font-size: 12px;
        padding: 6px 12px;
    }
    
    .carousel-indicators {
        bottom: 15px;
    }
    
    .carousel-indicators [data-bs-target] {
        width: 30px;
        height: 3px;
        margin: 0 3px;
    }
}
</style>

<?php endif; ?>
