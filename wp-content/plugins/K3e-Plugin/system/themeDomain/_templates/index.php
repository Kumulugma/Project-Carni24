<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Domena z tłumaczeniem', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: 100%">
        <form method="post" action="admin.php?page=theme_domain">
            <table>
                <thead>
                    <tr>
                        <th><?=__('Wartość', 'k3e')?></th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>
                                <input type="text" name="ThemeDomain" class="regular-text ltr" value="<?= K3eThemeDomain::getThemeDomain() ?>"/> 
                            </td>
                        </tr>
                </tbody>
            </table>
            <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>

        </form>
    </div>
</div>