<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_enqueue_admin_assets($hook) {
    wp_enqueue_media();
    
    $theme_options_hooks = array(
        'appearance_page_carni24-theme-options',
        'themes_page_carni24-theme-options',
        'toplevel_page_carni24-theme-options'
    );
    
    if (in_array($hook, $theme_options_hooks)) {
        wp_enqueue_style(
            'carni24-admin-style',
            CARNI24_THEME_URL . '/assets/admin/css/admin-theme-options.css',
            array(),
            CARNI24_VERSION
        );
        
        wp_enqueue_script(
            'carni24-admin-script',
            CARNI24_THEME_URL . '/assets/admin/js/admin-theme-options.js',
            array('jquery', 'wp-util', 'media-upload'),
            CARNI24_VERSION,
            true
        );
    }
    
    $post_edit_hooks = array('post.php', 'post-new.php');
    if (in_array($hook, $post_edit_hooks)) {
        wp_enqueue_style(
            'carni24-post-edit',
            CARNI24_THEME_URL . '/assets/admin/css/post-edit.css',
            array(),
            CARNI24_VERSION
        );
        
        wp_enqueue_script(
            'carni24-post-edit',
            CARNI24_THEME_URL . '/assets/admin/js/post-edit.js',
            array('jquery'),
            CARNI24_VERSION,
            true
        );
    }
    
    wp_enqueue_style(
        'carni24-admin-global',
        CARNI24_THEME_URL . '/assets/admin/css/admin-global.css',
        array(),
        CARNI24_VERSION
    );
    
    wp_localize_script('jquery', 'carni24_admin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_admin_nonce'),
        'fallback_site_name' => get_bloginfo('name'),
        'fallback_site_description' => get_bloginfo('description'),
        'strings' => array(
            'save_success' => __('Ustawienia zostały zapisane!', 'carni24'),
            'save_error' => __('Błąd zapisywania. Spróbuj ponownie.', 'carni24'),
            'confirm_delete' => __('Czy na pewno chcesz usunąć?', 'carni24'),
            'loading' => __('Ładowanie...', 'carni24'),
            'error' => __('Wystąpił błąd', 'carni24'),
        ),
        'hook' => $hook
    ));
}
add_action('admin_enqueue_scripts', 'carni24_enqueue_admin_assets');

function carni24_create_admin_directories() {
    $theme_dir = get_template_directory();
    
    $directories = array(
        $theme_dir . '/assets',
        $theme_dir . '/assets/admin',
        $theme_dir . '/assets/admin/css',
        $theme_dir . '/assets/admin/js'
    );
    
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }
    }
    
    $css_file = $theme_dir . '/assets/admin/css/admin-theme-options.css';
    if (!file_exists($css_file)) {
        $css_content = carni24_get_default_admin_css();
        file_put_contents($css_file, $css_content);
    }
    
    $js_file = $theme_dir . '/assets/admin/js/admin-theme-options.js';
    if (!file_exists($js_file)) {
        $js_content = carni24_get_default_admin_js();
        file_put_contents($js_file, $js_content);
    }
    
    $post_css = $theme_dir . '/assets/admin/css/post-edit.css';
    if (!file_exists($post_css)) {
        $post_css_content = carni24_get_post_edit_css();
        file_put_contents($post_css, $post_css_content);
    }
    
    $global_css = $theme_dir . '/assets/admin/css/admin-global.css';
    if (!file_exists($global_css)) {
        $global_css_content = carni24_get_global_admin_css();
        file_put_contents($global_css, $global_css_content);
    }
}
add_action('after_setup_theme', 'carni24_create_admin_directories');

