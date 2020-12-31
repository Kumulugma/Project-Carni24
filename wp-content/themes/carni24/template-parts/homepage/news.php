<section id="news" class="py-3 container">

    <div class="row mx-2 mb-2">
        <div class="col-12 border-bottom my-2">
            <h2>Aktualne wpisy</h2>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php
        global $paged;
        $paged = get_query_var('page') ? get_query_var('page') : 1;
        ?>
        <?php
        $latest_post = new WP_Query(array('posts_per_page' => 8, 'offset' => ((($paged - 1) * 8)), 'orderby' => 'date', 'order' => 'DESC', 'paged' => $paged));

        while ($latest_post->have_posts()) : $latest_post->the_post();
            ?>
            <?php $post_thumb = get_the_post_thumbnail_url(get_the_ID(), 'blog_thumb'); ?>

            <div class="col">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <div class="card shadow-sm">
                        <div class="news-img" style="background-image:url('<?= $post_thumb ?>')"></div>

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
                        <div class="card-body">
                            <h5><?php the_title(); ?></h5>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted"><?php the_category(', '); ?></small>
                        </div>
                    </div>
                </a>
            </div>

        <?php endwhile; ?>
    </div>
    <div class="pagination_wrap d-flex justify-content-center my-2">
        <?php
            $total_pages = $latest_post->max_num_pages;
           
            if ($total_pages > 1) {
                
            global $wp_query;
            $wp_query->query_vars['page'] > 1 ? $current = $wp_query->query_vars['page'] : $current = 1;

                $current_page = max(1, get_query_var('page'));

                echo paginate_links(array(
                    'base' => @add_query_arg('paged','%#%'),
                    'format' => '',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => __('« Poprzednia'),
                    'next_text' => __('Następna »'),
                ));
            }
            ?>
    </div>
    <?php
    wp_reset_postdata();
    wp_reset_query();
    ?>
</div>
</section>
