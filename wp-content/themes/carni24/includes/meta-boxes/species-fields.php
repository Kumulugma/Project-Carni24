<?php
/**
 * Meta boxes dla gatunków (species) - Uproszczona wersja
 * Usunięto niewyświetlane pola: humidity, soil, feeding, dormancy, propagation, notes
 * 
 * @package Carni24
 * @subpackage MetaBoxes
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Zastępuje istniejące meta boxy dla species ulepszonym interfejsem
 */
function carni24_override_species_meta_boxes() {
    // Usuń istniejące meta boxy species jeśli istnieją
    remove_meta_box('species_details', 'species', 'normal');
    remove_meta_box('species_bibliography', 'species', 'normal');
    
    // Dodaj nowe ulepszone meta boxy
    add_meta_box(
        'species_details_improved',
        '🌱 Szczegóły gatunku',
        'carni24_species_improved_details_callback',
        'species',
        'normal',
        'high'
    );

    add_meta_box(
        'species_stats_improved',
        '📊 Statystyki gatunku',
        'carni24_species_improved_stats_callback',
        'species',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_override_species_meta_boxes', 15);

/**
 * Główny meta box dla species - uproszczona wersja
 */
function carni24_species_improved_details_callback($post) {
    wp_nonce_field('carni24_species_improved_meta_nonce', 'carni24_species_improved_meta_nonce');
    
    // Pobierz dane - tylko te które są wyświetlane na froncie
    $scientific_name = get_post_meta($post->ID, '_species_scientific_name', true);
    $origin = get_post_meta($post->ID, '_species_origin', true);
    $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
    $family = get_post_meta($post->ID, '_species_family', true);
    $size = get_post_meta($post->ID, '_species_size', true);
    $habitat = get_post_meta($post->ID, '_species_habitat', true);
    $light = get_post_meta($post->ID, '_species_light', true);
    $watering = get_post_meta($post->ID, '_species_watering', true);
    $temperature = get_post_meta($post->ID, '_species_temperature', true);
    ?>
    
    <div class="carni24-species-metabox">
        <!-- Nawigacja zakładek -->
        <ul class="carni24-species-tabs">
            <li><a href="#species-basic" class="tab-link active" data-tab="species-basic">📋 Podstawowe</a></li>
            <li><a href="#species-care" class="tab-link" data-tab="species-care">🌿 Pielęgnacja</a></li>
        </ul>

        <!-- Zawartość zakładek -->
        <div class="carni24-species-content">
            
            <!-- Zakładka: Podstawowe informacje -->
            <div id="species-basic" class="species-tab-content active">
                <div class="species-info-card">
                    <h4>📝 Identyfikacja gatunku</h4>
                    <div class="species-field-grid">
                        <div class="species-field">
                            <label for="species_scientific_name">
                                <strong>Nazwa naukowa</strong>
                                <span class="field-hint">Łacińska nazwa binomialna</span>
                            </label>
                            <input type="text" id="species_scientific_name" name="species_scientific_name" 
                                   value="<?= esc_attr($scientific_name) ?>" 
                                   placeholder="np. Dionaea muscipula" />
                        </div>

                        <div class="species-field">
                            <label for="species_origin">
                                <strong>Pochodzenie geograficzne</strong>
                                <span class="field-hint">Naturalne środowisko</span>
                            </label>
                            <input type="text" id="species_origin" name="species_origin" 
                                   value="<?= esc_attr($origin) ?>" 
                                   placeholder="np. Południowo-wschodnie USA" />
                        </div>

                        <div class="species-field">
                            <label for="species_difficulty">
                                <strong>Poziom trudności hodowli</strong>
                                <span class="field-hint">Ocena dla początkujących</span>
                            </label>
                            <select id="species_difficulty" name="species_difficulty" class="difficulty-select">
                                <option value="">Wybierz poziom</option>
                                <option value="Łatwa" <?= selected($difficulty, 'Łatwa', false) ?>>🟢 Łatwa</option>
                                <option value="Średnia" <?= selected($difficulty, 'Średnia', false) ?>>🟡 Średnia</option>
                                <option value="Trudna" <?= selected($difficulty, 'Trudna', false) ?>>🔴 Trudna</option>
                            </select>
                        </div>

                        <div class="species-field">
                            <label for="species_family">
                                <strong>Rodzina botaniczna</strong>
                                <span class="field-hint">Klasyfikacja taksonomiczna</span>
                            </label>
                            <input type="text" id="species_family" name="species_family" 
                                   value="<?= esc_attr($family) ?>" 
                                   placeholder="np. Droseraceae" />
                        </div>

                        <div class="species-field">
                            <label for="species_size">
                                <strong>Rozmiar dorosłej rośliny</strong>
                                <span class="field-hint">Typowy rozmiar w cm</span>
                            </label>
                            <input type="text" id="species_size" name="species_size" 
                                   value="<?= esc_attr($size) ?>" 
                                   placeholder="np. 8-15 cm średnicy" />
                        </div>

                        <div class="species-field">
                            <label for="species_habitat">
                                <strong>Typ habitatu</strong>
                                <span class="field-hint">Naturalne środowisko</span>
                            </label>
                            <input type="text" id="species_habitat" name="species_habitat" 
                                   value="<?= esc_attr($habitat) ?>" 
                                   placeholder="np. bagno, torfowisko, wrzosowisko" />
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Zakładka: Wymagania pielęgnacyjne -->
            <div id="species-care" class="species-tab-content">
                <div class="care-section">
                    <h4>🌿 Warunki uprawy</h4>
                    <div class="care-requirements">
                        <div class="requirement-item">
                            <label for="species_light">
                                <strong>☀️ Wymagania świetlne</strong>
                                <span class="field-hint">Intensywność i rodzaj światła</span>
                            </label>
                            <input type="text" id="species_light" name="species_light" 
                                   value="<?= esc_attr($light) ?>" 
                                   placeholder="np. pełne słońce, 6000-8000 lux" />
                        </div>

                        <div class="requirement-item">
                            <label for="species_watering">
                                <strong>💧 Podlewanie</strong>
                                <span class="field-hint">Sposób nawadniania</span>
                            </label>
                            <input type="text" id="species_watering" name="species_watering" 
                                   value="<?= esc_attr($watering) ?>" 
                                   placeholder="np. stale wilgotne, podlewanie z dołu" />
                        </div>

                        <div class="requirement-item">
                            <label for="species_temperature">
                                <strong>🌡️ Temperatura</strong>
                                <span class="field-hint">Zakres temperatur w °C</span>
                            </label>
                            <input type="text" id="species_temperature" name="species_temperature" 
                                   value="<?= esc_attr($temperature) ?>" 
                                   placeholder="np. 20-30°C dzień, 15-20°C noc" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* ===== NAWIGACJA ZAKŁADEK ===== */
    .carni24-species-metabox {
        overflow: hidden;
    }

    .carni24-species-tabs {
        list-style: none;
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #e8f5e8, #d4edda);
        display: flex;
    }

    .carni24-species-tabs li {
        margin: 0;
    }

    .carni24-species-tabs .tab-link {
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        color: #155724;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .carni24-species-tabs .tab-link:hover {
        color: #0f4419;
        background: rgba(255,255,255,0.3);
    }

    .carni24-species-tabs .tab-link.active {
        color: #155724;
        border-bottom-color: #28a745;
        background: #fff;
    }

    /* Zawartość zakładek */
    .carni24-species-content {
        padding: 20px;
    }

    .species-tab-content {
        display: none;
    }

    .species-tab-content.active {
        display: block;
    }

    /* Karty informacyjne */
    .species-info-card,
    .care-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .species-info-card h4,
    .care-section h4 {
        margin: 0 0 16px;
        color: #155724;
        font-size: 16px;
        font-weight: 600;
        padding-bottom: 8px;
        border-bottom: 2px solid #c3e6cb;
    }

    /* Grid układy */
    .species-field-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
    }

    .care-requirements {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    /* Pola formularza */
    .species-field, .requirement-item {
        display: flex;
        flex-direction: column;
    }

    .species-field label,
    .requirement-item label {
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

    .species-field input,
    .species-field select,
    .requirement-item input,
    .requirement-item select {
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 13px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .species-field input:focus,
    .species-field select:focus,
    .requirement-item input:focus,
    .requirement-item select:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: none;
    }

    .difficulty-select option[value="Łatwa"] {
        color: #155724;
    }

    .difficulty-select option[value="Średnia"] {
        color: #856404;
    }

    .difficulty-select option[value="Trudna"] {
        color: #721c24;
    }

    /* Responsywność */
    @media (max-width: 768px) {
        .species-field-grid,
        .care-requirements {
            grid-template-columns: 1fr;
        }
        
        .carni24-species-tabs {
            flex-direction: column;
        }
        
        .carni24-species-tabs .tab-link {
            text-align: center;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obsługa zakładek
        const tabLinks = document.querySelectorAll('.carni24-species-tabs .tab-link');
        const tabContents = document.querySelectorAll('.species-tab-content');
        
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
 * Statystyki species - meta box w sidebarze
 */
function carni24_species_improved_stats_callback($post) {
    $views = function_exists('carni24_get_post_views') ? carni24_get_post_views($post->ID) : 0;
    $created = get_the_date('Y-m-d H:i:s', $post);
    $modified = get_the_modified_date('Y-m-d H:i:s', $post);
    
    ?>
    <div class="species-stats-container">
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
    .species-stats-container {
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
        border-left: 4px solid #28a745;
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
 * Zapisywanie meta danych species - uproszczona wersja
 */
function carni24_save_species_improved_meta($post_id) {
    // Sprawdzenia bezpieczeństwa
    if (!isset($_POST['carni24_species_improved_meta_nonce']) || 
        !wp_verify_nonce($_POST['carni24_species_improved_meta_nonce'], 'carni24_species_improved_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Lista pól do zapisania - TYLKO te wyświetlane na froncie
    $species_fields = array(
        'species_scientific_name',
        'species_origin', 
        'species_difficulty',
        'species_family',
        'species_size',
        'species_habitat',
        'species_light',
        'species_watering',
        'species_temperature'
    );
    
    // Zapisz pola
    foreach ($species_fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post', 'carni24_save_species_improved_meta');