function carni24_get_default_admin_css() {
    return '
.carni24-admin-container {
    max-width: 1200px;
    margin: 0 auto;
}

.nav-tab-wrapper {
    margin-bottom: 20px;
    border-bottom: 1px solid #ccd0d4;
}

.nav-tab {
    position: relative;
    display: inline-block;
    padding: 8px 16px;
    margin: 0 4px -1px 0;
    font-size: 14px;
    font-weight: 600;
    color: #646970;
    text-decoration: none;
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-bottom: none;
    border-radius: 4px 4px 0 0;
    transition: all 0.2s ease;
}

.nav-tab:hover {
    color: #135e96;
    background-color: #fff;
}

.nav-tab-active {
    color: #135e96;
    background-color: #fff;
    border-bottom: 1px solid #fff;
}

.tab-content {
    display: none;
    background: #fff;
    padding: 20px;
    border: 1px solid #c3c4c7;
    border-radius: 0 4px 4px 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.tab-content.active {
    display: block;
}

.carni24-field {
    margin-bottom: 20px;
}

.carni24-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #23282d;
}

.carni24-field input[type="text"],
.carni24-field input[type="url"],
.carni24-field input[type="email"],
.carni24-field input[type="number"],
.carni24-field textarea,
.carni24-field select {
    width: 100%;
    max-width: 500px;
    padding: 8px 12px;
    border: 1px solid #8c8f94;
    border-radius: 4px;
    font-size: 14px;
}

.carni24-field input[type="color"] {
    width: 60px;
    height: 40px;
    padding: 0;
    border: 1px solid #8c8f94;
    border-radius: 4px;
    cursor: pointer;
}

.carni24-field .description {
    margin-top: 5px;
    color: #646970;
    font-size: 13px;
    font-style: italic;
}

.carni24-char-counter {
    text-align: right;
    font-size: 12px;
    color: #646970;
    margin-top: 5px;
}

.carni24-char-counter.over-limit {
    color: #d63638;
    font-weight: bold;
}

.carni24-info-box {
    background: #e7f3ff;
    border: 1px solid #72aee6;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.carni24-warning-box {
    background: #fcf9e8;
    border: 1px solid #dba617;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.carni24-success-box {
    background: #edfaef;
    border: 1px solid #68de7c;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 20px;
}

.tool-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.tool-section:last-child {
    border-bottom: none;
}

.tool-section h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #23282d;
}

.image-sizes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin: 15px 0;
}

.image-size-item {
    padding: 15px;
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
}

.image-size-item strong {
    display: block;
    margin-bottom: 5px;
    color: #135e96;
}

.crop-badge {
    display: inline-block;
    padding: 2px 6px;
    background: #dba617;
    color: #fff;
    font-size: 10px;
    font-weight: bold;
    border-radius: 3px;
    margin-left: 5px;
}

.progress-bar {
    width: 100%;
    height: 24px;
    background: #f0f0f0;
    border: 1px solid #c3c4c7;
    border-radius: 12px;
    overflow: hidden;
    margin: 10px 0;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #135e96, #2271b1);
    width: 0%;
    transition: width 0.3s ease;
    border-radius: 12px;
}

.media-upload-preview {
    margin-top: 10px;
    max-width: 200px;
}

.media-upload-preview img {
    max-width: 100%;
    height: auto;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
}

.button-group {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.notice-carni24 {
    border-left: 4px solid #135e96;
}

@media (max-width: 782px) {
    .carni24-admin-container {
        margin: 0 10px;
    }
    
    .nav-tab {
        display: block;
        margin: 0 0 -1px 0;
        border-radius: 0;
    }
    
    .tab-content {
        border-radius: 4px;
    }
    
    .carni24-field input[type="text"],
    .carni24-field input[type="url"],
    .carni24-field input[type="email"], 
    .carni24-field textarea,
    .carni24-field select {
        max-width: 100%;
    }
    
    .image-sizes-grid {
        grid-template-columns: 1fr;
    }
}
';
}

