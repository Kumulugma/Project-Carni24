<section class="featured-posts-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="section-title text-center mb-5">
                    <span class="text-success">Wyróżnione</span> wpisy
                </h2>
            </div>
        </div>
        
        <div class="row g-4">
            <?php
            $featured_posts = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 6,
                'orderby' => 'date',
                'order' => 'DESC',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => '_featured_post',
                        'value' => '1',
                        'compare' => '='
                    ),
                    array(
                        'key' => '_featured_post',
                        'compare' => 'NOT EXISTS'
                    )
                )
            ));
            
            $post_count = 0;
            while ($featured_posts->have_posts()) : $featured_posts->the_post();
                $post_count++;
                
                $post_image = get_the_post_thumbnail_url(get_the_ID(), 'homepage_featured');
                if (!$post_image) {
                    $post_image = get_template_directory_uri() . '/assets/images/default-post.jpg';
                }
                
                // Użyj nowego pola meta zamiast excerpt
                $card_description = carni24_get_card_description(get_the_ID());
                
                $post_categories = get_the_category();
                $primary_category = !empty($post_categories) ? $post_categories[0] : null;
                
                // Różne rozmiary kart
                $card_class = 'col-md-6 col-lg-4';
                if ($post_count === 1) {
                    $card_class = 'col-md-12 col-lg-6';
                } elseif ($post_count === 2) {
                    $card_class = 'col-md-12 col-lg-6';
                }
                ?>
                
                <div class="<?= $card_class ?>">
                    <article class="post-card h-100 <?= $post_count <= 2 ? 'post-card-large' : 'post-card-small' ?>">
                        <div class="post-card-image">
                            <a href="<?= get_permalink() ?>" class="d-block">
                                <img src="<?= esc_url($post_image) ?>" 
                                     alt="<?= esc_attr(get_the_title()) ?>"
                                     class="img-fluid w-100"
                                     loading="lazy">
                            </a>
                            
                            <?php if ($primary_category) : ?>
                                <div class="post-card-category">
                                    <a href="<?= get_category_link($primary_category->term_id) ?>" 
                                       class="badge bg-success">
                                        <?= esc_html($primary_category->name) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="post-card-content">
                            <div class="post-card-meta">
                                <time datetime="<?= get_the_date('c') ?>" class="text-muted small">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= get_the_date() ?>
                                </time>
                                
                                <div class="post-card-author text-muted small">
                                    <i class="bi bi-person me-1"></i>
                                    <?= get_the_author() ?>
                                </div>
                            </div>
                            
                            <h3 class="post-card-title">
                                <a href="<?= get_permalink() ?>" class="text-decoration-none">
                                    <?= get_the_title() ?>
                                </a>
                            </h3>
                            
                            <div class="post-card-excerpt">
                                <?= $card_description ?>
                            </div>
                            
                            <div class="post-card-footer">
                                <a href="<?= get_permalink() ?>" class="btn btn-light btn-sm">
                                    Czytaj więcej
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                                
                                <?php
                                $reading_time = carni24_estimate_reading_time(get_the_content());
                                if ($reading_time > 0) :
                                ?>
                                    <small class="text-muted ms-auto">
                                        <i class="bi bi-clock me-1"></i>
                                        <?= $reading_time ?> min
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                </div>
                
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="<?= get_permalink(get_option('page_for_posts')) ?>" 
                   class="btn btn-success btn-lg">
                    Zobacz wszystkie wpisy
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* ===== FEATURED POSTS STYLES ===== */

.featured-posts-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.post-card {
    background: #ffffff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.post-card-image {
    position: relative;
    overflow: hidden;
}

.post-card-large .post-card-image {
    height: 300px;
}

.post-card-small .post-card-image {
    height: 200px;
}

.post-card-image img {
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.post-card:hover .post-card-image img {
    transform: scale(1.05);
}

.post-card-category {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 2;
}

.post-card-category .badge {
    font-size: 11px;
    padding: 6px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.post-card-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: calc(100% - 200px);
}

.post-card-large .post-card-content {
    height: calc(100% - 300px);
    padding: 25px;
}

.post-card-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.post-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 15px;
}

.post-card-large .post-card-title {
    font-size: 1.5rem;
    margin-bottom: 20px;
}

.post-card-title a {
    color: #212529;
    transition: color 0.3s ease;
}

.post-card-title a:hover {
    color: #28a745;
}

.post-card-excerpt {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: auto;
    flex-grow: 1;
}

.post-card-large .post-card-excerpt {
    font-size: 16px;
    margin-bottom: 20px;
}

.post-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f1f3f4;
}

.post-card .btn-light {
    font-size: 13px;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.post-card .btn-light:hover {
    transform: translateX(3px);
}

/* ===== RESPONSYWNOŚĆ ===== */

@media (max-width: 768px) {
    .post-card-large .post-card-image,
    .post-card-small .post-card-image {
        height: 200px;
    }
    
    .post-card-content {
        padding: 15px;
    }
    
    .post-card-title {
        font-size: 1.1rem;
    }
    
    .post-card-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .post-card-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

@media (max-width: 576px) {
    .featured-posts-section {
        padding: 3rem 0;
    }
    
    .post-card {
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 1.5rem;
        margin-bottom: 2rem !important;
    }
}
</style>