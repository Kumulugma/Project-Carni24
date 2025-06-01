<section id="article" class="py-5 container-fluid">
    <div class="container card p-2">
        <div class="row article">
            <div class="col-md-12">
                <h2 class="article-heading border-bottom"><?php the_title(); ?></h2>
                <div class="">
                    <?php if (has_post_thumbnail(get_the_ID())): ?>
                        <?php $image_url = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'scene'); ?>
                        <img class="img-fluid lazyload" 
                             src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                             data-src="<?= esc_url($image_url) ?>" 
                             alt="<?= esc_attr(get_the_title()) ?>"
                             width="1200"
                             height="675">
                    <?php endif; ?>
                </div>
                <p class="lead"><?php the_content(); ?></p>

                <div class="article-footer border-top d-flex justify-content-between align-items-center">
                    <?php $author = get_the_author_meta('display_name', $post->post_author); ?>
                    <div class="blog-post-author p-2">
                        <span>
                            <img class="rounded lazyload" 
                                 src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" 
                                 data-src="<?= esc_url(get_avatar_url($post->post_author, array('size' => 32))) ?>" 
                                 title="<?= esc_attr($author) ?>" 
                                 alt="<?= esc_attr($author) ?>"
                                 width="32"
                                 height="32"> 
                            <?= esc_html($author) ?>
                        </span>
                    </div>
                    <div class="blog-post-time">
                        <a href="<?= esc_url(get_permalink(get_the_ID())) ?>">
                            <i class="far fa-clock"></i><?= esc_html(__("Dodano: ", 'carni24')) ?><?= esc_html(get_the_date()) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>