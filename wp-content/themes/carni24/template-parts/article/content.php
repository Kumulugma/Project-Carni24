<?php
/**
 * Template part for species content
 * wp-content/themes/carni24/template-parts/article/content.php
 */

if (have_posts()) : while (have_posts()) : the_post(); ?>

<article class="species-article container my-5">
    <!-- Header gatunku -->
    <header class="species-header text-center mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Nazwa naukowa -->
                <?php 
                $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                if ($scientific_name) :
                ?>
                    <div class="species-scientific-name mb-3">
                        <em class="text-muted fs-5"><?= esc_html($scientific_name) ?></em>
                    </div>
                <?php endif; ?>
                
                <h1 class="species-title display-4 fw-bold mb-4 text-success"><?php the_title(); ?></h1>
                
                <!-- Meta informacje -->
                <div class="species-meta d-flex flex-wrap justify-content-center align-items-center gap-4 text-muted mb-4">
                    <div class="meta-item">
                        <i class="bi bi-calendar3 me-2"></i>
                        <time datetime="<?= get_the_date('c') ?>">
                            Dodano: <?= get_the_date('d F Y') ?>
                        </time>
                    </div>
                    
                    <?php 
                    $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                    if ($difficulty) :
                    ?>
                        <div class="meta-item">
                            <i class="bi bi-speedometer2 me-2"></i>
                            <span>Trudność: <?= esc_html($difficulty) ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                    $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                    if ($origin) :
                    ?>
                        <div class="meta-item">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>Pochodzenie: <?= esc_html($origin) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Kategorie -->
                <?php
                $categories = get_the_category();
                if (!empty($categories)) :
                ?>
                    <div class="species-categories mb-4">
                        <?php foreach ($categories as $category) : ?>
                            <a href="<?= esc_url(get_category_link($category->term_id)) ?>" 
                               class="badge bg-success text-decoration-none me-2 mb-2 fs-6">
                                <i class="bi bi-tag me-1"></i>
                                <?= esc_html($category->name) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="species-featured-image mb-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <figure class="text-center">
                        <?php the_post_thumbnail('large', [
                            'class' => 'img-fluid rounded shadow-lg',
                            'alt' => get_the_title()
                        ]); ?>
                        <?php 
                        $caption = get_the_post_thumbnail_caption();
                        if ($caption) :
                        ?>
                            <figcaption class="figure-caption mt-3 text-muted fs-6">
                                <?= esc_html($caption) ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Szybkie informacje -->
    <?php
    $quick_info = [];
    
    $size = get_post_meta(get_the_ID(), '_species_size', true);
    if ($size) $quick_info['Rozmiar'] = $size;
    
    $light = get_post_meta(get_the_ID(), '_species_light', true);
    if ($light) $quick_info['Światło'] = $light;
    
    $humidity = get_post_meta(get_the_ID(), '_species_humidity', true);
    if ($humidity) $quick_info['Wilgotność'] = $humidity;
    
    $temperature = get_post_meta(get_the_ID(), '_species_temperature', true);
    if ($temperature) $quick_info['Temperatura'] = $temperature;
    
    $watering = get_post_meta(get_the_ID(), '_species_watering', true);
    if ($watering) $quick_info['Podlewanie'] = $watering;

    if (!empty($quick_info)) :
    ?>
        <div class="species-quick-info mb-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Szybkie informacje
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <?php foreach ($quick_info as $label => $value) : ?>
                                    <div class="col-md-6">
                                        <div class="info-item d-flex">
                                            <strong class="info-label me-2"><?= esc_html($label) ?>:</strong>
                                            <span class="info-value"><?= esc_html($value) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Treść gatunku -->
    <div class="species-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="content-wrapper">
                    <?php the_content(); ?>
                </div>
                
                <!-- Cechy specjalne -->
                <?php
                $special_features = get_post_meta(get_the_ID(), '_species_features', true);
                if ($special_features && is_array($special_features)) :
                ?>
                    <div class="species-features mt-5 pt-4 border-top">
                        <h4 class="mb-3">
                            <i class="bi bi-star me-2 text-warning"></i>
                            Cechy specjalne
                        </h4>
                        <div class="features-list">
                            <?php foreach ($special_features as $feature) : ?>
                                <span class="badge bg-light text-dark me-2 mb-2 fs-6">
                                    <i class="bi bi-check-circle me-1 text-success"></i>
                                    <?= esc_html($feature) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tagi -->
                <?php
                $tags = get_the_tags();
                if ($tags) :
                ?>
                    <div class="species-tags mt-5 pt-4 border-top">
                        <h5 class="mb-3">
                            <i class="bi bi-tags me-2"></i>
                            Tagi:
                        </h5>
                        <div class="tags-list">
                            <?php foreach ($tags as $tag) : ?>
                                <a href="<?= esc_url(get_tag_link($tag->term_id)) ?>" 
                                   class="badge bg-secondary text-decoration-none me-2 mb-2">
                                    #<?= esc_html($tag->name) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Galeria -->
    <?php
    $gallery_images = get_post_meta(get_the_ID(), '_species_gallery', true);
    if ($gallery_images && is_array($gallery_images)) :
    ?>
        <div class="species-gallery mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h4 class="text-center mb-4">
                        <i class="bi bi-images me-2"></i>
                        Galeria zdjęć
                    </h4>
                    <div class="gallery-grid row g-3">
                        <?php foreach ($gallery_images as $image_id) : 
                            $image = wp_get_attachment_image_src($image_id, 'medium');
                            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                            if ($image) :
                        ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="gallery-item">
                                    <a href="<?= esc_url(wp_get_attachment_url($image_id)) ?>" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#galleryModal"
                                       data-bs-image="<?= esc_attr(wp_get_attachment_url($image_id)) ?>"
                                       data-bs-title="<?= esc_attr($image_alt ?: get_the_title()) ?>">
                                        <img src="<?= esc_url($image[0]) ?>" 
                                             alt="<?= esc_attr($image_alt ?: get_the_title()) ?>"
                                             class="img-fluid rounded">
                                    </a>
                                </div>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Przyciski udostępniania -->
    <div class="species-share mt-5 pt-4 border-top">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <span class="fw-semibold me-3 align-self-center">Udostępnij gatunek:</span>
                    
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(get_permalink()) ?>" 
                       target="_blank" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-facebook me-1"></i>
                        Facebook
                    </a>
                    
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(get_permalink()) ?>&text=<?= urlencode('Sprawdź gatunek: ' . get_the_title()) ?>" 
                       target="_blank" 
                       class="btn btn-outline-info btn-sm">
                        <i class="bi bi-twitter me-1"></i>
                        Twitter
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

