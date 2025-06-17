<?php
/**
 * Meta boxes dla poradników (guides) - Ulepszona wersja
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
 * Główny meta box dla guides - ulepszona wersja
 */
function carni24_guides_improved_details_callback($post) {
    wp_nonce_field('carni24_guides_improved_details_meta', 'carni24_guides_improved_details_nonce');
    
    // Pobierz dane
    $difficulty = get_post_meta($post->ID, '_guide_difficulty', true);
    $duration = get_post_meta($post->ID, '_guide_duration', true);
    $season = get_post_meta($post->ID, '_guide_season', true);
    $tools = get_post_meta($post->ID, '_guide_tools', true);
    $materials = get_post_meta($post->ID, '_guide_materials', true);
    $category = get_post_meta($post->ID, '_guide_category', true);
    $target_audience = get_post_meta($post->ID, '_guide_target_audience', true);
    $prerequisites = get_post_meta($post->ID, '_guide_prerequisites', true);
    $expected_results = get_post_meta($post->ID, '_guide_expected_results', true);
    $tips = get_post_meta($post->ID, '_guide_tips', true);
    $warnings = get_post_meta($post->ID, '_guide_warnings', true);
    ?>
    
    <div class="carni24-guides-metabox">
        <!-- Nawigacja zakładek -->
        <ul class="carni24-guides-tabs">
            <li><a href="#guides-basic" class="tab-link active" data-tab="guides-basic">📋 Podstawowe</a></li>
            <li><a href="#guides-execution" class="tab-link" data-tab="guides-execution">🔧 Wykonanie</a></li>
            <li><a href="#guides-additional" class="tab-link" data-tab="guides-additional">💡 Dodatkowe</a></li>
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
                                <span class="field-hint">Dla kogo przeznaczony jest poradnik</span>
                            </label>
                            <select id="guide_difficulty" name="guide_difficulty" class="difficulty-select">
                                <option value="">Wybierz poziom</option>
                                <option value="Początkujący" <?= selected($difficulty, 'Początkujący', false) ?>>
                                    🟢 Początkujący - pierwsze kroki
                                </option>
                                <option value="Średniozaawansowany" <?= selected($difficulty, 'Średniozaawansowany', false) ?>>
                                    🟡 Średniozaawansowany - pewne doświadczenie
                                </option>
                                <option value="Zaawansowany" <?= selected($difficulty, 'Zaawansowany', false) ?>>
                                    🔴 Zaawansowany - dla ekspertów
                                </option>
                            </select>
                        </div>

                        <div class="guides-field">
                            <label for="guide_duration">
                                <strong>Czas wykonania</strong>
                                <span class="field-hint">Szacowany czas potrzebny na wykonanie</span>
                            </label>
                            <input type="text" id="guide_duration" name="guide_duration" 
                                   value="<?= esc_attr($duration) ?>" 
                                   placeholder="np. 30 minut, 2 godziny, cały dzień" />
                        </div>

                        <div class="guides-field">
                            <label for="guide_season">
                                <strong>Najlepszy sezon</strong>
                                <span class="field-hint">Kiedy najlepiej wykonać czynności</span>
                            </label>
                            <select id="guide_season" name="guide_season" class="season-select">
                                <option value="">Wybierz sezon</option>
                                <option value="Wiosna" <?= selected($season, 'Wiosna', false) ?>>🌱 Wiosna</option>
                                <option value="Lato" <?= selected($season, 'Lato', false) ?>>☀️ Lato</option>
                                <option value="Jesień" <?= selected($season, 'Jesień', false) ?>>🍂 Jesień</option>
                                <option value="Zima" <?= selected($season, 'Zima', false) ?>>❄️ Zima</option>
                                <option value="Cały rok" <?= selected($season, 'Cały rok', false) ?>>🔄 Cały rok</option>
                                <option value="Wiosna-Lato" <?= selected($season, 'Wiosna-Lato', false) ?>>🌱☀️ Wiosna-Lato</option>
                                <option value="Jesień-Zima" <?= selected($season, 'Jesień-Zima', false) ?>>🍂❄️ Jesień-Zima</option>
                            </select>
                        </div>

                        <div class="guides-field">
                            <label for="guide_category">
                                <strong>Kategoria poradnika</strong>
                                <span class="field-hint">Główny temat poradnika</span>
                            </label>
                            <select id="guide_category" name="guide_category" class="category-select">
                                <option value="">Wybierz kategorię</option>
                                <option value="Pielęgnacja" <?= selected($category, 'Pielęgnacja', false) ?>>🌿 Pielęgnacja</option>
                                <option value="Rozmnażanie" <?= selected($category, 'Rozmnażanie', false) ?>>🌱 Rozmnażanie</option>
                                <option value="Choroby i szkodniki" <?= selected($category, 'Choroby i szkodniki', false) ?>>🦠 Choroby i szkodniki</option>
                                <option value="Podłoże i nawożenie" <?= selected($category, 'Podłoże i nawożenie', false) ?>>🌱 Podłoże i nawożenie</option>
                                <option value="Spoczynek zimowy" <?= selected($category, 'Spoczynek zimowy', false) ?>>❄️ Spoczynek zimowy</option>
                                <option value="Terrarium" <?= selected($category, 'Terrarium', false) ?>>🏠 Terrarium</option>
                                <option value="Karmienie" <?= selected($category, 'Karmienie', false) ?>>🍽️ Karmienie</option>
                                <option value="DIY" <?= selected($category, 'DIY', false) ?>>🔧 DIY</option>
                            </select>
                        </div>

                        <div class="guides-field">
                            <label for="guide_target_audience">
                                <strong>Grupa docelowa</strong>
                                <span class="field-hint">Dla kogo szczególnie przydatny</span>
                            </label>
                            <input type="text" id="guide_target_audience" name="guide_target_audience" 
                                   value="<?= esc_attr($target_audience) ?>" 
                                   placeholder="np. początkujący hodowcy, właściciele terrariów" />
                        </div>

                        <div class="guides-field full-width">
                            <label for="guide_prerequisites">
                                <strong>Wymagania wstępne</strong>
                                <span class="field-hint">Co czytelnik powinien wiedzieć/mieć przed rozpoczęciem</span>
                            </label>
                            <textarea id="guide_prerequisites" name="guide_prerequisites" 
                                      rows="3" placeholder="np. podstawowa wiedza o roślinach mięsożernych, doświadczenie w przesadzaniu..."><?= esc_textarea($prerequisites) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zakładka: Wykonanie -->
            <div id="guides-execution" class="guides-tab-content">
                <div class="execution-grid">
                    
                    <!-- Narzędzia -->
                    <div class="execution-section">
                        <h4>🔧 Potrzebne narzędzia</h4>
                        <div class="tools-field">
                            <textarea id="guide_tools" name="guide_tools" 
                                      rows="4" 
                                      placeholder="Wymień wszystkie narzędzia potrzebne do wykonania poradnika:&#10;• Nożyczki ogrodnicze&#10;• Doniczki 6cm&#10;• Pęseta&#10;• Atomizer"><?= esc_textarea($tools) ?></textarea>
                        </div>
                    </div>

                    <!-- Materiały -->
                    <div class="execution-section">
                        <h4>📦 Materiały i składniki</h4>
                        <div class="materials-field">
                            <textarea id="guide_materials" name="guide_materials" 
                                      rows="4" 
                                      placeholder="Wymień wszystkie materiały potrzebne do wykonania:&#10;• Torf kwaśny 2L&#10;• Perlit 1L&#10;• Woda destylowana&#10;• Roślina macierzysta"><?= esc_textarea($materials) ?></textarea>
                        </div>
                    </div>

                    <!-- Oczekiwane rezultaty -->
                    <div class="execution-section full-width">
                        <h4>🎯 Oczekiwane rezultaty</h4>
                        <div class="results-field">
                            <textarea id="guide_expected_results" name="guide_expected_results" 
                                      rows="3" 
                                      placeholder="Opisz co czytelnik osiągnie po wykonaniu poradnika..."><?= esc_textarea($expected_results) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zakładka: Dodatkowe -->
            <div id="guides-additional" class="guides-tab-content">
                <div class="additional-grid">
                    
                    <!-- Wskazówki -->
                    <div class="additional-section">
                        <h4>💡 Wskazówki i triki</h4>
                        <div class="tips-field">
                            <textarea id="guide_tips" name="guide_tips" 
                                      rows="5" 
                                      placeholder="Podziel się dodatkowymi wskazówkami, trikami i radami które ułatwią wykonanie..."><?= esc_textarea($tips) ?></textarea>
                        </div>
                    </div>

                    <!-- Ostrzeżenia -->
                    <div class="additional-section">
                        <h4>⚠️ Ostrzeżenia i częste błędy</h4>
                        <div class="warnings-field">
                            <textarea id="guide_warnings" name="guide_warnings" 
                                      rows="5" 
                                      placeholder="Opisz czego należy unikać, najczęstsze błędy i potencjalne problemy..."><?= esc_textarea($warnings) ?></textarea>
                        </div>
                    </div>

                    <!-- Przyszłe funkcje -->
                    <div class="future-features">
                        <h4>🔮 Planowane rozszerzenia</h4>
                        <p class="description">
                            W przyszłych wersjach tutaj znajdą się:
                            • Galeria krok po kroku<br>
                            • Filmiki instruktażowe<br>
                            • Lista powiązanych poradników<br>
                            • System ocen i komentarzy<br>
                            • Kalkulator kosztów materiałów
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
    .carni24-guides-metabox {
        margin: -6px -12px -12px;
        background: #fff;
    }

    /* Zakładki */
    .carni24-guides-tabs {
        margin: 0;
        padding: 0;
        list-style: none;
        border-bottom: 1px solid #e1e1e1;
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
    .execution-section,
    .additional-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .guides-info-card h4,
    .execution-section h4,
    .additional-section h4 {
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

    .execution-grid,
    .additional-grid {
        display: grid;
        gap: 20px;
    }

    .execution-section.full-width,
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
    .guides-field textarea,
    .tools-field textarea,
    .materials-field textarea,
    .results-field textarea,
    .tips-field textarea,
    .warnings-field textarea {
        padding: 8px 12px;
        border: 1px solid #8c8f94;
        border-radius: 4px;
        font-size: 13px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .guides-field input:focus,
    .guides-field select:focus,
    .guides-field textarea:focus,
    .tools-field textarea:focus,
    .materials-field textarea:focus,
    .results-field textarea:focus,
    .tips-field textarea:focus,
    .warnings-field textarea:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 1px #1976d2;
        outline: none;
    }

    /* Specjalne style dla selectów */
    .difficulty-select option[value="Początkujący"] {
        color: #28a745;
    }

    .difficulty-select option[value="Średniozaawansowany"] {
        color: #ffc107;
    }

    .difficulty-select option[value="Zaawansowany"] {
        color: #dc3545;
    }

    /* Textarea specjalne */
    .tools-field textarea,
    .materials-field textarea {
        background: #f0f8ff;
        border-color: #90caf9;
    }

    .tips-field textarea {
        background: #f1f8e9;
        border-color: #c8e6c9;
    }

    .warnings-field textarea {
        background: #fff3e0;
        border-color: #ffcc80;
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
        .carni24-guides-tabs {
            flex-direction: column;
        }
        
        .carni24-guides-content {
            padding: 15px;
        }
        
        .guides-field-grid {
            grid-template-columns: 1fr;
        }
        
        .guides-info-card,
        .execution-section,
        .additional-section {
            padding: 15px;
        }
    }

    @media (max-width: 600px) {
        .carni24-guides-tabs .tab-link {
            padding: 10px 15px;
            font-size: 13px;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obsługa zakładek
        const tabLinks = document.querySelectorAll('.carni24-guides-tabs .tab-link');
        const tabContents = document.querySelectorAll('.guides-tab-content');

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

        // Formatowanie czasu wykonania
        const durationInput = document.getElementById('guide_duration');
        if (durationInput) {
            durationInput.addEventListener('blur', function() {
                let value = this.value.trim();
                
                // Dodaj sugestie jednostek jeśli nie ma
                if (value && !value.match(/(minut|godzin|dni|dzień|godz|min)/i)) {
                    if (value.match(/^\d+$/)) {
                        const num = parseInt(value);
                        if (num < 60) {
                            this.value = value + ' minut';
                        } else {
                            this.value = Math.round(num / 60) + ' godzin';
                        }
                    }
                }
            });
        }

        // Auto-rozszerzanie textarea przy wpisywaniu
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });

        // Licznik znaków dla długich pól
        const longTextareas = ['guide_tips', 'guide_warnings', 'guide_prerequisites'];
        longTextareas.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                const counter = document.createElement('div');
                counter.className = 'char-counter';
                counter.style.fontSize = '11px';
                counter.style.color = '#666';
                counter.style.textAlign = 'right';
                counter.style.marginTop = '5px';
                
                function updateCounter() {
                    const length = field.value.length;
                    counter.textContent = length + ' znaków';
                    
                    if (length > 500) {
                        counter.style.color = '#d63638';
                    } else if (length > 300) {
                        counter.style.color = '#dba617';
                    } else {
                        counter.style.color = '#666';
                    }
                }
                
                field.addEventListener('input', updateCounter);
                field.parentNode.appendChild(counter);
                updateCounter(); // Pierwsza aktualizacja
            }
        });
    });
    </script>
    <?php
}

