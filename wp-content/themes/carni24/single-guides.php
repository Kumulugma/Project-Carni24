<?php 
/**
 * Template for displaying single guides
 * wp-content/themes/carni24/single-guides.php
 */

get_header(); ?>

<main class="single-guide-main">
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

        <!-- Guide Content -->
        <div class="guide-content-section">
            <div class="container">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article class="guide-article">
                        <!-- Header -->
                        <header class="guide-header">
                            <div class="row">
                                <div class="col-12">
                                    <h1 class="guide-title"><?php the_title(); ?></h1>
                                    
                                    <!-- Guide Meta Info -->
                                    <div class="guide-meta-info">
                                        <?php 
                                        $difficulty = get_post_meta(get_the_ID(), '_guide_difficulty', true);
                                        $duration = get_post_meta(get_the_ID(), '_guide_duration', true);
                                        $season = get_post_meta(get_the_ID(), '_guide_season', true);
                                        ?>
                                        
                                        <?php if ($difficulty) : ?>
                                            <span class="guide-difficulty-badge difficulty-<?= esc_attr($difficulty) ?>">
                                                <?= esc_html(carni24_get_guide_difficulty()) ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($duration) : ?>
                                            <span class="guide-duration">
                                                <i class="bi bi-clock"></i> <?= esc_html($duration) ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($season) : ?>
                                            <span class="guide-season">
                                                <i class="bi bi-calendar"></i> <?= esc_html(carni24_get_guide_season()) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </header>

                        <!-- Main Content Area -->
                        <div class="guide-main-content">
                            <div class="row">
                                <!-- Left Column - Content -->
                                <div class="col-lg-8">
                                    <div class="guide-content-wrapper">
                                        <?php the_content(); ?>
                                    </div>
                                </div>

                                <!-- Right Column - Sidebar -->
                                <div class="col-lg-4">
                                    <div class="guide-sidebar">
                                        <!-- Guide Meta -->
                                        <div class="guide-meta-box">
                                            <h4>Szczegóły poradnika</h4>
                                            <div class="meta-content">
                                                <!-- Placeholder for meta fields - to be filled later -->
                                                <div class="meta-item">
                                                    <span class="meta-label">Poziom trudności:</span>
                                                    <span class="meta-value">
                                                        <?= esc_html(carni24_get_guide_difficulty() ?: '—') ?>
                                                    </span>
                                                </div>
                                                <div class="meta-item">
                                                    <span class="meta-label">Czas realizacji:</span>
                                                    <span class="meta-value">
                                                        <?= esc_html(carni24_get_guide_duration() ?: '—') ?>
                                                    </span>
                                                </div>
                                                <div class="meta-item">
                                                    <span class="meta-label">Najlepszy sezon:</span>
                                                    <span class="meta-value">
                                                        <?= esc_html(carni24_get_guide_season() ?: '—') ?>
                                                    </span>
                                                </div>
                                                <?php 
                                                $tools = carni24_get_guide_tools();
                                                if ($tools) : ?>
                                                    <div class="meta-item">
                                                        <span class="meta-label">Potrzebne narzędzia:</span>
                                                        <div class="meta-value">
                                                            <?= wp_kses_post($tools) ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Related Categories -->
                                        <?php 
                                        $categories = get_the_terms(get_the_ID(), 'guide_category');
                                        if ($categories && !is_wp_error($categories)) : ?>
                                            <div class="guide-categories-box">
                                                <h4>Kategorie</h4>
                                                <div class="categories-list">
                                                    <?php foreach ($categories as $category) : ?>
                                                        <a href="<?= esc_url(get_term_link($category)) ?>" class="category-link">
                                                            <?= esc_html($category->name) ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Related Tags -->
                                        <?php 
                                        $tags = get_the_terms(get_the_ID(), 'guide_tag');
                                        if ($tags && !is_wp_error($tags)) : ?>
                                            <div class="guide-tags-box">
                                                <h4>Tagi</h4>
                                                <div class="tags-list">
                                                    <?php foreach ($tags as $tag) : ?>
                                                        <a href="<?= esc_url(get_term_link($tag)) ?>" class="tag-link">
                                                            #<?= esc_html($tag->name) ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bibliography Section -->
                        <footer class="guide-footer">
                            <div class="row">
                                <div class="col-12">
                                    <?php 
                                    $bibliography = get_post_meta(get_the_ID(), '_guide_bibliography', true);
                                    if ($bibliography) : ?>
                                        <div class="guide-bibliography">
                                            <h3>Bibliografia i źródła</h3>
                                            <div class="bibliography-content">
                                                <?= wp_kses_post($bibliography) ?>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="guide-bibliography">
                                            <h3>Bibliografia i źródła</h3>
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