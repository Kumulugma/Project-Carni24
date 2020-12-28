<section id="news" class="py-3 container">

    <div class="row mx-2 mb-2">
        <div class="col-12 border-bottom my-2">
            <h2>Aktualne wpisy</h2>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        <?php for($i = 0; $i < 8; $i ++) { ?>
        <div class="col">
            <div class="card shadow-sm">
                <div class="news-img" style="background-image:url('<?php bloginfo('template_url'); ?>/images/carousel/1.JPG');"></div>

                <div class="post-side">
                    <div class="post-calendar" data-content="10-26-2020">
                        <div class="post-calendar-m">
                            Październik				
                        </div>
                        <div class="post-calendar-d">
                            26				
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5>Tytuł</h5>
                    <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Zobacz więcej</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">Kategoria</small>
                </div>
            </div>
        </div>
        <?php } ?>
        
    </div>
</section>
