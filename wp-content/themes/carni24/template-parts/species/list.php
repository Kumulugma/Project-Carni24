<section id="list" class="py-5 container-fluid">
    <div class="container card p-2">
        <div class="row article" >

            <div class="col-md-12">
                <h2 class="article-heading border-bottom"><?php the_title() ?></h2>
                <?php the_content(); ?>
            </div>

            <div class="accordion accordion-flush" id="accordion">
                <?php
                $args = array(
                    'type' => 'species',
                    'child_of' => 0,
                    'parent' => '',
                    'orderby' => 'name',
                    'order' => 'DESC',
                    'hide_empty' => 1,
                    'hierarchical' => 1,
                    "exclude"   => 15,
                    'pad_counts' => false
                    );
                $categories = get_categories($args);


                array_pop($categories);
                foreach ($categories as $category) {
                    ?>
                    <div class="accordion-item"> 
                        <h2 class="accordion-header" id="flush-heading<?= $category->slug ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $category->slug ?>" aria-expanded="false" aria-controls="flush-collapse<?= $category->slug ?>">
                                <?php echo $category->name; ?>
                            </button>
                        </h2>
                        <div id="flush-collapse<?= $category->slug ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $category->slug ?>" data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <?php
                                    $species = new WP_Query(array(
                                        'post_type' => 'species',
                                        'cat' => $category->cat_ID,
                                        'orderby' => 'post_title',
                                        'order' => 'ASC',
                                        'posts_per_page' => -1
                                    ));
                                    ?>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="list-group" id="list-tab" role="tablist">
                                            <?php $i = 0; ?>
                                            <?php while ($species->have_posts()) : $species->the_post(); ?>
                                                <a 
                                                    class="list-group-item list-group-item-action <?= ($i == 0 ? 'active' : '') ?>" 
                                                    id="list-<?= $category->slug ?>-<?= get_the_ID() ?>-list" 
                                                    data-bs-toggle="list" 
                                                    href="#list-<?= $category->slug ?>-<?= get_the_ID() ?>" 
                                                    role="tab" 
                                                    aria-controls="<?= get_the_ID() ?>"
                                                    aria-selected="<?= ($i == 0 ? 'true' : 'false') ?>"
                                                    >
                                                        <?= get_the_title() ?>
                                                </a>
                                                <?php $i++; ?>
                                            <?php endwhile; ?>
                                            <?php wp_reset_query(); ?>
                                        </div>
                                    </div>                                    
                                    <div class="col-xs-12 col-md-8">
                                        <div class="tab-content p-2" id="nav-tabContent">
                                            <?php $i = 0; ?>
                                            <?php while ($species->have_posts()) : $species->the_post(); ?>
                                                <div 
                                                    class="tab-pane fade <?= ($i == 0 ? 'show active' : '') ?>" 
                                                    id="list-<?= $category->slug ?>-<?= get_the_ID() ?>" 
                                                    role="tabpanel" 
                                                    aria-labelledby="list-<?= $category->slug ?>-<?= get_the_ID() ?>-list"
                                                    >
                                                    <?= get_the_content() ?>
                                                </div>

                                                <?php $i++; ?>
                                            <?php endwhile; ?>
                                            <?php wp_reset_query(); ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
