<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Zaślepka', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=placeholder">
            <fieldset>
                <p>
                    <label for="placeholderActivate"><?=__('Uruchom zaślepkę: ', 'k3e')?></label>
                    <input type="checkbox" id="placeholderActivate" name="Placeholder[activate]" value="1" <?= (K3ePlaceholder::getStatus() == 1) ? "checked" : "" ?> >
                </p>
                <?php if (K3ePlaceholder::getStatus()) { ?>
                    <p>
                        <small><?=__('Shortcode do umieszczenia na stronie: ', 'k3e')?><input type='text' value='[K3E_PLACEHOLDER]'/></small>
                    </p>
                    <hr>
                    <p>
                        <label for="placeholderTitle"><?=__('Tytuł zaślepki: ', 'k3e')?></label>
                        <input type="text" id="placeholderTitle" name="Placeholder[name]" value="<?= K3ePlaceholder::getName() ?>">
                    </p>
                    <p>
                        <label for="placeholderNumber"><?=__('Ilość zakładek: ', 'k3e')?></label>
                        <input type="number" id="placeholderNumber" name="Placeholder[amount]" value="<?= K3ePlaceholder::getAmount() ?>" >
                    </p>
                    <hr>
                    <?php for ($amount = 0; $amount < K3ePlaceholder::getAmount(); $amount++) { ?>
                        <?php $placeholders = K3ePlaceholder::getPlaceholders() ?>
                        <h3><?php esc_html_e('Zakładka', 'k3e'); ?> <?= $amount + 1 ?></h3>
                        <p> 
                            <input type="checkbox" id="PlaceholderActive_<?= $amount ?>" name="Placeholders[<?= $amount ?>][active]" value="1" <?= (isset($placeholders[$amount]['active']) && $placeholders[$amount]['active'] == 1) ? "checked" : "" ?>>
                            <label for="PlaceholderActive_<?= $amount ?>"><?=__('Status: ', 'k3e')?><?= (isset($placeholders[$amount]['active']) && $placeholders[$amount]['active'] ? "Tak" : "Nie") ?></label>

                            <br>
                            <label for="PlaceholderHeadling_<?= $amount ?>"><?=__('Nagłówek: ', 'k3e')?></label>
                            <input type="text" id="PlaceholderHeadling_<?= $amount ?>" name="Placeholders[<?= $amount ?>][headling]" value="<?= isset($placeholders[$amount]['headling']) ? $placeholders[$amount]['headling'] : '' ?>">

                            <br>
                            <label for="PlaceholderContent_<?= $amount ?>"><?=__('Treść: ', 'k3e')?></label>
                            <textarea rows="10" cols="50" class="large-text code" name="Placeholders[<?= $amount ?>][content]" id="PlaceholderContent_<?= $amount ?>"> <?= isset($placeholders[$amount]['content']) ? $placeholders[$amount]['content'] : '' ?></textarea>

                        </p>
                    <?php } ?>
                <?php } ?>
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>