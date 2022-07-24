<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Wyłącz Gutenberga', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=disable_gutenberg">
            <fieldset>
                <h3><?= __('Wyłacz gutenberga', 'k3e') ?></h3>
                <?php foreach (get_post_types() as $type) { ?>
                    <?php if (!in_array($type, K3E::POST_EXCLUDES)) { ?>
                        <p>
                            <input type="checkbox" id="<?= $type ?>Form" name="DisableGutenberg[<?= $type ?>]" value="<?= $type ?>" <?= (in_array($type, K3eDisableGutenberg::getDisabled())) ? "checked" : "" ?>>
                            <label for="<?= $type ?>Form"><?php $post_type_obj = get_post_type_object($type) ?> <?= $post_type_obj->labels->singular_name; ?> [<?= $type ?>]</label>
                        </p>
                    <?php } ?>
                <?php } ?>
                <input type="hidden" value="<?= md5(rand(0, 255)) ?>" name="DisableGutenberg[salt]">
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>