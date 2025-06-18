<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_register_species_post_type() {
    $labels = array(
        'name'                  => 'Gatunki',
        'singular_name'         => 'Gatunek',
        'menu_name'             => 'Gatunki',
        'name_admin_bar'        => 'Gatunek',
        'archives'              => 'Archiwum gatunków',
        'attributes'            => 'Atrybuty gatunku',
        'parent_item_colon'     => 'Rodzic gatunku:',
        'all_items'             => 'Wszystkie gatunki',
        'add_new_item'          => 'Dodaj nowy gatunek',
        'add_new'               => 'Dodaj nowy',
        'new_item'              => 'Nowy gatunek',
        'edit_item'             => 'Edytuj gatunek',
        'update_item'           => 'Zaktualizuj gatunek',
        'view_item'             => 'Zobacz gatunek',
        'view_items'            => 'Zobacz gatunki',
        'search_items'          => 'Szukaj gatunków',
        'not_found'             => 'Nie znaleziono',
        'not_found_in_trash'    => 'Nie znaleziono w koszu',
        'featured_image'        => 'Zdjęcie główne',
        'set_featured_image'    => 'Ustaw zdjęcie główne',
        'remove_featured_image' => 'Usuń zdjęcie główne',
        'use_featured_image'    => 'Użyj jako zdjęcie główne',
        'insert_into_item'      => 'Wstaw do gatunku',
        'uploaded_to_this_item' => 'Przesłane do tego gatunku',
        'items_list'            => 'Lista gatunków',
        'items_list_navigation' => 'Nawigacja listy gatunków',
        'filter_items_list'     => 'Filtruj listę gatunków',
    );

    $args = array(
        'label'                 => 'Gatunek',
        'description'           => 'Gatunki roślin mięsożernych',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'),
        'taxonomies'            => array('species_category', 'species_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-carrot',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array(
            'slug' => 'gatunki',
            'with_front' => false,
        ),
    );

    register_post_type('species', $args);
}
add_action('init', 'carni24_register_species_post_type', 0);

function carni24_register_species_taxonomies() {
    $category_labels = array(
        'name'                       => 'Kategorie gatunków',
        'singular_name'              => 'Kategoria gatunku',
        'menu_name'                  => 'Kategorie',
        'all_items'                  => 'Wszystkie kategorie',
        'parent_item'                => 'Kategoria nadrzędna',
        'parent_item_colon'          => 'Kategoria nadrzędna:',
        'new_item_name'              => 'Nazwa nowej kategorii',
        'add_new_item'               => 'Dodaj nową kategorię',
        'edit_item'                  => 'Edytuj kategorię',
        'update_item'                => 'Zaktualizuj kategorię',
        'view_item'                  => 'Zobacz kategorię',
        'separate_items_with_commas' => 'Oddziel kategorie przecinkami',
        'add_or_remove_items'        => 'Dodaj lub usuń kategorie',
        'choose_from_most_used'      => 'Wybierz z najczęściej używanych',
        'popular_items'              => 'Popularne kategorie',
        'search_items'               => 'Szukaj kategorii',
        'not_found'                  => 'Nie znaleziono',
        'no_terms'                   => 'Brak kategorii',
        'items_list'                 => 'Lista kategorii',
        'items_list_navigation'      => 'Nawigacja kategorii',
    );

    $category_args = array(
        'labels'                     => $category_labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array(
            'slug' => 'kategoria-gatunku',
        ),
    );

    register_taxonomy('species_category', array('species'), $category_args);

    $tag_labels = array(
        'name'                       => 'Tagi gatunków',
        'singular_name'              => 'Tag gatunku',
        'menu_name'                  => 'Tagi',
        'all_items'                  => 'Wszystkie tagi',
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => 'Nazwa nowego tagu',
        'add_new_item'               => 'Dodaj nowy tag',
        'edit_item'                  => 'Edytuj tag',
        'update_item'                => 'Zaktualizuj tag',
        'view_item'                  => 'Zobacz tag',
        'separate_items_with_commas' => 'Oddziel tagi przecinkami',
        'add_or_remove_items'        => 'Dodaj lub usuń tagi',
        'choose_from_most_used'      => 'Wybierz z najczęściej używanych',
        'popular_items'              => 'Popularne tagi',
        'search_items'               => 'Szukaj tagów',
        'not_found'                  => 'Nie znaleziono',
        'no_terms'                   => 'Brak tagów',
        'items_list'                 => 'Lista tagów',
        'items_list_navigation'      => 'Nawigacja tagów',
    );

    $tag_args = array(
        'labels'                     => $tag_labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
        'rewrite'                    => array(
            'slug' => 'tag-gatunku',
        ),
    );

    register_taxonomy('species_tag', array('species'), $tag_args);
}
add_action('init', 'carni24_register_species_taxonomies', 0);

function carni24_species_meta_boxes() {
    add_meta_box(
        'species_details',
        'Szczegóły gatunku',
        'carni24_species_meta_box_callback',
        'species',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'carni24_species_meta_boxes');

function carni24_species_meta_box_callback($post) {
    wp_nonce_field('carni24_species_meta_nonce', 'carni24_species_meta_nonce');
    
    $scientific_name = get_post_meta($post->ID, '_species_scientific_name', true);
    $origin = get_post_meta($post->ID, '_species_origin', true);
    $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
    $light = get_post_meta($post->ID, '_species_light', true);
    $water = get_post_meta($post->ID, '_species_water', true);
    $temperature = get_post_meta($post->ID, '_species_temperature', true);
    $humidity = get_post_meta($post->ID, '_species_humidity', true);
    $soil = get_post_meta($post->ID, '_species_soil', true);
    $fertilizer = get_post_meta($post->ID, '_species_fertilizer', true);
    $size = get_post_meta($post->ID, '_species_size', true);
    $growth_rate = get_post_meta($post->ID, '_species_growth_rate', true);
    $flowering = get_post_meta($post->ID, '_species_flowering', true);
    $propagation = get_post_meta($post->ID, '_species_propagation', true);
    $dormancy = get_post_meta($post->ID, '_species_dormancy', true);
    ?>
    
    <style>
    .species-meta-table { width: 100%; border-collapse: collapse; }
    .species-meta-table th, .species-meta-table td { padding: 10px; border-bottom: 1px solid #e0e0e0; }
    .species-meta-table th { width: 200px; text-align: left; font-weight: 600; }
    .species-meta-table select, .species-meta-table input[type="text"] { width: 100%; max-width: 300px; }
    .species-meta-section { margin-bottom: 25px; }
    .species-meta-section h4 { margin: 0 0 15px; padding: 10px; background: #f5f5f5; }
    </style>
    
    <div class="species-meta-section">
        <h4>Podstawowe informacje</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_scientific_name">Nazwa naukowa</label></th>
                <td><input type="text" id="species_scientific_name" name="species_scientific_name" value="<?= esc_attr($scientific_name) ?>" placeholder="np. Dionaea muscipula" /></td>
            </tr>
            <tr>
                <th><label for="species_origin">Pochodzenie</label></th>
                <td><input type="text" id="species_origin" name="species_origin" value="<?= esc_attr($origin) ?>" placeholder="np. Ameryka Północna" /></td>
            </tr>
            <tr>
                <th><label for="species_difficulty">Trudność uprawy</label></th>
                <td>
                    <select id="species_difficulty" name="species_difficulty">
                        <option value="">Wybierz trudność</option>
                        <option value="easy" <?= selected($difficulty, 'easy', false) ?>>Łatwa</option>
                        <option value="medium" <?= selected($difficulty, 'medium', false) ?>>Średnia</option>
                        <option value="hard" <?= selected($difficulty, 'hard', false) ?>>Trudna</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_size">Rozmiar dorosłej rośliny</label></th>
                <td><input type="text" id="species_size" name="species_size" value="<?= esc_attr($size) ?>" placeholder="np. 5-10 cm" /></td>
            </tr>
        </table>
    </div>
    
    <div class="species-meta-section">
        <h4>Wymagania uprawowe</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_light">Wymagania świetlne</label></th>
                <td>
                    <select id="species_light" name="species_light">
                        <option value="">Wybierz wymagania</option>
                        <option value="low" <?= selected($light, 'low', false) ?>>Niskie</option>
                        <option value="medium" <?= selected($light, 'medium', false) ?>>Średnie</option>
                        <option value="high" <?= selected($light, 'high', false) ?>>Wysokie</option>
                        <option value="full_sun" <?= selected($light, 'full_sun', false) ?>>Pełne słońce</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_water">Wymagania wodne</label></th>
                <td>
                    <select id="species_water" name="species_water">
                        <option value="">Wybierz wymagania</option>
                        <option value="low" <?= selected($water, 'low', false) ?>>Niskie</option>
                        <option value="medium" <?= selected($water, 'medium', false) ?>>Średnie</option>
                        <option value="high" <?= selected($water, 'high', false) ?>>Wysokie</option>
                        <option value="bog" <?= selected($water, 'bog', false) ?>>Bagienne</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_temperature">Temperatura</label></th>
                <td><input type="text" id="species_temperature" name="species_temperature" value="<?= esc_attr($temperature) ?>" placeholder="np. 18-25°C" /></td>
            </tr>
            <tr>
                <th><label for="species_humidity">Wilgotność</label></th>
                <td><input type="text" id="species_humidity" name="species_humidity" value="<?= esc_attr($humidity) ?>" placeholder="np. 60-80%" /></td>
            </tr>
            <tr>
                <th><label for="species_soil">Podłoże</label></th>
                <td><input type="text" id="species_soil" name="species_soil" value="<?= esc_attr($soil) ?>" placeholder="np. torf + perlit" /></td>
            </tr>
            <tr>
                <th><label for="species_fertilizer">Nawożenie</label></th>
                <td><input type="text" id="species_fertilizer" name="species_fertilizer" value="<?= esc_attr($fertilizer) ?>" placeholder="np. nie nawozić / owady" /></td>
            </tr>
        </table>
    </div>
    
    <div class="species-meta-section">
        <h4>Dodatkowe informacje</h4>
        <table class="species-meta-table">
            <tr>
                <th><label for="species_growth_rate">Tempo wzrostu</label></th>
                <td>
                    <select id="species_growth_rate" name="species_growth_rate">
                        <option value="">Wybierz tempo</option>
                        <option value="slow" <?= selected($growth_rate, 'slow', false) ?>>Wolne</option>
                        <option value="medium" <?= selected($growth_rate, 'medium', false) ?>>Średnie</option>
                        <option value="fast" <?= selected($growth_rate, 'fast', false) ?>>Szybkie</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="species_flowering">Kwitnienie</label></th>
                <td><input type="text" id="species_flowering" name="species_flowering" value="<?= esc_attr($flowering) ?>" placeholder="np. wiosna-lato" /></td>
            </tr>
            <tr>
                <th><label for="species_propagation">Rozmnażanie</label></th>
                <td><input type="text" id="species_propagation" name="species_propagation" value="<?= esc_attr($propagation) ?>" placeholder="np. nasiona, podział" /></td>
            </tr>
            <tr>
                <th><label for="species_dormancy">Spoczynek zimowy</label></th>
                <td>
                    <select id="species_dormancy" name="species_dormancy">
                        <option value="">Wybierz</option>
                        <option value="yes" <?= selected($dormancy, 'yes', false) ?>>Tak</option>
                        <option value="no" <?= selected($dormancy, 'no', false) ?>>Nie</option>
                        <option value="partial" <?= selected($dormancy, 'partial', false) ?>>Częściowy</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
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
    
    $fields = array(
        'species_scientific_name' => '_species_scientific_name',
        'species_origin' => '_species_origin',
        'species_difficulty' => '_species_difficulty',
        'species_light' => '_species_light',
        'species_water' => '_species_water',
        'species_temperature' => '_species_temperature',
        'species_humidity' => '_species_humidity',
        'species_soil' => '_species_soil',
        'species_fertilizer' => '_species_fertilizer',
        'species_size' => '_species_size',
        'species_growth_rate' => '_species_growth_rate',
        'species_flowering' => '_species_flowering',
        'species_propagation' => '_species_propagation',
        'species_dormancy' => '_species_dormancy'
    );
    
    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'carni24_save_species_meta');

function carni24_species_admin_columns($columns) {
    // Zbuduj nową strukturę kolumn od zera
    $new_columns = array();
    
    // 1. Checkbox
    if (isset($columns['cb'])) {
        $new_columns['cb'] = $columns['cb'];
    }
    
    // 2. Obrazek wyróżniający
    $new_columns['featured_image'] = '🖼️ Obrazek';
    
    // 3. Tytuł
    if (isset($columns['title'])) {
        $new_columns['title'] = $columns['title'];
    }
    
    // 4. Custom excerpt (skrót)
    $new_columns['custom_excerpt'] = '📝 Skrót';
    
    // 5. Nazwa naukowa (specyficzne dla species)
    $new_columns['species_scientific'] = 'Nazwa naukowa';
    
    // 6. Inne kolumny specyficzne dla species
    $new_columns['species_difficulty'] = 'Trudność';
    $new_columns['species_origin'] = 'Pochodzenie';
    $new_columns['species_category'] = 'Kategorie';
    
    // 7. Data (zawsze na końcu)
    if (isset($columns['date'])) {
        $new_columns['date'] = $columns['date'];
    }
    
    return $new_columns;
}
// Ten hook ZOSTAJE - obsługuje wszystkie kolumny dla species
add_filter('manage_species_posts_columns', 'carni24_species_admin_columns');

function carni24_species_admin_columns_content($column, $post_id) {
    switch ($column) {
        // Obsługa obrazka wyróżniającego
        case 'featured_image':
            $thumbnail_id = get_post_thumbnail_id($post_id);
            
            if ($thumbnail_id) {
                $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'thumbnail');
                $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                $post_title = get_the_title($post_id);
                
                if ($thumbnail_url) {
                    echo '<div class="admin-thumbnail-container">';
                    echo '<img src="' . esc_url($thumbnail_url) . '" ';
                    echo 'alt="' . esc_attr($thumbnail_alt ?: $post_title) . '" ';
                    echo 'class="admin-thumbnail" ';
                    echo 'style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" ';
                    echo 'title="' . esc_attr($post_title) . '" />';
                    echo '</div>';
                }
            } else {
                echo '<div class="admin-thumbnail-placeholder" style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">';
                echo '<span style="font-size: 12px;">Brak</span>';
                echo '</div>';
            }
            break;
            
        // Obsługa custom excerpt
        case 'custom_excerpt':
            $custom_excerpt = get_post_meta($post_id, '_custom_excerpt', true);
            
            if (!empty($custom_excerpt)) {
                $excerpt_preview = wp_trim_words($custom_excerpt, 8, '...');
                echo '<span style="color: #16a34a; font-weight: 500;">✅ ' . esc_html($excerpt_preview) . '</span>';
            } else {
                echo '<span style="color: #6b7280;">—</span>';
            }
            break;
            
        // Nazwa naukowa
        case 'species_scientific':
            $scientific = get_post_meta($post_id, '_species_scientific_name', true);
            echo $scientific ? '<em>' . esc_html($scientific) . '</em>' : '—';
            break;
            
        // Trudność
        case 'species_difficulty':
            $difficulty = get_post_meta($post_id, '_species_difficulty', true);
            if ($difficulty) {
                $labels = array(
                    'easy' => 'Łatwa',
                    'medium' => 'Średnia', 
                    'hard' => 'Trudna'
                );
                $class = 'difficulty-' . $difficulty;
                echo '<span class="species-difficulty ' . esc_attr($class) . '">' . esc_html($labels[$difficulty] ?? $difficulty) . '</span>';
            } else {
                echo '—';
            }
            break;
            
        // Pochodzenie
        case 'species_origin':
            $origin = get_post_meta($post_id, '_species_origin', true);
            echo $origin ? esc_html($origin) : '—';
            break;
            
        // Kategorie
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
// Ten hook ZOSTAJE - obsługuje wszystkie kolumny dla species
add_action('manage_species_posts_custom_column', 'carni24_species_admin_columns_content', 10, 2);

function carni24_species_admin_styles() {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'species') {
        ?>
        <style>
        /* Style dla kolumn */
        .column-featured_image { width: 80px; }
        .column-custom_excerpt { width: 200px; }
        .column-species_scientific { width: 200px; }
        .column-species_difficulty { width: 100px; }
        .column-species_origin { width: 150px; }
        .column-species_category { width: 150px; }
        
        /* Style dla trudności */
        .species-difficulty {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .difficulty-easy {
            background: #d4edda;
            color: #155724;
        }
        .difficulty-medium {
            background: #fff3cd;
            color: #856404;
        }
        .difficulty-hard {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Style dla miniaturek */
        .admin-thumbnail-container {
            display: flex;
            align-items: center;
        }
        </style>
        <?php
    }
}
add_action('admin_head', 'carni24_species_admin_styles');

function carni24_flush_species_rewrite_rules() {
    carni24_register_species_post_type();
    carni24_register_species_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'carni24_flush_species_rewrite_rules');