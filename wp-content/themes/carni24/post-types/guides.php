<?php
/**
 * Custom Post Type: Poradniki (Guides)
 * Plik: post-types/guides.php
 * Autor: Carni24 Team
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Rejestracja Custom Post Type - Poradniki
 */
function carni24_register_guides_post_type() {
    $labels = array(
        'name'                  => _x('Poradniki', 'Post type general name', 'carni24'),
        'singular_name'         => _x('Poradnik', 'Post type singular name', 'carni24'),
        'menu_name'             => _x('Poradniki', 'Admin Menu text', 'carni24'),
        'name_admin_bar'        => _x('Poradnik', 'Add New on Toolbar', 'carni24'),
        'add_new'               => __('Dodaj nowy', 'carni24'),
        'add_new_item'          => __('Dodaj nowy poradnik', 'carni24'),
        'new_item'              => __('Nowy poradnik', 'carni24'),
        'edit_item'             => __('Edytuj poradnik', 'carni24'),
        'view_item'             => __('Wyświetl poradnik', 'carni24'),
        'all_items'             => __('Wszystkie poradniki', 'carni24'),
        'search_items'          => __('Szukaj poradników', 'carni24'),
        'parent_item_colon'     => __('Nadrzędny poradnik:', 'carni24'),
        'not_found'             => __('Nie znaleziono poradników.', 'carni24'),
        'not_found_in_trash'    => __('Nie znaleziono poradników w koszu.', 'carni24'),
        'featured_image'        => _x('Zdjęcie przewodnie', 'Overrides the "Featured Image" phrase', 'carni24'),
        'set_featured_image'    => _x('Ustaw zdjęcie przewodnie', 'Overrides the "Set featured image" phrase', 'carni24'),
        'remove_featured_image' => _x('Usuń zdjęcie przewodnie', 'Overrides the "Remove featured image" phrase', 'carni24'),
        'use_featured_image'    => _x('Użyj jako zdjęcie przewodnie', 'Overrides the "Use as featured image" phrase', 'carni24'),
        'archives'              => _x('Archiwum poradników', 'The post type archive label used in nav menus', 'carni24'),
        'insert_into_item'      => _x('Wstaw do poradnika', 'Overrides the "Insert into post" phrase', 'carni24'),
        'uploaded_to_this_item' => _x('Przesłane do tego poradnika', 'Overrides the "Uploaded to this post" phrase', 'carni24'),
        'filter_items_list'     => _x('Filtruj listę poradników', 'Screen reader text for the filter links', 'carni24'),
        'items_list_navigation' => _x('Nawigacja listy poradników', 'Screen reader text for the pagination', 'carni24'),
        'items_list'            => _x('Lista poradników', 'Screen reader text for the items list', 'carni24'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'poradniki'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-book-alt',
        'show_in_rest'       => true,
        'supports'           => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'author',
            'comments',
            'revisions',
            'custom-fields',
            'page-attributes'
        ),
        'taxonomies'         => array('guide_category', 'guide_tag'),
        'show_in_nav_menus'  => true,
        'can_export'         => true,
        'delete_with_user'   => false,
    );

    register_post_type('guides', $args);
}
add_action('init', 'carni24_register_guides_post_type');

/**
 * Rejestracja taksonomii dla poradników
 */
