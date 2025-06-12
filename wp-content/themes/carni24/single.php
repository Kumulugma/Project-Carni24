<?php 
/**
 * Template for displaying single posts
 * wp-content/themes/carni24/single.php
 */

get_header(); ?>

<!-- Dodaj overlay wyszukiwarki -->
<?php get_template_part('template-parts/search-overlay'); ?>

<main class="single-post-main">
    <!-- Breadcrumbs -->
    <div class="container py-3">
        <?php if (function_exists('carni24_breadcrumbs')) carni24_breadcrumbs(); ?>
    </div>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <!-- Artykuł -->
        <article class="article container my-5">
            <!-- Header artykułu -->
            <header class="article-header text-center mb-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <?php
                        // Kategorie
                        $categories = get_the_category();
                        if (!empty($categories)) :
                        ?>
                            <div class="article-categories mb-3">
                                <?php foreach ($categories as $category) : ?>
                                    <a href="<?= esc_url(get_category_link($category->term_id)) ?>" 
                                       class="badge bg-success text-decoration-none me-2">
                                        <i class="bi bi-tag me-1"></i>
                                        <?= esc_html($category->name) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <h1 class="article-title display-5 fw-bold mb-4"><?php the_title(); ?></h1>
                        
                        <!-- Meta informacje -->
                        <div class="article-meta d-flex flex-wrap justify-content-center align-items-center gap-4 text-muted">
                            <div class="meta-item">
                                <i class="bi bi-calendar3 me-2"></i>
                                <time datetime="<?= get_the_date('c') ?>">
                                    <?= get_the_date('d F Y') ?>
                                </time>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-person me-2"></i>
                                <span><?= get_the_author() ?></span>
                            </div>
                            <?php if (function_exists('get_field')) : 
                                $reading_time = get_field('reading_time');
                                if ($reading_time) :
                            ?>
                                <div class="meta-item">
                                    <i class="bi bi-clock me-2"></i>
                                    <span><?= esc_html($reading_time) ?> min czytania</span>
                                </div>
                            <?php endif; endif; ?>
                            <div class="meta-item">
                                <i class="bi bi-eye me-2"></i>
                                <span>
                                    <?php 
                                    $views = get_post_meta(get_the_ID(), 'post_views_count', true);
                                    echo $views ? $views : '0';
                                    ?> wyświetleń
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="article-featured-image mb-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <figure class="text-center">
                                <?php the_post_thumbnail('large', [
                                    'class' => 'img-fluid rounded shadow-sm',
                                    'alt' => get_the_title()
                                ]); ?>
                                <?php 
                                $caption = get_the_post_thumbnail_caption();
                                if ($caption) :
                                ?>
                                    <figcaption class="figure-caption mt-2 text-muted">
                                        <?= esc_html($caption) ?>
                                    </figcaption>
                                <?php endif; ?>
                            </figure>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Treść artykułu -->
            <div class="article-content">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <?php the_content(); ?>
                        
                        <!-- Tagi -->
                        <?php
                        $tags = get_the_tags();
                        if ($tags) :
                        ?>
                            <div class="article-tags mt-5 pt-4 border-top">
                                <h5 class="mb-3">
                                    <i class="bi bi-tags me-2"></i>
                                    Tagi:
                                </h5>
                                <div class="tags-list">
                                    <?php foreach ($tags as $tag) : ?>
                                        <a href="<?= esc_url(get_tag_link($tag->term_id)) ?>" 
                                           class="badge bg-light text-dark text-decoration-none me-2 mb-2">
                                            #<?= esc_html($tag->name) ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Przyciski udostępniania -->
            <div class="article-share mt-5 pt-4 border-top">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <span class="fw-semibold me-3 align-self-center">Udostępnij:</span>
                            
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(get_permalink()) ?>" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-facebook me-1"></i>
                                Facebook
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url=<?= urlencode(get_permalink()) ?>&text=<?= urlencode(get_the_title()) ?>" 
                               target="_blank" 
                               class="btn btn-outline-info btn-sm">
                                <i class="bi bi-twitter me-1"></i>
                                Twitter
                            </a>
                            
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(get_permalink()) ?>" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-linkedin me-1"></i>
                                LinkedIn
                            </a>
                            
                            <button type="button" 
                                    class="btn btn-outline-secondary btn-sm copy-link-btn" 
                                    data-url="<?= esc_attr(get_permalink()) ?>">
                                <i class="bi bi-link-45deg me-1"></i>
                                Kopiuj link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <!-- Nawigacja między postami -->
        <nav class="article-navigation py-4 bg-light">
            <div class="container">
                <div class="row">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ($prev_post) : ?>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <a href="<?= esc_url(get_permalink($prev_post)) ?>" 
                               class="nav-link d-flex align-items-center text-decoration-none">
                                <div class="nav-icon me-3">
                                    <i class="bi bi-chevron-left fs-4"></i>
                                </div>
                                <div class="nav-content">
                                    <small class="text-muted d-block">Poprzedni artykuł</small>
                                    <span class="nav-title fw-semibold"><?= esc_html($prev_post->post_title) ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($next_post) : ?>
                        <div class="col-md-6 text-md-end">
                            <a href="<?= esc_url(get_permalink($next_post)) ?>" 
                               class="nav-link d-flex align-items-center text-decoration-none justify-content-md-end">
                                <div class="nav-content order-md-1">
                                    <small class="text-muted d-block">Następny artykuł</small>
                                    <span class="nav-title fw-semibold"><?= esc_html($next_post->post_title) ?></span>
                                </div>
                                <div class="nav-icon ms-3 order-md-2">
                                    <i class="bi bi-chevron-right fs-4"></i>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- Powiązane artykuły -->
        <?php
        $related_posts = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [get_the_ID()],
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_related_posts',
                    'value' => get_the_ID(),
                    'compare' => 'LIKE'
                ]
            ],
            'orderby' => 'rand'
        ]);

        if (!$related_posts->have_posts()) {
            // Jeśli brak ręcznie powiązanych, pobierz z tej samej kategorii
            $categories = wp_get_post_categories(get_the_ID());
            if (!empty($categories)) {
                $related_posts = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post__not_in' => [get_the_ID()],
                    'category__in' => $categories,
                    'orderby' => 'rand'
                ]);
            }
        }

        if ($related_posts->have_posts()) :
        ?>
            <section class="related-articles py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center mb-4">
                            <h2 class="h3">
                                <i class="bi bi-journal-bookmark text-success me-2"></i>
                                Może Cię również zainteresować
                            </h2>
                        </div>
                    </div>
                    <div class="row g-4">
                        <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                            <div class="col-lg-4">
                                <article class="related-post h-100">
                                    <a href="<?= esc_url(get_permalink()) ?>" class="text-decoration-none d-block h-100">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="related-post-thumbnail-wrapper">
                                                <?php the_post_thumbnail('medium', [
                                                    'class' => 'related-post-thumbnail',
                                                    'alt' => get_the_title()
                                                ]); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="related-post-content">
                                            <h3 class="related-post-title"><?php the_title(); ?></h3>
                                            
                                            <div class="related-post-meta mb-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    <?= get_the_date('d.m.Y') ?>
                                                </small>
                                            </div>
                                            
                                            <p class="related-post-excerpt">
                                                <?= wp_trim_words(get_the_excerpt(), 15, '...') ?>
                                            </p>
                                        </div>
                                    </a>
                                </article>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php 
        endif;
        wp_reset_postdata();
        ?>

    <?php endwhile; endif; ?>
</main>

<script>
// Kopiowanie linku
document.addEventListener('DOMContentLoaded', function() {
    const copyBtns = document.querySelectorAll('.copy-link-btn');
    
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    showCopySuccess(this);
                });
            } else {
                // Fallback dla starszych przeglądarek
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showCopySuccess(this);
            }
        });
    });
    
    function showCopySuccess(btn) {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check me-1"></i>Skopiowano!';
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }
});

// Liczenie wyświetleń
if (!sessionStorage.getItem('viewed_post_<?= get_the_ID() ?>')) {
    fetch('<?= admin_url('admin-ajax.php') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=count_post_views&post_id=<?= get_the_ID() ?>&nonce=<?= wp_create_nonce('count_views_nonce') ?>'
    });
    sessionStorage.setItem('viewed_post_<?= get_the_ID() ?>', 'true');
}
</script>

<?php get_footer(); ?>