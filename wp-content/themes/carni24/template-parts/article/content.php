<section id="article" class="py-5 container-fluid">
    <div class="container card p-2">
        <div class="row article" >
            <div class="col-md-12">
                <h2 class="article-heading border-bottom"><?php the_title(); ?></h2>
                <div class="">
                    <?php if (has_post_thumbnail(get_the_ID())): ?>
                        <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'scene'); ?>
                        <img class="img-fluid lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?php echo $image[0]; ?>" alt="<?php the_title() ?>">
                    <?php endif; ?>
                </div>
                <p class="lead"><?php the_content(); ?></p>

                <div class="article-footer border-top d-flex justify-content-between align-items-center">
                    <?php $author = get_the_author_meta('display_name', $post->post_author); ?>
                    <div class="blog-post-author p-2">
                        <span><img class="rounded lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?= get_avatar_url($post->post_author) ?>" title="<?= $author ?>" alt="<?= $author ?>"> <?= $author ?></span>
                    </div>
                    <div class="blog-post-time">
                        <a href="<?= get_permalink(get_the_ID()) ?>"><i class="far fa-clock"></i><?=__("Dodano: ", 'carni24')?><?= get_the_date() ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
