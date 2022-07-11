<section id="carousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <?php $item = get_field('carousel_image_1', 7); ?>
                <div class="carousel-item active" data-bs-interval="<?= get_field('carousel_interval_1', 7) ?>">
                    <img class="d-block w-100 lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?= $item['sizes']['scene'] ?>" alt="<?= get_field('carousel_title_1', 7) ?>">
                </div>
                <?php $item = get_field('carousel_image_2', 7); ?>
                <?php if (isset($item['sizes'])) { ?>
                    <div class="carousel-item active" data-bs-interval="<?= get_field('carousel_interval_2', 7) ?>">
                        <img class="d-block w-100 lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?= $item['sizes']['scene'] ?>" alt="<?= get_field('carousel_title_2', 7) ?>">
                    </div> 
                <?php } ?>
                <?php $item = get_field('carousel_image_3', 7); ?>
                <?php if (isset($item['sizes'])) { ?>
                    <div class="carousel-item active" data-bs-interval="<?= get_field('carousel_interval_3', 7) ?>">
                        <img class="d-block w-100 lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?= $item['sizes']['scene'] ?>" alt="<?= get_field('carousel_title_3', 7) ?>">
                    </div> 
                <?php } ?>    
                <?php $item = get_field('carousel_image_4', 7); ?>
                <?php if (isset($item['sizes'])) { ?>
                    <div class="carousel-item active" data-bs-interval="<?= get_field('carousel_interval_4', 7) ?>">
                        <img class="d-block w-100 lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?= $item['sizes']['scene'] ?>" alt="<?= get_field('carousel_title_4', 7) ?>">
                    </div> 
                <?php } ?>
                <?php $item = get_field('carousel_image_5', 7); ?>
                <?php if (isset($item['sizes'])) { ?>
                    <div class="carousel-item active" data-bs-interval="<?= get_field('carousel_interval_5', 7) ?>">
                        <img class="d-block w-100 lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?= $item['sizes']['scene'] ?>" alt="<?= get_field('carousel_title_5', 7) ?>">
                    </div> 
                <?php } ?>

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