<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Konfigurator', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: none;">
        <form method="post" action="admin.php?page=static_content_config">
            <fieldset>
                <legend><?=__('Formularze danych statycznych:', 'k3e')?></legend>

                <p>
                    <input type="radio" id="form_page" name="StaticForm" value="1" <?= (K3eStaticContent::getStaticFormType() == 1) ? "checked" : "" ?>>
                    <label for="form_page"><?= __('Istnieje strona z formularzami', 'k3e') ?></label>
                </p>
                <p>
                    <input type="radio" id="form_generate" name="StaticForm" value="2" <?= (K3eStaticContent::getStaticFormType() == 2) ? "checked" : "" ?>>
                    <label for="form_generate"><?= __('Wygeneruj formularz', 'k3e') ?></label>
                </p>

                <hr/>
                <?php if (K3eStaticContent::getStaticFormType() == '1') { ?>
                    <select name="StaticFormId">
                        <option value="0" <?= (K3eStaticContent::getStaticFormType() == 0) ? "selected" : "" ?> >---</option>
                        <?php
                        foreach (get_pages() as $page) {
                            ?>
                            <option value="<?= $page->ID ?>" <?= (K3eStaticContent::getStaticFormID() == $page->ID) ? "selected" : "" ?>><?= $page->post_title ?></option>
                        <?php } ?>
                    </select>
                    <hr/>
                <?php } else { ?>

                    <p>
                        <label for="form_fields_amount"><?= __('Ilość pól', 'k3e') ?></label>
                        <input type="number" id="form_fields_amount" name="StaticFormsAmount" value="<?= K3eStaticContent::getStaticFormsAmount() ?>">
                    </p>
                    <hr/>
                    <?php if (K3eStaticContent::getStaticFormsAmount() > 0) { ?>
                        <?php for ($i = 0; $i < K3eStaticContent::getStaticFormsAmount(); $i++) { ?>
                            <?php $form = K3eStaticContent::getStaticForms(); ?>
                            <p>
                                <label for="form_fields_label_<?= $i ?>"><?= __('Etykieta', 'k3e') ?></label>
                                <input type="text" id="form_fields_label<?= $i ?>" name="StaticForms[<?= $i ?>][label]" value="<?= isset($form[$i]['label']) ? $form[$i]['label'] : '' ?>">

                                <label for="form_fields_name_<?= $i ?>"><?= __('Nazwa', 'k3e') ?></label>
                                <input type="text" id="form_fields_name_<?= $i ?>" name="StaticForms[<?= $i ?>][name]" value="<?= isset($form[$i]['name']) ? $form[$i]['name'] : '' ?>">

                                <select name="StaticForms[<?= $i ?>][type]">
                                    <option value="text" <?= (isset($form[$i]['type']) && 'text' == $form[$i]['type']) ? "selected" : "" ?>>text</option>
                                    <option value="number" <?= (isset($form[$i]['type']) && 'number' == $form[$i]['type']) ? "selected" : "" ?>>number</option>
                                    <?php /*
                                    <option value="select" <?= (isset($form[$i]['type']) && 'select' == $form[$i]['type']) ? "selected" : "" ?>>select</option>
                                    <option value="checkbox" <?= (isset($form[$i]['type']) && 'checkbox' == $form[$i]['type']) ? "selected" : "" ?>>checkbox</option>
                                     */?>
                                    <option value="textarea" <?= (isset($form[$i]['type']) && 'textarea' == $form[$i]['type']) ? "selected" : "" ?>>textarea</option>
                                </select>
                            </p>
                        <?php } ?>
                        <hr/>
                    <?php } ?>
                <?php } ?>
                <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>
            </fieldset>
        </form>
    </div>
</div>