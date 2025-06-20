<?php
/**
 * Admin Assets - Zaktualizowane style dla nowych metaboxów
 * 
 * @package Carni24
 * @subpackage Admin
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ładuje CSS i JS dla panelu administracyjnego
 */
function carni24_admin_enqueue_assets($hook) {
    global $post_type;
    
    // Ładuj tylko na stronach edycji postów
    if (!in_array($hook, array('post.php', 'post-new.php', 'edit.php'))) {
        return;
    }
    
    // Ładuj dla odpowiednich typów postów
    $allowed_post_types = array('post', 'page', 'species', 'guides');
    if (!in_array($post_type, $allowed_post_types)) {
        return;
    }
    
    // Ładuj skrypty WordPress Media Uploader
    wp_enqueue_media();
    
    // Ładuj własne style
    wp_add_inline_style('wp-admin', carni24_get_admin_css());
    
    // Ładuj własne skrypty
    wp_add_inline_script('wp-admin', carni24_get_admin_js());
}
add_action('admin_enqueue_scripts', 'carni24_admin_enqueue_assets');

/**
 * Zwraca CSS dla panelu administracyjnego
 */
function carni24_get_admin_css() {
    return '
    /* ===== OGÓLNE STYLE METABOXÓW ===== */
    .post-type-species .postbox,
    .post-type-guides .postbox {
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        overflow: hidden;
    }
    
    .post-type-species .postbox .postbox-header,
    .post-type-guides .postbox .postbox-header {
        background: linear-gradient(135deg, #e8f5e8, #d4edda);
        border-bottom: 1px solid #c3e6cb;
    }
    
    .post-type-species .postbox .postbox-header h2,
    .post-type-guides .postbox .postbox-header h2 {
        color: #155724;
        font-weight: 600;
    }
    
    /* SEO Meta Box - ulepszona wersja */
    #carni24_seo_settings_improved .postbox-header {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb) !important;
        border-bottom: 1px solid #90caf9 !important;
    }
    
    #carni24_seo_settings_improved .postbox-header h2 {
        color: #0d47a1 !important;
    }
    
    /* Species Meta Box - ulepszona wersja */
    #species_details_improved .postbox-header {
        background: linear-gradient(135deg, #e8f5e8, #d4edda) !important;
        border-bottom: 1px solid #c3e6cb !important;
    }
    
    #species_details_improved .postbox-header h2 {
        color: #155724 !important;
    }
    
    /* Guides Meta Box - ulepszona wersja */
    #guides_details_improved .postbox-header {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb) !important;
        border-bottom: 1px solid #90caf9 !important;
    }
    
    #guides_details_improved .postbox-header h2 {
        color: #0d47a1 !important;
    }
    
    /* Feature Meta Box - ulepszona wersja */
    #carni24_feature_settings_improved .postbox-header {
        background: linear-gradient(135deg, #fff3e0, #ffe0b2) !important;
        border-bottom: 1px solid #ffb74d !important;
    }
    
    #carni24_feature_settings_improved .postbox-header h2 {
        color: #e65100 !important;
    }
    
    /* ===== USUWANIE PADDING Z INSIDE ===== */
    #carni24_seo_settings_improved .inside,
    #species_details_improved .inside,
    #guides_details_improved .inside,
    #carni24_feature_settings_improved .inside,
    #species_bibliography_improved .inside,
    #guides_bibliography_improved .inside {
        padding: 0;
        margin: 0;
    }
    
    /* ===== RESPONSYWNE METABOXY ===== */
    @media (max-width: 850px) {
        .carni24-seo-tabs,
        .carni24-species-tabs,
        .carni24-guides-tabs {
            flex-wrap: wrap;
        }
        
        .carni24-seo-tabs .tab-link,
        .carni24-species-tabs .tab-link,
        .carni24-guides-tabs .tab-link {
            flex: 1;
            text-align: center;
            min-width: 0;
        }
    }
    
    /* ===== PREVIEW CARDS ===== */
    .google-preview,
    .facebook-preview,
    .feature-preview-card {
        transition: all 0.2s ease;
    }
    
    .google-preview:hover,
    .facebook-preview:hover,
    .feature-preview-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    /* ===== FIELDS IMPROVEMENTS ===== */
    .seo-field input:invalid,
    .species-field input:invalid,
    .guides-field input:invalid,
    .feature-field input:invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 1px #dc3545;
    }
    
    .seo-field input:valid,
    .species-field input:valid,
    .guides-field input:valid,
    .feature-field input:valid {
        border-color: #28a745;
    }
    
    /* ===== TOGGLE SWITCHES ===== */
    .feature-toggle:hover .toggle-slider {
        background: #999;
    }
    
    .feature-toggle input:checked:hover + .toggle-slider {
        background: #45a049;
    }
    
    /* ===== LOADING STATES ===== */
    .metabox-loading {
        position: relative;
        pointer-events: none;
        opacity: 0.6;
    }
    
    .metabox-loading::after {
        content: "⏳";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        z-index: 1000;
    }
    
    /* ===== NOTIFICATIONS ===== */
    .metabox-notification {
        position: fixed;
        top: 32px;
        right: 20px;
        background: #00a32a;
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        z-index: 9999;
        animation: slideInRight 0.3s ease;
    }
    
    .metabox-notification.error {
        background: #dc3545;
    }
    
    .metabox-notification.warning {
        background: #ffc107;
        color: #212529;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* ===== UNSAVED CHANGES INDICATOR ===== */
    .unsaved-changes {
        position: relative;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 183, 77, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 183, 77, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 183, 77, 0); }
    }
    
    /* ===== BETTER FORM ELEMENTS ===== */
    .carni24-seo-metabox select,
    .carni24-species-metabox select,
    .carni24-guides-metabox select,
    .carni24-feature-metabox select {
        background-image: url("data:image/svg+xml;charset=utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 4 5\'><path fill=\'%23666\' d=\'M2 0L0 2h4zm0 5L0 3h4z\'/></svg>");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 12px;
        padding-right: 30px;
        appearance: none;
    }
    
    /* ===== TOOLTIPS ===== */
    .field-hint {
        position: relative;
        cursor: help;
    }
    
    .field-hint:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 0;
        background: #333;
        color: white;
        padding: 4px 8px;
        border-radius: 3px;
        white-space: nowrap;
        z-index: 1000;
        font-size: 11px;
    }
    
    /* ===== DRAG AND DROP AREAS ===== */
    .image-upload-field {
        border: 2px dashed #c3c4c7;
        border-radius: 6px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
        background: #fafafa;
    }
    
    .image-upload-field:hover {
        border-color: #007cba;
        background: #f0f8ff;
    }
    
    .image-upload-field.drag-over {
        border-color: #00a32a;
        background: #f0fff0;
    }
    
    /* ===== ACCESSIBILITY IMPROVEMENTS ===== */
    .tab-link:focus,
    .feature-toggle:focus,
    .checkbox-item:focus {
        outline: 2px solid #007cba;
        outline-offset: 2px;
    }
    
    /* Reduce motion for users who prefer it */
    @media (prefers-reduced-motion: reduce) {
        .carni24-seo-tabs .tab-link,
        .carni24-species-tabs .tab-link,
        .carni24-guides-tabs .tab-link,
        .toggle-slider,
        .metabox-notification {
            transition: none;
            animation: none;
        }
    }
    
    /* ===== DARK MODE SUPPORT ===== */
    @media (prefers-color-scheme: dark) {
        .species-info-card,
        .care-section,
        .execution-section,
        .additional-section,
        .feature-section {
            background: #1e1e1e;
            border-color: #3c3c3c;
            color: #e0e0e0;
        }
        
        .species-info-card h4,
        .care-section h4,
        .execution-section h4,
        .additional-section h4,
        .feature-section h4 {
            color: #4fc3f7;
            border-bottom-color: #3c3c3c;
        }
        
        .google-preview,
        .facebook-preview,
        .feature-preview-card {
            background: #2d2d2d;
            border-color: #3c3c3c;
            color: #e0e0e0;
        }
    }
    
    /* ===== MOBILE OPTIMIZATIONS ===== */
    @media (max-width: 480px) {
        .carni24-seo-content,
        .carni24-species-content,
        .carni24-guides-content {
            padding: 10px;
        }
        
        .species-field-grid,
        .guides-field-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .feature-preview-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .color-picker-field {
            flex-direction: column;
            align-items: stretch;
        }
        
        .image-upload-field {
            flex-direction: column;
        }
    }
    ';
}

