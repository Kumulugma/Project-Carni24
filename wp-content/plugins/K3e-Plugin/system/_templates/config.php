<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Zaawansowane', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=k3e_config&save=form">
            <fieldset>
                <h2 class="wp-heading-inline">
                    <?php esc_html_e('Typ strony', 'k3e'); ?>
                </h2>
                <p class="">
                    <label for="k3e_config_onepage"><?= __('Strona typu Onepage', 'k3e') ?></label>
                    <input id="k3e_config_onepage" type="checkbox" name="Config[onepage]" value='1' <?= K3eSystem::getOnePageOption() == '1' ? 'checked' : '' ?>>
                </p>
                <input type='hidden' name="Config[control]" value="<?= md5(rand(0, 255)) ?>"/>
                <button class="button button-primary"  type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>