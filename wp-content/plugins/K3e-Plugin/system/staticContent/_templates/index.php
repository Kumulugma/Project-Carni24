<?php
$forms = K3eStaticContent::getStaticForms();
$content = K3eStaticContent::getStaticContent();
?>
<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Treść statyczna', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=static_content">
            <fieldset>
                <?php foreach ($forms as $k => $form) { ?>
                    <p>
                        <label for="form_content_label_<?= $i ?>"><?= $form['label'] ?></label>

                        <?php if ($form['type'] == 'text' || $form['number']) { ?>
                            <input type="<?= $form['type'] ?>" id="form_content_label_<?= $k ?>" name="StaticContent[<?= $form['name'] ?>]" value="<?= isset($content[$k][$form['name']]) ? $content[$k][$form['name']] : '' ?>">
                        <?php } elseif ($form['type'] == 'textarea') { ?>
                            <textarea id="form_content_label_<?= $k ?>" name="StaticContent[<?= $form['name'] ?>]"><?= isset($content[$k][$form['name']]) ? $content[$k][$form['name']] : '' ?></textarea>
                        <?php } ?>
                    </p>
                <?php } ?>

                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>