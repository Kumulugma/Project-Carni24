<?php 
/**
 * Template for displaying single blog posts
 * wp-content/themes/carni24/single.php
 */

get_header(); ?>

<main class="single-post-main">
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
        <div class="row">
            <!-- Główna treść -->
            <div class="col-lg-8">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article class="post-article">
                        
                        <!-- Header -->
                        <header class="post-header">
                            <h1 class="post-title"><?php the_title(); ?></h1>
                            
                            <div class="post-meta">
                                <span class="post-date">
                                    <i class="bi bi-calendar3"></i>
                                    <?= get_the_date('j F Y') ?>
                                </span>
                                <?php if (function_exists('carni24_get_reading_time')) : ?>
                                    <span class="post-reading-time">
                                        <i class="bi bi-clock"></i>
                                        <?= carni24_get_reading_time(get_the_content()) ?> min czytania
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Obrazek wyróżniający -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-featured-image">
                                    <?php the_post_thumbnail('large', array(
                                        'class' => 'img-fluid rounded shadow',
                                        'alt' => get_the_title()
                                    )); ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <!-- Treść -->
                        <div class="post-content px-4">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Tagi i kategorie -->
                        <footer class="post-footer">
                            <?php
                            $categories = get_the_category();
                            $tags = get_the_tags();
                            ?>
                            
                            <?php if ($categories) : ?>
                                <div class="post-categories">
                                    <h4>Kategorie:</h4>
                                    <div class="categories-list">
                                        <?php foreach ($categories as $category) : ?>
                                            <a href="<?= esc_url(get_category_link($category->term_id)) ?>" 
                                               class="category-link">
                                                <?= esc_html($category->name) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tags) : ?>
                                <div class="post-tags">
                                    <h4>Tagi:</h4>
                                    <div class="tags-list">
                                        <?php foreach ($tags as $tag) : ?>
                                            <a href="<?= esc_url(get_tag_link($tag->term_id)) ?>" 
                                               class="tag-link">
                                                <?= esc_html($tag->name) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </footer>
                        
                    </article>
                <?php endwhile; endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="post-sidebar">
                    <?php if (is_active_sidebar('blog-sidebar')) : ?>
                        <?php dynamic_sidebar('blog-sidebar'); ?>
                    <?php else : ?>
                        <!-- Domyślne widgety -->
                        <div class="widget">
                            <h3>Najnowsze wpisy</h3>
                            <?php
                            $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 5,
                                'post_status' => 'publish'
                            ));
                            
                            if ($recent_posts) :
                            ?>
                                <ul class="recent-posts-list">
                                    <?php foreach ($recent_posts as $post) : ?>
                                        <li>
                                            <a href="<?= esc_url(get_permalink($post['ID'])) ?>">
                                                <?= esc_html($post['post_title']) ?>
                                            </a>
                                            <span class="post-date"><?= get_the_date('j M Y', $post['ID']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>