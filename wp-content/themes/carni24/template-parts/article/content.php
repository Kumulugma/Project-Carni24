<section id="article" class="py-5 container-fluid">
    <div class="container card p-2">
        <div class="row article" >
            <div class="col-md-<?= (get_post_type() != 'post') ? 6 : 8 ?>">
                <h2 class="article-heading border-bottom"><?php the_title(); ?></h2>
                <p class="lead"><?php the_content(); ?></p>
            </div>
            <?php if (get_post_type() != 'post') { ?>
                <div class="col-md-2">
                    <?php if (get_field('place')) { ?>
                        <div class="row">
                            <div class="col fadeInLeft animate">
                                <div class='row article-box'>
                                    <div class='col-2'>
                                        <div class="droplet mx-auto justify-content-center d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class='col-10'><h4 class='mb-0 mt-3'><?= get_field('place') ?></h4></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (get_field('annual')) { ?>
                        <div class="row">
                            <div class="col fadeInLeft animate">
                                <div class='row article-box'>
                                    <div class='col-2'>
                                        <div class="droplet mx-auto justify-content-center d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class='col-10'><h4 class='mb-0 mt-3'><?= (get_field('annual') == 'nie') ? 'Jednoroczne' :'Wieloletnie' ?></h4></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="col-md-4">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php
                    $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
                    echo esc_url($src[0]);
                    ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('500x500', array('class' => 'img-thumbnail')); ?></a>
<?php endif; ?>
            </div>
            <?php if (get_field('gallery')) { ?>
            <div class="col-12">
                <?= do_shortcode(get_field('gallery')) ?>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
