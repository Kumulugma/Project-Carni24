<?php
// wp-content/themes/carni24/archive.php
// Zaktualizowany szablon archiwum z nowymi polami meta
?>

<?php get_header(); ?>

<!-- Dodaj overlay wyszukiwarki -->
<?php get_template_part('template-parts/search-overlay'); ?>

<main class="archive-main">
    <?php get_template_part('template-parts/main-submenu'); ?>
    
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Header archiwum -->
                <div class="archive-header mb-5">
                    <h1 class="archive-title">
                        <?php
                        if (is_category()) {
                            echo '<i class="bi bi-folder me-2 text-success"></i>';
                            single_cat_title();
                        } elseif (is_tag()) {
                            echo '<i class="bi bi-tag me-2 text-success"></i>';
                            single_tag_title();
                        } elseif (is_author()) {
                            echo '<i class="bi bi-person me-2 text-success"></i>';
                            echo 'Autor: ' . get_the_author();
                        } elseif (is_date()) {
                            echo '<i class="bi bi-calendar me-2 text-success"></i>';
                            echo 'Archiwum: ' . get_the_date('F Y');
                        } else {
                            echo '<i class="bi bi-collection me-2 text-success"></i>';
                            post_type_archive_title();
                        }
                        ?>
                    </h1>
                    
                    <?php if (is_category() && category_description()) : ?>
                        <div class="archive-description">
                            <?= category_description() ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="archive-meta">
                        <span class="text-muted">
                            <i class="bi bi-files me-1"></i>
                            Znaleziono: <?= $wp_query->found_posts ?> wpisów
                        </span>
                    </div>
                </div>
                
                <!-- Lista wpisów -->
                <div class="archive-posts">
                    <?php if (have_posts()) : ?>
                        <div class="row g-4">
                            <?php while (have_posts()) : the_post(); ?>
                                <div class="col-md-6">
                                    <article class="archive-post-card h-100">
                                        <div class="archive-post-image">
                                            <a href="<?= get_permalink() ?>">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <?= get_the_post_thumbnail(get_the_ID(), 'archive_thumb', array(
                                                        'class' => 'img-fluid w-100',
                                                        'loading' => 'lazy'
                                                    )) ?>
                                                <?php else : ?>
                                                    <img src="<?= get_template_directory_uri() ?>/assets/images/default-post.jpg" 
                                                         alt="<?= esc_attr(get_the_title()) ?>"
                                                         class="img-fluid w-100"
                                                         loading="lazy">
                                                <?php endif; ?>
                                            </a>
                                            
                                            <?php
                                            $post_categories = get_the_category();
                                            if (!empty($post_categories)) :
                                                $primary_category = $post_categories[0];
                                            ?>
                                                <div class="archive-post-category">
                                                    <a href="<?= get_category_link($primary_category->term_id) ?>" 
                                                       class="badge bg-success">
                                                        <?= esc_html($primary_category->name) ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="archive-post-content">
                                            <div class="archive-post-meta">
                                                <time datetime="<?= get_the_date('c') ?>" class="text-muted small">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <?= get_the_date() ?>
                                                </time>
                                                
                                                <span class="text-muted small">
                                                    <i class="bi bi-person me-1"></i>
                                                    <?= get_the_author() ?>
                                                </span>
                                            </div>
                                            
                                            <h2 class="archive-post-title">
                                                <a href="<?= get_permalink() ?>" class="text-decoration-none">
                                                    <?= get_the_title() ?>
                                                </a>
                                            </h2>
                                            
                                            <div class="archive-post-excerpt">
                                                <?= carni24_get_card_description(get_the_ID()) ?>
                                            </div>
                                            
                                            <div class="archive-post-footer">
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
                            <?php endwhile; ?>
                        </div>
                        
                        <!-- Paginacja -->
                        <div class="archive-pagination mt-5">
                            <?php
                            echo paginate_links(array(
                                'prev_text' => '<i class="bi bi-chevron-left"></i> Poprzednie',
                                'next_text' => 'Następne <i class="bi bi-chevron-right"></i>',
                                'class' => 'pagination justify-content-center'
                            ));
                            ?>
                        </div>
                        
                    <?php else : ?>
                        <div class="no-posts-found text-center py-5">
                            <i class="bi bi-search text-muted display-1 mb-3"></i>
                            <h3 class="text-muted">Brak wpisów</h3>
                            <p class="text-muted">W tej kategorii nie ma jeszcze żadnych wpisów.</p>
                            <a href="<?= home_url() ?>" class="btn btn-success">
                                Wróć na stronę główną
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<style>
/* ===== ARCHIVE STYLES ===== */

.archive-main {
    background: #f8f9fa;
    min-height: 100vh;
}

.archive-header {
    background: #ffffff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-left: 5px solid #28a745;
}

.archive-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 15px;
}

.archive-description {
    color: #6c757d;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

.archive-meta {
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
}

.archive-post-card {
    background: #ffffff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.archive-post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.archive-post-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.archive-post-image img {
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.archive-post-card:hover .archive-post-image img {
    transform: scale(1.05);
}

.archive-post-category {
    position: absolute;
    top: 15px;
    left: 15px;
    z-index: 2;
}

.archive-post-category .badge {
    font-size: 11px;
    padding: 6px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.archive-post-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: calc(100% - 220px);
}

.archive-post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.archive-post-title {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 15px;
}

.archive-post-title a {
    color: #212529;
    transition: color 0.3s ease;
}

.archive-post-title a:hover {
    color: #28a745;
}

.archive-post-excerpt {
    color: #6c757d;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: auto;
    flex-grow: 1;
}

.archive-post-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f1f3f4;
}

.archive-post-card .btn-light {
    font-size: 13px;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.archive-post-card .btn-light:hover {
    transform: translateX(3px);
}

/* Paginacja */
.archive-pagination .page-numbers {
    display: inline-block;
    padding: 10px 15px;
    margin: 0 5px;
    color: #28a745;
    text-decoration: none;
    border: 1px solid #dee2e6;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.archive-pagination .page-numbers:hover,
.archive-pagination .page-numbers.current {
    background: #28a745;
    color: #ffffff;
    border-color: #28a745;
}

/* No posts */
.no-posts-found {
    background: #ffffff;
    border-radius: 15px;
    padding: 60px 30px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

/* ===== RESPONSYWNOŚĆ ===== */

@media (max-width: 768px) {
    .archive-title {
        font-size: 2rem;
    }
    
    .archive-header {
        padding: 20px;
        margin-bottom: 30px;
    }
    
    .archive-post-image {
        height: 180px;
    }
    
    .archive-post-content {
        padding: 15px;
    }
    
    .archive-post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .archive-post-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

@media (max-width: 576px) {
    .archive-main .container {
        padding: 3rem 15px;
    }
    
    .col-md-6 {
        margin-bottom: 20px;
    }
    
    .archive-post-title {
        font-size: 1.1rem;
    }
}
</style>

<?php get_footer(); ?>
                