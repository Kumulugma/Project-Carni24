<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Konfiguracja', 'k3e'); ?>
    </h1>


    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="postbox-container" width="25%">
                <div class="card" style="margin:2px">
                    <form method="post" action="admin.php?page=konfiguracja">
                        <fieldset>
                            <?php foreach (K3eSystem::getModules() as $module => $args) { ?>
                                <p>
                                    <input type="checkbox" id="<?= $module ?>Form" name="System[<?= $module ?>]" value="1" <?= ($args['status'] == 1) ? "checked" : "" ?>>
                                    <label for="<?= $module ?>Form">Funkcjonalność: <?= $args['name'] ?></label>
                                </p>
                            <?php } ?>
                            <button class="button button-primary" type="submit">Zapisz</button>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="postbox-container" width="25%">
                <div class="card" style="margin:2px">
                    <table class="widefat importers striped" style="width:100%">
                        <thead><tr><th colspan="2">Moduły systemowe</th></tr></thead>
                        <tbody>
                            <?php foreach (K3eSystem::getModules() as $module => $args) { ?>
                            <tr class="importer-item">
                                <td class="import-system"><?= $args['name'] ?></td>
                            <td class="desc"><?=class_exists($args['class']) ? $args['class']::VERSION : "<span>Nie załadowane</span>"?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox-container" width="25%">
                <div class="card" style="margin:2px">
                    <table class="widefat importers striped" style="width:100%">
                        <thead><tr><th colspan="2">Moduły dodatkowe</th></tr></thead>
                        <tbody>
                            <?php foreach (K3eModules::loadedModules() as $module => $args) { ?>
                            <tr class="importer-item">
                                <td class="import-system"><?= $args['name'] ?></td>
                            <td class="desc"><?=class_exists($args['class']) ? $args['class']::VERSION : "<span>Nie załadowane</span>"?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="postbox-container" width="25%"></div>
        </div>
    </div>
</div>