/**
 * Zwraca JavaScript dla panelu administracyjnego
 */
function carni24_get_admin_js() {
    return '
    // Globalne funkcje pomocnicze dla metaboxów
    window.Carni24Admin = {
        // Pokazuje powiadomienie
        showNotification: function(message, type = "success", duration = 3000) {
            const notification = document.createElement("div");
            notification.className = "metabox-notification " + type;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = "slideInRight 0.3s ease reverse";
                setTimeout(() => notification.remove(), 300);
            }, duration);
        },
        
        // Pokazuje loading state na metaboxie
        showLoading: function(element) {
            element.classList.add("metabox-loading");
        },
        
        // Ukrywa loading state
        hideLoading: function(element) {
            element.classList.remove("metabox-loading");
        },
        
        // Waliduje URL
        isValidUrl: function(url) {
            try {
                new URL(url);
                return true;
            } catch {
                return false;
            }
        },
        
        // Waliduje hex color
        isValidHexColor: function(color) {
            return /^#[0-9A-F]{6}$/i.test(color);
        },
        
        // Debounce function
        debounce: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        // Auto-resize textarea
        autoResizeTextarea: function(textarea) {
            textarea.style.height = "auto";
            textarea.style.height = textarea.scrollHeight + "px";
        }
    };
    
    // Inicjalizacja po załadowaniu strony
    document.addEventListener("DOMContentLoaded", function() {
        // Auto-resize wszystkich textarea w metaboxach
        const textareas = document.querySelectorAll(".carni24-seo-metabox textarea, .carni24-species-metabox textarea, .carni24-guides-metabox textarea");
        textareas.forEach(textarea => {
            textarea.addEventListener("input", () => Carni24Admin.autoResizeTextarea(textarea));
            Carni24Admin.autoResizeTextarea(textarea); // Pierwsza inicjalizacja
        });
        
        // Obsługa keyboard navigation dla zakładek
        const tabLinks = document.querySelectorAll(".tab-link");
        tabLinks.forEach((link, index) => {
            link.addEventListener("keydown", function(e) {
                if (e.key === "ArrowRight" || e.key === "ArrowLeft") {
                    e.preventDefault();
                    const direction = e.key === "ArrowRight" ? 1 : -1;
                    const nextIndex = (index + direction + tabLinks.length) % tabLinks.length;
                    tabLinks[nextIndex].click();
                    tabLinks[nextIndex].focus();
                }
            });
        });
        
        // Obsługa drag and drop dla upload pól
        const uploadFields = document.querySelectorAll(".image-upload-field");
        uploadFields.forEach(field => {
            field.addEventListener("dragover", function(e) {
                e.preventDefault();
                this.classList.add("drag-over");
            });
            
            field.addEventListener("dragleave", function(e) {
                e.preventDefault();
                this.classList.remove("drag-over");
            });
            
            field.addEventListener("drop", function(e) {
                e.preventDefault();
                this.classList.remove("drag-over");
                // Tutaj można dodać obsługę drop plików
                Carni24Admin.showNotification("Funkcja drag & drop będzie dostępna w przyszłej wersji", "warning");
            });
        });
        
        // Obsługa unsaved changes
        let hasUnsavedChanges = false;
        const importantInputs = document.querySelectorAll("input[name*=seo_], input[name*=species_], input[name*=guide_], input[name*=feature_]");
        
        importantInputs.forEach(input => {
            input.addEventListener("change", function() {
                hasUnsavedChanges = true;
                // Dodaj ostrzeżenie przy opuszczeniu strony
                window.onbeforeunload = function() {
                    return hasUnsavedChanges ? "Masz niezapisane zmiany. Czy na pewno chcesz opuścić stronę?" : null;
                };
            });
        });
        
        // Usuń ostrzeżenie po zapisaniu
        const saveButtons = document.querySelectorAll("#publish, #save-post");
        saveButtons.forEach(button => {
            button.addEventListener("click", function() {
                hasUnsavedChanges = false;
                window.onbeforeunload = null;
            });
        });
        
        // Inicjalizacja tooltipów dla field hints
        const fieldHints = document.querySelectorAll(".field-hint");
        fieldHints.forEach(hint => {
            if (hint.textContent && !hint.title) {
                hint.title = hint.textContent;
            }
        });
        
        // Obsługa pełnego ekranu dla metaboxów (na przyszłość)
        const metaboxHeaders = document.querySelectorAll(".postbox-header h2");
        metaboxHeaders.forEach(header => {
            if (header.closest("#carni24_seo_settings_improved, #species_details_improved, #guides_details_improved")) {
                const fullscreenBtn = document.createElement("button");
                fullscreenBtn.type = "button";
                fullscreenBtn.innerHTML = "⛶";
                fullscreenBtn.className = "button button-small";
                fullscreenBtn.style.marginLeft = "10px";
                fullscreenBtn.title = "Tryb pełnoekranowy (w przygotowaniu)";
                fullscreenBtn.onclick = () => Carni24Admin.showNotification("Tryb pełnoekranowy będzie dostępny wkrótce", "warning");
                header.appendChild(fullscreenBtn);
            }
        });
        
        // Powiadomienie o załadowaniu ulepszonych metaboxów
        setTimeout(() => {
            if (document.querySelector(".carni24-seo-metabox, .carni24-species-metabox, .carni24-guides-metabox, .carni24-feature-metabox")) {
                Carni24Admin.showNotification("🎉 Ulepszone metaboxy zostały załadowane!", "success", 2000);
            }
        }, 1000);
    });
    ';
}

