<?php 
/**
 * Template for displaying single pages
 * wp-content/themes/carni24/page.php
 */

get_header(); ?>

<main class="single-page-main">
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

    <div class="container-fluid p-md-5">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="page-article">
                
                <!-- Header -->
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    
                    <!-- Obrazek wyróżniający -->
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="page-featured-image">
                            <?php the_post_thumbnail('large', array(
                                'class' => 'img-fluid rounded shadow',
                                'alt' => get_the_title()
                            )); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Treść -->
                <div class="page-content px-md-4">
                    <?php the_content(); ?>
                </div>
                
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>