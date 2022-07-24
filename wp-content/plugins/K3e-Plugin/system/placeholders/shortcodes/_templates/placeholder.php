<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
        <div>
            <a href="<?=get_site_url()?>" id="main-logo" ><h3 class="float-md-start mb-0"><?= unserialize(get_option(K3E::OPTION_PLACEHOLDER_NAME)) ?></h3></a>
            <?php $tabs = unserialize(get_option(K3E::OPTION_PLACEHOLDERS)); ?>
            <?php
            $tab1 = 0;
            $tab2 = 0;
            ?>
            <nav class="nav nav-masthead justify-content-center float-md-end">
                <?php for ($i = 0; $i < unserialize(get_option(K3E::OPTION_PLACEHOLDER_AMOUNT)); $i++) { ?>
                    <?php if ($tabs[$i]['active'] == 1) { ?> 
                        <a class="nav-link <?= $tab1 == 0 ? 'active' : '' ?>" id="tab-<?= strtolower($tabs[$i]['headling']) ?>-tab" data-bs-toggle="tab" data-bs-target="#tab-<?= strtolower($tabs[$i]['headling']) ?>" role="tab" aria-controls="tab-<?= strtolower($tabs[$i]['headling']) ?>" aria-selected="true"><?= $tabs[$i]['headling'] ?></a>
                        <?php $tab1++; ?>
                    <?php } ?>
                <?php } ?>
            </nav>
        </div>
    </header>

    <main class="px-3">
        <div class="tab-content" id="TabsContent">
            <?php for ($i = 0; $i < unserialize(get_option(K3E::OPTION_PLACEHOLDER_AMOUNT)); $i++) { ?>
                <?php if ($tabs[$i]['active'] == 1) { ?> 
                    <div class="tab-pane fade show <?= $tab2 == 0 ? 'active' : '' ?>" id="tab-<?= strtolower($tabs[$i]['headling']) ?>" role="tabpanel" aria-labelledby="<?= strtolower($tabs[$i]['headling']) ?>-tab">
                        <h1><?= $tabs[$i]['headling'] ?></h1>
                        <p class="lead">
                            <?= $tabs[$i]['content'] ?>
                        </p>
                    </div>
                    <?php $tab2++; ?>
                <?php } ?>
            <?php } ?>

        </div>

    </main>

    <footer class="mt-auto text-white-50">
        <p><?=__('Wspierane przez ', 'k3e')?><a href="https://k3e.pl/" class="text-white">K3e.pl</a></p>
    </footer>
</div>