<?php
/**
 * Template sekcji manifest - popularne wpisy
 * Plik: template-parts/homepage/manifest.php
 * Autor: Carni24 Team
 * POPRAWIONA WERSJA - bez przycisku, poprawiona struktura
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

<section id="manifest" class="manifest-section">
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

        <!-- Grid artykułów -->
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
                
                <div class="col-lg-6">
                    <article class="manifest-article" data-aos="fade-up" data-aos-delay="<?= $counter * 100 ?>">
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
                                <div class="manifest-meta">
                                    <span class="manifest-date">
                                        <i class="bi bi-calendar3"></i>
                                        <?= get_the_date('d M Y') ?>
                                    </span>
                                    <span class="manifest-reading-time">
                                        <i class="bi bi-clock"></i>
                                        <?= $reading_time ?> min
                                    </span>
                                </div>
                                
                                <h3 class="manifest-title-post">
                                    <?php the_title(); ?>
                                </h3>
                                
                                <div class="manifest-excerpt">
                                    <?= wp_trim_words(get_the_excerpt(), 20, '...') ?>
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

<?php
// Helper function - dodaj do functions.php jeśli nie istnieje
if (!function_exists('carni24_calculate_reading_time')) {
    function carni24_calculate_reading_time($content) {
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 słów na minutę
        return max(1, $reading_time);
    }
}
?>