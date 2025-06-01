<?php

class K3E_Migrate_Admin {
    
    public function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('K3E Migrate', 'k3e-migrate'),
            __('K3E Migrate', 'k3e-migrate'),
            'manage_options',
            'k3e-migrate',
            array($this, 'render_admin_page'),
            'dashicons-migrate',
            30
        );
        
        add_submenu_page(
            'k3e-migrate',
            __('Eksport z systemu', 'k3e-migrate'),
            __('Eksport z systemu', 'k3e-migrate'),
            'manage_options',
            'k3e-migrate-export',
            array($this, 'render_export_page')
        );
        
        add_submenu_page(
            'k3e-migrate',
            __('Import z JSON', 'k3e-migrate'),
            __('Import z JSON', 'k3e-migrate'),
            'manage_options',
            'k3e-migrate-import',
            array($this, 'render_import_page')
        );
    }
    
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('K3E Migrate', 'k3e-migrate'); ?></h1>
            
            <div class="k3e-migrate-dashboard">
                <div class="k3e-migrate-card">
                    <h2><?php _e('Eksport z systemu', 'k3e-migrate'); ?></h2>
                    <p><?php _e('Eksportuj Custom Post Types wraz z polami meta do pliku JSON.', 'k3e-migrate'); ?></p>
                    <a href="<?php echo admin_url('admin.php?page=k3e-migrate-export'); ?>" class="button button-primary">
                        <?php _e('Rozpocznij eksport', 'k3e-migrate'); ?>
                    </a>
                </div>
                
                <div class="k3e-migrate-card">
                    <h2><?php _e('Import z JSON', 'k3e-migrate'); ?></h2>
                    <p><?php _e('Importuj dane z pliku JSON do WordPress.', 'k3e-migrate'); ?></p>
                    <a href="<?php echo admin_url('admin.php?page=k3e-migrate-import'); ?>" class="button button-primary">
                        <?php _e('Rozpocznij import', 'k3e-migrate'); ?>
                    </a>
                </div>
            </div>
            
            <div class="k3e-migrate-info">
                <h3><?php _e('Informacje o wtyczce', 'k3e-migrate'); ?></h3>
                <p><?php _e('K3E Migrate pozwala na łatwy eksport i import Custom Post Types wraz z wszystkimi polami meta.', 'k3e-migrate'); ?></p>
                
                <h4><?php _e('Funkcjonalności:', 'k3e-migrate'); ?></h4>
                <ul>
                    <li><?php _e('Eksport dowolnego CPT z systemu', 'k3e-migrate'); ?></li>
                    <li><?php _e('Wybór konkretnych pól meta do eksportu', 'k3e-migrate'); ?></li>
                    <li><?php _e('Import z automatycznym rozpoznaniem CPT', 'k3e-migrate'); ?></li>
                    <li><?php _e('Opcja tworzenia nowego CPT podczas importu', 'k3e-migrate'); ?></li>
                    <li><?php _e('Przetwarzanie partiami dla dużych ilości danych', 'k3e-migrate'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    public function render_export_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Eksport z systemu', 'k3e-migrate'); ?></h1>
            
            <div class="k3e-migrate-export-container">
                <div id="step-1" class="k3e-migrate-step active">
                    <h2><?php _e('Krok 1: Wybór Custom Post Type', 'k3e-migrate'); ?></h2>
                    <p><?php _e('Wybierz typ treści, który chcesz wyeksportować.', 'k3e-migrate'); ?></p>
                    
                    <div class="k3e-migrate-form">
                        <select id="export-cpt" class="widefat">
                            <option value=""><?php _e('Wybierz Custom Post Type', 'k3e-migrate'); ?></option>
                        </select>
                        
                        <div class="k3e-migrate-loading" style="display: none;">
                            <span class="spinner is-active"></span>
                            <span><?php _e('Ładowanie...', 'k3e-migrate'); ?></span>
                        </div>
                        
                        <p class="submit">
                            <button type="button" id="go-to-step-2" class="button button-primary" disabled>
                                <?php _e('Przejdź do kroku 2', 'k3e-migrate'); ?>
                            </button>
                        </p>
                    </div>
                </div>
                
                <div id="step-2" class="k3e-migrate-step">
                    <h2><?php _e('Krok 2: Wybór pól meta', 'k3e-migrate'); ?></h2>
                    <p><?php _e('Wybierz które pola meta mają zostać wyeksportowane.', 'k3e-migrate'); ?></p>
                    
                    <div class="k3e-migrate-form">
                        <div class="export-options">
                            <h3><?php _e('Podstawowe opcje', 'k3e-migrate'); ?></h3>
                            <p>
                                <label>
                                    <input type="checkbox" id="export-titles" checked>
                                    <?php _e('Eksportuj tytuły', 'k3e-migrate'); ?>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" id="export-content" checked>
                                    <?php _e('Eksportuj treść', 'k3e-migrate'); ?>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" id="export-thumbnails" checked>
                                    <?php _e('Eksportuj miniatury', 'k3e-migrate'); ?>
                                </label>
                            </p>
                        </div>
                        
                        <div class="meta-fields-container">
                            <h3><?php _e('Pola meta', 'k3e-migrate'); ?></h3>
                            <div id="meta-fields-list">
                                <p><?php _e('Wybierz CPT w kroku 1, aby zobaczyć dostępne pola meta.', 'k3e-migrate'); ?></p>
                            </div>
                        </div>
                        
                        <div class="export-stats">
                            <p><strong><?php _e('Liczba postów do eksportu:', 'k3e-migrate'); ?></strong> <span id="posts-count">0</span></p>
                        </div>
                        
                        <p class="submit">
                            <button type="button" id="back-to-step-1" class="button">
                                <?php _e('Wróć do kroku 1', 'k3e-migrate'); ?>
                            </button>
                            <button type="button" id="start-export" class="button button-primary">
                                <?php _e('Rozpocznij eksport', 'k3e-migrate'); ?>
                            </button>
                        </p>
                    </div>
                </div>
                
                <div id="step-3" class="k3e-migrate-step">
                    <h2><?php _e('Krok 3: Proces eksportu', 'k3e-migrate'); ?></h2>
                    
                    <div class="k3e-migrate-progress">
                        <div class="k3e-migrate-progress-bar">
                            <div class="k3e-migrate-progress-bar-inner" style="width: 0%;">
                                <span class="k3e-migrate-progress-percentage">0%</span>
                            </div>
                        </div>
                        <p class="k3e-migrate-progress-status">
                            <?php _e('Przygotowanie do eksportu...', 'k3e-migrate'); ?>
                        </p>
                    </div>
                    
                    <div class="k3e-migrate-export-results" style="display: none;">
                        <h3><?php _e('Eksport zakończony', 'k3e-migrate'); ?></h3>
                        <p class="export-summary"></p>
                        <p class="submit">
                            <a href="#" id="download-export" class="button button-primary">
                                <?php _e('Pobierz plik JSON', 'k3e-migrate'); ?>
                            </a>
                            <button type="button" id="new-export" class="button">
                                <?php _e('Nowy eksport', 'k3e-migrate'); ?>
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function render_import_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Import z JSON', 'k3e-migrate'); ?></h1>
            
            <div class="k3e-migrate-import-container">
                <div class="k3e-migrate-import-form">
                    <h2><?php _e('Importuj dane z pliku JSON', 'k3e-migrate'); ?></h2>
                    <p><?php _e('Wybierz plik JSON wyeksportowany z K3E Migrate.', 'k3e-migrate'); ?></p>
                    
                    <div class="import-file-section">
                        <h3><?php _e('Wybierz plik', 'k3e-migrate'); ?></h3>
                        <input type="file" id="import-file" accept=".json" />
                        <p class="description"><?php _e('Akceptowane formaty: .json', 'k3e-migrate'); ?></p>
                    </div>
                    
                    <div class="import-options" style="display: none;">
                        <h3><?php _e('Opcje importu', 'k3e-migrate'); ?></h3>
                        
                        <div class="import-cpt-options">
                            <h4><?php _e('Custom Post Type', 'k3e-migrate'); ?></h4>
                            <p>
                                <label>
                                    <input type="radio" name="cpt-mode" value="existing" checked>
                                    <?php _e('Użyj istniejącego CPT', 'k3e-migrate'); ?>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="radio" name="cpt-mode" value="create">
                                    <?php _e('Utwórz nowy CPT (jeśli nie istnieje)', 'k3e-migrate'); ?>
                                </label>
                            </p>
                        </div>
                        
                        <div class="import-data-options">
                            <h4><?php _e('Dane do importu', 'k3e-migrate'); ?></h4>
                            <p>
                                <label>
                                    <input type="radio" name="import-mode" value="update" checked>
                                    <?php _e('Aktualizuj istniejące posty', 'k3e-migrate'); ?>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="radio" name="import-mode" value="create">
                                    <?php _e('Utwórz nowe posty', 'k3e-migrate'); ?>
                                </label>
                            </p>
                        </div>
                        
                        <div class="import-details">
                            <h4><?php _e('Szczegóły importu', 'k3e-migrate'); ?></h4>
                            <div class="import-summary"></div>
                        </div>
                    </div>
                    
                    <p class="submit">
                        <button type="button" id="start-import" class="button button-primary" disabled>
                            <?php _e('Rozpocznij import', 'k3e-migrate'); ?>
                        </button>
                    </p>
                    
                    <div class="k3e-migrate-import-progress" style="display: none;">
                        <div class="k3e-migrate-progress-bar">
                            <div class="k3e-migrate-progress-bar-inner" style="width: 0%;">
                                <span class="k3e-migrate-progress-percentage">0%</span>
                            </div>
                        </div>
                        <p class="k3e-migrate-progress-status">
                            <?php _e('Przygotowanie do importu...', 'k3e-migrate'); ?>
                        </p>
                    </div>
                    
                    <div class="k3e-migrate-import-results" style="display: none;">
                        <h3><?php _e('Import zakończony', 'k3e-migrate'); ?></h3>
                        <div class="import-summary-final"></div>
                        <div class="import-errors" style="display: none;">
                            <h4><?php _e('Błędy podczas importu', 'k3e-migrate'); ?></h4>
                            <ul class="import-error-list"></ul>
                        </div>
                        <p class="submit">
                            <button type="button" id="new-import" class="button button-primary">
                                <?php _e('Nowy import', 'k3e-migrate'); ?>
                            </button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}