<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_species_care_meta_box_callback($post) {
    $light = get_post_meta($post->ID, '_species_light', true);
    $water = get_post_meta($post->ID, '_species_water', true);
    $temperature = get_post_meta($post->ID, '_species_temperature', true);
    $humidity = get_post_meta($post->ID, '_species_humidity', true);
    $soil = get_post_meta($post->ID, '_species_soil', true);
    $fertilizer = get_post_meta($post->ID, '_species_fertilizer', true);
    $growth_rate = get_post_meta($post->ID, '_species_growth_rate', true);
    $flowering = get_post_meta($post->ID, '_species_flowering', true);
    $propagation = get_post_meta($post->ID, '_species_propagation', true);
    $dormancy = get_post_meta($post->ID, '_species_dormancy', true);
    $special_notes = get_post_meta($post->ID, '_species_special_notes', true);
    ?>
    
    <div class="species-meta-section">
        <h4>☀️ Wymagania środowiskowe</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_light">Wymagania świetlne</label></th>
                <td>
                    <select id="species_light" name="species_light">
                        <option value="">Wybierz wymagania</option>
                        <option value="low" <?= selected($light, 'low', false) ?>>🌑 Niskie (1000-3000 lux)</option>
                        <option value="medium" <?= selected($light, 'medium', false) ?>>🌗 Średnie (3000-6000 lux)</option>
                        <option value="high" <?= selected($light, 'high', false) ?>>🌕 Wysokie (6000-12000 lux)</option>
                        <option value="full_sun" <?= selected($light, 'full_sun', false) ?>>☀️ Pełne słońce (12000+ lux)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_water">Wymagania wodne</label></th>
                <td>
                    <select id="species_water" name="species_water">
                        <option value="">Wybierz wymagania</option>
                        <option value="low" <?= selected($water, 'low', false) ?>>💧 Niskie (suche podłoże)</option>
                        <option value="medium" <?= selected($water, 'medium', false) ?>>💧💧 Średnie (wilgotne podłoże)</option>
                        <option value="high" <?= selected($water, 'high', false) ?>>💧💧💧 Wysokie (mokre podłoże)</option>
                        <option value="bog" <?= selected($water, 'bog', false) ?>>🌊 Bagienne (stojąca woda)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_temperature">Temperatura</label></th>
                <td><input type="text" id="species_temperature" name="species_temperature" value="<?= esc_attr($temperature) ?>" placeholder="np. 18-25°C (dzień), 10-15°C (noc)" /></td>
            </tr>
            <tr>
                <th><label for="species_humidity">Wilgotność powietrza</label></th>
                <td><input type="text" id="species_humidity" name="species_humidity" value="<?= esc_attr($humidity) ?>" placeholder="np. 60-80%" /></td>
            </tr>
        </table>
    </div>
    
    <div class="species-meta-section">
        <h4>🌱 Pielęgnacja i uprawa</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_soil">Podłoże</label></th>
                <td><input type="text" id="species_soil" name="species_soil" value="<?= esc_attr($soil) ?>" placeholder="np. torf + perlit (1:1)" /></td>
            </tr>
            <tr>
                <th><label for="species_fertilizer">Nawożenie</label></th>
                <td><input type="text" id="species_fertilizer" name="species_fertilizer" value="<?= esc_attr($fertilizer) ?>" placeholder="np. nie nawozić / owady / słaby roztwór" /></td>
            </tr>
            <tr>
                <th><label for="species_growth_rate">Tempo wzrostu</label></th>
                <td>
                    <select id="species_growth_rate" name="species_growth_rate">
                        <option value="">Wybierz tempo</option>
                        <option value="slow" <?= selected($growth_rate, 'slow', false) ?>>🐌 Wolne</option>
                        <option value="medium" <?= selected($growth_rate, 'medium', false) ?>>🚶 Średnie</option>
                        <option value="fast" <?= selected($growth_rate, 'fast', false) ?>>🏃 Szybkie</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_flowering">Kwitnienie</label></th>
                <td><input type="text" id="species_flowering" name="species_flowering" value="<?= esc_attr($flowering) ?>" placeholder="np. wiosna-lato, białe kwiaty" /></td>
            </tr>
            <tr>
                <th><label for="species_propagation">Rozmnażanie</label></th>
                <td><input type="text" id="species_propagation" name="species_propagation" value="<?= esc_attr($propagation) ?>" placeholder="np. nasiona, podział, sadzonki liściowe" /></td>
            </tr>
            <tr>
                <th><label for="species_dormancy">Spoczynek zimowy</label></th>
                <td>
                    <select id="species_dormancy" name="species_dormancy">
                        <option value="">Wybierz</option>
                        <option value="yes" <?= selected($dormancy, 'yes', false) ?>>❄️ Tak (wymagany)</option>
                        <option value="no" <?= selected($dormancy, 'no', false) ?>>🌿 Nie</option>
                        <option value="partial" <?= selected($dormancy, 'partial', false) ?>>🍂 Częściowy</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="species-meta-section">
        <h4>📝 Dodatkowe uwagi</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_special_notes">Specjalne wskazówki</label></th>
                <td><textarea id="species_special_notes" name="species_special_notes" rows="4" style="width: 100%; max-width: 100%;" placeholder="Ważne informacje, ostrzeżenia, wskazówki dla hodowców..."><?= esc_textarea($special_notes) ?></textarea></td>
            </tr>
        </table>
    </div>
    <?php
}

