<?php /* Template Name: Strona domowa */ ?>
<?php get_header(); ?>
<main>
<?php get_template_part( 'template-parts/main-submenu' ); ?>
<?php get_template_part( 'template-parts/homepage/searchbar' ); ?>
<?php get_template_part( 'template-parts/homepage/news' ); ?>
<?php get_template_part( 'template-parts/homepage/carousel' ); ?>
<?php get_template_part( 'template-parts/homepage/manifest' ); ?>    
</main>
<?php get_footer(); 