function carni24_register_guides_taxonomies() {
    
    // Kategorie poradników
    $category_labels = array(
        'name'              => _x('Kategorie poradników', 'taxonomy general name', 'carni24'),
        'singular_name'     => _x('Kategoria poradników', 'taxonomy singular name', 'carni24'),
        'search_items'      => __('Szukaj kategorii', 'carni24'),
        'all_items'         => __('Wszystkie kategorie', 'carni24'),
        'parent_item'       => __('Nadrzędna kategoria', 'carni24'),
        'parent_item_colon' => __('Nadrzędna kategoria:', 'carni24'),
        'edit_item'         => __('Edytuj kategorię', 'carni24'),
        'update_item'       => __('Aktualizuj kategorię', 'carni24'),
        'add_new_item'      => __('Dodaj nową kategorię', 'carni24'),
        'new_item_name'     => __('Nazwa nowej kategorii', 'carni24'),
        'menu_name'         => __('Kategorie', 'carni24'),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'kategoria-poradnikow'),
        'show_in_rest'      => true,
    );

    register_taxonomy('guide_category', array('guides'), $category_args);

    // Tagi poradników
    $tag_labels = array(
        'name'                       => _x('Tagi poradników', 'taxonomy general name', 'carni24'),
        'singular_name'              => _x('Tag poradników', 'taxonomy singular name', 'carni24'),
        'search_items'               => __('Szukaj tagów', 'carni24'),
        'popular_items'              => __('Popularne tagi', 'carni24'),
        'all_items'                  => __('Wszystkie tagi', 'carni24'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => __('Edytuj tag', 'carni24'),
        'update_item'                => __('Aktualizuj tag', 'carni24'),
        'add_new_item'               => __('Dodaj nowy tag', 'carni24'),
        'new_item_name'              => __('Nazwa nowego tagu', 'carni24'),
        'separate_items_with_commas' => __('Oddziel tagi przecinkami', 'carni24'),
        'add_or_remove_items'        => __('Dodaj lub usuń tagi', 'carni24'),
        'choose_from_most_used'      => __('Wybierz z najczęściej używanych', 'carni24'),
        'not_found'                  => __('Nie znaleziono tagów.', 'carni24'),
        'menu_name'                  => __('Tagi', 'carni24'),
    );

    $tag_args = array(
        'hierarchical'          => false,
        'labels'                => $tag_labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array('slug' => 'tag-poradnikow'),
        'show_in_rest'          => true,
    );

    register_taxonomy('guide_tag', array('guides'), $tag_args);
}
add_action('init', 'carni24_register_guides_taxonomies');

/**
 * Dodaj meta boxy dla poradników
 */