<!-- Modal galerii -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="galleryModalLabel">Zdjęcie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img src="" alt="" class="img-fluid" id="galleryModalImage">
            </div>
        </div>
    </div>
</div>

<!-- Nawigacja między gatunkami -->
<nav class="species-navigation py-4 bg-light">
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
                            <i class="bi bi-chevron-left fs-4 text-success"></i>
                        </div>
                        <div class="nav-content">
                            <small class="text-muted d-block">Poprzedni gatunek</small>
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
                            <small class="text-muted d-block">Następny gatunek</small>
                            <span class="nav-title fw-semibold"><?= esc_html($next_post->post_title) ?></span>
                        </div>
                        <div class="nav-icon ms-3 order-md-2">
                            <i class="bi bi-chevron-right fs-4 text-success"></i>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Powiązane gatunki -->
<?php
$related_species = new WP_Query([
    'post_type' => 'species',
    'posts_per_page' => 3,
    'post__not_in' => [get_the_ID()],
    'orderby' => 'rand'
]);

// Jeśli brak gatunków, spróbuj z tej samej kategorii
if (!$related_species->have_posts()) {
    $categories = wp_get_post_categories(get_the_ID());
    if (!empty($categories)) {
        $related_species = new WP_Query([
            'post_type' => 'species',
            'posts_per_page' => 3,
            'post__not_in' => [get_the_ID()],
            'category__in' => $categories,
            'orderby' => 'rand'
        ]);
    }
}

if ($related_species->have_posts()) :
?>
    <section class="related-species py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="h3">
                        <i class="bi bi-flower1 text-success me-2"></i>
                        Podobne gatunki
                    </h2>
                </div>
            </div>
            <div class="row g-4">
                <?php while ($related_species->have_posts()) : $related_species->the_post(); ?>
                    <div class="col-lg-4">
                        <article class="species-card h-100">
                            <a href="<?= esc_url(get_permalink()) ?>" class="text-decoration-none d-block h-100">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="species-thumbnail-wrapper position-relative overflow-hidden">
                                        <?php the_post_thumbnail('medium', [
                                            'class' => 'species-thumbnail w-100',
                                            'alt' => get_the_title(),
                                            'style' => 'height: 200px; object-fit: cover;'
                                        ]); ?>
                                        
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
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge <?= $difficulty_class ?> fs-6">
                                                    <?= esc_html($difficulty) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="species-placeholder d-flex align-items-center justify-content-center bg-light" 
                                         style="height: 200px;">
                                        <i class="bi bi-flower1 fs-1 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="species-card-content p-3">
                                    <h3 class="species-card-title mb-2"><?php the_title(); ?></h3>
                                    
                                    <?php 
                                    $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                                    if ($scientific_name) :
                                    ?>
                                        <p class="species-scientific text-muted mb-2">
                                            <em><?= esc_html($scientific_name) ?></em>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <p class="species-card-excerpt text-muted mb-3">
                                        <?= wp_trim_words(get_the_excerpt(), 12, '...') ?>
                                    </p>
                                    
                                    <!-- Pochodzenie i rozmiar -->
                                    <div class="species-meta small text-muted">
                                        <?php
                                        $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                                        $size = get_post_meta(get_the_ID(), '_species_size', true);
                                        ?>
                                        
                                        <?php if ($origin) : ?>
                                            <div class="mb-1">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                <?= esc_html($origin) ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($size) : ?>
                                            <div>
                                                <i class="bi bi-arrows-angle-expand me-1"></i>
                                                <?= esc_html($size) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </article>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="<?= esc_url(get_post_type_archive_link('species')) ?>" 
                       class="btn btn-success">
                        <i class="bi bi-collection me-2"></i>
                        Zobacz wszystkie gatunki
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php 
endif;
wp_reset_postdata();
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal galerii
    const galleryModal = document.getElementById('galleryModal');
    if (galleryModal) {
        galleryModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-bs-image');
            const imageTitle = button.getAttribute('data-bs-title');
            
            const modalImage = galleryModal.querySelector('#galleryModalImage');
            const modalTitle = galleryModal.querySelector('#galleryModalLabel');
            
            modalImage.src = imageSrc;
            modalImage.alt = imageTitle;
            modalTitle.textContent = imageTitle;
        });
    }
    
    // Kopiowanie linku
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
</script>

<?php endwhile; endif; ?>