<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Google Analytics', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=g_analytics">
            <fieldset>
                <h3><?= __('Aktywuj Google Analytics', 'k3e') ?></h3>
                <p>
                    <label for="GoogleAnalyticsActivate"><?=__('Uruchom Analytics: ', 'k3e')?></label>
                    <input type="checkbox" id="GoogleAnalyticsActivate" name="GoogleAnalytics[activate]" value="1" <?= (K3eGoogleAnalytics::getStatus() == 1) ? "checked" : "" ?> >
                </p>
                <?php if (K3eGoogleAnalytics::getStatus()) { ?>
                    <h3><?= __('Podaj kod', 'k3e') ?></h3>
                    <p>
                        <label for="GoogleAnalyticsCode"><?= __('Google Analytics', 'k3e') ?></label>
                        <input type="text" id="GoogleAnalyticsCode" name="GoogleAnalytics[code]" value="<?= K3eGoogleAnalytics::getCode() ?>">
                    </p>
                <?php } ?>
                <input type="hidden" value="<?= md5(rand(0, 255)) ?>" name="GoogleAnalytics[salt]">
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>