<?php 
/**
 * Template for displaying single species
 * wp-content/themes/carni24/single-species.php
 */

get_header(); ?>

<main class="single-species-main">
    <div class="container-fluid">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php if (function_exists('get_breadcrumb')) : ?>
                            <nav aria-label="breadcrumb" class="breadcrumbs-nav">
                                <?php get_breadcrumb(); ?>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Species Content -->
        <div class="species-content-section">
            <div class="container">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article class="species-article">
                        <!-- Header -->
                        <header class="species-header">
                            <div class="row">
                                <div class="col-12">
                                    <h1 class="species-title"><?php the_title(); ?></h1>
                                    <?php 
                                    $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                                    if ($scientific_name) : ?>
                                        <p class="species-scientific-name">
                                            <em><?= esc_html($scientific_name) ?></em>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </header>

                        <!-- Main Content Area -->
                        <div class="species-main-content">
                            <div class="row">
                                <!-- Left Column - Content -->
                                <div class="col-lg-8">
                                    <div class="species-content-wrapper">
                                        <?php the_content(); ?>
                                    </div>

                                    <!-- Galeria -->
                                    <div class="species-gallery-section">
                                        <?php 
                                        $gallery_images = get_post_meta(get_the_ID(), '_species_gallery', true);
                                        if ($gallery_images && is_array($gallery_images)) : ?>
                                            <h3>Galeria</h3>
                                            <div class="species-gallery">
                                                <?php foreach ($gallery_images as $image_id) : 
                                                    $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                                    $image_full = wp_get_attachment_image_url($image_id, 'full');
                                                    if ($image_url) : ?>
                                                        <div class="gallery-item">
                                                            <a href="<?= esc_url($image_full) ?>" data-lightbox="species-gallery">
                                                                <img src="<?= esc_url($image_url) ?>" alt="<?= esc_attr(get_the_title()) ?>">
                                                            </a>
                                                        </div>
                                                    <?php endif;
                                                endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Right Column - Sidebar -->
                                <div class="col-lg-4">
                                    <div class="species-sidebar">
                                        <!-- Featured Image -->
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="species-featured-image">
                                                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Species Meta -->
                                        <div class="species-meta-box">
                                            <h4>Informacje o gatunku</h4>
                                            <div class="meta-content">
                                                <!-- Placeholder for meta fields - to be filled later -->
                                                <div class="meta-item">
                                                    <span class="meta-label">Rodzina:</span>
                                                    <span class="meta-value">
                                                        <?= esc_html(get_post_meta(get_the_ID(), '_species_family', true) ?: '—') ?>
                                                    </span>
                                                </div>
                                                <div class="meta-item">
                                                    <span class="meta-label">Pochodzenie:</span>
                                                    <span class="meta-value">
                                                        <?= esc_html(get_post_meta(get_the_ID(), '_species_origin', true) ?: '—') ?>
                                                    </span>
                                                </div>
                                                <div class="meta-item">
                                                    <span class="meta-label">Trudność uprawy:</span>
                                                    <span class="meta-value">
                                                        <?= esc_html(get_post_meta(get_the_ID(), '_species_difficulty', true) ?: '—') ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Distribution Map -->
                                        <div class="species-map-section">
                                            <h4>Rozmieszczenie geograficzne</h4>
                                            <div class="map-placeholder">
                                                <?php 
                                                $map_image = get_post_meta(get_the_ID(), '_species_map_image', true);
                                                if ($map_image) : 
                                                    $map_url = wp_get_attachment_image_url($map_image, 'medium');
                                                    if ($map_url) : ?>
                                                        <img src="<?= esc_url($map_url) ?>" alt="Mapa rozmieszczenia <?= esc_attr(get_the_title()) ?>" class="img-fluid">
                                                    <?php endif;
                                                else : ?>
                                                    <div class="map-placeholder-content">
                                                        <p>Mapa rozmieszczenia zostanie dodana</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bibliography Section -->
                        <footer class="species-footer">
                            <div class="row">
                                <div class="col-12">
                                    <?php 
                                    $bibliography = get_post_meta(get_the_ID(), '_species_bibliography', true);
                                    if ($bibliography) : ?>
                                        <div class="species-bibliography">
                                            <h3>Bibliografia</h3>
                                            <div class="bibliography-content">
                                                <?= wp_kses_post($bibliography) ?>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="species-bibliography">
                                            <h3>Bibliografia</h3>
                                            <div class="bibliography-placeholder">
                                                <p>Bibliografia zostanie dodana</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </footer>
                    </article>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>