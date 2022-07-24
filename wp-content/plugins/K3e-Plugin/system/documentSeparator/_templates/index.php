<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Separator Tytułu', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: 100%">
        <form method="post" action="admin.php?page=document_separator">
            <table>
                <thead>
                    <tr>
                        <th><?=__('Wartość', 'k3e')?></th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>
                                <input type="text" name="DocumentSeparator" class="regular-text ltr" value="<?= K3eDocumentSeparator::getSeparator() ?>"/> 
                            </td>
                        </tr>
                </tbody>
            </table>
            <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>

        </form>
    </div>
</div>