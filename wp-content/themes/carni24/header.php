<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    
        <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="">
        <meta name="author" content="Kumulugma">
        <title><?php wp_title(':', true, 'right'); ?> <?php bloginfo('title'); ?> - <?php bloginfo('description'); ?></title>
        <meta name="descripton" content="<?php wp_title(); ?>">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        
        <?php wp_head(); ?>
        
        <!-- Bootstrap core CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

        <!-- Custom -->
        <link href="<?php bloginfo('template_url'); ?>/css/style.css" rel="stylesheet">
        <link href="<?php bloginfo('template_url'); ?>/css/scene.css" rel="stylesheet">
        <link href="<?php bloginfo('template_url'); ?>/css/homepage.css" rel="stylesheet">


        <!-- Favicons -->
        <meta name="theme-color" content="#7952b3">
    </head>

<?php get_template_part( 'template-parts/main-header' ); 