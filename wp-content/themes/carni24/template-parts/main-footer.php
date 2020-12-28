<footer id="footer" class="text-muted py-5 ">
    <div class="container-fluid px-5">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-lg-3">
                <h5>Nasze zasoby</h5>
                    <?php wp_nav_menu(
                            array(
                                    'menu' => 'Stopka - Menu informacji',
                                    'container'       => false,
                                    'items_wrap' => '<ul id="%1$s" class="list-unstyled text-small nav">%3$s</ul>'
                            )
                        ); 
                    ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-lg-3">
                <h5>Najnowsze wpisy</h5>
                <ul class="list-unstyled text-small nav">
                    <?php $the_query = new WP_Query('posts_per_page=5'); ?>

                    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>

                        <li class="nav-item"><a class="nav-link link-secondary" href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>

                    <?php endwhile;
                    wp_reset_postdata(); ?>

                </ul>
            </div>
            <div class="col-xs-12 col-sm-6 col-lg-3">
                <h5>Informacje</h5>
                    <?php wp_nav_menu(
                            array(
                                    'menu' => 'Stopka - Menu informacji',
                                    'container'       => false,
                                    'items_wrap' => '<ul id="%1$s" class="list-unstyled text-small nav">%3$s</ul>'
                            )
                        ); 
                    ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-lg-2 small d-flex align-items-end justify-content-end">
                <div class="row row-col-12">
                    <div class="mb-1 d-flex justify-content-end">Copyright © <?php echo esc_html(date_i18n(__('Y', 'carni24'))); ?> <?php echo esc_html(get_bloginfo('name')); ?></div>
                    <div class="mb-1 d-flex justify-content-end">Realizacja: <a href="#" class="nav-link ms-2 p-0">K3e.pl</a></div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-lg-1 d-flex justify-content-center">
                <p>
                    <a href="#" class="link-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-square-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 11.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11.5z"/>
                        </svg>
                    </a>
                </p>
            </div>

        </div>
    </div>
</footer>