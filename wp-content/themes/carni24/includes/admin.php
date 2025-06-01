<?php

// === ADMIN ENQUEUE SCRIPTS ===

// Enqueue media uploader
function enqueue_media_uploader() {
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enqueue_media_uploader');

// Enqueue admin styles and scripts for theme options page
function carni24_admin_enqueue_scripts($hook) {
    // Debug - sprawdź jaki jest hook
    if (current_user_can('manage_options') && isset($_GET['debug_hook'])) {
        echo '<div class="notice notice-info"><p><strong>Debug Hook:</strong> ' . esc_html($hook) . '</p></div>';
    }
    
    // Sprawdź różne możliwe nazwy hook'a
    $theme_options_hooks = array(
        'appearance_page_carni24-theme-options',
        'themes_page_carni24-theme-options',
        'carni24-theme-options'
    );
    

    
    // Enqueue WordPress media uploader
    wp_enqueue_media();
    
    // Sprawdź czy pliki istnieją przed załadowaniem
    $css_file = get_template_directory() . '/assets/admin/css/admin-theme-options.css';
    $js_file = get_template_directory() . '/assets/admin/js/admin-theme-options.js';
    
    // Debug info
    if (current_user_can('manage_options') && isset($_GET['debug_files'])) {
        echo '<div class="notice notice-info">';
        echo '<p><strong>CSS File:</strong> ' . (file_exists($css_file) ? '✅ Exists' : '❌ Missing') . ' - ' . esc_html($css_file) . '</p>';
        echo '<p><strong>JS File:</strong> ' . (file_exists($js_file) ? '✅ Exists' : '❌ Missing') . ' - ' . esc_html($js_file) . '</p>';
        echo '</div>';
    }
    
    // Zawsze używaj inline CSS/JS dla pewności
    add_action('admin_head', 'carni24_inline_admin_css');
    add_action('admin_footer', 'carni24_inline_admin_js');
    
    
    // Pass data to JavaScript
    wp_localize_script('jquery', 'carni24_admin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_admin_nonce'),
        'fallback_site_name' => get_bloginfo('name'),
        'fallback_site_description' => get_bloginfo('description'),
        'hook' => $hook
    ));
}
add_action('admin_enqueue_scripts', 'carni24_admin_enqueue_scripts');

// === DIRECTORY CREATION ===

