<section id="carousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                
                <?php $item = get_post_meta(get_the_ID(), 'carousel_image_1', true); ?>
                <?php if (!empty($item)) : ?>
                <div class="carousel-item active" data-bs-interval="<?= esc_attr(get_post_meta(get_the_ID(), 'carousel_interval_1', true)) ?>">
                    <img class="d-block w-100 lazyload" 
                         src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                         data-src="<?= esc_url(wp_get_attachment_image_url($item, 'carousel')) ?>" 
                         alt="<?= esc_attr(get_post_meta(get_the_ID(), 'carousel_title_1', true)) ?>"
                         width="1920"
                         height="1080">
                </div>
                <?php endif; ?>

                <?php for ($i = 2; $i <= 5; $i++) : ?>
                    <?php $item = get_post_meta(get_the_ID(), "carousel_image_$i", true); ?>
                    <?php if (!empty($item)) : ?>
                        <div class="carousel-item" data-bs-interval="<?= esc_attr(get_post_meta(get_the_ID(), "carousel_interval_$i", true)) ?>">
                            <img class="d-block w-100 lazyload" 
                                 src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                                 data-src="<?= esc_url(wp_get_attachment_image_url($item, 'carousel')) ?>" 
                                 alt="<?= esc_attr(get_post_meta(get_the_ID(), "carousel_title_$i", true)) ?>"
                                 width="1920"
                                 height="1080">
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
    
    <a class="carousel-control-prev" href="#carousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Poprzednie</span>
    </a>
    <a class="carousel-control-next" href="#carousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Następne</span>
    </a>
</section>