/**
 * Dodaje informacje o miniaturze wpisu
 */
function carni24_admin_post_thumbnail_html($content, $post_id) {
    if (get_post_type($post_id) === 'species' || get_post_type($post_id) === 'guides') {
        $content .= '<p class="description">💡 <strong>Wskazówka:</strong> Obraz będzie automatycznie przeskalowany do różnych rozmiarów. Optymalny rozmiar to 800x600px.</p>';
    }
    
    return $content;
}
add_filter('admin_post_thumbnail_html', 'carni24_admin_post_thumbnail_html', 10, 2);

/**
 * Dodaje kolumny do list postów
 */
function carni24_add_admin_columns($columns) {
    global $post_type;
    
    if ($post_type === 'species') {
        $columns['species_difficulty'] = 'Trudność';
        $columns['species_origin'] = 'Pochodzenie';
        $columns['species_views'] = 'Wyświetlenia';
    } elseif ($post_type === 'guides') {
        $columns['guide_difficulty'] = 'Trudność';
        $columns['guide_category'] = 'Kategoria';
        $columns['guide_duration'] = 'Czas wykonania';
    }
    
    return $columns;
}
add_filter('manage_species_posts_columns', 'carni24_add_admin_columns');
add_filter('manage_guides_posts_columns', 'carni24_add_admin_columns');

