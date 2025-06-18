<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_register_guides_post_type() {
    $labels = array(
        'name'                  => 'Poradniki',
        'singular_name'         => 'Poradnik',
        'menu_name'             => 'Poradniki',
        'name_admin_bar'        => 'Poradnik',
        'archives'              => 'Archiwum poradników',
        'attributes'            => 'Atrybuty poradnika',
        'parent_item_colon'     => 'Rodzic poradnika:',
        'all_items'             => 'Wszystkie poradniki',
        'add_new_item'          => 'Dodaj nowy poradnik',
        'add_new'               => 'Dodaj nowy',
        'new_item'              => 'Nowy poradnik',
        'edit_item'             => 'Edytuj poradnik',
        'update_item'           => 'Zaktualizuj poradnik',
        'view_item'             => 'Zobacz poradnik',
        'view_items'            => 'Zobacz poradniki',
        'search_items'          => 'Szukaj poradników',
        'not_found'             => 'Nie znaleziono',
        'not_found_in_trash'    => 'Nie znaleziono w koszu',
        'featured_image'        => 'Zdjęcie główne',
        'set_featured_image'    => 'Ustaw zdjęcie główne',
        'remove_featured_image' => 'Usuń zdjęcie główne',
        'use_featured_image'    => 'Użyj jako zdjęcie główne',
        'insert_into_item'      => 'Wstaw do poradnika',
        'uploaded_to_this_item' => 'Przesłane do tego poradnika',
        'items_list'            => 'Lista poradników',
        'items_list_navigation' => 'Nawigacja listy poradników',
        'filter_items_list'     => 'Filtruj listę poradników',
    );

    $args = array(
        'label'                 => 'Poradnik',
        'description'           => 'Poradniki uprawy roślin mięsożernych',
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'author'),
        'taxonomies'            => array('guide_category', 'guide_tag', 'guide_difficulty'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 21,
        'menu_icon'             => 'dashicons-book',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array(
            'slug' => 'poradniki',
            'with_front' => false,
        ),
    );

    register_post_type('guides', $args);
}
add_action('init', 'carni24_register_guides_post_type', 0);

function carni24_register_guides_taxonomies() {
    $category_labels = array(
        'name'                       => 'Kategorie poradników',
        'singular_name'              => 'Kategoria poradnika',
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
            'slug' => 'kategoria-poradnika',
        ),
    );

    register_taxonomy('guide_category', array('guides'), $category_args);

    $tag_labels = array(
        'name'                       => 'Tagi poradników',
        'singular_name'              => 'Tag poradnika',
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
            'slug' => 'tag-poradnika',
        ),
    );

    register_taxonomy('guide_tag', array('guides'), $tag_args);

    $difficulty_labels = array(
        'name'                       => 'Poziom trudności',
        'singular_name'              => 'Poziom',
        'menu_name'                  => 'Poziom trudności',
        'all_items'                  => 'Wszystkie poziomy',
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => 'Nazwa nowego poziomu',
        'add_new_item'               => 'Dodaj nowy poziom',
        'edit_item'                  => 'Edytuj poziom',
        'update_item'                => 'Zaktualizuj poziom',
        'view_item'                  => 'Zobacz poziom',
        'separate_items_with_commas' => 'Oddziel poziomy przecinkami',
        'add_or_remove_items'        => 'Dodaj lub usuń poziomy',
        'choose_from_most_used'      => 'Wybierz z najczęściej używanych',
        'popular_items'              => 'Popularne poziomy',
        'search_items'               => 'Szukaj poziomów',
        'not_found'                  => 'Nie znaleziono',
        'no_terms'                   => 'Brak poziomów',
        'items_list'                 => 'Lista poziomów',
        'items_list_navigation'      => 'Nawigacja poziomów',
    );

    $difficulty_args = array(
        'labels'                     => $difficulty_labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => array(
            'slug' => 'poziom-trudnosci',
        ),
    );

    register_taxonomy('guide_difficulty', array('guides'), $difficulty_args);
}
add_action('init', 'carni24_register_guides_taxonomies', 0);

