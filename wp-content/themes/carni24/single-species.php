<?php 
/**
 * Template for displaying single species
 * wp-content/themes/carni24/single-species.php
 */

get_header(); ?>

<!-- Dodaj overlay wyszukiwarki -->
<?php get_template_part('template-parts/search-overlay'); ?>

<main class="single-species-main">
    <!-- Breadcrumbs -->
    <div class="container py-3">
        <?php if (function_exists('carni24_breadcrumbs')) carni24_breadcrumbs(); ?>
    </div>

    <!-- Treść gatunku -->
    <?php get_template_part('template-parts/article/content'); ?>
</main>

<?php get_footer(); ?>