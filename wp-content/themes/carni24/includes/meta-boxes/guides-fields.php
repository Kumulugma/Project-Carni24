<?php
/**
 * Meta boxes dla poradników (guides)
 */

// Dodaj meta boxy dla guides
function carni24_add_guides_meta_boxes() {
    add_meta_box(
        'guides_details',
        'Szczegóły poradnika',
        'carni24_guides_details_callback',
        'guides',
        'normal',
        'high'
    );
    
    add_meta_box(
        'guides_bibliography',
        'Bibliografia i źródła',
        'carni24_guides_bibliography_callback',
        'guides',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_add_guides_meta_boxes');

function carni24_guides_details_callback($post) {
    wp_nonce_field('carni24_guides_details_meta', 'carni24_guides_details_nonce');
    
    $difficulty = get_post_meta($post->ID, '_guide_difficulty', true);
    $duration = get_post_meta($post->ID, '_guide_duration', true);
    $season = get_post_meta($post->ID, '_guide_season', true);
    $tools = get_post_meta($post->ID, '_guide_tools', true);
    $materials = get_post_meta($post->ID, '_guide_materials', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="guide_difficulty">Poziom trudności:</label></th>
            <td>
                <select id="guide_difficulty" name="guide_difficulty">
                    <option value="">Wybierz poziom</option>
                    <option value="Początkujący" <?= selected($difficulty, 'Początkujący', false) ?>>Początkujący</option>
                    <option value="Średniozaawansowany" <?= selected($difficulty, 'Średniozaawansowany', false) ?>>Średniozaawansowany</option>
                    <option value="Zaawansowany" <?= selected($difficulty, 'Zaawansowany', false) ?>>Zaawansowany</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="guide_duration">Czas wykonania:</label></th>
            <td><input type="text" id="guide_duration" name="guide_duration" value="<?= esc_attr($duration) ?>" placeholder="np. 30 minut, 2 godziny" /></td>
        </tr>
        <tr>
            <th><label for="guide_season">Najlepszy sezon:</label></th>
            <td><input type="text" id="guide_season" name="guide_season" value="<?= esc_attr($season) ?>" placeholder="np. Wiosna, Cały rok" /></td>
        </tr>
        <tr>
            <th><label for="guide_tools">Potrzebne narzędzia:</label></th>
            <td><textarea id="guide_tools" name="guide_tools" rows="3"><?= esc_textarea($tools) ?></textarea></td>
        </tr>
        <tr>
            <th><label for="guide_materials">Materiały:</label></th>
            <td><textarea id="guide_materials" name="guide_materials" rows="3"><?= esc_textarea($materials) ?></textarea></td>
        </tr>
    </table>
    <?php
}

function carni24_guides_bibliography_callback($post) {
    wp_nonce_field('carni24_guides_bibliography_meta', 'carni24_guides_bibliography_nonce');
    $bibliography = get_post_meta($post->ID, '_guide_bibliography', true);
    
    echo '<p><label for="guide_bibliography">Bibliografia, źródła i referencje:</label></p>';
    wp_editor($bibliography, 'guide_bibliography', array(
        'textarea_name' => 'guide_bibliography',
        'media_buttons' => false,
        'textarea_rows' => 8,
        'teeny' => true
    ));
}

// Zapisywanie meta danych
add_action('save_post', 'carni24_save_guides_meta');
function carni24_save_guides_meta($post_id) {
    // Sprawdzenia bezpieczeństwa
    if ((!isset($_POST['carni24_guides_details_nonce']) || 
         !wp_verify_nonce($_POST['carni24_guides_details_nonce'], 'carni24_guides_details_meta')) &&
        (!isset($_POST['carni24_guides_bibliography_nonce']) || 
         !wp_verify_nonce($_POST['carni24_guides_bibliography_nonce'], 'carni24_guides_bibliography_meta'))) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Zapisz szczegóły
    $fields = ['guide_difficulty', 'guide_duration', 'guide_season', 'guide_tools', 'guide_materials'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Zapisz bibliografię
    if (isset($_POST['guide_bibliography'])) {
        update_post_meta($post_id, '_guide_bibliography', wp_kses_post($_POST['guide_bibliography']));
    }
}
?>