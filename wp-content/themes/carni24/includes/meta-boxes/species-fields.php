<?php
/**
 * Meta boxes dla gatunków (species) - Ulepszona wersja
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
    
    // Dodaj nowe ulepszone meta boxy
    add_meta_box(
        'species_details_improved',
        '🌱 Szczegóły gatunku',
        'carni24_species_improved_meta_box_callback',
        'species',
        'normal',
        'high'
    );
    
    add_meta_box(
        'species_stats_improved',
        '📊 Statystyki',
        'carni24_species_improved_stats_callback',
        'species',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_override_species_meta_boxes', 15);

/**
 * Główny meta box dla species - ulepszona wersja
 */
function carni24_species_improved_meta_box_callback($post) {
    wp_nonce_field('carni24_species_improved_meta_nonce', 'carni24_species_improved_meta_nonce');
    
    // Pobierz dane
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
    $special_notes = get_post_meta($post->ID, '_species_special_notes', true);
    ?>
    
    <div class="carni24-species-metabox">
        <!-- Nawigacja zakładek -->
        <ul class="carni24-species-tabs">
            <li><a href="#species-basic" class="tab-link active" data-tab="species-basic">📋 Podstawowe</a></li>
            <li><a href="#species-care" class="tab-link" data-tab="species-care">🌿 Pielęgnacja</a></li>
            <li><a href="#species-advanced" class="tab-link" data-tab="species-advanced">🔬 Zaawansowane</a></li>
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
                                   placeholder="np. Północna Karolina, USA" />
                        </div>

                        <div class="species-field">
                            <label for="species_difficulty">
                                <strong>Poziom trudności hodowli</strong>
                            </label>
                            <select id="species_difficulty" name="species_difficulty" class="difficulty-select">
                                <option value="">Wybierz poziom</option>
                                <option value="easy" <?= selected($difficulty, 'easy', false) ?>>
                                    🟢 Łatwy - dla początkujących
                                </option>
                                <option value="medium" <?= selected($difficulty, 'medium', false) ?>>
                                    🟡 Średni - wymaga doświadczenia
                                </option>
                                <option value="hard" <?= selected($difficulty, 'hard', false) ?>>
                                    🔴 Trudny - tylko dla ekspertów
                                </option>
                            </select>
                        </div>

                        <div class="species-field">
                            <label for="species_size">
                                <strong>Rozmiar dorosłej rośliny</strong>
                            </label>
                            <input type="text" id="species_size" name="species_size" 
                                   value="<?= esc_attr($size) ?>" 
                                   placeholder="np. 5-8 cm średnicy" />
                        </div>

                        <div class="species-field">
                            <label for="species_growth_rate">
                                <strong>Tempo wzrostu</strong>
                            </label>
                            <select id="species_growth_rate" name="species_growth_rate">
                                <option value="">Wybierz tempo</option>
                                <option value="slow" <?= selected($growth_rate, 'slow', false) ?>>🐌 Powolne</option>
                                <option value="medium" <?= selected($growth_rate, 'medium', false) ?>>🚶 Średnie</option>
                                <option value="fast" <?= selected($growth_rate, 'fast', false) ?>>🏃 Szybkie</option>
                            </select>
                        </div>

                        <div class="species-field">
                            <label for="species_flowering">
                                <strong>Kwitnienie</strong>
                            </label>
                            <input type="text" id="species_flowering" name="species_flowering" 
                                   value="<?= esc_attr($flowering) ?>" 
                                   placeholder="np. wiosna-lato, białe kwiaty" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Zakładka: Pielęgnacja -->
            <div id="species-care" class="species-tab-content">
                <div class="species-care-grid">
                    
                    <!-- Wymagania środowiskowe -->
                    <div class="care-section">
                        <h4>☀️ Wymagania środowiskowe</h4>
                        <div class="care-requirements">
                            <div class="requirement-item">
                                <label for="species_light">Światło</label>
                                <select id="species_light" name="species_light" class="requirement-select">
                                    <option value="">Wybierz wymagania</option>
                                    <option value="low" <?= selected($light, 'low', false) ?>>
                                        🌑 Niskie (1000-3000 lux)
                                    </option>
                                    <option value="medium" <?= selected($light, 'medium', false) ?>>
                                        🌗 Średnie (3000-6000 lux)
                                    </option>
                                    <option value="high" <?= selected($light, 'high', false) ?>>
                                        🌕 Wysokie (6000-12000 lux)
                                    </option>
                                    <option value="full_sun" <?= selected($light, 'full_sun', false) ?>>
                                        ☀️ Pełne słońce (12000+ lux)
                                    </option>
                                </select>
                            </div>

                            <div class="requirement-item">
                                <label for="species_water">Woda</label>
                                <select id="species_water" name="species_water" class="requirement-select">
                                    <option value="">Wybierz wymagania</option>
                                    <option value="low" <?= selected($water, 'low', false) ?>>
                                        💧 Niskie (suche podłoże)
                                    </option>
                                    <option value="medium" <?= selected($water, 'medium', false) ?>>
                                        💧💧 Średnie (wilgotne podłoże)
                                    </option>
                                    <option value="high" <?= selected($water, 'high', false) ?>>
                                        💧💧💧 Wysokie (mokre podłoże)
                                    </option>
                                    <option value="bog" <?= selected($water, 'bog', false) ?>>
                                        🌊 Bagienne (stojąca woda)
                                    </option>
                                </select>
                            </div>

                            <div class="requirement-item">
                                <label for="species_temperature">Temperatura</label>
                                <input type="text" id="species_temperature" name="species_temperature" 
                                       value="<?= esc_attr($temperature) ?>" 
                                       placeholder="np. 18-25°C" />
                            </div>

                            <div class="requirement-item">
                                <label for="species_humidity">Wilgotność</label>
                                <input type="text" id="species_humidity" name="species_humidity" 
                                       value="<?= esc_attr($humidity) ?>" 
                                       placeholder="np. 60-80%" />
                            </div>
                        </div>
                    </div>

                    <!-- Podłoże i odżywianie -->
                    <div class="care-section">
                        <h4>🌱 Podłoże i odżywianie</h4>
                        <div class="care-requirements">
                            <div class="requirement-item full-width">
                                <label for="species_soil">Skład podłoża</label>
                                <textarea id="species_soil" name="species_soil" rows="3" 
                                          placeholder="np. torf + perlit (1:1), pH 4.0-5.5"><?= esc_textarea($soil) ?></textarea>
                            </div>

                            <div class="requirement-item full-width">
                                <label for="species_fertilizer">Nawożenie</label>
                                <textarea id="species_fertilizer" name="species_fertilizer" rows="3" 
                                          placeholder="np. karmieniu owadami raz w miesiącu, nie stosować nawozów mineralnych"><?= esc_textarea($fertilizer) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Rozmnażanie i spoczynek -->
                    <div class="care-section">
                        <h4>🔄 Rozmnażanie i cykl życiowy</h4>
                        <div class="care-requirements">
                            <div class="requirement-item">
                                <label for="species_propagation">Metody rozmnażania</label>
                                <input type="text" id="species_propagation" name="species_propagation" 
                                       value="<?= esc_attr($propagation) ?>" 
                                       placeholder="np. nasiona, podział, sadzonki liściowe" />
                            </div>

                            <div class="requirement-item">
                                <label for="species_dormancy">Spoczynek zimowy</label>
                                <select id="species_dormancy" name="species_dormancy" class="requirement-select">
                                    <option value="">Wybierz typ</option>
                                    <option value="yes" <?= selected($dormancy, 'yes', false) ?>>
                                        ❄️ Tak (wymagany)
                                    </option>
                                    <option value="no" <?= selected($dormancy, 'no', false) ?>>
                                        🌿 Nie
                                    </option>
                                    <option value="partial" <?= selected($dormancy, 'partial', false) ?>>
                                        🍂 Częściowy
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zakładka: Zaawansowane -->
            <div id="species-advanced" class="species-tab-content">
                <div class="species-field-group">
                    <div class="species-field">
                        <label for="species_special_notes">
                            <strong>📝 Specjalne wskazówki i uwagi</strong>
                            <span class="field-hint">Ważne informacje, ostrzeżenia, wskazówki dla hodowców</span>
                        </label>
                        <textarea id="species_special_notes" name="species_special_notes" 
                                  rows="6" class="large-textarea"
                                  placeholder="Wprowadź dodatkowe informacje, które mogą być przydatne dla hodowców tego gatunku..."><?= esc_textarea($special_notes) ?></textarea>
                    </div>

                    <!-- Sekcja dla przyszłych rozszerzeń -->
                    <div class="future-features">
                        <h4>🔮 Planowane funkcje</h4>
                        <p class="description">
                            W przyszłych wersjach tutaj znajdą się:
                            • System tagów dla cech rośliny<br>
                            • Galeria zdjęć<br>
                            • Historia zmian<br>
                            • Powiązane gatunki
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .carni24-species-metabox {
        margin: -6px -12px -12px;
        background: #fff;
    }

    /* Zakładki */
    .carni24-species-tabs {
        margin: 0;
        padding: 0;
        list-style: none;
        border-bottom: 1px solid #e1e1e1;
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
        color: #0c3c17;
        background: rgba(255,255,255,0.3);
    }

    .carni24-species-tabs .tab-link.active {
        color: #0c3c17;
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

    .species-care-grid {
        display: grid;
        gap: 20px;
    }

    .care-requirements {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .requirement-item.full-width {
        grid-column: 1 / -1;
    }

    /* Pola formularza */
    .species-field {
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
    .species-field textarea,
    .requirement-item input,
    .requirement-item select,
    .requirement-item textarea {
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 13px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .species-field input:focus,
    .species-field select:focus,
    .species-field textarea:focus,
    .requirement-item input:focus,
    .requirement-item select:focus,
    .requirement-item textarea:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 1px #28a745;
        outline: none;
    }

    /* Specjalne style dla selectów */
    .difficulty-select option[value="easy"] {
        color: #28a745;
    }

    .difficulty-select option[value="medium"] {
        color: #ffc107;
    }

    .difficulty-select option[value="hard"] {
        color: #dc3545;
    }

    .requirement-select {
        background: #fff;
    }

    /* Duże textarea */
    .large-textarea {
        min-height: 120px;
        resize: vertical;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    /* Sekcja przyszłych funkcji */
    .future-features {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 6px;
        padding: 16px;
        margin-top: 20px;
    }

    .future-features h4 {
        margin: 0 0 10px;
        color: #856404;
        font-size: 14px;
    }

    .future-features .description {
        margin: 0;
        color: #856404;
        font-size: 13px;
        line-height: 1.5;
    }

    /* Responsywność */
    @media (max-width: 782px) {
        .carni24-species-tabs {
            flex-direction: column;
        }
        
        .carni24-species-content {
            padding: 15px;
        }
        
        .species-field-grid,
        .care-requirements {
            grid-template-columns: 1fr;
        }
        
        .species-info-card,
        .care-section {
            padding: 15px;
        }
    }

    @media (max-width: 600px) {
        .carni24-species-tabs .tab-link {
            padding: 10px 15px;
            font-size: 13px;
        }
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obsługa zakładek
        const tabLinks = document.querySelectorAll('.carni24-species-tabs .tab-link');
        const tabContents = document.querySelectorAll('.species-tab-content');

        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Usuń aktywne klasy
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Dodaj aktywną klasę
                this.classList.add('active');
                const targetTab = document.getElementById(this.getAttribute('data-tab'));
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            });
        });

        // Automatyczne formatowanie nazwy naukowej (kursywa w podglądzie)
        const scientificNameInput = document.getElementById('species_scientific_name');
        if (scientificNameInput) {
            scientificNameInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.style.fontStyle = 'italic';
                } else {
                    this.style.fontStyle = 'normal';
                }
            });
            
            // Sprawdź przy ładowaniu
            if (scientificNameInput.value.length > 0) {
                scientificNameInput.style.fontStyle = 'italic';
            }
        }

        // Walidacja temperatury i wilgotności
        const tempInput = document.getElementById('species_temperature');
        const humidityInput = document.getElementById('species_humidity');
        
        function validateNumericRange(input, expectedPattern) {
            input.addEventListener('blur', function() {
                const value = this.value.trim();
                if (value && !value.match(expectedPattern)) {
                    this.style.borderColor = '#dc3545';
                    this.title = 'Wprowadź zakres np. 18-25°C lub 60-80%';
                } else {
                    this.style.borderColor = '';
                    this.title = '';
                }
            });
        }

        if (tempInput) {
            validateNumericRange(tempInput, /^\d+(-\d+)?°?C?$/i);
        }
        
        if (humidityInput) {
            validateNumericRange(humidityInput, /^\d+(-\d+)?%?$/);
        }
    });
    </script>
    <?php
}

