<section id="conditions" class="py-5 container-fluid">
    <div class="container card p-2">
        <div class="row" >
            <div class="col-md-8">
                <h2 class="article-heading border-bottom"><?php the_title(); ?></h2>
                <?php the_content(); ?>
            </div>
            <div class="col-md-4">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
                    echo esc_url($src[0]);
                    ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('500x500', array('class' => 'img-thumbnail')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>