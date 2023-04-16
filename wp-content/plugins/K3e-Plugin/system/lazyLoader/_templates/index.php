<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('LazyLoader', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=lazyloader">
            <fieldset>
                <p>
                    <label for="lazyloaderActivate"><?=__('Uruchom LazyLoader: ', 'k3e')?></label>
                    <input type="checkbox" id="lazyloaderActivate" name="LazyLoader[activate]" value="1" <?= (K3eLazyLoader::getStatus() == 1) ? "checked" : "" ?> >
                    <input type="hidden" name="LazyLoader[token]" value="<?=md5(rand(1,255))?>"/>
                </p>
                <p>
                    <textarea name="LazyLoader[placeholder]" rows="10" cols="120"><?= K3eLazyLoader::placeholder()?></textarea>
                </p>
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>