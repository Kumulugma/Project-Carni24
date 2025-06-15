<?php
// wp-content/themes/carni24/template-parts/static/main.php
?>
<section id="static" class="py-5 container-fluid">
    <div class="container card p-2">
        <div class="row">
            <div class="col-12">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="page-header">
                            <h1 class="article-heading border-bottom"><?php the_title(); ?></h1>
                        </header>
                        
                        <div class="page-content">
                            <?php the_content(); ?>
                            
                            <?php
                            // Pokaż paginację dla stron z więcej niż jedną częścią
                            wp_link_pages(array(
                                'before' => '<div class="page-links"><span class="page-links-title">' . __('Strony:', 'carni24') . '</span>',
                                'after'  => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                            ));
                            ?>
                        </div>
                    </article>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
</section>