function carni24_get_default_admin_js() {
    return '
jQuery(document).ready(function($) {
    
    // Tab switching
    $(\'.nav-tab\').click(function(e) {
        e.preventDefault();
        const target = $(this).attr(\'href\');
        
        $(\'.nav-tab\').removeClass(\'nav-tab-active\');
        $(this).addClass(\'nav-tab-active\');
        
        $(\'.tab-content\').removeClass(\'active\');
        $(target).addClass(\'active\');
        
        // Update URL hash
        window.location.hash = target;
    });
    
    // Load active tab from URL hash
    if (window.location.hash) {
        const hash = window.location.hash;
        $(\'.nav-tab[href="\' + hash + \'"]\').click();
    }
    
    // Character counters
    $(\'[maxlength]\').each(function() {
        const $this = $(this);
        const maxLength = $this.attr(\'maxlength\');
        const $counter = $(\'<div class="carni24-char-counter"><span>0</span> / \' + maxLength + \' znaków</div>\');
        $this.after($counter);
        
        $this.on(\'input keyup\', function() {
            const length = $this.val().length;
            $counter.find(\'span\').text(length);
            $counter.toggleClass(\'over-limit\', length > maxLength);
        }).trigger(\'input\');
    });
    
    // AJAX form submission
    $(\'#carni24-theme-options-form\').on(\'submit\', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find(\'input[type="submit"]\');
        const originalText = $submitBtn.val();
        
        $submitBtn.val(carni24_admin.strings.loading).prop(\'disabled\', true);
        
        $.ajax({
            url: carni24_admin.ajax_url,
            type: \'POST\',
            data: $form.serialize() + \'&action=carni24_save_theme_options&nonce=\' + carni24_admin.nonce,
            success: function(response) {
                if (response.success) {
                    showNotice(carni24_admin.strings.save_success, \'success\');
                } else {
                    showNotice(response.data || carni24_admin.strings.save_error, \'error\');
                }
            },
            error: function() {
                showNotice(carni24_admin.strings.save_error, \'error\');
            },
            complete: function() {
                $submitBtn.val(originalText).prop(\'disabled\', false);
            }
        });
    });
    
    // Show notice
    function showNotice(message, type) {
        const $notice = $(\'<div class="notice notice-\' + type + \' is-dismissible"><p>\' + message + \'</p></div>\');
        $(\'.carni24-admin-container\').prepend($notice);
        
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    // Media uploader
    window.carni24MediaUploader = function(fieldId, previewId) {
        const mediaUploader = wp.media({
            title: \'Wybierz obraz\',
            button: { text: \'Użyj tego obrazu\' },
            multiple: false
        });
        
        mediaUploader.on(\'select\', function() {
            const attachment = mediaUploader.state().get(\'selection\').first().toJSON();
            $(\'#\' + fieldId).val(attachment.id);
            
            if (previewId) {
                const $preview = $(\'#\' + previewId);
                $preview.html(\'<img src="\' + attachment.sizes.medium.url + \'" alt="" />\');
            }
        });
        
        mediaUploader.open();
    };
    
    // Clear media
    window.carni24ClearMedia = function(fieldId, previewId) {
        $(\'#\' + fieldId).val(\'\');
        if (previewId) {
            $(\'#\' + previewId).empty();
        }
    };
    
    // Color picker change
    $(\'input[type="color"]\').on(\'change\', function() {
        const $this = $(this);
        const $preview = $this.siblings(\'.color-preview\');
        if ($preview.length) {
            $preview.css(\'background-color\', $this.val());
        }
    });
    
    // Confirm delete actions
    $(\'.delete-action\').on(\'click\', function(e) {
        if (!confirm(carni24_admin.strings.confirm_delete)) {
            e.preventDefault();
        }
    });
    
    // Auto-save drafts
    let autoSaveTimer;
    $(\'form\').on(\'input change\', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Auto-save logic here if needed
        }, 30000);
    });
    
    // Tooltips
    if (typeof jQuery.fn.tooltip !== \'undefined\') {
        $(\'[data-toggle="tooltip"]\').tooltip();
    }
    
    // Sortable lists
    if (typeof jQuery.fn.sortable !== \'undefined\') {
        $(\'.sortable-list\').sortable({
            handle: \'.sort-handle\',
            placeholder: \'sortable-placeholder\',
            update: function() {
                // Handle sort order updates
            }
        });
    }
});

// SEO Test function
function carni24TestSEO() {
    const resultsDiv = document.getElementById(\'seo-test-results\');
    resultsDiv.innerHTML = \'<p>Sprawdzanie konfiguracji SEO...</p>\';
    
    jQuery.post(carni24_admin.ajax_url, {
        action: \'carni24_test_seo\',
        nonce: carni24_admin.nonce
    }, function(response) {
        if (response.success) {
            let html = \'<div class="carni24-success-box"><strong>Test SEO zakończony:</strong><ul>\';
            Object.keys(response.data).forEach(key => {
                const status = response.data[key] ? \'✅\' : \'❌\';
                const label = key.replace(/_/g, \' \').replace(/\\b\\w/g, l => l.toUpperCase());
                html += \'<li>\' + status + \' \' + label + \'</li>\';
            });
            html += \'</ul></div>\';
            resultsDiv.innerHTML = html;
        } else {
            resultsDiv.innerHTML = \'<div class="carni24-warning-box">Błąd testu SEO: \' + (response.data || \'Nieznany błąd\') + \'</div>\';
        }
    });
}

// Clear cache function
function carni24ClearCache() {
    const resultsDiv = document.getElementById(\'cache-clear-results\');
    resultsDiv.innerHTML = \'<p>Czyszczenie cache...</p>\';
    
    jQuery.post(carni24_admin.ajax_url, {
        action: \'carni24_clear_cache\',
        nonce: carni24_admin.nonce
    }, function(response) {
        if (response.success) {
            let html = \'<div class="carni24-success-box"><strong>Cache został wyczyszczony!</strong>\';
            if (response.data.cleared && response.data.cleared.length > 0) {
                html += \'<ul>\';
                response.data.cleared.forEach(item => {
                    html += \'<li>✅ \' + item + \'</li>\';
                });
                html += \'</ul>\';
            }
            html += \'</div>\';
            resultsDiv.innerHTML = html;
        } else {
            resultsDiv.innerHTML = \'<div class="carni24-warning-box">Błąd czyszczenia cache: \' + (response.data || \'Nieznany błąd\') + \'</div>\';
        }
    });
}

// Regenerate thumbnails
function carni24RegenerateThumbnails() {
    const progressDiv = document.getElementById(\'regenerate-progress\');
    const statusP = document.getElementById(\'regenerate-status\');
    const progressFill = document.querySelector(\'.progress-fill\');
    
    if (!progressDiv || !statusP || !progressFill) return;
    
    progressDiv.style.display = \'block\';
    progressFill.style.width = \'0%\';
    statusP.textContent = \'Rozpoczynanie regeneracji...\';
    
    jQuery.post(carni24_admin.ajax_url, {
        action: \'carni24_regenerate_thumbnails\',
        nonce: carni24_admin.nonce
    }, function(response) {
        if (response.success) {
            progressFill.style.width = \'100%\';
            statusP.textContent = \'Regenerowano \' + response.data.regenerated + \' z \' + response.data.total + \' obrazów!\';
            
            if (response.data.errors && response.data.errors.length > 0) {
                statusP.innerHTML += \'<br><small>Błędy: \' + response.data.errors.join(\', \') + \'</small>\';
            }
            
            setTimeout(() => {
                progressDiv.style.display = \'none\';
            }, 3000);
        } else {
            progressFill.style.width = \'0%\';
            statusP.textContent = \'Błąd regeneracji: \' + (response.data || \'Nieznany błąd\');
        }
    });
}
';
}

function carni24_get_post_edit_css() {
    return '
.species-meta-box,
.feature-meta-box,
.seo-meta-box {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.species-meta-table,
.feature-meta-table,
.seo-meta-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.species-meta-table th,
.species-meta-table td,
.feature-meta-table th,
.feature-meta-table td,
.seo-meta-table th,
.seo-meta-table td {
    padding: 10px;
    border-bottom: 1px solid #e0e0e0;
    vertical-align: top;
}

.species-meta-table th,
.feature-meta-table th,
.seo-meta-table th {
    width: 180px;
    text-align: left;
    font-weight: 600;
    background: #f9f9f9;
    color: #23282d;
}

.species-meta-section,
.feature-meta-section,
.seo-meta-section {
    margin-bottom: 25px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}

.species-meta-section h4,
.feature-meta-section h4,
.seo-meta-section h4 {
    margin: 0;
    padding: 12px 15px;
    background: linear-gradient(135deg, #e8f5e8, #d4edda);
    border-bottom: 1px solid #c3e6cb;
    color: #155724;
    font-size: 14px;
    font-weight: 600;
}

.species-meta-section .species-meta-table,
.feature-meta-section .feature-meta-table,
.seo-meta-section .seo-meta-table {
    margin: 0;
}

.species-meta-table select,
.species-meta-table input[type="text"],
.species-meta-table input[type="number"],
.species-meta-table textarea,
.feature-meta-table select,
.feature-meta-table input[type="text"],
.feature-meta-table input[type="color"],
.feature-meta-table textarea,
.seo-meta-table input[type="text"],
.seo-meta-table input[type="url"],
.seo-meta-table textarea {
    width: 100%;
    max-width: 400px;
    padding: 6px 10px;
    border: 1px solid #8c8f94;
    border-radius: 3px;
    font-size: 13px;
}

.species-meta-table textarea,
.feature-meta-table textarea,
.seo-meta-table textarea {
    min-height: 60px;
    resize: vertical;
}

.difficulty-indicator {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
    vertical-align: middle;
}

.difficulty-easy { background: #28a745; }
.difficulty-medium { background: #ffc107; }
.difficulty-hard { background: #dc3545; }

.feature-checkbox-group {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 15px;
}

.feature-checkbox-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: normal;
    cursor: pointer;
}

.feature-checkbox-group input[type="checkbox"] {
    margin-right: 8px;
}

.feature-color-preview {
    width: 30px;
    height: 30px;
    border: 2px solid #ddd;
    border-radius: 4px;
    display: inline-block;
    margin-left: 10px;
    vertical-align: middle;
}

.feature-image-preview,
.seo-og-image-preview {
    margin-top: 10px;
    max-width: 200px;
}

.feature-image-preview img,
.seo-og-image-preview img {
    max-width: 100%;
    height: auto;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.seo-char-counter {
    text-align: right;
    font-size: 11px;
    color: #666;
    margin-top: 3px;
}

.seo-char-counter.over-limit {
    color: #d63638;
    font-weight: bold;
}

.post-edit-notice {
    background: #fff;
    border-left: 4px solid #0073aa;
    box-shadow: 0 1px 1px rgba(0,0,0,0.04);
    margin: 5px 15px 2px;
    padding: 1px 12px;
}

.metabox-tabs {
    border-bottom: 1px solid #e0e0e0;
    margin: 0 0 15px 0;
    padding: 0;
}

.metabox-tabs li {
    display: inline-block;
    margin: 0;
    padding: 0;
}

.metabox-tabs a {
    display: block;
    padding: 8px 15px;
    text-decoration: none;
    color: #666;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
}

.metabox-tabs a:hover,
.metabox-tabs a.active {
    color: #0073aa;
    border-bottom-color: #0073aa;
}

.tab-content-meta {
    display: none;
}

.tab-content-meta.active {
    display: block;
}

@media (max-width: 782px) {
    .species-meta-table th,
    .feature-meta-table th,
    .seo-meta-table th {
        width: auto;
        display: block;
        border-bottom: none;
        padding-bottom: 5px;
    }
    
    .species-meta-table td,
    .feature-meta-table td,
    .seo-meta-table td {
        display: block;
        padding-top: 5px;
    }
    
    .species-meta-table,
    .feature-meta-table,
    .seo-meta-table {
        border: none;
    }
    
    .species-meta-table tr,
    .feature-meta-table tr,
    .seo-meta-table tr {
        border-bottom: 1px solid #e0e0e0;
        padding: 10px 0;
        display: block;
    }
    
    .species-meta-table select,
    .species-meta-table input,
    .species-meta-table textarea,
    .feature-meta-table select,
    .feature-meta-table input,
    .feature-meta-table textarea,
    .seo-meta-table input,
    .seo-meta-table textarea {
        max-width: 100%;
    }
}
';
}

function carni24_get_global_admin_css() {
    return '
.carni24-admin-notice {
    border-left: 4px solid #0073aa;
    background: #fff;
    padding: 12px;
    margin: 15px 0;
    box-shadow: 0 1px 1px rgba(0,0,0,0.04);
}

.carni24-admin-notice.notice-success {
    border-left-color: #46b450;
}

.carni24-admin-notice.notice-warning {
    border-left-color: #ffb900;
}

.carni24-admin-notice.notice-error {
    border-left-color: #dc3232;
}

.column-featured_status {
    width: 120px;
}

.column-species_difficulty,
.column-guide_difficulty {
    width: 100px;
}

.column-species_views,
.column-guide_views {
    width: 80px;
    text-align: center;
}

.species-difficulty,
.guide-difficulty {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
}

.species-difficulty.difficulty-easy,
.guide-difficulty.guide-difficulty-beginner {
    background: #d4edda;
    color: #155724;
}

.species-difficulty.difficulty-medium,
.guide-difficulty.guide-difficulty-intermediate {
    background: #fff3cd;
    color: #856404;
}

.species-difficulty.difficulty-hard,
.guide-difficulty.guide-difficulty-advanced {
    background: #f8d7da;
    color: #721c24;
}

.guide-difficulty.guide-difficulty-expert {
    background: #d1ecf1;
    color: #0c5460;
}

.featured-post-row {
    background-color: #fff8e1 !important;
}

.featured-post-row .row-title {
    font-weight: 600;
}

.carni24-dashboard-widget {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 15px;
}

.carni24-dashboard-widget h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #135e96;
    font-size: 16px;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 8px;
}

.carni24-quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin: 15px 0;
}

.carni24-stat-item {
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    padding: 15px;
    text-align: center;
}

.carni24-stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #135e96;
    display: block;
}

.carni24-stat-label {
    font-size: 12px;
    color: #646970;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 5px;
}

.carni24-recent-activity {
    max-height: 300px;
    overflow-y: auto;
}

.carni24-activity-item {
    display: flex;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.carni24-activity-item:last-child {
    border-bottom: none;
}

.carni24-activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #135e96;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    margin-right: 10px;
    flex-shrink: 0;
}

.carni24-activity-content {
    flex-grow: 1;
}

.carni24-activity-title {
    font-weight: 600;
    margin-bottom: 2px;
}

.carni24-activity-meta {
    font-size: 12px;
    color: #646970;
}

.carni24-admin-toolbar {
    background: #135e96;
    color: #fff;
    padding: 10px 15px;
    margin: -20px -20px 20px -20px;
    border-radius: 4px 4px 0 0;
}

.carni24-admin-toolbar h2 {
    margin: 0;
    color: #fff;
    font-size: 18px;
}

.carni24-admin-tabs {
    display: flex;
    border-bottom: 1px solid #c3c4c7;
    margin: 0 0 20px 0;
    padding: 0;
    list-style: none;
}

.carni24-admin-tabs li {
    margin: 0;
}

.carni24-admin-tabs a {
    display: block;
    padding: 12px 20px;
    text-decoration: none;
    color: #646970;
    border-bottom: 3px solid transparent;
    transition: all 0.2s ease;
    font-weight: 500;
}

.carni24-admin-tabs a:hover {
    color: #135e96;
    background: #f6f7f7;
}

.carni24-admin-tabs a.active {
    color: #135e96;
    border-bottom-color: #135e96;
    background: #fff;
}

.carni24-help-text {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 12px;
    margin: 10px 0;
    font-size: 13px;
    color: #495057;
}

.carni24-pro-feature {
    opacity: 0.6;
    pointer-events: none;
    position: relative;
}

.carni24-pro-feature::after {
    content: "PRO";
    position: absolute;
    top: 5px;
    right: 5px;
    background: #dc3232;
    color: #fff;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: bold;
}

.carni24-loading {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #135e96;
    border-radius: 50%;
    animation: carni24-spin 1s linear infinite;
}

@keyframes carni24-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.carni24-success-message {
    background: #edfaef;
    border: 1px solid #68de7c;
    color: #155724;
    padding: 8px 12px;
    border-radius: 4px;
    margin: 10px 0;
}

.carni24-error-message {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
    padding: 8px 12px;
    border-radius: 4px;
    margin: 10px 0;
}

.carni24-button-group {
    display: flex;
    gap: 8px;
    margin: 10px 0;
}

.carni24-button-secondary {
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    color: #2c3338;
    padding: 6px 12px;
    border-radius: 3px;
    text-decoration: none;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.carni24-button-secondary:hover {
    background: #f0f0f1;
    border-color: #8c8f94;
}

.carni24-settings-group {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    margin-bottom: 20px;
}

.carni24-settings-group-header {
    background: #f6f7f7;
    border-bottom: 1px solid #c3c4c7;
    padding: 12px 20px;
    font-weight: 600;
    color: #135e96;
}

.carni24-settings-group-content {
    padding: 20px;
}

.wp-admin .carni24-notice {
    border-left: 4px solid #135e96;
    background: #fff;
    margin: 15px 0;
    padding: 1px 12px;
}

.wp-admin .carni24-notice p {
    margin: 12px 0;
}

@media screen and (max-width: 782px) {
    .carni24-admin-tabs {
        flex-direction: column;
    }
    
    .carni24-admin-tabs a {
        border-bottom: none;
        border-left: 3px solid transparent;
    }
    
    .carni24-admin-tabs a.active {
        border-left-color: #135e96;
        border-bottom-color: transparent;
    }
    
    .carni24-quick-stats {
        grid-template-columns: 1fr;
    }
    
    .carni24-button-group {
        flex-direction: column;
    }
}
';
}

function carni24_inline_admin_css() {
    ?>
    <style>
    <?php echo carni24_get_default_admin_css(); ?>
    </style>
    <?php
}

function carni24_inline_admin_js() {
    ?>
    <script>
    <?php echo carni24_get_default_admin_js(); ?>
    </script>
    <?php
}

function carni24_admin_footer_text($footer_text) {
    $current_screen = get_current_screen();
    
    if (strpos($current_screen->id, 'carni24') !== false) {
        $footer_text = '<span id="footer-thankyou">Motyw <strong>Carni24 v' . CARNI24_VERSION . '</strong> • <a href="https://wordpress.org/" target="_blank">WordPress</a></span>';
    }
    
    return $footer_text;
}
add_filter('admin_footer_text', 'carni24_admin_footer_text');

function carni24_admin_body_class($classes) {
    $classes .= ' carni24-admin';
    
    $current_screen = get_current_screen();
    if (strpos($current_screen->id, 'carni24') !== false) {
        $classes .= ' carni24-admin-page';
    }
    
    return $classes;
}
add_filter('admin_body_class', 'carni24_admin_body_class');

function carni24_remove_admin_color_scheme_picker() {
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
}
add_action('admin_init', 'carni24_remove_admin_color_scheme_picker');

function carni24_custom_admin_favicon() {
    $favicon_url = CARNI24_THEME_URL . '/assets/images/admin-favicon.ico';
    if (file_exists(CARNI24_THEME_PATH . '/assets/images/admin-favicon.ico')) {
        echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '" />';
    }
}
add_action('admin_head', 'carni24_custom_admin_favicon');

function carni24_admin_menu_customization() {
    global $menu, $submenu;
    
    $menu[25][0] = 'Komentarze <span class="awaiting-mod count-0"><span class="pending-count">0</span></span>';
    
    if (isset($menu[25]) && strpos($menu[25][2], 'edit-comments.php') !== false) {
        unset($menu[25]);
    }
}
add_action('admin_menu', 'carni24_admin_menu_customization', 999);

function carni24_admin_post_thumbnail_html($content, $post_id) {
    if (get_post_type($post_id) === 'species' || get_post_type($post_id) === 'guides') {
        $content .= '<p class="description">Zalecany rozmiar: 800x600px. Obraz będzie automatycznie przeskalowany do różnych rozmiarów.</p>';
    }
    
    return $content;
}
add_filter('admin_post_thumbnail_html', 'carni24_admin_post_thumbnail_html', 10, 2);

function carni24_admin_print_styles() {
    ?>
    <style>
    .post-type-species .postbox,
    .post-type-guides .postbox {
        border: 1px solid #c3e6cb;
        border-radius: 4px;
    }
    
    .post-type-species .postbox h2,
    .post-type-guides .postbox h2 {
        background: linear-gradient(135deg, #e8f5e8, #d4edda);
        color: #155724;
        border-bottom: 1px solid #c3e6cb;
    }
    
    #carni24_seo_settings .inside,
    #carni24_feature_settings .inside {
        padding: 0;
    }
    
    .carni24-metabox-tabs {
        border-bottom: 1px solid #e0e0e0;
        margin: 0;
        padding: 0 15px;
        background: #f9f9f9;
    }
    
    .carni24-metabox-tabs li {
        display: inline-block;
        margin: 0;
    }
    
    .carni24-metabox-tabs a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #666;
        border-bottom: 2px solid transparent;
    }
    
    .carni24-metabox-tabs a:hover,
    .carni24-metabox-tabs a.active {
        color: #135e96;
        border-bottom-color: #135e96;
    }
    </style>
    <?php
}
add_action('admin_print_styles', 'carni24_admin_print_styles');
