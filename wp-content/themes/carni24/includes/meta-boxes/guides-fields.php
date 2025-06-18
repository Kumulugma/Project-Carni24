<?php
/**
 * Meta boxes dla poradników (guides) - Uproszczona wersja
 * Usunięto niewyświetlane pola: category, target_audience, prerequisites, expected_results, tips, warnings
 * 
 * @package Carni24
 * @subpackage MetaBoxes
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Zastępuje istniejące meta boxy dla guides ulepszonym interfejsem
 */
function carni24_override_guides_meta_boxes() {
    // Usuń istniejące meta boxy guides jeśli istnieją
    remove_meta_box('guides_details', 'guides', 'normal');
    remove_meta_box('guides_bibliography', 'guides', 'normal');
    
    // Dodaj nowe ulepszone meta boxy
    add_meta_box(
        'guides_details_improved',
        '📖 Szczegóły poradnika',
        'carni24_guides_improved_details_callback',
        'guides',
        'normal',
        'high'
    );
    
    add_meta_box(
        'guides_bibliography_improved',
        '📚 Bibliografia i źródła',
        'carni24_guides_improved_bibliography_callback',
        'guides',
        'normal',
        'default'
    );

    add_meta_box(
        'guides_stats_improved',
        '📊 Statystyki poradnika',
        'carni24_guides_improved_stats_callback',
        'guides',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_override_guides_meta_boxes', 15);

/**
 * Główny meta box dla guides - uproszczona wersja
 */
function carni24_guides_improved_details_callback($post) {
    wp_nonce_field('carni24_guides_improved_details_meta', 'carni24_guides_improved_details_nonce');
    
    // Pobierz dane - tylko te które są wyświetlane na froncie
    $difficulty = get_post_meta($post->ID, '_guide_difficulty', true);
    $duration = get_post_meta($post->ID, '_guide_duration', true);
    $season = get_post_meta($post->ID, '_guide_season', true);
    $tools = get_post_meta($post->ID, '_guide_tools', true);
    $materials = get_post_meta($post->ID, '_guide_materials', true);
    ?>
    
    <div class="carni24-guides-metabox">
        <!-- Nawigacja zakładek -->
        <ul class="carni24-guides-tabs">
            <li><a href="#guides-basic" class="tab-link active" data-tab="guides-basic">📋 Podstawowe</a></li>
            <li><a href="#guides-resources" class="tab-link" data-tab="guides-resources">🔧 Zasoby</a></li>
        </ul>

        <!-- Zawartość zakładek -->
        <div class="carni24-guides-content">
            
            <!-- Zakładka: Podstawowe informacje -->
            <div id="guides-basic" class="guides-tab-content active">
                <div class="guides-info-card">
                    <h4>📋 Klasyfikacja poradnika</h4>
                    <div class="guides-field-grid">
                        <div class="guides-field">
                            <label for="guide_difficulty">
                                <strong>Poziom trudności</strong>
                                <span class="field-hint">Wymagane doświadczenie</span>
                            </label>
                            <select id="guide_difficulty" name="guide_difficulty" class="difficulty-select">
                                <option value="">Wybierz poziom</option>
                                <option value="Początkujący" <?= selected($difficulty, 'Początkujący', false) ?>>🟢 Początkujący</option>
                                <option value="Średniozaawansowany" <?= selected($difficulty, 'Średniozaawansowany', false) ?>>🟡 Średniozaawansowany</option>
                                <option value="Zaawansowany" <?= selected($difficulty, 'Zaawansowany', false) ?>>🔴 Zaawansowany</option>
                            </select>
                        </div>

                        <div class="guides-field">
                            <label for="guide_duration">
                                <strong>Czas wykonania</strong>
                                <span class="field-hint">Szacowany czas potrzebny</span>
                            </label>
                            <input type="text" id="guide_duration" name="guide_duration" 
                                   value="<?= esc_attr($duration) ?>" 
                                   placeholder="np. 30 min, 2 godziny, cały dzień" />
                        </div>

                        <div class="guides-field">
                            <label for="guide_season">
                                <strong>Najlepszy sezon</strong>
                                <span class="field-hint">Kiedy wykonywać poradnik</span>
                            </label>
                            <select id="guide_season" name="guide_season" class="season-select">
                                <option value="">Wybierz sezon</option>
                                <option value="Cały rok" <?= selected($season, 'Cały rok', false) ?>>🔄 Cały rok</option>
                                <option value="Wiosna" <?= selected($season, 'Wiosna', false) ?>>🌱 Wiosna</option>
                                <option value="Lato" <?= selected($season, 'Lato', false) ?>>☀️ Lato</option>
                                <option value="Jesień" <?= selected($season, 'Jesień', false) ?>>🍂 Jesień</option>
                                <option value="Zima" <?= selected($season, 'Zima', false) ?>>❄️ Zima</option>
                                <option value="Wiosna-Lato" <?= selected($season, 'Wiosna-Lato', false) ?>>🌱☀️ Wiosna-Lato</option>
                                <option value="Jesień-Zima" <?= selected($season, 'Jesień-Zima', false) ?>>🍂❄️ Jesień-Zima</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Zakładka: Zasoby i materiały -->
            <div id="guides-resources" class="guides-tab-content">
                <div class="resources-section">
                    <h4>🔧 Potrzebne zasoby</h4>
                    <div class="resources-grid">
                        <div class="guides-field full-width">
                            <label for="guide_tools">
                                <strong>🛠️ Narzędzia</strong>
                                <span class="field-hint">Lista narzędzi potrzebnych do wykonania</span>
                            </label>
                            <textarea id="guide_tools" name="guide_tools" 
                                      rows="4" placeholder="np. nożyczki, doniczka, spray do nawadniania"><?= esc_textarea($tools) ?></textarea>
                        </div>

                        <div class="guides-field full-width">
                            <label for="guide_materials">
                                <strong>📦 Materiały</strong>
                                <span class="field-hint">Lista materiałów i składników</span>
                            </label>
                            <textarea id="guide_materials" name="guide_materials" 
                                      rows="4" placeholder="np. torf, perlit, woda destylowana, nasiona"><?= esc_textarea($materials) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* ===== NAWIGACJA ZAKŁADEK ===== */
    .carni24-guides-metabox {
        overflow: hidden;
    }

    .carni24-guides-tabs {
        list-style: none;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        display: flex;
    }

    .carni24-guides-tabs li {
        margin: 0;
    }

    .carni24-guides-tabs .tab-link {
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        color: #0d47a1;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .carni24-guides-tabs .tab-link:hover {
        color: #1565c0;
        background: rgba(255,255,255,0.3);
    }

    .carni24-guides-tabs .tab-link.active {
        color: #0d47a1;
        border-bottom-color: #1976d2;
        background: #fff;
    }

    /* Zawartość zakładek */
    .carni24-guides-content {
        padding: 20px;
    }

    .guides-tab-content {
        display: none;
    }

    .guides-tab-content.active {
        display: block;
    }

    /* Karty informacyjne */
    .guides-info-card,
    .resources-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .guides-info-card h4,
    .resources-section h4 {
        margin: 0 0 16px;
        color: #0d47a1;
        font-size: 16px;
        font-weight: 600;
        padding-bottom: 8px;
        border-bottom: 2px solid #bbdefb;
    }

    /* Grid układy */
    .guides-field-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
    }

    .resources-grid {
        display: grid;
        gap: 20px;
    }

    .guides-field.full-width {
        grid-column: 1 / -1;
    }

    /* Pola formularza */
    .guides-field {
        display: flex;
        flex-direction: column;
    }

    .guides-field label {
        margin-bottom: 6px;
        font-weight: 600;
        color: #1d2327;
        font-size: 13px;
    }

    .field-hint {
        font-weight: 400;
        color: #646970;
        font-size: 12px;
        margin-left: 4px;
    }

    .guides-field input,
    .guides-field select,
    .guides-field textarea {
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 13px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .guides-field input:focus,
    .guides-field select:focus,
    .guides-field textarea:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: none;
    }

    .guides-field textarea {
        resize: vertical;
        min-height: 80px;
        font-family: inherit;
    }

    .difficulty-select option[value="Początkujący"] {
        color: #155724;
    }

    .difficulty-select option[value="Średniozaawansowany"] {
        color: #856404;
    }

    .difficulty-select option[value="Zaawansowany"] {
        color: #721c24;
    }

    /* Responsywność */
    @media (max-width: 768px) {
        .guides-field-grid {
            grid-template-columns: 1fr;
        }
        
        .carni24-guides-tabs {
            flex-direction: column;
        }
        
        .carni24-guides-tabs .tab-link {
            text-align: center;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obsługa zakładek
        const tabLinks = document.querySelectorAll('.carni24-guides-tabs .tab-link');
        const tabContents = document.querySelectorAll('.guides-tab-content');
        
        tabLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetTab = this.getAttribute('data-tab');
                
                // Usuń aktywne klasy
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Dodaj aktywną klasę do klikniętej zakładki
                this.classList.add('active');
                
                // Pokaż odpowiednią zawartość
                const targetContent = document.getElementById(targetTab);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Bibliografia i źródła - meta box
 */
function carni24_guides_improved_bibliography_callback($post) {
    wp_nonce_field('carni24_guides_improved_bibliography_meta', 'carni24_guides_improved_bibliography_nonce');
    
    $bibliography = get_post_meta($post->ID, '_guide_bibliography', true);
    ?>
    
    <div class="guides-bibliography-wrapper">
        <div class="bibliography-info">
            <p class="description">
                <strong>📚 Dodaj źródła i referencje użyte w poradniku.</strong><br>
                Lista zostanie wyświetlona na dole artykułu. Używaj formatowania HTML.
            </p>
        </div>
        
        <div class="bibliography-field">
            <label for="guide_bibliography">
                <strong>Bibliografia</strong>
                <span class="field-hint">Książki, artykuły, strony internetowe</span>
            </label>
            
            <?php
            wp_editor($bibliography, 'guide_bibliography', array(
                'textarea_name' => 'guide_bibliography',
                'textarea_rows' => 8,
                'media_buttons' => false,
                'teeny' => true,
                'quicktags' => array('buttons' => 'strong,em,link,ul,ol,li')
            ));
            ?>
        </div>
    </div>

    <style>
    .guides-bibliography-wrapper {
        padding: 15px 0;
    }
    
    .bibliography-info {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 15px;
    }
    
    .bibliography-info .description {
        margin: 0;
        color: #856404;
        font-size: 13px;
        line-height: 1.5;
    }
    
    .bibliography-field label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1d2327;
        font-size: 14px;
    }
    
    .field-hint {
        font-weight: 400;
        color: #646970;
        font-size: 12px;
        margin-left: 4px;
    }
    </style>
    <?php
}

/**
 * Statystyki guides - meta box w sidebarze
 */
function carni24_guides_improved_stats_callback($post) {
    $views = function_exists('carni24_get_post_views') ? carni24_get_post_views($post->ID) : 0;
    $created = get_the_date('Y-m-d H:i:s', $post);
    $modified = get_the_modified_date('Y-m-d H:i:s', $post);
    
    ?>
    <div class="guides-stats-container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-icon">👁️</span>
                <div class="stat-content">
                    <span class="stat-number"><?= number_format($views) ?></span>
                    <span class="stat-label">Wyświetleń</span>
                </div>
            </div>
            
            <div class="stat-item">
                <span class="stat-icon">📅</span>
                <div class="stat-content">
                    <span class="stat-number"><?= get_the_date('j M') ?></span>
                    <span class="stat-label">Utworzono</span>
                </div>
            </div>
            
            <div class="stat-item">
                <span class="stat-icon">✏️</span>
                <div class="stat-content">
                    <span class="stat-number"><?= get_the_modified_date('j M') ?></span>
                    <span class="stat-label">Edytowano</span>
                </div>
            </div>
        </div>

        <div class="stats-actions">
            <a href="<?= get_permalink($post->ID) ?>" target="_blank" class="button button-secondary">
                👁️ Podgląd
            </a>
            <button type="button" class="button button-secondary" onclick="location.reload()">
                🔄 Odśwież
            </button>
        </div>
    </div>

    <style>
    .guides-stats-container {
        padding: 10px 0;
    }
    
    .stats-grid {
        display: grid;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 6px;
        border-left: 4px solid #1976d2;
    }
    
    .stat-icon {
        font-size: 18px;
    }
    
    .stat-content {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .stat-number {
        font-weight: 600;
        font-size: 16px;
        color: #1d2327;
    }
    
    .stat-label {
        font-size: 12px;
        color: #646970;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stats-actions {
        display: flex;
        gap: 8px;
    }

    .stats-actions .button {
        flex: 1;
        text-align: center;
        font-size: 12px;
    }
    </style>
    <?php
}

/**
 * Zapisywanie meta danych guides - uproszczona wersja
 */
function carni24_save_guides_improved_meta($post_id) {
    // Sprawdzenia bezpieczeństwa
    if ((!isset($_POST['carni24_guides_improved_details_nonce']) || 
         !wp_verify_nonce($_POST['carni24_guides_improved_details_nonce'], 'carni24_guides_improved_details_meta')) &&
        (!isset($_POST['carni24_guides_improved_bibliography_nonce']) || 
         !wp_verify_nonce($_POST['carni24_guides_improved_bibliography_nonce'], 'carni24_guides_improved_bibliography_meta'))) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Lista pól do zapisania - TYLKO te wyświetlane na froncie
    $guide_fields = array(
        'guide_difficulty',
        'guide_duration', 
        'guide_season',
        'guide_tools',
        'guide_materials'
    );
    
    // Zapisz pola
    foreach ($guide_fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            
            // Sanityzacja w zależności od typu pola
            if (in_array($field, ['guide_tools', 'guide_materials'])) {
                $value = sanitize_textarea_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
            
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
    
    // Zapisz bibliografię
    if (isset($_POST['guide_bibliography'])) {
        update_post_meta($post_id, '_guide_bibliography', wp_kses_post($_POST['guide_bibliography']));
    }
}
add_action('save_post', 'carni24_save_guides_improved_meta');