// Stwórz niezbędne foldery
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
    
    // Stwórz podstawowy plik CSS jeśli nie istnieje
    $css_file = $theme_dir . '/assets/admin/css/admin-theme-options.css';
    if (!file_exists($css_file)) {
        $basic_css = '/* Carni24 Admin Styles - podstawowa wersja */
.carni24-theme-options { background: #f9f9f9; margin: 0 -20px; padding: 0; }
.carni24-header { background: linear-gradient(135deg, #4a7c59 0%, #2c5530 100%); color: white; margin: 0 -20px 20px -22px; padding: 30px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.carni24-header h1 { color: white; font-size: 32px; margin: 0; }
.carni24-grid { display: grid; grid-template-columns: 1fr 300px; gap: 30px; max-width: 1200px; margin: 0 auto; padding: 0 40px 40px; }
.carni24-main-content { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.carni24-sidebar { display: flex; flex-direction: column; gap: 20px; }
.carni24-section { border-bottom: 1px solid #f0f0f0; }
.carni24-section-header { padding: 30px 40px 20px; background: #fafafa; border-bottom: 1px solid #eee; }
.carni24-section-content { padding: 30px 40px; }
.carni24-field { margin-bottom: 25px; }
.carni24-label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
.carni24-input, .carni24-textarea { width: 100%; padding: 12px 16px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 14px; }
.carni24-btn { display: inline-flex; align-items: center; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; background: #4a7c59; color: white; }
.carni24-btn:hover { background: #3d6749; color: white; }
.carni24-btn-large { padding: 15px 30px; font-size: 16px; }
.carni24-widget { background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
.carni24-widget-header { background: #fafafa; padding: 20px; border-bottom: 1px solid #eee; }
.carni24-widget-content { padding: 20px; }
.carni24-save-section { background: #f8f9fa; padding: 30px 40px; border-top: 1px solid #e1e5e9; text-align: center; }
.status-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
.status-value.ok { color: #28a745; }
.status-value.missing { color: #dc3545; }
.carni24-media-upload { border: 2px dashed #e1e5e9; border-radius: 6px; padding: 20px; text-align: center; }
.carni24-no-image { color: #999; padding: 40px 20px; }
@media (max-width: 1024px) { .carni24-grid { grid-template-columns: 1fr; } }';
        
        file_put_contents($css_file, $basic_css);
    }
    
    // Stwórz podstawowy plik JS jeśli nie istnieje
    $js_file = $theme_dir . '/assets/admin/js/admin-theme-options.js';
    if (!file_exists($js_file)) {
        $basic_js = 'jQuery(document).ready(function($) {
    console.log("Carni24 Admin JS loaded");
    
    // Media uploader dla OG image
    window.openDefaultOgImageUploader = function() {
        var mediaUploader = wp.media({
            title: "Wybierz domyślny obraz OG",
            button: { text: "Użyj tego obrazu" },
            multiple: false,
            library: { type: "image" }
        });
        
        mediaUploader.on("select", function() {
            var attachment = mediaUploader.state().get("selection").first().toJSON();
            $("#default_og_image").val(attachment.id);
            
            var imageHtml = "<img src=\"" + (attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url) + "\" alt=\"" + attachment.alt + "\" style=\"max-width: 100%; height: auto;\">";
            $("#default_og_image_preview").html(imageHtml);
        });
        
        mediaUploader.open();
    };
    
    window.clearDefaultOgImage = function() {
        $("#default_og_image").val("");
        $("#default_og_image_preview").html("<div class=\"carni24-no-image\"><span class=\"dashicons dashicons-format-image\"></span><p>Brak obrazu</p></div>");
    };
});';
        
        file_put_contents($js_file, $basic_js);
    }
}

// Uruchom przy aktywacji motywu
add_action('after_switch_theme', 'carni24_create_admin_directories');

// Inline CSS fallback
function carni24_inline_admin_css() {
    ?>
    <style>
    /* Carni24 Admin Theme Options - Inline Styles */
    .carni24-theme-options { 
        background: #f9f9f9; 
        margin: 0 -20px; 
        padding: 0; 
    }
    
    .carni24-header { 
        background: linear-gradient(135deg, #4a7c59 0%, #2c5530 100%); 
        color: white; 
        margin: 0 -20px 20px -22px; 
        padding: 30px 40px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
    }
    
    .carni24-header-content {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .carni24-logo {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .carni24-icon {
        font-size: 32px;
        margin-right: 15px;
    }
    
    .carni24-header h1 { 
        color: white; 
        font-size: 32px; 
        margin: 0;
        font-weight: 700;
    }
    
    .carni24-logo .version {
        font-size: 14px;
        font-weight: 400;
        opacity: 0.8;
        margin-left: 10px;
    }
    
    .carni24-subtitle {
        margin: 0;
        font-size: 16px;
        opacity: 0.9;
    }
    
    .carni24-grid { 
        display: grid; 
        grid-template-columns: 1fr 300px; 
        gap: 30px; 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 0 40px 40px; 
    }
    
    .carni24-main-content { 
        background: white; 
        border-radius: 8px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        overflow: hidden;
    }
    
    .carni24-sidebar { 
        display: flex; 
        flex-direction: column; 
        gap: 20px; 
    }
    
    .carni24-section { 
        border-bottom: 1px solid #f0f0f0; 
    }
    
    .carni24-section:last-child {
        border-bottom: none;
    }
    
    .carni24-section-header { 
        padding: 30px 40px 20px; 
        background: #fafafa; 
        border-bottom: 1px solid #eee; 
    }
    
    .carni24-section-header h2 {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 600;
        color: #2c5530;
        display: flex;
        align-items: center;
    }
    
    .carni24-section-header h2 .icon {
        margin-right: 10px;
        font-size: 20px;
    }
    
    .carni24-section-header p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    
    .carni24-section-content { 
        padding: 30px 40px; 
    }
    
    .carni24-field { 
        margin-bottom: 25px; 
    }
    
    .carni24-field:last-child {
        margin-bottom: 0;
    }
    
    .carni24-label { 
        display: flex;
        align-items: center;
        margin-bottom: 8px; 
        font-weight: 600; 
        color: #333; 
    }
    
    .carni24-help {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        background: #4a7c59;
        color: white;
        border-radius: 50%;
        font-size: 12px;
        font-weight: bold;
        margin-left: 8px;
        cursor: help;
    }
    
    .carni24-input, .carni24-textarea { 
        width: 100%; 
        padding: 12px 16px; 
        border: 2px solid #e1e5e9; 
        border-radius: 6px; 
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .carni24-input:focus,
    .carni24-textarea:focus {
        outline: none;
        border-color: #4a7c59;
        box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.1);
    }
    
    .carni24-char-counter {
        text-align: right;
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }
    
    .carni24-btn { 
        display: inline-flex; 
        align-items: center; 
        padding: 10px 20px; 
        border: none; 
        border-radius: 6px; 
        font-size: 14px; 
        font-weight: 500; 
        text-decoration: none; 
        cursor: pointer; 
        background: #4a7c59; 
        color: white;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .carni24-btn:hover { 
        background: #3d6749; 
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .carni24-btn .dashicons {
        margin-right: 8px;
        font-size: 16px;
    }
    
    .carni24-btn-large { 
        padding: 15px 30px; 
        font-size: 16px; 
    }
    
    .carni24-btn-secondary {
        background: #6c757d;
    }
    
    .carni24-btn-secondary:hover {
        background: #5a6268;
        color: white;
    }
    
    .carni24-btn-success {
        background: #28a745;
    }
    
    .carni24-btn-success:hover {
        background: #218838;
        color: white;
    }
    
    .carni24-btn-block { 
        width: 100%; 
        justify-content: center; 
        margin-bottom: 10px; 
    }
    
    .carni24-widget { 
        background: white; 
        border-radius: 8px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        margin-bottom: 20px;
        overflow: hidden;
    }
    
    .carni24-widget-header { 
        background: #fafafa; 
        padding: 20px; 
        border-bottom: 1px solid #eee; 
    }
    
    .carni24-widget-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #2c5530;
        display: flex;
        align-items: center;
    }
    
    .carni24-widget-header h3 .icon {
        margin-right: 8px;
    }
    
    .carni24-widget-content { 
        padding: 20px; 
    }
    
    .carni24-save-section { 
        background: #f8f9fa; 
        padding: 30px 40px; 
        border-top: 1px solid #e1e5e9; 
        text-align: center; 
    }
    
    .carni24-info-box {
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .carni24-info-box strong {
        color: #1565c0;
    }
    
    .carni24-info-box ol {
        margin: 8px 0 0 20px;
        color: #1976d2;
    }
    
    .status-item { 
        display: flex; 
        justify-content: space-between; 
        align-items: center;
        padding: 8px 0; 
        border-bottom: 1px solid #f0f0f0; 
    }
    
    .status-item:last-child {
        border-bottom: none;
    }
    
    .status-label {
        font-weight: 500;
    }
    
    .status-value.ok { 
        color: #28a745; 
    }
    
    .status-value.missing { 
        color: #dc3545; 
    }
    
    .carni24-media-upload { 
        border: 2px dashed #e1e5e9; 
        border-radius: 6px; 
        padding: 20px; 
        text-align: center;
        transition: border-color 0.2s;
    }
    
    .carni24-media-upload:hover {
        border-color: #4a7c59;
    }
    
    .carni24-media-preview {
        margin-bottom: 15px;
    }
    
    .carni24-media-preview img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
    }
    
    .carni24-no-image { 
        color: #999; 
        padding: 40px 20px; 
    }
    
    .carni24-no-image .dashicons {
        font-size: 48px;
        opacity: 0.5;
    }
    
    .carni24-no-image p {
        margin: 10px 0 0;
        font-size: 14px;
    }
    
    .carni24-media-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    
    /* SEO Preview */
    .seo-preview-box, .title-preview-box {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        background: #f8f9fa;
        margin: 10px 0;
        font-family: arial, sans-serif;
    }
    
    .seo-title {
        color: #1a0dab;
        font-size: 18px;
        line-height: 1.3;
        margin-bottom: 5px;
        cursor: pointer;
    }
    
    .seo-title:hover {
        text-decoration: underline;
    }
    
    .seo-url {
        color: #006621;
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    .seo-description {
        color: #545454;
        font-size: 14px;
        line-height: 1.4;
    }
    
    .title-preview {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 16px;
        font-weight: 500;
        color: #2c5530;
        background: white;
        padding: 10px;
        border-radius: 4px;
        border-left: 4px solid #4a7c59;
    }
    
    /* Thumbnails */
    .image-sizes-list .size-item {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .image-sizes-list .size-item:last-child {
        border-bottom: none;
    }
    
    .crop-indicator {
        background: #ffc107;
        color: #333;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: bold;
    }
    
    .thumbnail-actions {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #f0f0f0;
    }
    
    .thumbnail-help {
        margin-top: 15px;
        background: #e3f2fd;
        border: 1px solid #bbdefb;
        border-radius: 4px;
        padding: 10px;
    }
    
    #thumbnail-results { 
        background: #f8f9fa; 
        border: 1px solid #e9ecef; 
        border-radius: 4px; 
        padding: 10px; 
        font-size: 12px; 
        max-height: 200px; 
        overflow-y: auto; 
        display: none; 
        margin-top: 15px;
    }
    
    #thumbnail-results.show { 
        display: block; 
    }
    
    .thumbnail-result-item {
        padding: 5px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .thumbnail-result-item:last-child {
        border-bottom: none;
    }
    
    .thumbnail-result-success { 
        color: #28a745; 
    }
    
    .thumbnail-result-error { 
        color: #dc3545; 
    }
    
    /* Quick links */
    .carni24-quick-links .quick-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        text-decoration: none;
        color: #333;
        border-bottom: 1px solid #f0f0f0;
        transition: color 0.2s;
    }
    
    .carni24-quick-links .quick-link:hover {
        color: #4a7c59;
    }
    
    .carni24-quick-links .quick-link:last-child {
        border-bottom: none;
    }
    
    .carni24-quick-links .quick-link .dashicons {
        margin-right: 10px;
        color: #4a7c59;
    }
    
    .carni24-quick-links .count {
        background: #4a7c59;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    /* Features */
    .carni24-features .feature-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
        font-size: 14px;
    }
    
    .carni24-features .feature-icon {
        margin-right: 10px;
        font-size: 16px;
    }
    
    /* Theme info */
    .carni24-theme-info .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .carni24-theme-info .info-item:last-child {
        border-bottom: none;
    }
    
    /* Responsive */
    @media (max-width: 1024px) { 
        .carni24-grid { 
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 0 20px 20px;
        }
        
        .carni24-header {
            margin: 0 -20px 20px -20px;
            padding: 20px;
        }
        
        .carni24-section-header,
        .carni24-section-content,
        .carni24-save-section {
            padding: 20px;
        }
        
        .carni24-logo h1 {
            font-size: 24px;
        }
        
        .carni24-media-buttons {
            flex-direction: column;
        }
    }
    
    @media (max-width: 768px) {
        .carni24-header {
            text-align: center;
        }
        
        .carni24-logo {
            justify-content: center;
        }
        
        .carni24-btn-large {
            padding: 12px 20px;
            font-size: 14px;
        }
    }
    </style>
    <?php
}

// Inline JS fallback
function carni24_inline_admin_js() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Media uploader dla OG image
        window.openDefaultOgImageUploader = function() {
            var mediaUploader = wp.media({
                title: 'Wybierz domyślny obraz OG',
                button: { text: 'Użyj tego obrazu' },
                multiple: false,
                library: { type: 'image' }
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#default_og_image').val(attachment.id);
                
                var imageHtml = '<img src="' + (attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url) + '" alt="' + attachment.alt + '" style="max-width: 100%; height: auto;">';
                $('#default_og_image_preview').html(imageHtml);
            });
            
            mediaUploader.open();
        };
        
        window.clearDefaultOgImage = function() {
            $('#default_og_image').val('');
            $('#default_og_image_preview').html('<div class="carni24-no-image"><span class="dashicons dashicons-format-image"></span><p>Brak obrazu</p></div>');
        };
        
        // Liczniki znaków
        $('#navigation_content').on('input', function() {
            $('#nav-content-counter').text($(this).val().length);
        }).trigger('input');
        
        $('#default_meta_description').on('input', function() {
            $('#meta-desc-counter').text($(this).val().length);
        }).trigger('input');
        
        // Update podglądów SEO
        function updatePreviews() {
            var siteName = $('#site_name').val() || 'Nazwa witryny';
            var siteDesc = $('#site_description').val() || 'Opis witryny';
            var metaDesc = $('#default_meta_description').val() || siteDesc;
            
            $('#preview-title').text(siteName + ' - ' + siteDesc);
            $('#preview-description').text(metaDesc);
            $('#title-preview').text(siteName + (siteDesc ? ' - ' + siteDesc : ''));
        }
        
        $('#site_name, #site_description, #default_meta_description').on('input', updatePreviews);
        
        // Funkcje miniaturek
        window.carni24CheckThumbnails = function() {
            $('#thumbnail-results').html('<div>Sprawdzanie miniaturek...</div>').addClass('show');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'carni24_check_thumbnails',
                    nonce: '<?= wp_create_nonce('carni24_admin_nonce') ?>'
                },
                success: function(response) {
                    if (response.success) {
                        var html = '<strong>Wyniki sprawdzenia:</strong><br><br>';
                        
                        response.data.forEach(function(item) {
                            html += '<div class="thumbnail-result-item">';
                            html += '<strong>' + item.title + '</strong> (ID: ' + item.id + ')<br>';
                            html += 'Plik źródłowy: ' + (item.file_exists ? '✅ OK' : '❌ Brak') + '<br>';
                            html += 'Metadata: ' + (item.has_metadata ? '✅ OK' : '❌ Brak') + '<br>';
                            
                            if (Object.keys(item.sizes).length > 0) {
                                html += 'Miniaturki: ';
                                var sizeResults = [];
                                for (var size in item.sizes) {
                                    sizeResults.push(size + ': ' + (item.sizes[size] ? '✅' : '❌'));
                                }
                                html += sizeResults.join(', ');
                            } else {
                                html += 'Brak miniaturek';
                            }
                            
                            html += '</div>';
                        });
                        
                        $('#thumbnail-results').html(html);
                    } else {
                        $('#thumbnail-results').html('<div class="thumbnail-result-error">Błąd: ' + (response.data || 'Nieznany błąd') + '</div>');
                    }
                },
                error: function() {
                    $('#thumbnail-results').html('<div class="thumbnail-result-error">Błąd komunikacji z serwerem</div>');
                }
            });
        };
        
        window.carni24ForceRegenerateThumbnails = function() {
            if (!confirm('Czy na pewno chcesz wymusić regenerację miniaturek dla 10 ostatnich obrazów? To może zająć kilka minut.')) {
                return;
            }
            
            $('#thumbnail-results').html('<div>Regenerowanie miniaturek... To może potrwać kilka minut.</div>').addClass('show');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                timeout: 300000, // 5 minut
                data: {
                    action: 'carni24_force_regenerate_thumbnails',
                    nonce: '<?= wp_create_nonce('carni24_admin_nonce') ?>'
                },
                success: function(response) {
                    if (response.success) {
                        var html = '<strong>Wyniki regeneracji:</strong><br><br>';
                        
                        response.data.forEach(function(item) {
                            html += '<div class="thumbnail-result-item ' + 
                                   (item.status === 'success' ? 'thumbnail-result-success' : 'thumbnail-result-error') + '">';
                            html += '<strong>' + item.title + '</strong> (ID: ' + item.id + ')<br>';
                            html += item.message;
                            html += '</div>';
                        });
                        
                        $('#thumbnail-results').html(html);
                    } else {
                        $('#thumbnail-results').html('<div class="thumbnail-result-error">Błąd: ' + (response.data || 'Nieznany błąd') + '</div>');
                    }
                },
                error: function() {
                    $('#thumbnail-results').html('<div class="thumbnail-result-error">Błąd lub timeout - sprawdź logi serwera</div>');
                }
            });
        };
    });
    </script>
    <?php
}

// === ADMIN NOTICES ===

// Enhanced notice styles for all admin pages
function carni24_admin_notice_styles() {
    ?>
    <style>
    .notice.notice-success .notice-content {
        display: flex;
        align-items: center;
        padding: 5px 0;
    }
    .notice.notice-success .notice-icon {
        margin-right: 10px;
        font-size: 16px;
    }
    .notice.notice-success .notice-text {
        flex: 1;
    }
    </style>
    <?php
}
add_action('admin_head', 'carni24_admin_notice_styles');

// Dodaj powiadomienie w panelu admina jeśli SEO nie jest skonfigurowane
function carni24_seo_admin_notice() {
    $screen = get_current_screen();
    
    // Pokaż tylko na dashboard i stronie ustawień motywu
    if ($screen && ($screen->id === 'dashboard' || $screen->id === 'appearance_page_carni24-theme-options')) {
        $test_results = carni24_quick_seo_test();
        
        $missing_seo = array();
        if (!$test_results['site_name']) $missing_seo[] = 'Nazwa witryny';
        if (!$test_results['default_meta_description']) $missing_seo[] = 'Meta Description';
        if (!$test_results['default_og_image']) $missing_seo[] = 'Obraz OG';
        
        if (!empty($missing_seo) && $screen->id === 'dashboard') {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong>Carni24 SEO:</strong> Brakuje niektórych ustawień SEO: <?= implode(', ', $missing_seo) ?>. 
                    <a href="<?= admin_url('themes.php?page=carni24-theme-options#seo-settings') ?>">Skonfiguruj teraz</a>
                </p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'carni24_seo_admin_notice');

// === DASHBOARD WIDGETS ===

function carni24_theme_dashboard_widget() {
    wp_add_dashboard_widget(
        'carni24_theme_widget',
        'Motyw Carni24',
        'carni24_theme_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'carni24_theme_dashboard_widget');

function carni24_theme_dashboard_widget_content() {
    echo '<p>Witaj w panelu administracyjnym motywu Carni24!</p>';
    echo '<p><strong>Szybkie linki:</strong></p>';
    echo '<ul>';
    echo '<li><a href="' . admin_url('themes.php?page=carni24-theme-options') . '">Ustawienia motywu</a></li>';
    echo '<li><a href="' . admin_url('edit.php?post_type=species') . '">Gatunki (' . wp_count_posts('species')->publish . ')</a></li>';
    echo '<li><a href="' . admin_url('options-general.php?page=carni24-sitemap') . '">Mapa strony</a></li>';
    echo '</ul>';
    
    $last_species = get_posts(array('post_type' => 'species', 'numberposts' => 1));
    if (!empty($last_species)) {
        echo '<p><strong>Ostatnio dodany gatunek:</strong><br>';
        echo '<a href="' . admin_url('post.php?post=' . $last_species[0]->ID . '&action=edit') . '">' . $last_species[0]->post_title . '</a></p>';
    }
}

// === THUMBNAIL FUNCTIONS ===

// AJAX - sprawdź miniaturki ostatnich obrazów
function carni24_ajax_check_thumbnails() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'numberposts' => 10,
        'post_status' => 'any'
    ));
    
    $results = array();
    
    foreach ($attachments as $attachment) {
        $metadata = wp_get_attachment_metadata($attachment->ID);
        $file_path = get_attached_file($attachment->ID);
        
        $result = array(
            'id' => $attachment->ID,
            'title' => $attachment->post_title,
            'file_exists' => file_exists($file_path),
            'has_metadata' => !empty($metadata),
            'sizes' => array()
        );
        
        if (!empty($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size_name => $size_data) {
                $size_file = dirname($file_path) . '/' . $size_data['file'];
                $result['sizes'][$size_name] = file_exists($size_file);
            }
        }
        
        $results[] = $result;
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_carni24_check_thumbnails', 'carni24_ajax_check_thumbnails');

// AJAX - wymuś regenerację miniaturek
function carni24_ajax_force_regenerate_thumbnails() {
    check_ajax_referer('carni24_admin_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_die('Brak uprawnień');
    }
    
    // Zwiększ limity czasu i pamięci
    set_time_limit(300);
    ini_set('memory_limit', '512M');
    
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'numberposts' => 10,
        'post_status' => 'any'
    ));
    
    $results = array();
    
    foreach ($attachments as $attachment) {
        $file_path = get_attached_file($attachment->ID);
        
        if (!file_exists($file_path)) {
            $results[] = array(
                'id' => $attachment->ID,
                'title' => $attachment->post_title,
                'status' => 'error',
                'message' => 'Plik źródłowy nie istnieje'
            );
            continue;
        }
        
        // Usuń stare miniaturki
        $metadata = wp_get_attachment_metadata($attachment->ID);
        if (!empty($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size_data) {
                $size_file = dirname($file_path) . '/' . $size_data['file'];
                if (file_exists($size_file)) {
                    unlink($size_file);
                }
            }
        }
        
        // Regeneruj miniaturki
        $new_metadata = wp_generate_attachment_metadata($attachment->ID, $file_path);
        
        if (!empty($new_metadata)) {
            wp_update_attachment_metadata($attachment->ID, $new_metadata);
            
            $generated_count = count($new_metadata['sizes'] ?? array());
            
            $results[] = array(
                'id' => $attachment->ID,
                'title' => $attachment->post_title,
                'status' => 'success',
                'message' => "Wygenerowano {$generated_count} miniaturek"
            );
        } else {
            $results[] = array(
                'id' => $attachment->ID,
                'title' => $attachment->post_title,
                'status' => 'error',
                'message' => 'Błąd podczas generowania miniaturek'
            );
        }
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_carni24_force_regenerate_thumbnails', 'carni24_ajax_force_regenerate_thumbnails');

// Dodaj informacje o miniaturkach do Media Library
function carni24_attachment_fields_to_edit($fields, $post) {
    if (!wp_attachment_is_image($post->ID)) {
        return $fields;
    }
    
    $metadata = wp_get_attachment_metadata($post->ID);
    $file_path = get_attached_file($post->ID);
    
    $info = '<strong>Diagnostyka miniaturek:</strong><br>';
    $info .= 'Plik źródłowy: ' . (file_exists($file_path) ? '✅ OK' : '❌ Brak') . '<br>';
    
    if (!empty($metadata['sizes'])) {
        $info .= 'Miniaturki:<br>';
        foreach ($metadata['sizes'] as $size_name => $size_data) {
            $size_file = dirname($file_path) . '/' . $size_data['file'];
            $status = file_exists($size_file) ? '✅' : '❌';
            $info .= "- {$size_name}: {$status} ({$size_data['width']}×{$size_data['height']})<br>";
        }
    } else {
        $info .= 'Brak miniaturek<br>';
    }
    
    $fields['carni24_thumbnail_info'] = array(
        'label' => 'Carni24 Info',
        'input' => 'html',
        'html' => $info
    );
    
    return $fields;
}
add_filter('attachment_fields_to_edit', 'carni24_attachment_fields_to_edit', 10, 2);