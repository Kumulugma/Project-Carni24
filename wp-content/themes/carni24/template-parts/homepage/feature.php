<section id="feature" class="py-5 container-fluid">
    <div class="container">
        <div class="row featurette">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="col-md-7">
                        <h2 class="featurette-heading"><?= esc_html(get_post_meta(get_the_ID(), 'feature_title', true)) ?></h2>
                        <p class="lead"><?= wp_kses_post(get_post_meta(get_the_ID(), 'feature_content', true)) ?></p>
                    </div>
                    <div class="col-md-5">
                        <?php $item = get_post_meta(get_the_ID(), 'feature_image', true); ?>
                        <?php if (!empty($item)) : ?>
                        <img class="d-block w-100 featurette-image img-fluid mx-auto lazyload" 
                             src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                             data-src="<?= esc_url(wp_get_attachment_image_url($item, 'feature')) ?>" 
                             width="600" 
                             height="400" 
                             alt="<?= esc_attr(get_post_meta(get_the_ID(), 'feature_title', true)) ?>">
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</section>