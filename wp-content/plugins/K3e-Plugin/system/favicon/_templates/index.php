<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Favicon', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=favicon" enctype="multipart/form-data">
            <fieldset>
                <h3><?= __('Aktywuj favicon', 'k3e') ?></h3>
                <p>
                    <label for="FaviconActivate"><?= __('Podepnij favicon', 'k3e') ?></label>
                    <input type="checkbox" id="FaviconActivate" name="Favicon[activate]" value="1" <?= (K3eFavicon::getStatus() == 1) ? "checked" : "" ?> >
                </p>
                <?php if ((K3eFavicon::getStatus() == 1)) { ?>
                    <p>
                        <?= __('Paczka z: ', 'k3e') ?> <a href="https://realfavicongenerator.net/">https://realfavicongenerator.net/</a>
                    </p>
                    <p>
                        <label for="FaviconPackage"><?= __('Paczka z faviconami: ', 'k3e') ?></label>

                        <input type="file"
                               id="FaviconPackage" name="FaviconPackage"
                               accept=".zip,.rar,.7zip">
                    </p>
                <?php } ?>
                <input type="hidden" value="<?= md5(rand(0, 255)) ?>" name="Favicon[salt]">
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>