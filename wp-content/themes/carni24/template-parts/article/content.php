<section id="article" class="py-5 container-fluid">
    <div class="container card p-2">
        <div class="row article" >
            <div class="col-md-6">
                <h2 class="article-heading border-bottom"><?php the_title(); ?></h2>
                <p class="lead"><?php the_content(); ?></p>
            </div>
            <div class="col-md-2">
                <div class="row">
                    <div class="col fadeInLeft animate">
                        <div class='row article-box'>
                            <div class='col-2'>
                                <div class="droplet mx-auto justify-content-center d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-richtext" viewBox="0 0 16 16">
                                        <path d="M7.5 3.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm-.861 1.542l1.33.886 1.854-1.855a.25.25 0 0 1 .289-.047L11 4.75V7a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 7v-.5s1.54-1.274 1.639-1.208zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                                        <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2z"/>
                                        <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1H1z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class='col-10'><h4 class='mb-0 mt-3'>38 wpisów</h4></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php $src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
                echo esc_url($src[0]); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('500x500', array('class' => 'img-thumbnail')); ?></a>
<?php endif; ?>
            </div>
        </div>
    </div>
</section>
