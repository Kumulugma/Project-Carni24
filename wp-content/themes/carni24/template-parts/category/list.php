<section id="category" class="py-5 container-fluid">
    <div class="container p-2">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">

            <?php
            global $paged;
            $paged = get_query_var('page') ? get_query_var('page') : 1;
            ?>
            <?php
            $args = array(
                'posts_per_page' => 12,
                'cat' => get_query_var('cat'),
                'offset' => ((($paged - 1) * 12)),
                'orderby' => 'date',
                'order' => 'DESC',
                'paged' => $paged
            );
            $category_items = new WP_Query($args);

            while ($category_items->have_posts()) : $category_items->the_post();
                ?>
                <?php $post_thumb = get_the_post_thumbnail_url(get_the_ID(), 'blog_thumb'); ?>

                <div class="col">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <div class="card shadow-sm">
                            <div class="news-img" style="background-image:url('<?= $post_thumb ?>');"></div>

                            <div class="post-side">
                                <div class="post-calendar" data-content="10-26-2020">
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
                        </div>
                    </a>
                </div>

            <?php endwhile; ?>
        </div>
        <div class="pagination_wrap d-flex justify-content-center my-2">
            <?php
            $total_pages = $category_items->max_num_pages;

            if ($total_pages > 1) {
                
            global $wp_query;
            $wp_query->query_vars['page'] > 1 ? $current = $wp_query->query_vars['page'] : $current = 1;

                $current_page = max(1, get_query_var('page'));

                echo paginate_links(array(
                    'base' => @add_query_arg('page','%#%'),
                    'format' => '',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => __('« Poprzednia'),
                    'next_text' => __('Następna »'),
                ));
            }
            ?>
        </div>

    </div>
</div>
</section>
