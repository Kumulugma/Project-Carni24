<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Konfiguracja', 'k3e'); ?>
    </h1>
    <form method="post" action="admin.php?page=<?= initSystem::PLUGIN_PAGE?>&save=form">
        <fieldset>
            <?php foreach (initSystem::modules() as $module => $status) { ?>
                <p>
                    <input type="checkbox" id="<?= $module ?>Form" name="Form[<?= $module ?>]" value="1" <?= ($status == 1) ? "checked" : "" ?>>
                    <label for="<?= $module ?>Form">Moduł: <?= $module ?></label>
                </p>
            <?php } ?>
            <button class="button button-primary" type="submit">Zapisz</button>
        </fieldset>
    </form>
</div>