/**
 * Wypełnia kolumny danymi
 */
function carni24_fill_admin_columns($column, $post_id) {
    switch ($column) {
        case 'species_difficulty':
        case 'guide_difficulty':
            $difficulty = get_post_meta($post_id, '_' . $column, true);
            if ($difficulty) {
                $class = '';
                $text = $difficulty;
                
                if (strpos($difficulty, 'łatwy') !== false || $difficulty === 'easy' || $difficulty === 'Początkujący') {
                    $class = 'easy';
                    $text = 'Łatwy';
                } elseif (strpos($difficulty, 'średni') !== false || $difficulty === 'medium' || $difficulty === 'Średniozaawansowany') {
                    $class = 'medium';
                    $text = 'Średni';
                } elseif (strpos($difficulty, 'trudny') !== false || $difficulty === 'hard' || $difficulty === 'Zaawansowany') {
                    $class = 'hard';
                    $text = 'Trudny';
                }
                
                echo '<span class="difficulty-badge ' . $class . '">' . esc_html($text) . '</span>';
            } else {
                echo '—';
            }
            break;
            
        case 'species_origin':
            $origin = get_post_meta($post_id, '_species_origin', true);
            echo $origin ? esc_html($origin) : '—';
            break;
            
        case 'species_views':
            $views = function_exists('carni24_get_post_views') ? carni24_get_post_views($post_id) : 0;
            echo number_format($views);
            break;
            
        case 'guide_category':
            $category = get_post_meta($post_id, '_guide_category', true);
            echo $category ? esc_html($category) : '—';
            break;
            
        case 'guide_duration':
            $duration = get_post_meta($post_id, '_guide_duration', true);
            echo $duration ? esc_html($duration) : '—';
            break;
    }
}
add_action('manage_species_posts_custom_column', 'carni24_fill_admin_columns', 10, 2);
add_action('manage_guides_posts_custom_column', 'carni24_fill_admin_columns', 10, 2);

/**
 * Dodaje dashboard widget z statystykami
 */