function carni24_add_guides_meta_boxes() {
    add_meta_box(
        'guide_details',
        __('Szczegóły poradnika', 'carni24'),
        'carni24_guide_details_callback',
        'guides',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_add_guides_meta_boxes');

/**
 * Callback dla meta boxa szczegółów poradnika
 */
function carni24_guide_details_callback($post) {
    wp_nonce_field('carni24_guide_meta_box', 'carni24_guide_meta_box_nonce');
    
    $difficulty = get_post_meta($post->ID, '_guide_difficulty', true);
    $duration = get_post_meta($post->ID, '_guide_duration', true);
    $tools_needed = get_post_meta($post->ID, '_guide_tools_needed', true);
    $season = get_post_meta($post->ID, '_guide_season', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="guide_difficulty"><?php _e('Poziom trudności:', 'carni24'); ?></label></th>
            <td>
                <select id="guide_difficulty" name="guide_difficulty" class="postbox">
                    <option value="beginner" <?php selected($difficulty, 'beginner'); ?>><?php _e('Początkujący', 'carni24'); ?></option>
                    <option value="intermediate" <?php selected($difficulty, 'intermediate'); ?>><?php _e('Średniozaawansowany', 'carni24'); ?></option>
                    <option value="advanced" <?php selected($difficulty, 'advanced'); ?>><?php _e('Zaawansowany', 'carni24'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="guide_duration"><?php _e('Czas realizacji:', 'carni24'); ?></label></th>
            <td>
                <input type="text" id="guide_duration" name="guide_duration" value="<?php echo esc_attr($duration); ?>" placeholder="np. 30 minut, 2 godziny">
                <p class="description"><?php _e('Szacowany czas potrzebny na wykonanie poradnika', 'carni24'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="guide_tools_needed"><?php _e('Potrzebne narzędzia:', 'carni24'); ?></label></th>
            <td>
                <textarea id="guide_tools_needed" name="guide_tools_needed" rows="3" class="large-text"><?php echo esc_textarea($tools_needed); ?></textarea>
                <p class="description"><?php _e('Lista narzędzi i materiałów potrzebnych do realizacji', 'carni24'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="guide_season"><?php _e('Najlepszy sezon:', 'carni24'); ?></label></th>
            <td>
                <select id="guide_season" name="guide_season" class="postbox">
                    <option value="" <?php selected($season, ''); ?>><?php _e('Cały rok', 'carni24'); ?></option>
                    <option value="spring" <?php selected($season, 'spring'); ?>><?php _e('Wiosna', 'carni24'); ?></option>
                    <option value="summer" <?php selected($season, 'summer'); ?>><?php _e('Lato', 'carni24'); ?></option>
                    <option value="autumn" <?php selected($season, 'autumn'); ?>><?php _e('Jesień', 'carni24'); ?></option>
                    <option value="winter" <?php selected($season, 'winter'); ?>><?php _e('Zima', 'carni24'); ?></option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Zapisz meta dane poradnika
 */
function carni24_save_guide_meta($post_id) {
    if (!isset($_POST['carni24_guide_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['carni24_guide_meta_box_nonce'], 'carni24_guide_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['post_type']) && 'guides' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Zapisz dane
    if (isset($_POST['guide_difficulty'])) {
        update_post_meta($post_id, '_guide_difficulty', sanitize_text_field($_POST['guide_difficulty']));
    }

    if (isset($_POST['guide_duration'])) {
        update_post_meta($post_id, '_guide_duration', sanitize_text_field($_POST['guide_duration']));
    }

    if (isset($_POST['guide_tools_needed'])) {
        update_post_meta($post_id, '_guide_tools_needed', sanitize_textarea_field($_POST['guide_tools_needed']));
    }

    if (isset($_POST['guide_season'])) {
        update_post_meta($post_id, '_guide_season', sanitize_text_field($_POST['guide_season']));
    }
}
add_action('save_post', 'carni24_save_guide_meta');

/**
 * Dodaj kolumny do listy poradników w adminie
 */
function carni24_guides_admin_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        
        if ($key === 'title') {
            $new_columns['guide_difficulty'] = __('Poziom trudności', 'carni24');
            $new_columns['guide_duration'] = __('Czas realizacji', 'carni24');
        }
    }
    
    return $new_columns;
}
add_filter('manage_guides_posts_columns', 'carni24_guides_admin_columns');

/**
 * Wypełnij kolumny w liście poradników
 */
function carni24_guides_admin_columns_content($column, $post_id) {
    switch ($column) {
        case 'guide_difficulty':
            $difficulty = get_post_meta($post_id, '_guide_difficulty', true);
            $difficulty_labels = array(
                'beginner' => __('Początkujący', 'carni24'),
                'intermediate' => __('Średniozaawansowany', 'carni24'),
                'advanced' => __('Zaawansowany', 'carni24')
            );
            
            if ($difficulty && isset($difficulty_labels[$difficulty])) {
                $class = 'difficulty-' . $difficulty;
                echo '<span class="guide-difficulty ' . esc_attr($class) . '">' . esc_html($difficulty_labels[$difficulty]) . '</span>';
            } else {
                echo '<span class="guide-difficulty">—</span>';
            }
            break;
            
        case 'guide_duration':
            $duration = get_post_meta($post_id, '_guide_duration', true);
            echo $duration ? esc_html($duration) : '—';
            break;
    }
}
add_action('manage_guides_posts_custom_column', 'carni24_guides_admin_columns_content', 10, 2);

/**
 * Dodaj style dla kolumn w adminie
 */
function carni24_guides_admin_styles() {
    ?>
    <style>
    .guide-difficulty {
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .difficulty-beginner {
        background: #d4edda;
        color: #155724;
    }
    .difficulty-intermediate {
        background: #fff3cd;
        color: #856404;
    }
    .difficulty-advanced {
        background: #f8d7da;
        color: #721c24;
    }
    </style>
    <?php
}
add_action('admin_head', 'carni24_guides_admin_styles');

/**
 * Flush rewrite rules po aktywacji
 */
function carni24_guides_flush_rewrites() {
    carni24_register_guides_post_type();
    carni24_register_guides_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'carni24_guides_flush_rewrites');

/**
 * Funkcje pomocnicze dla poradników
 */

// Pobierz poziom trudności poradnika
function carni24_get_guide_difficulty($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $difficulty = get_post_meta($post_id, '_guide_difficulty', true);
    $difficulty_labels = array(
        'beginner' => __('Początkujący', 'carni24'),
        'intermediate' => __('Średniozaawansowany', 'carni24'),
        'advanced' => __('Zaawansowany', 'carni24')
    );
    
    return isset($difficulty_labels[$difficulty]) ? $difficulty_labels[$difficulty] : '';
}

// Pobierz czas realizacji poradnika
function carni24_get_guide_duration($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, '_guide_duration', true);
}

// Pobierz potrzebne narzędzia
function carni24_get_guide_tools($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return get_post_meta($post_id, '_guide_tools_needed', true);
}

// Pobierz najlepszy sezon
function carni24_get_guide_season($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $season = get_post_meta($post_id, '_guide_season', true);
    $season_labels = array(
        'spring' => __('Wiosna', 'carni24'),
        'summer' => __('Lato', 'carni24'),
        'autumn' => __('Jesień', 'carni24'),
        'winter' => __('Zima', 'carni24')
    );
    
    return isset($season_labels[$season]) ? $season_labels[$season] : __('Cały rok', 'carni24');
}