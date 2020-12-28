<section id="feature" class="py-5 container-fluid">
    <div class="container">
        <div class="row featurette" >
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="col-md-7">
                        <h2 class="featurette-heading"><?= get_field('feature_title', 7) ?></h2>
                        <p class="lead"><?= get_field('feature_content', 7) ?></p>
                    </div>
                    <div class="col-md-5">
                        <?php $item = get_field('feature_image', 7); ?>
                        <img src="<?= $item['sizes']['scene'] ?>" class="d-block w-100 featurette-image img-fluid mx-auto" width="500" height="500" alt="<?= get_field('feature_title', 7) ?>">
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </div>
</section>