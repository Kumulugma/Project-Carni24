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

/**
 * Dodaje kolumnę z obrazkiem wyróżniającym dla wszystkich typów postów
 */
function carni24_add_thumbnail_column($columns) {
    // Sprawdź czy kolumna już istnieje (unikaj duplikacji)
    if (isset($columns['featured_image'])) {
        return $columns;
    }
    
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        // Dodaj kolumnę obrazka po checkboxie, ale przed tytułem
        if ($key === 'title') {
            $new_columns['featured_image'] = '🖼️ Obrazek';
        }
        
        $new_columns[$key] = $value;
    }
    
    return $new_columns;
}

// Dodaj kolumnę dla wszystkich typów postów
add_filter('manage_posts_columns', 'carni24_add_thumbnail_column');
add_filter('manage_pages_columns', 'carni24_add_thumbnail_column');
add_filter('manage_guides_posts_columns', 'carni24_add_thumbnail_column');

/**
 * Wypełnia kolumnę obrazka treścią
 */
function carni24_fill_thumbnail_column($column, $post_id) {
    if ($column === 'featured_image') {
        $thumbnail_id = get_post_thumbnail_id($post_id);
        
        if ($thumbnail_id) {
            // Pobierz obrazek w rozmiarze thumbnail
            $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'thumbnail');
            $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            $post_title = get_the_title($post_id);
            
            if ($thumbnail_url) {
                echo '<div class="admin-thumbnail-container">';
                echo '<img src="' . esc_url($thumbnail_url) . '" ';
                echo 'alt="' . esc_attr($thumbnail_alt ?: $post_title) . '" ';
                echo 'class="admin-thumbnail" ';
                echo 'title="' . esc_attr($post_title) . '" />';
                
                // Dodaj informacje o rozmiarze obrazka
                $attachment_metadata = wp_get_attachment_metadata($thumbnail_id);
                if ($attachment_metadata && isset($attachment_metadata['width'], $attachment_metadata['height'])) {
                    echo '<div class="thumbnail-info">';
                    echo '<small>' . $attachment_metadata['width'] . ' × ' . $attachment_metadata['height'] . 'px</small>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
        } else {
            // Brak obrazka - pokaż placeholder
            echo '<div class="admin-thumbnail-container no-thumbnail">';
            echo '<div class="admin-thumbnail-placeholder">';
            echo '<i class="dashicons dashicons-format-image"></i>';
            echo '<span>Brak obrazka</span>';
            echo '</div>';
            echo '</div>';
        }
    }
}

// Dodaj wypełnianie kolumny dla wszystkich typów postów
add_action('manage_posts_custom_column', 'carni24_fill_thumbnail_column', 10, 2);
add_action('manage_pages_custom_column', 'carni24_fill_thumbnail_column', 10, 2);
add_action('manage_guides_posts_custom_column', 'carni24_fill_thumbnail_column', 10, 2);

/**
 * Czyni kolumnę z obrazkiem sortowalną
 */
function carni24_make_thumbnail_column_sortable($columns) {
    $columns['featured_image'] = 'featured_image';
    return $columns;
}

add_filter('manage_edit-post_sortable_columns', 'carni24_make_thumbnail_column_sortable');
add_filter('manage_edit-page_sortable_columns', 'carni24_make_thumbnail_column_sortable');
add_filter('manage_edit-species_sortable_columns', 'carni24_make_thumbnail_column_sortable');
add_filter('manage_edit-guides_sortable_columns', 'carni24_make_thumbnail_column_sortable');

/**
 * Obsługuje sortowanie kolumny z obrazkiem
 */
function carni24_handle_thumbnail_column_sorting($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ($orderby === 'featured_image') {
        $query->set('meta_key', '_thumbnail_id');
        $query->set('orderby', 'meta_value_num');
        
        // Użytkownicy bez obrazka na końcu
        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key' => '_thumbnail_id',
                'value' => '',
                'compare' => '!='
            ),
            array(
                'key' => '_thumbnail_id',
                'value' => '',
                'compare' => 'NOT EXISTS'
            )
        ));
    }
}
add_action('pre_get_posts', 'carni24_handle_thumbnail_column_sorting');

/**
 * Ustaw szerokość kolumny z obrazkiem
 */