/**
 * Meta box ze statystykami - ulepszona wersja
 */
function carni24_species_improved_stats_callback($post) {
    $views = function_exists('carni24_get_post_views') ? carni24_get_post_views($post->ID) : 0;
    $date_added = get_the_date('Y-m-d H:i', $post->ID);
    $last_modified = get_the_modified_date('Y-m-d H:i', $post->ID);
    $featured = get_post_meta($post->ID, '_is_featured', true);
    $difficulty = get_post_meta($post->ID, '_species_difficulty', true);
    ?>
    
    <div class="species-stats-widget">
        <div class="stat-item">
            <div class="stat-icon">👁️</div>
            <div class="stat-info">
                <div class="stat-label">Wyświetlenia</div>
                <div class="stat-value"><?= number_format($views) ?></div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <div class="stat-label">Dodano</div>
                <div class="stat-value"><?= date('d.m.Y', strtotime($date_added)) ?></div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon">✏️</div>
            <div class="stat-info">
                <div class="stat-label">Ostatnia edycja</div>
                <div class="stat-value"><?= date('d.m.Y', strtotime($last_modified)) ?></div>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
                <div class="stat-label">Status</div>
                <div class="stat-value">
                    <?php if (get_post_status($post->ID) === 'publish'): ?>
                        <span class="status-published">✅ Opublikowany</span>
                    <?php else: ?>
                        <span class="status-draft">📝 Szkic</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($difficulty): ?>
        <div class="stat-item">
            <div class="stat-icon">
                <?php 
                echo $difficulty === 'easy' ? '🟢' : 
                    ($difficulty === 'medium' ? '🟡' : '🔴'); 
                ?>
            </div>
            <div class="stat-info">
                <div class="stat-label">Trudność</div>
                <div class="stat-value">
                    <?php 
                    echo $difficulty === 'easy' ? 'Łatwy' : 
                        ($difficulty === 'medium' ? 'Średni' : 'Trudny'); 
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($featured): ?>
        <div class="stat-item featured">
            <div class="stat-icon">⭐</div>
            <div class="stat-info">
                <div class="stat-label">Wyróżniony</div>
                <div class="stat-value">Tak</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="stats-actions">
            <a href="<?= get_edit_post_link($post->ID) ?>" class="button button-small">
                ✏️ Edytuj
            </a>
            <a href="<?= get_permalink($post->ID) ?>" class="button button-small" target="_blank">
                👁️ Zobacz
            </a>
        </div>
    </div>

    <style>
    .species-stats-widget {
        padding: 0;
    }

    .stat-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f1;
    }

    .stat-item:last-of-type {
        border-bottom: none;
    }

    .stat-item.featured {
        background: linear-gradient(90deg, #fff3cd, transparent);
        margin: 0 -12px;
        padding-left: 12px;
        padding-right: 12px;
        border-radius: 4px;
    }

    .stat-icon {
        font-size: 18px;
        margin-right: 12px;
        min-width: 24px;
        text-align: center;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 11px;
        color: #646970;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .stat-value {
        font-size: 13px;
        color: #1d2327;
        font-weight: 600;
    }

    .status-published {
        color: #00a32a;
    }

    .status-draft {
        color: #dba617;
    }

    .stats-actions {
        margin-top: 16px;
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
 * Dodaje meta box bibliografii dla species - ulepszona wersja  
 */
function carni24_add_species_improved_bibliography_meta_box() {
    add_meta_box(
        'species_bibliography_improved',
        '📚 Bibliografia i źródła',
        'carni24_species_improved_bibliography_callback',
        'species',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'carni24_add_species_improved_bibliography_meta_box', 15);

function carni24_species_improved_bibliography_callback($post) {
    wp_nonce_field('carni24_species_improved_bibliography_meta', 'carni24_species_improved_bibliography_nonce');
    $bibliography = get_post_meta($post->ID, '_species_bibliography', true);
    
    echo '<div class="bibliography-section">';
    echo '<p class="description">📚 Dodaj źródła, publikacje naukowe i referencje dotyczące tego gatunku.</p>';
    wp_editor($bibliography, 'species_bibliography', array(
        'textarea_name' => 'species_bibliography',
        'media_buttons' => false,
        'textarea_rows' => 6,
        'teeny' => true,
        'quicktags' => array('buttons' => 'strong,em,link,ul,ol,li')
    ));
    echo '</div>';
}

/**
 * Zapisywanie danych species - ulepszona wersja
 */
function carni24_save_species_improved_meta($post_id) {
    // Sprawdzenia bezpieczeństwa
    if (!isset($_POST['carni24_species_improved_meta_nonce']) || 
        !wp_verify_nonce($_POST['carni24_species_improved_meta_nonce'], 'carni24_species_improved_meta_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    // Lista pól do zapisania
    $species_fields = array(
        'species_scientific_name',
        'species_origin', 
        'species_difficulty',
        'species_light',
        'species_water',
        'species_temperature',
        'species_humidity',
        'species_soil',
        'species_fertilizer',
        'species_size',
        'species_growth_rate',
        'species_flowering',
        'species_propagation',
        'species_dormancy',
        'species_special_notes'
    );
    
    // Zapisz pola
    foreach ($species_fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            
            // Sanityzacja w zależności od typu pola
            if (in_array($field, ['species_soil', 'species_fertilizer', 'species_special_notes'])) {
                $value = sanitize_textarea_field($value);
            } else {
                $value = sanitize_text_field($value);
            }
            
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post', 'carni24_save_species_improved_meta');

/**
 * Zapisywanie bibliografii species - ulepszona wersja
 */
function carni24_save_species_improved_bibliography($post_id) {
    if (!isset($_POST['carni24_species_improved_bibliography_nonce']) || 
        !wp_verify_nonce($_POST['carni24_species_improved_bibliography_nonce'], 'carni24_species_improved_bibliography_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['species_bibliography'])) {
        update_post_meta($post_id, '_species_bibliography', wp_kses_post($_POST['species_bibliography']));
    }
}
add_action('save_post', 'carni24_save_species_improved_bibliography');