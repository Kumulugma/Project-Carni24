<section id="manifest" class="bg-light border-top border-bottom container-fluid">
    <div class="row mx-2 mt-2">
        <div class="col-12 border-bottom my-2">
            <h2>Popularne wpisy</h2>
        </div>
    </div>
    <?php
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 2,
        'orderby' => 'rand'
    );
    ?>
    <div class="row row-cols-1 row-cols-sm-2 m-2 py-2">

        <?php
        $random_post = new WP_Query($args);

        while ($random_post->have_posts()) : $random_post->the_post();
            ?>
            <?php $post_thumb = get_the_post_thumbnail_url(get_the_ID(), 'blog_thumb'); ?>

            <div class="col px-1 article">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <div class="row g-0 border rounded overflow-hidden flex-md-row shadow-sm h-md-250 position-relative">

                        <div class="col p-4 d-flex flex-column position-static">
                            <h3 class="mb-0 border-bottom"><?php the_title(); ?></h3>
                            <p class="mb-auto"><?php the_excerpt(); ?></p>
                            <strong class="d-inline-block mb-2 text-muted"><?php the_category(', '); ?></strong>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary">Zobacz więcej</button>
                            </div>
                        </div>

                        <div class="col-auto d-none d-lg-block">
                            <div class="manifest-img" style="background-image:url('<?= $post_thumb ?>');"></div>
                        </div>

                        <div class="post-side">
                            <div class="post-calendar">
                                <div class="post-calendar-m">
                                    <?php the_date('F'); ?>					
                                </div>
                                <div class="post-calendar-d">
                                    <?= get_the_date('d'); ?>					
                                </div>
                            </div>
                        </div>
                    </div>
                </a>    
            </div>



        <?php endwhile; ?>
    </div>


</section>