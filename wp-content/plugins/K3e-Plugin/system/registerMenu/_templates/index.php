<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Rejestracja menu', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: 100%">
        <form method="post" action="admin.php?page=register_menu">
            <table>
                <thead>
                    <tr>
                        <th><?=__('Nazwa', 'k3e')?></th>
                        <th><?=__('Slug', 'k3e')?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (K3eRegisterMenu::getLoadMenus() as $menu_slug => $menu_args) { ?>
                        <tr>
                            <td>
                                <input type="text" name="RegisterMenu[<?= $menu_slug ?>][name]" class="regular-text ltr" value="<?= $menu_args['name'] ?>"/> 
                            </td>
                            <td>
                                <input type="text" name="RegisterMenu[<?= $menu_slug ?>][slug]" class="regular-text ltr" value="<?= $menu_args['slug'] ?>"/> 
                            </td>
                            <td>
                                <button class="button button-secondary remove-menu"><?=__('Usuń', 'k3e')?></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <hr>
                            <h5><?=__('Dodaj nowe Menu', 'k3e')?></h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="RegisterMenu[new][name]" class="regular-text ltr"/>
                        </td>
                        <td>
                            <input type="text" name="RegisterMenu[new][slug]" class="regular-text ltr"/>
                        </td>
                        <td>    

                        </td>
                    </tr>
                </tfoot>
            </table>
            <button class="button button-primary" type="submit"><?=__('Zapisz', 'k3e')?></button>

        </form>
    </div>
</div>