function carni24_dashboard_widget() {
    wp_add_dashboard_widget(
        'carni24_stats_widget',
        '🌱 Statystyki Carni24',
        'carni24_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'carni24_dashboard_widget');

/**
 * Zawartość dashboard widget
 */
function carni24_dashboard_widget_content() {
    $species_count = wp_count_posts('species')->publish;
    $guides_count = wp_count_posts('guides')->publish;
    $posts_count = wp_count_posts('post')->publish;
    
    // Najczęściej oglądane
    $popular_posts = get_posts(array(
        'posts_per_page' => 3,
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'post_type' => array('post', 'species', 'guides')
    ));
    
    echo '<div class="carni24-dashboard-stats">';
    echo '<div class="stats-grid">';
    echo '<div class="stat-item"><span class="count">' . $species_count . '</span><span class="label">Gatunki</span></div>';
    echo '<div class="stat-item"><span class="count">' . $guides_count . '</span><span class="label">Poradniki</span></div>';
    echo '<div class="stat-item"><span class="count">' . $posts_count . '</span><span class="label">Artykuły</span></div>';
    echo '</div>';
    
    if (!empty($popular_posts)) {
        echo '<h4>📈 Najpopularniejsze</h4>';
        echo '<ul class="popular-list">';
        foreach ($popular_posts as $post) {
            $views = get_post_meta($post->ID, 'post_views_count', true) ?: 0;
            echo '<li><a href="' . get_edit_post_link($post->ID) . '">' . esc_html($post->post_title) . '</a> <span class="views">(' . number_format($views) . ' wyświetleń)</span></li>';
        }
        echo '</ul>';
    }
    
    echo '</div>';
    
    echo '<style>
    .carni24-dashboard-stats .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .carni24-dashboard-stats .stat-item {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 4px solid #28a745;
    }
    
    .carni24-dashboard-stats .count {
        display: block;
        font-size: 24px;
        font-weight: bold;
        color: #28a745;
    }
    
    .carni24-dashboard-stats .label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
    }
    
    .carni24-dashboard-stats .popular-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .carni24-dashboard-stats .popular-list li {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
    
    .carni24-dashboard-stats .popular-list li:last-child {
        border-bottom: none;
    }
    
    .carni24-dashboard-stats .views {
        color: #666;
        font-size: 12px;
    }
    
    @media (max-width: 600px) {
        .carni24-dashboard-stats .stats-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>';
}

/**
 * Dodaje help tabs
 */
function carni24_add_help_tabs() {
    $screen = get_current_screen();
    
    if ($screen->post_type === 'species') {
        $screen->add_help_tab(array(
            'id' => 'species_help',
            'title' => 'Pomoc - Gatunki',
            'content' => '
                <h3>🌱 Jak dodać gatunek rośliny mięsożernej</h3>
                <p><strong>Podstawowe informacje:</strong></p>
                <ul>
                    <li>Wprowadź nazwę naukową w formacie <em>Genus species</em></li>
                    <li>Podaj dokładne pochodzenie geograficzne</li>
                    <li>Wybierz odpowiedni poziom trudności hodowli</li>
                </ul>
                <p><strong>Wymagania środowiskowe:</strong></p>
                <ul>
                    <li>Światło: podaj konkretne wartości w lux lub opis</li>
                    <li>Woda: określ typ podłoża (suche, wilgotne, mokre, bagienne)</li>
                    <li>Temperatura: podaj zakres w stopniach Celsjusza</li>
                    <li>Wilgotność: podaj zakres procentowy</li>
                </ul>
                <p><strong>Wskazówki SEO:</strong></p>
                <ul>
                    <li>Użyj nazwy naukowej w tytule i opisie</li>
                    <li>Dodaj słowa kluczowe związane z hodowlą</li>
                    <li>Napisz przydatny meta opis (120-160 znaków)</li>
                </ul>
            '
        ));
    }
    
    if ($screen->post_type === 'guides') {
        $screen->add_help_tab(array(
            'id' => 'guides_help', 
            'title' => 'Pomoc - Poradniki',
            'content' => '
                <h3>📖 Jak napisać dobry poradnik</h3>
                <p><strong>Struktura poradnika:</strong></p>
                <ol>
                    <li>Jasny tytuł opisujący cel</li>
                    <li>Wprowadzenie - co osiągnie czytelnik</li>
                    <li>Lista potrzebnych narzędzi i materiałów</li>
                    <li>Krok po kroku instrukcje</li>
                    <li>Wskazówki i najczęstsze błędy</li>
                    <li>Podsumowanie i efekt końcowy</li>
                </ol>
                <p><strong>Poziomy trudności:</strong></p>
                <ul>
                    <li><strong>Początkujący:</strong> Pierwszy kontakt z tematem</li>
                    <li><strong>Średniozaawansowany:</strong> Pewne doświadczenie wymagane</li>
                    <li><strong>Zaawansowany:</strong> Dla ekspertów i profesjonalistów</li>
                </ul>
                <p><strong>Wskazówki pisania:</strong></p>
                <ul>
                    <li>Używaj prostego, zrozumiałego języka</li>
                    <li>Dodawaj zdjęcia do każdego kroku</li>
                    <li>Podaj szacowany czas wykonania</li>
                    <li>Ostrzeż przed potencjalnymi problemami</li>
                </ul>
            '
        ));
    }
}
add_action('load-post.php', 'carni24_add_help_tabs');
add_action('load-post-new.php', 'carni24_add_help_tabs');