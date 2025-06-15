<?php 
/**
 * Template for displaying single guides - DOPRACOWANY
 * wp-content/themes/carni24/single-guides.php
 */

get_header(); ?>

<main class="single-guide-main">
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

    <div class="container-fluid p-5">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="guide-article">
                
                <!-- Header z obrazkiem wyróżniającym -->
                <header class="guide-header">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="guide-title"><?php the_title(); ?></h1>
                            
                            <div class="guide-meta-info">
                                <?php
                                $difficulty = get_post_meta(get_the_ID(), '_guide_difficulty', true);
                                $duration = get_post_meta(get_the_ID(), '_guide_duration', true);
                                $season = get_post_meta(get_the_ID(), '_guide_season', true);
                                ?>
                                
                                <?php if ($difficulty) : ?>
                                    <span class="meta-badge difficulty-<?= esc_attr(strtolower($difficulty)) ?>">
                                        <i class="bi bi-star-fill"></i>
                                        Poziom: <?= esc_html($difficulty) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($duration) : ?>
                                    <span class="meta-badge">
                                        <i class="bi bi-clock"></i>
                                        Czas: <?= esc_html($duration) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($season) : ?>
                                    <span class="meta-badge">
                                        <i class="bi bi-calendar4-week"></i>
                                        Sezon: <?= esc_html($season) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Obrazek wyróżniający -->
                        <div class="col-lg-4">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="guide-featured-image">
                                    <?php the_post_thumbnail('large', array(
                                        'class' => 'img-fluid rounded shadow',
                                        'alt' => get_the_title()
                                    )); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <!-- Główna treść - 2 kolumny -->
                <div class="guide-main-content">
                    <div class="row">
                        <!-- Lewa kolumna - treść -->
                        <div class="col-lg-8">
                            <div class="guide-content-wrapper">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                        <!-- Prawa kolumna - meta informacje -->
                        <div class="col-lg-4">
                            <div class="guide-sidebar">
                                
                                <!-- Informacje o poradniku -->
                                <div class="guide-meta-box">
                                    <h3 class="meta-box-title">
                                        <i class="bi bi-info-circle"></i>
                                        Informacje o poradniku
                                    </h3>
                                    <div class="meta-box-content">
                                        <?php
                                        $tools = get_post_meta(get_the_ID(), '_guide_tools', true);
                                        $materials = get_post_meta(get_the_ID(), '_guide_materials', true);
                                        ?>
                                        
                                        <?php if ($tools) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label">
                                                    <i class="bi bi-tools"></i>
                                                    Narzędzia:
                                                </span>
                                                <span class="meta-value"><?= esc_html($tools) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($materials) : ?>
                                            <div class="meta-item">
                                                <span class="meta-label">
                                                    <i class="bi bi-box"></i>
                                                    Materiały:
                                                </span>
                                                <span class="meta-value"><?= esc_html($materials) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="meta-item">
                                            <span class="meta-label">
                                                <i class="bi bi-person"></i>
                                                Autor:
                                            </span>
                                            <span class="meta-value"><?= get_the_author() ?></span>
                                        </div>
                                        
                                        <div class="meta-item">
                                            <span class="meta-label">
                                                <i class="bi bi-calendar3"></i>
                                                Opublikowano:
                                            </span>
                                            <span class="meta-value"><?= get_the_date('j F Y') ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kategorie i tagi -->
                                <?php
                                $guide_categories = get_the_terms(get_the_ID(), 'guide_category');
                                $guide_tags = get_the_terms(get_the_ID(), 'guide_tag');
                                ?>
                                
                                <?php if ($guide_categories && !is_wp_error($guide_categories)) : ?>
                                    <div class="guide-categories-box">
                                        <h3 class="meta-box-title">
                                            <i class="bi bi-bookmark"></i>
                                            Kategorie
                                        </h3>
                                        <div class="categories-list">
                                            <?php foreach ($guide_categories as $category) : ?>
                                                <a href="<?= esc_url(get_term_link($category)) ?>" 
                                                   class="category-link">
                                                    <?= esc_html($category->name) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($guide_tags && !is_wp_error($guide_tags)) : ?>
                                    <div class="guide-tags-box">
                                        <h3 class="meta-box-title">
                                            <i class="bi bi-tags"></i>
                                            Tagi
                                        </h3>
                                        <div class="tags-list">
                                            <?php foreach ($guide_tags as $tag) : ?>
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
                $bibliography = get_post_meta(get_the_ID(), '_guide_bibliography', true);
                if ($bibliography) :
                ?>
                    <footer class="guide-footer">
                        <div class="guide-bibliography">
                            <h3>
                                <i class="bi bi-book"></i>
                                Bibliografia i źródła
                            </h3>
                            <div class="bibliography-content px-4">
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