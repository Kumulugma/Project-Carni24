<section class="text-center container-fluid overflow-hidden g-3" id="scene">
    <div data-depth="0.6" class="container-fluid overflow-hidden" id="layer-1">
        <p></p>
    </div>
    <div id="hero" class="container-fluid">
        <div class="mt-5 card mx-auto hero-transparent" id="layer-2">
            <h1 class="fw-light"><?= get_field('scene_headling', 7) ?></h1>
            <p>
                <?= get_field('scene_content', 7) ?>
            </p>
        </div>
    </div>
</section>