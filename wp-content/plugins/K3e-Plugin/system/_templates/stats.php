<?php
wp_register_script('k3e-admin', plugins_url('../../assets/js/k3e-admin.js', __FILE__), array('jquery'), '1.0.0', true);
wp_enqueue_script('k3e-admin');
wp_register_style('k3e-admin', plugins_url('../../assets/css/k3e-admin.css', __FILE__), array(), '1.0.0', 'all');
wp_enqueue_style('k3e-admin');
?>

<div class="wrap" id="configuration-page">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Konfiguracja', 'k3e'); ?>
    </h1>


    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">
            <div class="nav-tab-wrapper">
                <a href="#tab-default" class="nav-tab nav-tab-active">Ogólne</a>
                <a href="#tab-thumbnails" class="nav-tab">Miniaturki</a>
                <a href="#tab-wordpress" class="nav-tab">Wordpress</a>
                <a href="#tab-seo" class="nav-tab">SEO</a>
                <a href="#tab-config" class="nav-tab">Konfiguracja</a>
            </div>
            <div class="tab-content" id="tab-default">
                <div class="postbox-container default-container" width="25%">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="2"><?= __('Aktywna konfiguracja', 'k3e') ?></th></tr></thead>
                            <tbody>

                                <tr class="importer-item">
                                    <td class="import-system"><?= __('Strona One Page', 'k3e') ?></td>
                                    <td class="import-system"><?= K3eSystem::getOnePageOption() == 1 ? __('Tak', 'k3e') : __('Nie', 'k3e') ?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="reset"></div>
            </div>
            <div class="tab-content" id="tab-thumbnails" style="display:none;">
                <div class="postbox-container thumbnails-container" width="50%">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="2">Dodane miniaturki</th></tr></thead>
                            <tbody>
                                <?php
                                $thumbnails = K3eSystem::getThumbnailsOption();
                                if ($thumbnails) {
                                    foreach ($thumbnails as $thumbnail) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system"><?= $thumbnail['name'] ?></td>
                                            <td class="desc"><?= $thumbnail['width'] ?>x<?= $thumbnail['height'] ?> [<?= ($thumbnail['crop'] == 1) ? "Przycięte" : "Nie przycięte" ?>]</td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="importer-item">
                                        <td colspan="2" class="import-system"><?= __('Brak nowych miniaturek', 'k3e') ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>

                        <br>

                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="2"><?= __('Wszystkie miniaturki', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                global $_wp_additional_image_sizes;

                                $all_thumbnails = $_wp_additional_image_sizes;
                                if ($all_thumbnails) {
                                    foreach ($all_thumbnails as $key => $thumbnail) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system"><?= $key ?></td>
                                            <td class="desc"><?= $thumbnail['width'] ?>x<?= $thumbnail['height'] ?> [<?= ($thumbnail['crop'] == 1) ? "Przycięte" : "Nie przycięte" ?>]</td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="importer-item">
                                        <td colspan="2" class="import-system"><?= __('Brak nowych miniaturek', 'k3e') ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="reset"></div>
            </div>
            <div class="tab-content" id="tab-wordpress" style="display:none;">
                <div class="postbox-container wordpress-container" width="25%">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th><?= __('Ukryte menu', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                $menus = K3eSystem::getHiddenMenusOption();
                                if ($menus) {
                                    foreach ($menus as $menu) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system"><?= $menu ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><?= __('Brak ukrytych menu', 'k3e') ?></td>
                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>

                        <br>

                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="2"><?= __('Wsparcie szablonów', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                $supports = K3eSystem::getThemeSupportOption();
                                if ($supports) {
                                    foreach ($supports as $support) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system"><?= $support['name'] ?></td>
                                            <td class="import-system"><?= ($support['status'] == 1) ? "Włączone" : "---" ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="importer-item">
                                        <td colspan="2" class="import-system"><?= __('Brak dodatkowego wsparcia w szablonie', 'k3e') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="postbox-container" width="25%">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="1"><?= __('Aktywne typy wpisów', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                foreach (get_post_types() as $type => $value) {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><?= $type ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="postbox-container" width="25%">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="1"><?= __('Aktywne Shortcody', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                global $shortcode_tags;
                                if ($shortcode_tags) {
                                    foreach ($shortcode_tags as $code => $value) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system">[<?= $code ?>]</td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="importer-item">
                                        <td colspan="1" class="import-system"><?= __('Brak dodatkowego wsparcia w szablonie', 'k3e') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="reset"></div>
            </div>
            <div class="tab-content" id="tab-seo" style="display:none;">
                <div class="postbox-container seo-container">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="3"><?= __('Mapa strony', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                if (class_exists('K3eSitemap')) {
                                    if (file_exists(get_home_path() . "sitemap.xml")) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system" id="sitemapText"><a href="<?= get_site_url() . "/sitemap.xml" ?>"><?= get_site_url() . "/sitemap.xml" ?></a></td>
                                            <td class="import-system" id="sitemapDate">
                                                <small>
                                                    <?= date(get_option('date_format'), filemtime(get_home_path() . "sitemap.xml")); ?>
                                                    <?= date(get_option('time_format'), filemtime(get_home_path() . "sitemap.xml")); ?>
                                                </small>
                                            </td>
                                            <td class="import-system">
                                                <button type="submit" class="button button-primary" id="newSitemap">Wygeneruj nowe</button>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr class="importer-item">
                                            <td colspan="2" class="import-system"><?= __('Brak wygenerowanej mapy. Zapisz wpis lub stronę i spróbuj ponownie.', 'k3e') ?></td>
                                            <td class="import-system">
                                                <button type="submit" class="button button-primary" id="newSitemap">Wygeneruj nowe</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <?php
                                if (class_exists('K3eRobots')) {
                                    if (file_exists(get_home_path() . "robots.txt")) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system" id="robotsText"><a href="<?= get_site_url() . "/robots.txt" ?>"><?= get_site_url() . "/robots.txt" ?></a></td>
                                            <td class="import-system" id="robotsDate">
                                                <small>
                                                    <?= date(get_option('date_format'), filemtime(get_home_path() . "robots.txt")); ?>
                                                    <?= date(get_option('time_format'), filemtime(get_home_path() . "robots.txt")); ?>
                                                </small>
                                            </td>
                                            <td class="import-system">
                                                <button type="submit" class="button button-primary" id="newRobotsTXT">Wygeneruj nowe</button>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr class="importer-item">
                                            <td colspan="2" class="import-system"><?= __('Brak wygenerowanego pliku robots.TXT. Zapisz wpis lub stronę i spróbuj ponownie.', 'k3e') ?></td>
                                            <td class="import-system">
                                                <button type="submit" class="button button-primary" id="newRobotsTXT">Wygeneruj nowe</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="3"><?= __('Lista wpisów na mapie', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                $active = get_option(K3e::OPTION_SITEMAP_POST_TYPES);

                                $args = array(
                                    'post_type' => is_array($active) ? $active : 'post',
                                    'numberposts' => -1,
                                    'order' => 'DESC'
                                ); 
                                $query = new WP_Query($args);

                                if ($query->have_posts()) {
                                    while ($query->have_posts()) {
                                        $query->the_post();
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system"><?=get_the_title()?></td>
                                            <td class="import-system"><?=get_permalink()?></td>
                                            <td class="import-system"><?=get_post_modified_time('Y-m-d\TH:i:s+00:00', false, get_the_ID())?></td>
                                        </tr>
                                        <?php
                                    }
                                    wp_reset_postdata();
                                } else {
                                    ?>
                                        <tr colspan="2" class="importer-item">
                                            <td class="import-system"><?=__('Brak wyników', 'k3e')?></td>
                                        </tr>
                                        <?php
                                }                               
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>  
                <div class="postbox-container seo-container">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="2"><?= __('Aktywne typy wpisów', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                $active = get_option(K3e::OPTION_SITEMAP_POST_TYPES);
                                foreach (get_post_types() as $type => $value) {
                                    ?>
                                    <tr class="importer-item">
                                        <td class="import-system"><?= $type ?></td>
                                        <td>
                                            <input type="checkbox" name="type[<?= $type ?>]" value="<?= $type ?>" <?= in_array($type, $active) ? 'checked' : '' ?>>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">
                                        <button type="submit" class="button button-primary" id="activeTypes">Zapisz</button>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="reset"></div>  
            </div>  
            <div class="tab-content" id="tab-config" style="display:none;">
                <div class="postbox-container config-container" width="100%">
                    <div class="card" style="margin:2px">
                        <table class="widefat importers striped" style="width:100%">
                            <thead><tr><th colspan="2"><?= __('Pełna konfiguracja', 'k3e') ?></th></tr></thead>
                            <tbody>
                                <?php
                                $config = K3eSystem::getFullConfig();
                                if ($config) {
                                    foreach ($config as $item_key => $item_value) {
                                        ?>
                                        <tr class="importer-item">
                                            <td class="import-system"><?= $item_key ?></td>
                                            <td class="import-system import-system-content" style="display: inline-block; overflow: hidden; white-space: nowrap;"><?= $item_value ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr class="importer-item">
                                        <td colspan="2" class="import-system"><?= __('Konfiguracja jest pusta', 'k3e') ?></td>
                                    </tr>
<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="reset"></div>
            </div>
        </div>
    </div>
</div>