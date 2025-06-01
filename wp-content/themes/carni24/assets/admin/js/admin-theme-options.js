jQuery(document).ready(function($) {
    
    // === CHARACTER COUNTERS ===
    
    // Navigation content counter
    $('#navigation_content').on('input', function() {
        const length = $(this).val().length;
        $('#nav-content-counter').text(length);
        
        // Change color based on length
        if (length > 180) {
            $('#nav-content-counter').css('color', '#dc3545');
        } else if (length > 150) {
            $('#nav-content-counter').css('color', '#ffc107');
        } else {
            $('#nav-content-counter').css('color', '#28a745');
        }
    }).trigger('input');
    
    // Meta description counter
    $('#default_meta_description').on('input', function() {
        const length = $(this).val().length;
        $('#meta-desc-counter').text(length);
        
        // Change color based on length
        if (length > 160) {
            $('#meta-desc-counter').css('color', '#dc3545');
        } else if (length > 140) {
            $('#meta-desc-counter').css('color', '#ffc107');
        } else {
            $('#meta-desc-counter').css('color', '#28a745');
        }
    }).trigger('input');
    
    // === SEO PREVIEW UPDATES ===
    
    function updateSeoPreview() {
        const siteName = $('#site_name').val() || carni24_admin.fallback_site_name || 'Nazwa witryny';
        const siteDesc = $('#site_description').val() || carni24_admin.fallback_site_description || 'Opis witryny';
        const metaDesc = $('#default_meta_description').val() || siteDesc;
        
        $('#preview-title').text(siteName + ' - ' + siteDesc);
        $('#preview-description').text(metaDesc);
    }
    
    function updateTitlePreview() {
        const siteName = $('#site_name').val() || carni24_admin.fallback_site_name || 'Nazwa witryny';
        const siteDesc = $('#site_description').val() || carni24_admin.fallback_site_description || 'Opis witryny';
        
        let titleText = siteName;
        if (siteDesc) {
            titleText += ' - ' + siteDesc;
        }
        
        $('#title-preview').text(titleText);
    }
    
    // Update previews on input
    $('#site_name, #site_description, #default_meta_description').on('input', updateSeoPreview);
    $('#site_name, #site_description').on('input', updateTitlePreview);
    
    // === MEDIA UPLOADER FOR DEFAULT OG IMAGE ===
    
    window.openDefaultOgImageUploader = function() {
        const mediaUploader = wp.media({
            title: 'Wybierz domyślny obraz OG',
            button: { text: 'Użyj tego obrazu' },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#default_og_image').val(attachment.id);
            
            // Update preview
            const imageHtml = '<img src="' + attachment.sizes.medium.url + '" alt="' + attachment.alt + '">';
            $('#default_og_image_preview').html(imageHtml);
            
            // Show success message
            showNotification('Obraz został wybrany!', 'success');
        });
        
        mediaUploader.open();
    };
    
    window.clearDefaultOgImage = function() {
        $('#default_og_image').val('');
        $('#default_og_image_preview').html(
            '<div class="carni24-no-image">' +
            '<span class="dashicons dashicons-format-image"></span>' +
            '<p>Brak obrazu</p>' +
            '</div>'
        );
        
        showNotification('Obraz został usunięty', 'info');
    };
    
    // === FORM VALIDATION ===
    
    $('.carni24-form').on('submit', function(e) {
        let hasErrors = false;
        
        // Check required fields
        $('.carni24-input[required], .carni24-textarea[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('error');
                hasErrors = true;
            } else {
                $(this).removeClass('error');
            }
        });
        
        // Check meta description length
        const metaDesc = $('#default_meta_description').val();
        if (metaDesc && metaDesc.length > 160) {
            $('#default_meta_description').addClass('warning');
            showNotification('Meta Description jest dłuższy niż 160 znaków', 'warning');
        } else {
            $('#default_meta_description').removeClass('warning');
        }
        
        if (hasErrors) {
            e.preventDefault();
            showNotification('Proszę wypełnić wszystkie wymagane pola', 'error');
            
            // Scroll to first error
            $('html, body').animate({
                scrollTop: $('.error').first().offset().top - 100
            }, 500);
        }
    });
    
    // === TOOLTIPS ===
    
    $('.carni24-help').each(function() {
        $(this).attr('title', $(this).attr('title'));
    });
    
    // === SMOOTH SCROLLING FOR ANCHOR LINKS ===
    
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // === AJAX SEO TEST ===
    
    function runSeoTest() {
        $.ajax({
            url: carni24_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'carni24_check_seo',
                nonce: carni24_admin.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateSeoStatus(response.data);
                }
            },
            error: function() {
                showNotification('Błąd podczas sprawdzania SEO', 'error');
            }
        });
    }
    
    function updateSeoStatus(results) {
        // Update status indicators
        $('.status-value').each(function() {
            const label = $(this).siblings('.status-label').text().toLowerCase();
            
            if (label.includes('nazwa witryny')) {
                $(this).removeClass('ok missing').addClass(results.site_name ? 'ok' : 'missing');
                $(this).text(results.site_name ? '✅ Ustawiona' : '❌ Brak');
            }
            // Add more status updates as needed
        });
    }
    
    // === NOTIFICATION SYSTEM ===
    
    function showNotification(message, type = 'info') {
        const notification = $('<div class="carni24-notification carni24-notification-' + type + '">' + message + '</div>');
        
        // Remove existing notifications
        $('.carni24-notification').remove();
        
        // Add new notification
        $('body').append(notification);
        
        // Animate in
        notification.fadeIn(300);
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // === FORM AUTOSAVE (Optional) ===
    
    let autosaveTimeout;
    
    $('.carni24-input, .carni24-textarea').on('input', function() {
        clearTimeout(autosaveTimeout);
        
        // Show saving indicator
        if (!$('.autosave-indicator').length) {
            $('.carni24-save-section').prepend('<div class="autosave-indicator">Zapisywanie...</div>');
        }
        
        autosaveTimeout = setTimeout(function() {
            // Here you could implement autosave functionality
            $('.autosave-indicator').text('Zapisano').fadeOut(2000, function() {
                $(this).remove();
            });
        }, 2000);
    });
    
    // === KEYBOARD SHORTCUTS ===
    
    $(document).on('keydown', function(e) {
        // Ctrl+S or Cmd+S to save
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
            e.preventDefault();
            $('.carni24-form').submit();
        }
        
        // Escape to clear focus
        if (e.keyCode === 27) {
            $('input, textarea').blur();
        }
    });
    
    // === DYNAMIC HELP SYSTEM ===
    
    $('.carni24-help').hover(
        function() {
            const helpText = $(this).attr('title');
            if (helpText) {
                const tooltip = $('<div class="carni24-tooltip">' + helpText + '</div>');
                $('body').append(tooltip);
                
                const offset = $(this).offset();
                tooltip.css({
                    top: offset.top - tooltip.outerHeight() - 10,
                    left: offset.left - (tooltip.outerWidth() / 2) + ($(this).outerWidth() / 2)
                }).fadeIn(200);
            }
        },
        function() {
            $('.carni24-tooltip').fadeOut(200, function() {
                $(this).remove();
            });
        }
    );
    
    // === FIELD FOCUS ENHANCEMENT ===
    
    $('.carni24-input, .carni24-textarea').on('focus', function() {
        $(this).closest('.carni24-field').addClass('focused');
    }).on('blur', function() {
        $(this).closest('.carni24-field').removeClass('focused');
    });
    
    // === INITIAL SETUP ===
    
    // Add smooth transitions
    $('*').css('transition', 'all 0.2s ease');
    
    // Initialize tooltips
    $('[title]').each(function() {
        $(this).attr('data-original-title', $(this).attr('title'));
    });
    
    console.log('Carni24 Admin JavaScript loaded successfully');
    
});

// === CSS INJECTION FOR NOTIFICATIONS ===

jQuery(document).ready(function() {
    const notificationCSS = `
        <style>
        .carni24-notification {
            position: fixed;
            top: 32px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            z-index: 100000;
            display: none;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .carni24-notification-success {
            background: #28a745;
        }
        
        .carni24-notification-error {
            background: #dc3545;
        }
        
        .carni24-notification-warning {
            background: #ffc107;
            color: #333;
        }
        
        .carni24-notification-info {
            background: #17a2b8;
        }
        
        .carni24-tooltip {
            position: absolute;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            max-width: 200px;
            z-index: 10000;
            display: none;
        }
        
        .carni24-tooltip:after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border: 5px solid transparent;
            border-top-color: #333;
        }
        
        .carni24-field.focused .carni24-label {
            color: #4a7c59;
        }
        
        .carni24-input.error,
        .carni24-textarea.error {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
        
        .carni24-input.warning,
        .carni24-textarea.warning {
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }
        
        .autosave-indicator {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-bottom: 10px;
            font-style: italic;
        }
        </style>
    `;
    
    jQuery('head').append(notificationCSS);
});