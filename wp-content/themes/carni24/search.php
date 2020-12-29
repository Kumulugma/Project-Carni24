<?php /* Template Name: Wyszukiwarka */ ?>
<?php get_template_part( 'assets/search' ); ?>
<?php get_header(); ?>
<main>
<?php get_template_part( 'template-parts/main-scene' ); ?>
<?php get_template_part( 'template-parts/main-submenu' ); ?>
<?php get_template_part( 'template-parts/breadcrumbs' ); ?>
<?php get_template_part( 'template-parts/search/list' ); ?>    
</main>
<?php get_footer(); 
