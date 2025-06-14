<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_species_meta_boxes() {
    add_meta_box(
        'species_details',
        'Szczegóły gatunku',
        'carni24_species_meta_box_callback',
        'species',
        'normal',
        'high'
    );
    
    add_meta_box(
        'species_care_info',
        'Informacje o pielęgnacji',
        'carni24_species_care_meta_box_callback',
        'species',
        'normal',
        'default'
    );
    
    add_meta_box(
        'species_stats',
        'Statystyki i wyświetlenia',
        'carni24_species_stats_meta_box_callback',
        'species',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_species_meta_boxes');

function carni24_species_meta_box_callback($post) {
    wp_nonce_field('carni24_species_meta_nonce', 'carni24_species_meta_nonce');
    
    $scientific_name = get_post_meta($post->ID, '_species_scientific_name', true);
    $origin = get_post_meta($post->ID, '_species_origin', true);
    $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
    $size = get_post_meta($post->ID, '_species_size', true);
    $family = get_post_meta($post->ID, '_species_family', true);
    $genus = get_post_meta($post->ID, '_species_genus', true);
    $common_names = get_post_meta($post->ID, '_species_common_names', true);
    $conservation_status = get_post_meta($post->ID, '_species_conservation_status', true);
    ?>
    
    <style>
    .species-meta-table { width: 100%; border-collapse: collapse; }
    .species-meta-table th, .species-meta-table td { padding: 10px; border-bottom: 1px solid #e0e0e0; }
    .species-meta-table th { width: 200px; text-align: left; font-weight: 600; background: #f9f9f9; }
    .species-meta-table select, .species-meta-table input[type="text"] { width: 100%; max-width: 300px; }
    .species-meta-table textarea { width: 100%; max-width: 400px; height: 60px; }
    .species-meta-section { margin-bottom: 25px; }
    .species-meta-section h4 { margin: 0 0 15px; padding: 10px; background: #e8f5e8; border-left: 4px solid #28a745; }
    .difficulty-indicator { display: inline-block; width: 12px; height: 12px; border-radius: 50%; margin-right: 5px; }
    .difficulty-easy { background: #28a745; }
    .difficulty-medium { background: #ffc107; }
    .difficulty-hard { background: #dc3545; }
    </style>
    
    <div class="species-meta-section">
        <h4>🧬 Klasyfikacja naukowa</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_scientific_name">Nazwa naukowa *</label></th>
                <td><input type="text" id="species_scientific_name" name="species_scientific_name" value="<?= esc_attr($scientific_name) ?>" placeholder="np. Dionaea muscipula" required /></td>
            </tr>
            <tr>
                <th><label for="species_family">Rodzina</label></th>
                <td><input type="text" id="species_family" name="species_family" value="<?= esc_attr($family) ?>" placeholder="np. Droseraceae" /></td>
            </tr>
            <tr>
                <th><label for="species_genus">Rodzaj</label></th>
                <td><input type="text" id="species_genus" name="species_genus" value="<?= esc_attr($genus) ?>" placeholder="np. Dionaea" /></td>
            </tr>
            <tr>
                <th><label for="species_common_names">Nazwy popularne</label></th>
                <td><textarea id="species_common_names" name="species_common_names" placeholder="Oddziel przecinkami"><?= esc_textarea($common_names) ?></textarea></td>
            </tr>
        </table>
    </div>
    
    <div class="species-meta-section">
        <h4>🌍 Informacje geograficzne</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_origin">Pochodzenie *</label></th>
                <td><input type="text" id="species_origin" name="species_origin" value="<?= esc_attr($origin) ?>" placeholder="np. Ameryka Północna" required /></td>
            </tr>
            <tr>
                <th><label for="species_conservation_status">Status ochrony</label></th>
                <td>
                    <select id="species_conservation_status" name="species_conservation_status">
                        <option value="">Wybierz status</option>
                        <option value="lc" <?= selected($conservation_status, 'lc', false) ?>>LC - Najmniejszej troski</option>
                        <option value="nt" <?= selected($conservation_status, 'nt', false) ?>>NT - Bliski zagrożenia</option>
                        <option value="vu" <?= selected($conservation_status, 'vu', false) ?>>VU - Narażony</option>
                        <option value="en" <?= selected($conservation_status, 'en', false) ?>>EN - Zagrożony</option>
                        <option value="cr" <?= selected($conservation_status, 'cr', false) ?>>CR - Krytycznie zagrożony</option>
                        <option value="ew" <?= selected($conservation_status, 'ew', false) ?>>EW - Wymarły w naturze</option>
                        <option value="ex" <?= selected($conservation_status, 'ex', false) ?>>EX - Wymarły</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="species-meta-section">
        <h4>📊 Podstawowe cechy</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_difficulty">Trudność uprawy *</label></th>
                <td>
                    <select id="species_difficulty" name="species_difficulty" required>
                        <option value="">Wybierz trudność</option>
                        <option value="easy" <?= selected($difficulty, 'easy', false) ?>><span class="difficulty-indicator difficulty-easy"></span>Łatwa</option>
                        <option value="medium" <?= selected($difficulty, 'medium', false) ?>><span class="difficulty-indicator difficulty-medium"></span>Średnia</option>
                        <option value="hard" <?= selected($difficulty, 'hard', false) ?>><span class="difficulty-indicator difficulty-hard"></span>Trudna</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_size">Rozmiar dorosłej rośliny</label></th>
                <td><input type="text" id="species_size" name="species_size" value="<?= esc_attr($size) ?>" placeholder="np. 5-15 cm średnica" /></td>
            </tr>
        </table>
    </div>
    <?php
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

function carni24_save_species_meta($post_id) {
    if (!isset($_POST['carni24_species_meta_nonce']) || !wp_verify_nonce($_POST['carni24_species_meta_nonce'], 'carni24_species_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (get_post_type($post_id) !== 'species') {
        return;
    }
    
    $fields = array(
        'species_scientific_name' => '_species_scientific_name',
        'species_family' => '_species_family',
        'species_genus' => '_species_genus',
        'species_common_names' => '_species_common_names',
        'species_origin' => '_species_origin',
        'species_conservation_status' => '_species_conservation_status',
        'species_difficulty' => '_species_difficulty',
        'species_size' => '_species_size',
        'species_light' => '_species_light',
        'species_water' => '_species_water',
        'species_temperature' => '_species_temperature',
        'species_humidity' => '_species_humidity',
        'species_soil' => '_species_soil',
        'species_fertilizer' => '_species_fertilizer',
        'species_growth_rate' => '_species_growth_rate',
        'species_flowering' => '_species_flowering',
        'species_propagation' => '_species_propagation',
        'species_dormancy' => '_species_dormancy',
        'species_special_notes' => '_species_special_notes'
    );
    
    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            if ($field === 'species_common_names' || $field === 'species_special_notes') {
                update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$field]));
            } else {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('save_post', 'carni24_save_species_meta');

function carni24_species_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['featured_image'] = '🖼️';
    $new_columns['title'] = $columns['title'];
    $new_columns['species_scientific'] = 'Nazwa naukowa';
    $new_columns['species_difficulty'] = 'Trudność';
    $new_columns['species_origin'] = 'Pochodzenie';
    $new_columns['species_views'] = 'Wyświetlenia';
    $new_columns['species_category'] = 'Kategorie';
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_species_posts_columns', 'carni24_species_admin_columns');

function carni24_species_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'species_scientific':
            $scientific = get_post_meta($post_id, '_species_scientific_name', true);
            echo $scientific ? '<em>' . esc_html($scientific) . '</em>' : '—';
            break;
            
        case 'species_difficulty':
            $difficulty = get_post_meta($post_id, '_species_difficulty', true);
            if ($difficulty) {
                $labels = array(
                    'easy' => '🟢 Łatwa',
                    'medium' => '🟡 Średnia',
                    'hard' => '🔴 Trudna'
                );
                echo $labels[$difficulty] ?? $difficulty;
            } else {
                echo '—';
            }
            break;
            
        case 'species_origin':
            $origin = get_post_meta($post_id, '_species_origin', true);
            echo $origin ? esc_html($origin) : '—';
            break;
            
        case 'species_views':
            $views = carni24_get_post_views($post_id);
            echo '<strong>' . number_format($views) . '</strong>';
            break;
            
        case 'featured_image':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(40, 40));
            } else {
                echo '—';
            }
            break;
            
        case 'species_category':
            $terms = get_the_terms($post_id, 'species_category');
            if ($terms && !is_wp_error($terms)) {
                $term_names = array();
                foreach ($terms as $term) {
                    $term_names[] = $term->name;
                }
                echo implode(', ', $term_names);
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_species_posts_custom_column', 'carni24_species_admin_columns_content', 10, 2);

function carni24_species_admin_styles() {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'species') {
        echo '<style>
        .column-featured_image { width: 50px; }
        .column-species_scientific { width: 200px; }
        .column-species_difficulty { width: 100px; }
        .column-species_origin { width: 150px; }
        .column-species_views { width: 80px; text-align: center; }
        .column-species_category { width: 120px; }
        
        .species-difficulty {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        </style>';
    }
}
add_action('admin_head', 'carni24_species_admin_styles');

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