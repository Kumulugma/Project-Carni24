<?php
/**
 * Template Name: Lista wpisów - NAPRAWIONY (na wzór archive-species.php)
 * Plik: page-blog.php
 * Autor: Carni24 Team
 * Szablon strony wyświetlającej wszystkie wpisy z paginacją
 */

get_header();

// Sortowanie i filtrowanie
$orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';

// Paginacja
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Query arguments
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 12,
    'paged' => $paged,
);

// Sortowanie
switch ($orderby) {
    case 'title':
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
        break;
    case 'title-desc':
        $args['orderby'] = 'title';
        $args['order'] = 'DESC';
        break;
    case 'popular':
        $args['meta_key'] = '_post_views';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        break;
    default:
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
}

$blog_query = new WP_Query($args);
?>

<!-- CSS INLINE PRO TESTY -->
<style>
    /* CRITICAL STYLES FOR VIEW TOGGLE */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
        transition: all 0.3s ease;
    }

    .blog-grid[data-view="list"] {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }

    .blog-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .blog-grid[data-view="list"] .blog-card {
        display: flex !important;
        height: auto !important;
        min-height: 200px !important;
    }

    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: #16a34a;
    }

    .blog-link {
        text-decoration: none !important;
        color: inherit !important;
        display: block !important;
        height: 100% !important;
    }

    .blog-grid[data-view="list"] .blog-link {
        display: flex !important;
        flex-direction: row !important;
        align-items: stretch !important;
        width: 100% !important;
    }

    .blog-image-container {
        position: relative;
        height: 250px;
        background: linear-gradient(135deg, #f8faf8 0%, #f0f4f0 100%);
        overflow: hidden;
    }

    .blog-grid[data-view="list"] .blog-image-container {
        width: 250px !important;
        height: 200px !important;
        flex-shrink: 0 !important;
    }

    .blog-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .blog-card:hover .blog-thumbnail {
        transform: scale(1.1);
    }

    .blog-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        font-size: 4rem;
        color: #16a34a;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    .blog-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .blog-grid[data-view="list"] .blog-content {
        padding: 2rem !important;
    }

    .blog-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.75rem;
        line-height: 1.4;
        text-decoration: none;
    }

    .blog-card-title:hover {
        color: #16a34a;
    }

    .blog-card-excerpt {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
    }

    .blog-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #9ca3af;
        margin-top: auto;
    }

    .blog-meta-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .blog-meta-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .blog-meta-author {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .blog-meta-category {
        background: #f3f4f6;
        color: #374151;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* Controls styling */
    .blog-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .view-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sort-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .control-label {
        font-weight: 600;
        color: #374151;
        margin-right: 1rem;
    }

    /* Hero section */
    .blog-page-hero {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        padding: 4rem 0;
        text-align: center;
    }

    .hero-content {
        margin: 0 auto;
        padding: 0 2rem;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .hero-description {
        font-size: 1.2rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .container {
        margin: 0 auto;
        padding: 0 2rem;
    }

    .blog-content-section {
        padding: 4rem 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .blog-controls {
            flex-direction: column;
            gap: 1rem;
        }

        .blog-grid {
            grid-template-columns: 1fr;
        }

        .blog-grid[data-view="list"] .blog-card {
            flex-direction: column !important;
        }

        .blog-grid[data-view="list"] .blog-image-container {
            width: 100% !important;
            height: 200px !important;
        }
    }
</style>

<section class="blog-page-hero">
    <div class="hero-content">
        <h1 class="hero-title">
            <i class="bi bi-journal-text me-3"></i>
            Wszystkie wpisy
        </h1>
        <p class="hero-description">
            Poznaj fascynujący świat roślin mięsożernych - artykuły, poradniki i ciekawostki
        </p>

        <?php if ($blog_query->have_posts()) : ?>
            <div class="blog-count">
                <span style="background: white; color: #16a34a; padding: 0.75rem 1.5rem; border-radius: 50px; font-weight: 600; display: inline-block;">
                    <?php
                    echo $blog_query->found_posts . ' ' . _n('artykuł', 'artykuły', $blog_query->found_posts, 'carni24');
                    ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="blog-content-section p-5">
    <div class="container-fluid">
        <?php if ($blog_query->have_posts()) : ?>
            
            <!-- Kontrolki widoku i sortowania -->
            <div class="blog-controls" style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 2rem;">
                <div class="view-toggle">
                    <span class="control-label">Widok:</span>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm active" data-view="grid">
                            <i class="bi bi-grid-3x3-gap"></i> Siatka
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" data-view="list">
                            <i class="bi bi-list"></i> Lista
                        </button>
                    </div>
                </div>

                <div class="sort-controls">
                    <span class="control-label">Sortowanie:</span>
                    <select class="form-select form-select-sm" id="blog-sort-select" style="width: auto; border-color: #16a34a;">
                        <option value="date" <?= selected($orderby, 'date', false) ?>>Najnowsze</option>
                        <option value="title" <?= selected($orderby, 'title', false) ?>>A-Z</option>
                        <option value="title-desc" <?= selected($orderby, 'title-desc', false) ?>>Z-A</option>
                        <option value="popular" <?= selected($orderby, 'popular', false) ?>>Najpopularniejsze</option>
                        <option value="commented" <?= selected($orderby, 'commented', false) ?>>Najkomentowane</option>
                    </select>
                </div>
            </div>

            <!-- Siatka wpisów -->
            <div class="blog-grid" id="blogGrid" data-view="grid">
                <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                    <article class="blog-card">
                        <a href="<?= get_permalink() ?>" class="blog-link">
                            <div class="blog-image-container">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?= get_the_post_thumbnail(get_the_ID(), 'medium_large', ['class' => 'blog-thumbnail', 'alt' => get_the_title()]) ?>
                                <?php else : ?>
                                    <div class="blog-placeholder">
                                        <i class="bi bi-newspaper"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="blog-content">
                                <div>
                                    <h2 class="blog-card-title"><?= get_the_title() ?></h2>
                                    <p class="blog-card-excerpt">
                                        <?= wp_trim_words(get_the_excerpt(), 20, '...') ?>
                                    </p>
                                </div>
                                
                                <div class="blog-meta">
                                    <div class="blog-meta-left">
                                        <span class="blog-meta-date">
                                            <i class="bi bi-calendar3"></i>
                                            <?= get_the_date('d.m.Y') ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ($blog_query->max_num_pages > 1) : ?>
                <div style="margin-top: 3rem; text-align: center;">
                    <?php
                    echo paginate_links(array(
                        'total' => $blog_query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'format' => '?paged=%#%',
                        'show_all' => false,
                        'end_size' => 1,
                        'mid_size' => 2,
                        'prev_next' => true,
                        'prev_text' => '<i class="bi bi-chevron-left"></i> Poprzednia',
                        'next_text' => 'Następna <i class="bi bi-chevron-right"></i>',
                        'add_args' => array('orderby' => $orderby),
                    ));
                    ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 4rem; color: #9ca3af; margin-bottom: 1rem;">
                    <i class="bi bi-search"></i>
                </div>
                <h2>Brak wpisów</h2>
                <p>Nie znaleziono żadnych wpisów do wyświetlenia.</p>
                <a href="<?= home_url() ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #16a34a; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 1rem;">
                    <i class="bi bi-house"></i>
                    Wróć na stronę główną
                </a>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</section>

<!-- JAVASCRIPT INLINE -->
<script>
    console.log('Blog page script loading...');

    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded, initializing view toggle...');

        const viewButtons = document.querySelectorAll('[data-view]');
        const blogGrid = document.getElementById('blogGrid');
        const sortSelect = document.getElementById('blog-sort-select');

        console.log('Found elements:', {
            buttons: viewButtons.length,
            grid: !!blogGrid,
            select: !!sortSelect
        });

        // View toggle functionality
        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const view = this.getAttribute('data-view');
                console.log('Switching to view:', view);

                // Update buttons
                viewButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Update grid
                if (blogGrid) {
                    blogGrid.setAttribute('data-view', view);
                    console.log('Grid view updated to:', view);
                }
            });
        });

        // Sort functionality
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                const orderby = this.value;
                console.log('Changing sort to:', orderby);

                // Build new URL with sort parameter
                const url = new URL(window.location);
                url.searchParams.set('orderby', orderby);
                url.searchParams.delete('paged'); // Reset pagination
                
                console.log('Redirecting to:', url.toString());
                window.location.href = url.toString();
            });
        }

        console.log('Blog page script initialized successfully');
    });
</script>

<?php get_footer(); ?>