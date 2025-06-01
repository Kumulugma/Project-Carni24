/**
 * Carni24 Admin Theme Options JavaScript
 */

jQuery(document).ready(function($) {
    
    // Character counters
    function setupCharCounters() {
        // Navigation content counter
        const navContent = $('#navigation_content');
        const navCounter = $('#nav-content-counter');
        const maxNavLength = 200;
        
        function updateNavCounter() {
            const length = navContent.val().length;
            navCounter.text(length);
            
            if (length > maxNavLength * 0.9) {
                navCounter.parent().addClass('warning');
            } else {
                navCounter.parent().removeClass('warning');
            }
            
            if (length > maxNavLength) {
                navCounter.parent().addClass('danger');
            } else {
                navCounter.parent().removeClass('danger');
            }
        }
        
        navContent.on('input', updateNavCounter);
        updateNavCounter();
        
        // Meta description counter
        const metaDesc = $('#default_meta_description');
        const metaCounter = $('#meta-desc-counter');
        const maxMetaLength = 160;
        
        function updateMetaCounter() {
            const length = metaDesc.val().length;
            metaCounter.text(length);
            
            if (length > maxMetaLength * 0.9) {
                metaCounter.parent().addClass('warning');
            } else {
                metaCounter.parent().removeClass('warning');
            }
            
            if (length > maxMetaLength) {
                metaCounter.parent().addClass('danger');
            } else {
                metaCounter.parent().removeClass('danger');
            }
        }
        
        metaDesc.on('input', updateMetaCounter);
        updateMetaCounter();
    }
    
    // Media uploader for default OG image
    window.openDefaultOgImageUploader = function() {
        const mediaUploader = wp.media({
            title: 'Wybierz domyślny obraz OG',
            button: {
                text: 'Użyj tego obrazu'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#default_og_image').val(attachment.id);
            
            const previewHtml = `<img src="${attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url}" alt="${attachment.alt}">`;
            $('#default_og_image_preview').html(previewHtml);
            
            // Add success animation
            $('#default_og_image_preview').addClass('updated');
            setTimeout(() => {
                $('#default_og_image_preview').removeClass('updated');
            }, 1000);
        });
        
        mediaUploader.open();
    };
    
    // Clear default OG image
    window.clearDefaultOgImage = function() {
        $('#default_og_image').val('');
        $('#default_og_image_preview').html(`
            <div class="carni24-no-image">
                <span class="dashicons dashicons-format-image"></span>
                <p>Brak obrazu</p>
            </div>
        `);
    };
    
    // Tooltips for help icons
    function setupTooltips() {
        $('.carni24-help').each(function() {
            const $this = $(this);
            const title = $this.attr('title');
            if (title) {
                $this.attr('data-tooltip', title);
                $this.removeAttr('title');
            }
        });
    }
    
    // Form validation
    function setupFormValidation() {
        $('.carni24-form').on('submit', function(e) {
            let isValid = true;
            const form = $(this);
            
            // Clear previous errors
            $('.carni24-field-error').removeClass('carni24-field-error');
            $('.carni24-error-message').remove();
            
            // Validate required fields
            form.find('[required]').each(function() {
                const field = $(this);
                if (!field.val().trim()) {
                    field.closest('.carni24-field').addClass('carni24-field-error');
                    field.after('<div class="carni24-error-message">To pole jest wymagane</div>');
                    isValid = false;
                }
            });
            
            // Validate URL fields
            form.find('input[type="url"]').each(function() {
                const field = $(this);
                const value = field.val().trim();
                if (value && !isValidUrl(value)) {
                    field.closest('.carni24-field').addClass('carni24-field-error');
                    field.after('<div class="carni24-error-message">Wprowadź prawidłowy URL</div>');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = $('.carni24-field-error').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                }
                
                // Show error notification
                showNotification('Proszę poprawić błędy w formularzu', 'error');
            } else {
                // Show loading state
                const submitBtn = form.find('[type="submit"]');
                submitBtn.addClass('carni24-loading');
                submitBtn.prop('disabled', true);
                
                // Show loading notification
                showNotification('Zapisywanie ustawień...', 'info');
            }
        });
    }
    
    // URL validation helper
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
    
    // Notification system
    function showNotification(message, type = 'success') {
        const notification = $(`
            <div class="notice notice-${type} is-dismissible carni24-notification">
                <p>${message}</p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Zamknij</span>
                </button>
            </div>
        `);
        
        $('.carni24-theme-options').prepend(notification);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            notification.fadeOut(() => {
                notification.remove();
            });
        }, 5000);
        
        // Manual dismiss
        notification.find('.notice-dismiss').on('click', function() {
            notification.fadeOut(() => {
                notification.remove();
            });
        });
    }
    
    // Smooth scrolling for anchor links
    function setupSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $($(this).attr('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
    }
    
    // Auto-save functionality (optional)
    function setupAutoSave() {
        let autoSaveTimeout;
        
        $('.carni24-input, .carni24-textarea').on('input', function() {
            clearTimeout(autoSaveTimeout);
            
            autoSaveTimeout = setTimeout(() => {
                if (typeof carni24_admin !== 'undefined') {
                    // Auto-save logic here if needed
                    console.log('Auto-save triggered');
                }
            }, 5000);
        });
    }
    
    // Enhanced media preview animations
    function setupMediaAnimations() {
        $('.carni24-media-preview img').on('load', function() {
            $(this).hide().fadeIn(300);
        });
    }
    
    // Keyboard shortcuts
    function setupKeyboardShortcuts() {
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
                e.preventDefault();
                $('.carni24-form').submit();
                return false;
            }
        });
    }
    
    // Tab navigation for accessibility
    function setupTabNavigation() {
        $('.carni24-section-header').each(function(index) {
            $(this).attr('tabindex', index + 1);
        });
        
        $('.carni24-section-header').on('keydown', function(e) {
            if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                e.preventDefault();
                $(this).click();
            }
        });
    }
    
    // Section collapse/expand (optional enhancement)
    function setupSectionToggle() {
        $('.carni24-section-header').css('cursor', 'pointer');
        
        $('.carni24-section-header').on('click', function() {
            const content = $(this).next('.carni24-section-content');
            const section = $(this).closest('.carni24-section');
            
            content.slideToggle(300);
            section.toggleClass('collapsed');
        });
    }
    
    // Real-time field validation
    function setupRealTimeValidation() {
        // Email validation
        $('input[type="email"]').on('blur', function() {
            const email = $(this).val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                $(this).addClass('invalid');
                showFieldError($(this), 'Wprowadź prawidłowy adres email');
            } else {
                $(this).removeClass('invalid');
                clearFieldError($(this));
            }
        });
        
        // URL validation
        $('input[type="url"]').on('blur', function() {
            const url = $(this).val();
            
            if (url && !isValidUrl(url)) {
                $(this).addClass('invalid');
                showFieldError($(this), 'Wprowadź prawidłowy URL');
            } else {
                $(this).removeClass('invalid');
                clearFieldError($(this));
            }
        });
    }
    
    function showFieldError(field, message) {
        clearFieldError(field);
        field.after(`<div class="carni24-field-error-inline">${message}</div>`);
    }
    
    function clearFieldError(field) {
        field.next('.carni24-field-error-inline').remove();
    }
    
    // Initialize all functions
    function init() {
        setupCharCounters();
        setupTooltips();
        setupFormValidation();
        setupSmoothScrolling();
        setupAutoSave();
        setupMediaAnimations();
        setupKeyboardShortcuts();
        setupTabNavigation();
        setupRealTimeValidation();
        
        // Optional: Setup section toggle
        // setupSectionToggle();
        
        console.log('Carni24 Admin Theme Options initialized');
    }
    
    // Initialize when document is ready
    init();
    
    // Re-initialize after AJAX requests
    $(document).ajaxComplete(function() {
        init();
    });
});

// Additional CSS for field validation
const additionalCSS = `
<style>
.carni24-field-error .carni24-input,
.carni24-field-error .carni24-textarea {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
}

.carni24-error-message,
.carni24-field-error-inline {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.carni24-input.invalid,
.carni24-textarea.invalid {
    border-color: #dc3545 !important;
}

.carni24-notification {
    margin-bottom: 20px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.carni24-section.collapsed .carni24-section-content {
    display: none;
}

.carni24-section.collapsed .carni24-section-header::after {
    content: ' [Zwinięte]';
    font-size: 12px;
    color: #6c757d;
}

.updated {
    animation: pulse 0.5s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
`;

// Inject additional CSS
$('head').append(additionalCSS);