function carni24_species_stats_meta_box_callback($post) {
    $views = carni24_get_post_views($post->ID);
    $date_added = get_the_date('Y-m-d H:i', $post->ID);
    $last_modified = get_the_modified_date('Y-m-d H:i', $post->ID);
    $featured = get_post_meta($post->ID, '_is_featured', true);
    ?>
    
    <style>
    .species-stats-table { width: 100%; }
    .species-stats-table th, .species-stats-table td { padding: 8px 0; border-bottom: 1px solid #eee; }
    .species-stats-table th { font-weight: 600; }
    .stat-value { font-weight: bold; color: #0073aa; }
    .featured-badge { background: #d63638; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; }
    </style>
    
    <table class="species-stats-table">
        <tr>
            <th>Wyświetlenia:</th>
            <td><span class="stat-value"><?= number_format($views) ?></span></td>
        </tr>
        <tr>
            <th>Dodano:</th>
            <td><?= $date_added ?></td>
        </tr>
        <tr>
            <th>Ostatnia edycja:</th>
            <td><?= $last_modified ?></td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>
                <?= get_post_status($post->ID) === 'publish' ? '✅ Opublikowany' : '⏳ Szkic' ?>
                <?php if ($featured): ?>
                    <br><span class="featured-badge">⭐ Wyróżniony</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    
    <p style="margin-top: 15px;">
        <strong>Szybkie akcje:</strong><br>
        <a href="<?= get_permalink($post->ID) ?>" target="_blank" class="button button-small">👁️ Zobacz</a>
        <a href="<?= admin_url('post.php?post=' . $post->ID . '&action=edit') ?>" class="button button-small">✏️ Edytuj</a>
    </p>
    <?php
}

function carni24_species_sortable_columns($columns) {
    $columns['species_difficulty'] = 'species_difficulty';
    $columns['species_views'] = 'species_views';
    $columns['species_origin'] = 'species_origin';
    return $columns;
}
add_filter('manage_edit-species_sortable_columns', 'carni24_species_sortable_columns');

function carni24_species_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if ('species_difficulty' === $query->get('orderby')) {
        $query->set('meta_key', '_species_difficulty');
        $query->set('orderby', 'meta_value');
    }
    
    if ('species_views' === $query->get('orderby')) {
        $query->set('meta_key', 'post_views_count');
        $query->set('orderby', 'meta_value_num');
    }
    
    if ('species_origin' === $query->get('orderby')) {
        $query->set('meta_key', '_species_origin');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'carni24_species_orderby');