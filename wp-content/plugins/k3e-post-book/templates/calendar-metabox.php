<?php
/**
 * Template dla metabox kalendarza
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="k3e-post-book-widget" class="k3e-post-book-container">
    <div class="k3e-loading" style="display: none;">
        <div class="spinner is-active"></div>
        <p><?php _e('Ładowanie kalendarza...', 'k3e-post-book'); ?></p>
    </div>
    
    <div class="k3e-calendar-navigation">
        <div class="k3e-nav-selects">
            <select id="k3e-month-select" class="k3e-select">
                <?php for($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php selected($m, $current_month); ?>>
                        <?php echo date_i18n('F', mktime(0, 0, 0, $m, 1)); ?>
                    </option>
                <?php endfor; ?>
            </select>
            
            <select id="k3e-year-select" class="k3e-select">
                <?php for($y = 2010; $y <= 2030; $y++): ?>
                    <option value="<?php echo $y; ?>" <?php selected($y, $current_year); ?>>
                        <?php echo $y; ?>
                    </option>
                <?php endfor; ?>
            </select>
            
            <button id="k3e-date-submit" class="k3e-submit-btn" type="button">
                <?php _e('Pokaż', 'k3e-post-book'); ?>
            </button>
        </div>
    </div>
    
    <div class="k3e-calendar-container">
        <?php echo $calendar->generate_calendar($current_month, $current_year); ?>
    </div>
    
    <div class="k3e-calendar-legend">
        <div class="k3e-legend-item">
            <span class="k3e-legend-color k3e-today-color"></span>
            <span><?php _e('Dzisiaj', 'k3e-post-book'); ?></span>
        </div>
        <div class="k3e-legend-item">
            <span class="k3e-legend-color k3e-posts-std-color"></span>
            <span><?php _e('Wpisy', 'k3e-post-book'); ?></span>
        </div>
        <div class="k3e-legend-item">
            <span class="k3e-legend-color k3e-pages-color"></span>
            <span><?php _e('Strony', 'k3e-post-book'); ?></span>
        </div>
        <div class="k3e-legend-item">
            <span class="k3e-legend-color k3e-species-color"></span>
            <span><?php _e('Gatunki', 'k3e-post-book'); ?></span>
        </div>
        <div class="k3e-legend-item">
            <span class="k3e-legend-color k3e-guides-color"></span>
            <span><?php _e('Poradniki', 'k3e-post-book'); ?></span>
        </div>
        <p class="k3e-legend-note"><?php _e('Kliknij dzień aby zobaczyć szczegóły', 'k3e-post-book'); ?></p>
    </div>
    
    <!-- Modal do wyświetlania wpisów -->
    <div id="k3e-posts-modal" class="k3e-modal" style="display: none;">
        <div class="k3e-modal-overlay"></div>
        <div class="k3e-modal-content">
            <div class="k3e-modal-header">
                <h3 id="k3e-modal-title"></h3>
                <button class="k3e-modal-close" type="button">&times;</button>
            </div>
            <div class="k3e-modal-body">
                <div id="k3e-modal-posts-list"></div>
            </div>
        </div>
    </div>
</div>

<!-- Dane do JavaScript -->
<script type="text/javascript">
    var k3eCurrentMonth = <?php echo $current_month; ?>;
    var k3eCurrentYear = <?php echo $current_year; ?>;
</script>