function carni24_guides_meta_boxes() {
    add_meta_box(
        'guide_details',
        'Szczegóły poradnika',
        'carni24_guide_meta_box_callback',
        'guides',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'carni24_guides_meta_boxes');

function carni24_guide_meta_box_callback($post) {
    wp_nonce_field('carni24_guide_meta_nonce', 'carni24_guide_meta_nonce');
    
    $difficulty_level = get_post_meta($post->ID, '_guide_difficulty_level', true);
    $estimated_time = get_post_meta($post->ID, '_guide_estimated_time', true);
    $required_tools = get_post_meta($post->ID, '_guide_required_tools', true);
    $materials_needed = get_post_meta($post->ID, '_guide_materials_needed', true);
    $best_season = get_post_meta($post->ID, '_guide_best_season', true);
    $target_plants = get_post_meta($post->ID, '_guide_target_plants', true);
    $warning_notes = get_post_meta($post->ID, '_guide_warning_notes', true);
    $tips = get_post_meta($post->ID, '_guide_tips', true);
    $video_url = get_post_meta($post->ID, '_guide_video_url', true);
    $external_links = get_post_meta($post->ID, '_guide_external_links', true);
    ?>
    
    <style>
    .guide-meta-table { width: 100%; border-collapse: collapse; }
    .guide-meta-table th, .guide-meta-table td { padding: 10px; border-bottom: 1px solid #e0e0e0; }
    .guide-meta-table th { width: 200px; text-align: left; font-weight: 600; }
    .guide-meta-table select, .guide-meta-table input[type="text"], .guide-meta-table input[type="url"] { width: 100%; max-width: 400px; }
    .guide-meta-table textarea { width: 100%; max-width: 400px; height: 80px; }
    .guide-meta-section { margin-bottom: 25px; }
    .guide-meta-section h4 { margin: 0 0 15px; padding: 10px; background: #f0f8ff; border-left: 4px solid #0073aa; }
    </style>
    
    <div class="guide-meta-section">
        <h4>Podstawowe informacje</h4>
        <table class="guide-meta-table">
            <tr>
                <th><label for="guide_difficulty_level">Poziom trudności</label></th>
                <td>
                    <select id="guide_difficulty_level" name="guide_difficulty_level">
                        <option value="">Wybierz poziom</option>
                        <option value="beginner" <?= selected($difficulty_level, 'beginner', false) ?>>Początkujący</option>
                        <option value="intermediate" <?= selected($difficulty_level, 'intermediate', false) ?>>Średniozaawansowany</option>
                        <option value="advanced" <?= selected($difficulty_level, 'advanced', false) ?>>Zaawansowany</option>
                        <option value="expert" <?= selected($difficulty_level, 'expert', false) ?>>Ekspert</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="guide_estimated_time">Szacowany czas</label></th>
                <td><input type="text" id="guide_estimated_time" name="guide_estimated_time" value="<?= esc_attr($estimated_time) ?>" placeholder="np. 30 minut, 2 godziny" /></td>
            </tr>
            <tr>
                <th><label for="guide_best_season">Najlepszy sezon</label></th>
                <td>
                    <select id="guide_best_season" name="guide_best_season">
                        <option value="">Wybierz sezon</option>
                        <option value="spring" <?= selected($best_season, 'spring', false) ?>>Wiosna</option>
                        <option value="summer" <?= selected($best_season, 'summer', false) ?>>Lato</option>
                        <option value="autumn" <?= selected($best_season, 'autumn', false) ?>>Jesień</option>
                        <option value="winter" <?= selected($best_season, 'winter', false) ?>>Zima</option>
                        <option value="year_round" <?= selected($best_season, 'year_round', false) ?>>Cały rok</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="guide_target_plants">Docelowe rośliny</label></th>
                <td><input type="text" id="guide_target_plants" name="guide_target_plants" value="<?= esc_attr($target_plants) ?>" placeholder="np. Dionaea, Sarracenia, wszystkie" /></td>
            </tr>
        </table>
    </div>
    
    <div class="guide-meta-section">
        <h4>Wymagania i materiały</h4>
        <table class="guide-meta-table">
            <tr>
                <th><label for="guide_required_tools">Wymagane narzędzia</label></th>
                <td><textarea id="guide_required_tools" name="guide_required_tools" placeholder="Lista narzędzi, każde w nowej linii"><?= esc_textarea($required_tools) ?></textarea></td>
            </tr>
            <tr>
                <th><label for="guide_materials_needed">Potrzebne materiały</label></th>
                <td><textarea id="guide_materials_needed" name="guide_materials_needed" placeholder="Lista materiałów, każdy w nowej linii"><?= esc_textarea($materials_needed) ?></textarea></td>
            </tr>
        </table>
    </div>
    
    <div class="guide-meta-section">
        <h4>Dodatkowe informacje</h4>
        <table class="guide-meta-table">
            <tr>
                <th><label for="guide_warning_notes">Ostrzeżenia i uwagi</label></th>
                <td><textarea id="guide_warning_notes" name="guide_warning_notes" placeholder="Ważne ostrzeżenia dla czytelników"><?= esc_textarea($warning_notes) ?></textarea></td>
            </tr>
            <tr>
                <th><label for="guide_tips">Dodatkowe wskazówki</label></th>
                <td><textarea id="guide_tips" name="guide_tips" placeholder="Przydatne wskazówki i triki"><?= esc_textarea($tips) ?></textarea></td>
            </tr>
            <tr>
                <th><label for="guide_video_url">URL filmu YouTube</label></th>
                <td><input type="url" id="guide_video_url" name="guide_video_url" value="<?= esc_attr($video_url) ?>" placeholder="https://www.youtube.com/watch?v=..." /></td>
            </tr>
            <tr>
                <th><label for="guide_external_links">Przydatne linki</label></th>
                <td><textarea id="guide_external_links" name="guide_external_links" placeholder="Lista przydatnych linków, każdy w nowej linii"><?= esc_textarea($external_links) ?></textarea></td>
            </tr>
        </table>
    </div>
    <?php
}

function carni24_save_guide_meta($post_id) {
    if (!isset($_POST['carni24_guide_meta_nonce']) || !wp_verify_nonce($_POST['carni24_guide_meta_nonce'], 'carni24_guide_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        'guide_difficulty_level' => '_guide_difficulty_level',
        'guide_estimated_time' => '_guide_estimated_time',
        'guide_required_tools' => '_guide_required_tools',
        'guide_materials_needed' => '_guide_materials_needed',
        'guide_best_season' => '_guide_best_season',
        'guide_target_plants' => '_guide_target_plants',
        'guide_warning_notes' => '_guide_warning_notes',
        'guide_tips' => '_guide_tips',
        'guide_video_url' => '_guide_video_url',
        'guide_external_links' => '_guide_external_links'
    );
    
    foreach ($fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            if ($field === 'guide_video_url') {
                update_post_meta($post_id, $meta_key, esc_url_raw($_POST[$field]));
            } elseif (in_array($field, array('guide_required_tools', 'guide_materials_needed', 'guide_warning_notes', 'guide_tips', 'guide_external_links'))) {
                update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$field]));
            } else {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('save_post', 'carni24_save_guide_meta');

function carni24_guides_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['featured_image'] = 'Zdjęcie';
    $new_columns['title'] = $columns['title'];
    $new_columns['guide_difficulty'] = 'Poziom';
    $new_columns['guide_time'] = 'Czas';
    $new_columns['guide_season'] = 'Sezon';
    $new_columns['guide_category'] = 'Kategorie';
    $new_columns['author'] = $columns['author'];
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_guides_posts_columns', 'carni24_guides_admin_columns');

function carni24_guides_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'guide_difficulty':
            $difficulty = get_post_meta($post_id, '_guide_difficulty_level', true);
            if ($difficulty) {
                $labels = array(
                    'beginner' => 'Początkujący',
                    'intermediate' => 'Średniozaawansowany',
                    'advanced' => 'Zaawansowany',
                    'expert' => 'Ekspert'
                );
                $class = 'guide-difficulty-' . $difficulty;
                echo '<span class="guide-difficulty ' . esc_attr($class) . '">' . esc_html($labels[$difficulty]) . '</span>';
            } else {
                echo '—';
            }
            break;
            
        case 'guide_time':
            $time = get_post_meta($post_id, '_guide_estimated_time', true);
            echo $time ? esc_html($time) : '—';
            break;
            
        case 'guide_season':
            $season = get_post_meta($post_id, '_guide_best_season', true);
            if ($season) {
                $seasons = array(
                    'spring' => '🌸 Wiosna',
                    'summer' => '☀️ Lato',
                    'autumn' => '🍂 Jesień',
                    'winter' => '❄️ Zima',
                    'year_round' => '🗓️ Cały rok'
                );
                echo $seasons[$season];
            } else {
                echo '—';
            }
            break;
            
        case 'featured_image':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo '—';
            }
            break;
            
        case 'guide_category':
            $terms = get_the_terms($post_id, 'guide_category');
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
//add_action('manage_guides_posts_custom_column', 'carni24_guides_admin_columns_content', 10, 2);

function carni24_guides_admin_styles() {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'guides') {
        ?>
        <style>
        .guide-difficulty {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .guide-difficulty-beginner {
            background: #d4edda;
            color: #155724;
        }
        .guide-difficulty-intermediate {
            background: #fff3cd;
            color: #856404;
        }
        .guide-difficulty-advanced {
            background: #f8d7da;
            color: #721c24;
        }
        .guide-difficulty-expert {
            background: #d1ecf1;
            color: #0c5460;
        }
        .column-featured_image {
            width: 60px;
        }
        .column-guide_difficulty {
            width: 120px;
        }
        .column-guide_time {
            width: 100px;
        }
        .column-guide_season {
            width: 120px;
        }
        </style>
        <?php
    }
}
add_action('admin_head', 'carni24_guides_admin_styles');

function carni24_create_default_guide_terms() {
    if (!term_exists('Podstawy uprawy', 'guide_category')) {
        wp_insert_term('Podstawy uprawy', 'guide_category', array(
            'description' => 'Podstawowe informacje o uprawie roślin mięsożernych',
            'slug' => 'podstawy-uprawy'
        ));
    }
    
    if (!term_exists('Rozmnażanie', 'guide_category')) {
        wp_insert_term('Rozmnażanie', 'guide_category', array(
            'description' => 'Poradniki dotyczące rozmnażania roślin',
            'slug' => 'rozmnazanie'
        ));
    }
    
    if (!term_exists('Pielęgnacja', 'guide_category')) {
        wp_insert_term('Pielęgnacja', 'guide_category', array(
            'description' => 'Pielęgnacja i utrzymanie roślin mięsożernych',
            'slug' => 'pielegnacja'
        ));
    }
    
    if (!term_exists('Problemy i choroby', 'guide_category')) {
        wp_insert_term('Problemy i choroby', 'guide_category', array(
            'description' => 'Rozwiązywanie problemów z roślinami',
            'slug' => 'problemy-choroby'
        ));
    }
    
    $difficulty_terms = array(
        'Początkujący' => 'poczatkujacy',
        'Średniozaawansowany' => 'sredniozaawansowany',
        'Zaawansowany' => 'zaawansowany',
        'Ekspert' => 'ekspert'
    );
    
    foreach ($difficulty_terms as $name => $slug) {
        if (!term_exists($name, 'guide_difficulty')) {
            wp_insert_term($name, 'guide_difficulty', array(
                'slug' => $slug
            ));
        }
    }
}
add_action('init', 'carni24_create_default_guide_terms', 999);

function carni24_flush_guides_rewrite_rules() {
    carni24_register_guides_post_type();
    carni24_register_guides_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'carni24_flush_guides_rewrite_rules');