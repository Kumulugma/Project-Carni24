<!DOCTYPE html>
<html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="">
        <meta name="author" content="Kumulugma">
        <title><?php wp_title(':', true, 'right'); ?> <?php bloginfo('title'); ?> - <?php bloginfo('description'); ?></title>
        <meta name="descripton" content="<?php wp_title(); ?>">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

        <?php wp_head(); ?>

    </head>

    <?php
    get_template_part('template-parts/main-header');
    