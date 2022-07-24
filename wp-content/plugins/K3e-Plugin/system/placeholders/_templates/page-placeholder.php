<!DOCTYPE html>
<html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="">
        <meta name="author" content="Kumulugma">
        <title><?php bloginfo('name'); ?></title>
        <meta name="descripton" content="<?php bloginfo('description'); ?>">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php wp_head(); ?>

    </head>
    <body class="d-flex h-100 text-center text-white bg-dark">
        <?php wp_body_open(); ?>

        <?php the_content(); ?>

        <?php wp_footer(); ?>
    </body>
</html>