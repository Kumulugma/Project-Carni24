<?php
/**
 * Template sekcji manifest - popularne wpisy
 * Plik: template-parts/homepage/manifest.php
 * Autor: Carni24 Team
 * POPRAWIONA WERSJA - 4 wpisy w linii, responsive, czas czytania na dole
 */

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 4,
    'meta_key' => 'post_views_count',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
    'date_query' => array(
        array(
            'after' => '30 days ago'
        )
    )
);

// Fallback do losowych jeśli brak popularnych
$popular_posts = new WP_Query($args);
if (!$popular_posts->have_posts()) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 4,
        'orderby' => 'rand'
    );
    $popular_posts = new WP_Query($args);
}
?>

<section id="manifest" class="manifest-section px-4">
    <div class="container-fluid">
        <!-- Header sekcji -->
        <div class="manifest-header">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="manifest-title">
                        <i class="bi bi-fire"></i>
                        Popularne wpisy
                    </h2>
                    <p class="manifest-subtitle">Najczęściej czytane artykuły z ostatnich 30 dni</p>
                </div>
            </div>
        </div>

        <!-- Grid artykułów - 4 w linii na dużych ekranach -->
        <div class="manifest-grid">
            <div class="row g-4">
                <?php
                $counter = 0;
                while ($popular_posts->have_posts()) : $popular_posts->the_post();
                    $counter++;
                    $post_thumb = get_the_post_thumbnail_url(get_the_ID(), 'manifest_thumb');
                    $categories = get_the_category();
                    $primary_category = !empty($categories) ? $categories[0] : null;
                    $reading_time = carni24_calculate_reading_time(get_the_content());
                    $post_views = get_post_meta(get_the_ID(), 'post_views_count', true) ?: 0;
                ?>
                
                <!-- Kolumny responsive: xl=3 (4 wpisy), lg=4 (3 wpisy), md=6 (2 wpisy), sm=12 (1 wpis) -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <article class="manifest-article h-100" data-aos="fade-up" data-aos-delay="<?= $counter * 100 ?>">
                        <a href="<?= esc_url(get_permalink()) ?>" class="manifest-link">
                            
                            <!-- Obrazek -->
                            <div class="manifest-image-container">
                                <?php if ($post_thumb) : ?>
                                    <img src="<?= esc_url($post_thumb) ?>" 
                                         alt="<?= esc_attr(get_the_title()) ?>" 
                                         class="manifest-image">
                                <?php else : ?>
                                    <div class="manifest-image manifest-image-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Badge popularity -->
                                <div class="manifest-badge">
                                    <i class="bi bi-eye"></i>
                                    <?= number_format($post_views) ?>
                                </div>
                                
                                <!-- Kategoria -->
                                <?php if ($primary_category) : ?>
                                    <div class="manifest-category">
                                        <?= esc_html($primary_category->name) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Treść -->
                            <div class="manifest-content">
                                <!-- Meta informacje górne -->
                                <div class="manifest-meta">
                                    <span class="manifest-date">
                                        <i class="bi bi-calendar3"></i>
                                        <?= get_the_date('d M Y') ?>
                                    </span>
                                </div>
                                
                                <!-- Tytuł -->
                                <h3 class="manifest-title-post">
                                    <?= esc_html(get_the_title()) ?>
                                </h3>
                                
                                <!-- Excerpt -->
                                <div class="manifest-excerpt">
                                    <?= wp_trim_words(get_the_excerpt(), 15, '...') ?>
                                </div>
                                
                                
                                <!-- Czas czytania na dole -->
                                <div class="manifest-reading-time">
                                    <i class="bi bi-clock"></i>
                                    <span><?= $reading_time ?> min czytania</span>
                                </div>
                            </div>
                        </a>
                    </article>
                </div>
                
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
</section>