/**
 * Meta box bibliografii - ulepszona wersja
 */
function carni24_guides_improved_bibliography_callback($post) {
    wp_nonce_field('carni24_guides_improved_bibliography_meta', 'carni24_guides_improved_bibliography_nonce');
    $bibliography = get_post_meta($post->ID, '_guide_bibliography', true);
    
    echo '<div class="bibliography-section">';
    echo '<p class="description">📚 Dodaj źródła, publikacje i referencje wykorzystane przy tworzeniu poradnika.</p>';
    wp_editor($bibliography, 'guide_bibliography', array(
        'textarea_name' => 'guide_bibliography',
        'media_buttons' => false,
        'textarea_rows' => 6,
        'teeny' => true,
        'quicktags' => array('buttons' => 'strong,em,link,ul,ol,li')
    ));
    echo '</div>';
}

/**
 * Meta box statystyk - ulepszona wersja
 */
function carni24_guides_improved_stats_callback($post) {
    $views = function_exists('carni24_get_post_views') ? carni24_get_post_views($post->ID) : 0;
    $difficulty = get_post_meta($post->ID, '_guide_difficulty', true);
    $category = get_post_meta($post->ID, '_guide_category', true);
    $duration = get_post_meta($post->ID, '_guide_duration', true);
    $featured = get_post_meta($post->ID, '_is_featured', true);
    $reading_time = function_exists('carni24_get_reading_time') ? carni24_get_reading_time($post->ID) : 0;
    ?>
    
    <div class="guides-stats-widget">
        <div class="stat-item">
            <div class="stat-icon">👁️</div>
            <div class="stat-info">
                <div class="stat-label">Wyświetlenia</div>
                <div class="stat-value"><?= number_format($views) ?></div>
            </div>
        </div>

        <?php if ($reading_time): ?>
        <div class="stat-item">
            <div class="stat-icon">📖</div>
            <div class="stat-info">
                <div class="stat-label">Czas czytania</div>
                <div class="stat-value"><?= $reading_time ?> min</div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($difficulty): ?>
        <div class="stat-item">
            <div class="stat-icon">
                <?php 
                echo $difficulty === 'Początkujący' ? '🟢' : 
                    ($difficulty === 'Średniozaawansowany' ? '🟡' : '🔴'); 
                ?>
            </div>
            <div class="stat-info">
                <div class="stat-label">Trudność</div>
                <div class="stat-value"><?= esc_html($difficulty) ?></div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($category): ?>
        <div class="stat-item">
            <div class="stat-icon">🏷️</div>
            <div class="stat-info">
                <div class="stat-label">Kategoria</div>
                <div class="stat-value"><?= esc_html($category) ?></div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($duration): ?>
        <div class="stat-item">
            <div class="stat-icon">⏱️</div>
            <div class="stat-info">
                <div class="stat-label">Czas wykonania</div>
                <div class="stat-value"><?= esc_html($duration) ?></div>
            </div>
        </div>
        <?php endif; ?>

        <div class="stat-item">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <div class="stat-label">Opublikowano</div>
                <div class="stat-value"><?= get_the_date('d.m.Y', $post->ID) ?></div>
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
    .guides-stats-widget {
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
        background: linear-gradient(90deg, #e3f2fd, transparent);
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
 * Zapisywanie meta danych guides - ulepszona wersja
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
    
    // Lista pól do zapisania
    $guide_fields = array(
        'guide_difficulty',
        'guide_duration', 
        'guide_season',
        'guide_category',
        'guide_target_audience',
        'guide_prerequisites',
        'guide_tools',
        'guide_materials',
        'guide_expected_results',
        'guide_tips',
        'guide_warnings'
    );
    
    // Zapisz pola
    foreach ($guide_fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            
            // Sanityzacja w zależności od typu pola
            if (in_array($field, ['guide_tools', 'guide_materials', 'guide_prerequisites', 'guide_expected_results', 'guide_tips', 'guide_warnings'])) {
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