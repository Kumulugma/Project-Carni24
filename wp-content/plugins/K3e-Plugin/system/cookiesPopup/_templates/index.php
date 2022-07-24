<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Informacja o ciasteczkach', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=cookies_popup">
            <fieldset>
                <h3><?= __('Aktywuj wyskakujące okienko', 'k3e') ?></h3>
                <p>
                    <label for="CookiesPopupActivate"><?=__('Przełącznik Cookies: ', 'k3e')?></label>
                    <input type="checkbox" id="CookiesPopupActivate" name="CookiesPopup[activate]" value="1" <?= (K3eCookiesPopup::getStatus() == 1) ? "checked" : "" ?> >
                </p>
                <?php if (K3eCookiesPopup::getStatus()) { ?>
                    <h3><?= __('Podaj treść', 'k3e') ?></h3>
                    <p>
                        <label for="CookiesPopupContent"><?= __('Treść komunikatu', 'k3e') ?></label>
                        <br>
                        <textarea id="CookiesPopupContent" name="CookiesPopup[content]" rows="20" cols="200"><?= K3eCookiesPopup::getContent() ?></textarea>
                    </p>
                <?php } ?>
                <input type="hidden" value="<?= md5(rand(0, 255)) ?>" name="CookiesPopup[salt]">
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>