function carni24_admin_thumbnail_column_width() {
    ?>
    <style>
    /* ===== KOLUMNA Z OBRAZKAMI ===== */
    .column-featured_image {
        width: 80px !important;
        text-align: center;
    }
    
    .admin-thumbnail-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    
    .admin-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid #ddd;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .admin-thumbnail:hover {
        border-color: #0073aa;
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .admin-thumbnail-placeholder {
        width: 60px;
        height: 60px;
        background: #f6f7f7;
        border: 2px dashed #c3c4c7;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #787c82;
        font-size: 11px;
        text-align: center;
    }
    
    .admin-thumbnail-placeholder .dashicons {
        font-size: 20px;
        width: 20px;
        height: 20px;
        margin-bottom: 2px;
    }
    
    .admin-thumbnail-placeholder span {
        font-size: 9px;
        line-height: 1;
    }
    
    .thumbnail-info {
        font-size: 10px;
        color: #787c82;
        text-align: center;
        line-height: 1.2;
    }
    
    .no-thumbnail .admin-thumbnail-placeholder {
        opacity: 0.6;
    }
    
    /* Hover na całym wierszu pokazuje podgląd */
    .type-post:hover .admin-thumbnail,
    .type-page:hover .admin-thumbnail,
    .type-species:hover .admin-thumbnail,
    .type-guides:hover .admin-thumbnail {
        border-color: #0073aa;
    }
    
    /* Responsive - ukryj obrazki na małych ekranach */
    @media screen and (max-width: 782px) {
        .column-featured_image {
            display: none;
        }
    }
    
    /* Quick Edit - dodaj informację o obrazku */
    .inline-edit-row .featured_image_info {
        margin: 8px 0;
        padding: 8px;
        background: #f6f7f7;
        border-radius: 4px;
        font-size: 12px;
    }
    
    .featured_image_info strong {
        color: #1d2327;
    }
    
    .featured_image_info.has-thumbnail {
        background: #d7fdd7;
        border-left: 3px solid #00a32a;
    }
    
    .featured_image_info.no-thumbnail {
        background: #fff2cd;
        border-left: 3px solid #dba617;
    }
    </style>
    <?php
}
add_action('admin_head', 'carni24_admin_thumbnail_column_width');

/**
 * Dodaje informacje o obrazku do Quick Edit
 */
function carni24_add_thumbnail_info_to_quick_edit($column_name, $post_type) {
    if ($column_name === 'featured_image') {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Dodaj informację o obrazku do Quick Edit
            $('a.editinline').on('click', function() {
                var post_id = $(this).closest('tr').attr('id').replace('post-', '');
                var thumbnail_col = $('#post-' + post_id + ' .column-featured_image');
                var has_thumbnail = thumbnail_col.find('.admin-thumbnail').length > 0;
                
                setTimeout(function() {
                    var quick_edit = $('#edit-' + post_id);
                    
                    // Usuń poprzednie info
                    quick_edit.find('.featured_image_info').remove();
                    
                    // Dodaj nowe info
                    var info_html = '<div class="featured_image_info ' + 
                        (has_thumbnail ? 'has-thumbnail' : 'no-thumbnail') + '">';
                    
                    if (has_thumbnail) {
                        var img_src = thumbnail_col.find('.admin-thumbnail').attr('src');
                        var img_info = thumbnail_col.find('.thumbnail-info small').text();
                        
                        info_html += '<strong>✅ Obrazek wyróżniający:</strong> Ustawiony ';
                        if (img_info) {
                            info_html += '(' + img_info + ')';
                        }
                        info_html += '<br><img src="' + img_src + '" style="max-width: 100px; height: auto; margin-top: 4px; border-radius: 4px;">';
                    } else {
                        info_html += '<strong>⚠️ Brak obrazka wyróżniającego</strong><br>';
                        info_html += 'Rozważ dodanie obrazka dla lepszego SEO i wyglądu.';
                    }
                    
                    info_html += '</div>';
                    
                    quick_edit.find('.inline-edit-col-left').append(info_html);
                }, 100);
            });
        });
        </script>
        <?php
    }
}
add_action('quick_edit_custom_box', 'carni24_add_thumbnail_info_to_quick_edit', 10, 2);

/**
 * Dodaje bulk action dla ustawiania obrazków
 */
