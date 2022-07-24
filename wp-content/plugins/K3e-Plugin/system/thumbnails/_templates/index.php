<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Dodatkowe miniaturki', 'k3e'); ?>
    </h1>

    <div class="card" style="max-width: 100%">
        <form method="post" action="admin.php?page=thumbnails">
            <table>
                <thead>
                    <tr>
                        <th><?=__('Nazwa', 'k3e')?></th>
                        <th><?=__('Szerokość', 'k3e')?></th>
                        <th><?=__('Wysokość', 'k3e')?></th>
                        <th><?=__('Przycięcie', 'k3e')?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (K3eThumbnails::getThumbnails() as $thumbnail_name => $thumbnail_args) { ?>
                        <tr>
                            <td>
                                <input type="text" name="Thumbnail[<?= $thumbnail_name ?>][name]" class="regular-text ltr" value="<?= $thumbnail_args['name'] ?>"/> 
                            </td>
                            <td>
                                <input type="text" name="Thumbnail[<?= $thumbnail_name ?>][width]" class="regular-text ltr" value="<?= $thumbnail_args['width'] ?>"/> 
                            </td>
                            <td>
                                <input type="text" name="Thumbnail[<?= $thumbnail_name ?>][height]" class="regular-text ltr" value="<?= $thumbnail_args['height'] ?>"/> 
                            </td>
                            <td>
                                <select name="Thumbnail[<?= $thumbnail_name ?>][crop]">
                                    <option value="1" <?= $thumbnail_args['crop'] == '1' ? "selected" : "" ?>><?=__('Tak', 'k3e')?></option>
                                    <option value="0" <?= $thumbnail_args['crop'] == '0' ? "selected" : "" ?>><?=__('Nie', 'k3e')?></option>
                                </select>
                            </td>
                            <td><button class="button button-secondary remove-thumbnail"><?=__('Usuń', 'k3e')?></button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <hr>
                            <h5><?=__('Dodaj nowy rozmiar miniaturek', 'k3e')?></h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="Thumbnail[new][name]" class="regular-text ltr"/>
                        </td>
                        <td>
                            <input type="text" name="Thumbnail[new][width]" class="regular-text ltr"/>
                        </td>
                        <td>
                            <input type="text" name="Thumbnail[new][height]" class="regular-text ltr"/>
                        </td>
                        <td>    
                            <select name="Thumbnail[new][crop]">
                                <option value="1"><?=__('Tak', 'k3e')?></option>
                                <option value="0"><?=__('Nie', 'k3e')?></option>
                            </select>
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