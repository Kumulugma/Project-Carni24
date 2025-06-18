<?php 
/**
 * Template for displaying single species - DOPRACOWANY
 * wp-content/themes/carni24/single-species.php
 */

get_header(); ?>

<main class="single-species-main">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs-section">
        <div class="container-fluid px-5">
            <?php if (function_exists('carni24_breadcrumbs')) : ?>
                <nav aria-label="breadcrumb" class="breadcrumbs-nav">
                    <?php carni24_breadcrumbs(); ?>
                </nav>
            <?php endif; ?>
        </div>
    </div>

    <div class="container-fluid px-md-5">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="species-article">
                
                <!-- Header z obrazkiem wyróżniającym -->
                <header class="species-header">
                    <div class="row align-items-center">
                        <div class="col-lg-12">
                            <h1 class="species-title"><?php the_title(); ?></h1>
                            <?php 
                            $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                            if ($scientific_name) : ?>
                                <p class="species-scientific-name">
                                    <em><?= esc_html($scientific_name) ?></em>
                                </p>
                            <?php endif; ?>
                            
                            <div class="species-meta-info">
                                <?php
                                $origin = get_post_meta(get_the_ID(), '_species_origin', true);
                                $difficulty = get_post_meta(get_the_ID(), '_species_difficulty', true);
                                $family = get_post_meta(get_the_ID(), '_species_family', true);
                                ?>
                                
                                <?php if ($origin) : ?>
                                    <span class="meta-badge">
                                        <i class="bi bi-geo-alt"></i>
                                        Pochodzenie: <?= esc_html($origin) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($difficulty) : ?>
                                    <span class="meta-badge difficulty-<?= esc_attr(strtolower($difficulty)) ?>">
                                        <i class="bi bi-star-fill"></i>
                                        Trudność: <?= esc_html($difficulty) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($family) : ?>
                                    <span class="meta-badge">
                                        <i class="bi bi-diagram-3"></i>
                                        Rodzina: <?= esc_html($family) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </div>
                </header>

                <!-- Główna treść - 2 kolumny -->
                <div class="species-main-content">
                    <div class="row">
                        <!-- Lewa kolumna - treść -->
                        <div class="col-lg-8">
                            <div class="species-content-wrapper px-4">
                                <?php the_content(); ?>
                                
                                <!-- Dodatkowe galerie/obrazy -->
                                <?php
                                $gallery_images = get_post_meta(get_the_ID(), '_species_gallery', true);
                                if ($gallery_images) :
                                ?>
                                    <div class="species-gallery mt-4">
                                        <h3>Galeria</h3>
                                        <div class="gallery-grid">
                                            <?php foreach ($gallery_images as $image_id) : ?>
                                                <div class="gallery-item">
                                                    <?= wp_get_attachment_image($image_id, 'medium', false, array('class' => 'img-fluid rounded')); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Prawa kolumna - meta informacje -->
                        <div class="col-lg-4">
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="species-featured-image">
                                    <?php the_post_thumbnail('large', array(
                                        'class' => 'img-fluid rounded shadow',
                                        'alt' => get_the_title()
                                    )); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="species-sidebar">
                                
                                <!-- Szybkie informacje -->
                                <div class="species-meta-box">
                                    <h3 class="meta-box-title">
                                        <i class="bi bi-info-circle"></i>
                                        Podstawowe informacje
                                    </h3>
                                    <div class="meta-box-content">
                                        <?php
                                        $size = get_post_meta(get_the_ID(), '_species_size', true);
                                        $habitat = get_post_meta(get_the_ID(), '_species_habitat', true);
                                        $light = get_post_meta(get_the_ID(), '_species_light', true);
                                        $watering = get_post_meta(get_the_ID(), '_species_watering', true);
                                        $temperature = get_post_meta(get_the_ID(), '_species_temperature', true);
                                        ?>
                                        
                                        <?php if ($size) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label">
                                                    <i class="bi bi-arrows-angle-expand"></i>
                                                    Rozmiar:
                                                </span>
                                                <span class="meta-value"><?= esc_html($size) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($habitat) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label">
                                                    <i class="bi bi-tree"></i>
                                                    Habitat:
                                                </span>
                                                <span class="meta-value"><?= esc_html($habitat) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($light) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label">
                                                    <i class="bi bi-sun"></i>
                                                    Światło:
                                                </span>
                                                <span class="meta-value"><?= esc_html($light) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($watering) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label">
                                                    <i class="bi bi-droplet"></i>
                                                    Podlewanie:
                                                </span>
                                                <span class="meta-value"><?= esc_html($watering) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($temperature) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label">
                                                    <i class="bi bi-thermometer"></i>
                                                    Temperatura:
                                                </span>
                                                <span class="meta-value"><?= esc_html($temperature) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="meta-item">
                                            <span class="meta-label">
                                                <i class="bi bi-calendar3"></i>
                                                Dodano:
                                            </span>
                                            <span class="meta-value"><?= get_the_date('j F Y') ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kategorie i tagi -->
                                <?php
                                $species_categories = get_the_terms(get_the_ID(), 'species_category');
                                $species_tags = get_the_terms(get_the_ID(), 'species_tag');
                                ?>
                                
                                <?php if ($species_categories && !is_wp_error($species_categories)) : ?>
                                    <div class="species-categories-box">
                                        <h3 class="meta-box-title">
                                            <i class="bi bi-bookmark"></i>
                                            Kategorie
                                        </h3>
                                        <div class="categories-list">
                                            <?php foreach ($species_categories as $category) : ?>
                                                <a href="<?= esc_url(get_term_link($category)) ?>" 
                                                   class="category-link">
                                                    <?= esc_html($category->name) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($species_tags && !is_wp_error($species_tags)) : ?>
                                    <div class="species-tags-box">
                                        <h3 class="meta-box-title">
                                            <i class="bi bi-tags"></i>
                                            Tagi
                                        </h3>
                                        <div class="tags-list">
                                            <?php foreach ($species_tags as $tag) : ?>
                                                <a href="<?= esc_url(get_term_link($tag)) ?>" 
                                                   class="tag-link">
                                                    <?= esc_html($tag->name) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sekcja bibliografii -->
                <?php
                $bibliography = get_post_meta(get_the_ID(), '_species_bibliography', true);
                if ($bibliography) :
                ?>
                    <footer class="species-footer">
                        <div class="species-bibliography">
                            <h3>
                                <i class="bi bi-book"></i>
                                Bibliografia i źródła
                            </h3>
                            <div class="bibliography-content">
                                <?= wpautop($bibliography) ?>
                            </div>
                        </div>
                    </footer>
                <?php endif; ?>
                
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>