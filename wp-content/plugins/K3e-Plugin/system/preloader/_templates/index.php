<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Preloader', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=preloader">
            <fieldset>
                <p>
                    <label for="preloaderActivate"><?=__('Uruchom preloader: ', 'k3e')?></label>
                    <input type="checkbox" id="preloaderActivate" name="Preloader[activate]" value="1" <?= (K3ePreloader::getStatus() == 1) ? "checked" : "" ?> >
                    <input type="hidden" name="Preloader[token]" value="<?=md5(rand(1,255))?>"/>
                </p>
                
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>