function carni24_add_thumbnail_bulk_actions($bulk_actions) {
    $bulk_actions['set_featured_image'] = 'Ustaw obrazek wyróżniający';
    $bulk_actions['remove_featured_image'] = 'Usuń obrazek wyróżniający';
    return $bulk_actions;
}

add_filter('bulk_actions-edit-post', 'carni24_add_thumbnail_bulk_actions');
add_filter('bulk_actions-edit-page', 'carni24_add_thumbnail_bulk_actions');
add_filter('bulk_actions-edit-species', 'carni24_add_thumbnail_bulk_actions');
add_filter('bulk_actions-edit-guides', 'carni24_add_thumbnail_bulk_actions');

/**
 * Obsługuje bulk actions dla obrazków
 */
function carni24_handle_thumbnail_bulk_actions($redirect_to, $doaction, $post_ids) {
    if ($doaction === 'remove_featured_image') {
        foreach ($post_ids as $post_id) {
            delete_post_thumbnail($post_id);
        }
        
        $redirect_to = add_query_arg(array(
            'bulk_thumbnails_removed' => count($post_ids)
        ), $redirect_to);
    }
    
    return $redirect_to;
}

add_filter('handle_bulk_actions-edit-post', 'carni24_handle_thumbnail_bulk_actions', 10, 3);
add_filter('handle_bulk_actions-edit-page', 'carni24_handle_thumbnail_bulk_actions', 10, 3);
add_filter('handle_bulk_actions-edit-species', 'carni24_handle_thumbnail_bulk_actions', 10, 3);
add_filter('handle_bulk_actions-edit-guides', 'carni24_handle_thumbnail_bulk_actions', 10, 3);

/**
 * Pokazuje komunikat po bulk action
 */
function carni24_thumbnail_bulk_action_admin_notice() {
    if (!empty($_REQUEST['bulk_thumbnails_removed'])) {
        $count = intval($_REQUEST['bulk_thumbnails_removed']);
        printf(
            '<div class="notice notice-success is-dismissible"><p>%s</p></div>',
            sprintf(
                _n(
                    'Usunięto obrazek wyróżniający z %d wpisu.',
                    'Usunięto obrazki wyróżniające z %d wpisów.',
                    $count,
                    'carni24'
                ),
                $count
            )
        );
    }
}
add_action('admin_notices', 'carni24_thumbnail_bulk_action_admin_notice');

/**
 * Dodaje licznik postów z/bez obrazków do At a Glance
 */
function carni24_add_thumbnail_stats_to_glance() {
    $post_types = array('post', 'page', 'species', 'guides');
    
    foreach ($post_types as $post_type) {
        $with_thumbnails = get_posts(array(
            'post_type' => $post_type,
            'meta_key' => '_thumbnail_id',
            'meta_value' => '',
            'meta_compare' => '!=',
            'numberposts' => -1,
            'fields' => 'ids'
        ));
        
        $without_thumbnails = get_posts(array(
            'post_type' => $post_type,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key' => '_thumbnail_id',
                    'value' => ''
                )
            ),
            'numberposts' => -1,
            'fields' => 'ids'
        ));
        
        $with_count = count($with_thumbnails);
        $without_count = count($without_thumbnails);
        
        if ($with_count > 0 || $without_count > 0) {
            $post_type_obj = get_post_type_object($post_type);
            $label = $post_type_obj->labels->name;
            
            echo '<li class="post-count thumbnail-stats">';
            echo '<span class="thumbnails-with">📷 ' . $with_count . '</span> / ';
            echo '<span class="thumbnails-without">📷 ' . $without_count . '</span>';
            echo ' ' . $label;
            echo '</li>';
        }
    }
}
add_action('dashboard_glance_items', 'carni24_add_thumbnail_stats_to_glance');

/**
 * Style dla Dashboard stats
 */
function carni24_dashboard_thumbnail_stats_style() {
    ?>
    <style>
    .thumbnail-stats {
        position: relative;
    }
    
    .thumbnails-with {
        color: #00a32a;
        font-weight: 600;
    }
    
    .thumbnails-without {
        color: #dba617;
        font-weight: 600;
    }
    
    .thumbnail-stats::before {
        content: '🖼️';
        margin-right: 4px;
    }
    </style>
    <?php
}
add_action('admin_head-index.php', 'carni24_dashboard_thumbnail_stats_style');