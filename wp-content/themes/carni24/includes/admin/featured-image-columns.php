<?php
/**
 * Dodaje kolumnę z obrazkami wyróżniającymi dla posts i pages
 * Plik: wp-content/themes/carni24/includes/admin/featured-image-columns.php
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dodaje kolumnę obrazka wyróżniającego dla posts i pages
 */
function carni24_add_featured_image_column($columns) {
    // Sprawdź aktualny typ wpisu
    global $typenow;
    
    // Dodaj kolumnę tylko dla posts i pages
    if (!in_array($typenow, ['post', 'page'])) {
        return $columns;
    }
    
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        // Dodaj checkbox na początku (jeśli istnieje)
        if ($key === 'cb') {
            $new_columns[$key] = $value;
            // Dodaj kolumnę obrazka zaraz po checkbox
            $new_columns['featured_image'] = '🖼️ Obrazek';
        } else {
            $new_columns[$key] = $value;
        }
    }
    
    // Fallback - jeśli nie ma checkbox, dodaj na początku
    if (!isset($new_columns['featured_image'])) {
        $new_columns = array_merge(
            ['featured_image' => '🖼️ Obrazek'],
            $new_columns
        );
    }
    
    return $new_columns;
}

/**
 * Wypełnia kolumnę obrazka wyróżniającego
 */
function carni24_fill_featured_image_column($column, $post_id) {
    if ($column === 'featured_image') {
        $thumbnail_id = get_post_thumbnail_id($post_id);
        
        if ($thumbnail_id) {
            $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'thumbnail');
            $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
            $post_title = get_the_title($post_id);
            
            if ($thumbnail_url) {
                echo '<div class="admin-thumbnail-container" style="display: flex; align-items: center;">';
                echo '<img src="' . esc_url($thumbnail_url) . '" ';
                echo 'alt="' . esc_attr($thumbnail_alt ?: $post_title) . '" ';
                echo 'class="admin-thumbnail" ';
                echo 'style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; cursor: pointer;" ';
                echo 'title="Kliknij aby powiększyć: ' . esc_attr($post_title) . '" ';
                echo 'onclick="carni24ShowImagePreview(\'' . esc_url(wp_get_attachment_image_url($thumbnail_id, 'medium')) . '\', \'' . esc_js($post_title) . '\')" />';
                echo '</div>';
            }
        } else {
            echo '<div class="admin-thumbnail-placeholder" style="width: 50px; height: 50px; background: #f0f0f1; border: 1px dashed #c3c4c7; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #646970; font-size: 11px;">';
            echo '<span>Brak</span>';
            echo '</div>';
        }
    }
}

/**
 * Dodaje CSS dla kolumny obrazków
 */
function carni24_admin_featured_image_column_css() {
    echo '<style>
        .column-featured_image {
            width: 70px;
            text-align: center;
        }
        
        .admin-thumbnail:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .admin-thumbnail-placeholder:hover {
            background: #e8e8e8;
            cursor: help;
        }
        
        /* Modal dla podglądu obrazka */
        .carni24-image-modal {
            display: none;
            position: fixed;
            z-index: 999999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            cursor: pointer;
        }
        
        .carni24-image-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }
        
        .carni24-image-modal img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .carni24-image-modal-close {
            position: absolute;
            top: 10px;
            right: 20px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1000000;
        }
        
        .carni24-image-modal-close:hover {
            color: #ccc;
        }
    </style>';
}

/**
 * Dodaje JavaScript dla podglądu obrazków
 */
function carni24_admin_featured_image_column_js() {
    echo '<script>
        function carni24ShowImagePreview(imageUrl, title) {
            // Usuń istniejący modal jeśli istnieje
            const existingModal = document.getElementById("carni24-image-modal");
            if (existingModal) {
                existingModal.remove();
            }
            
            // Utwórz modal
            const modal = document.createElement("div");
            modal.id = "carni24-image-modal";
            modal.className = "carni24-image-modal";
            modal.style.display = "block";
            
            modal.innerHTML = `
                <span class="carni24-image-modal-close">&times;</span>
                <div class="carni24-image-modal-content">
                    <img src="${imageUrl}" alt="${title}" title="${title}">
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Zamknij modal po kliknięciu
            modal.onclick = function() {
                modal.remove();
            };
            
            // Zamknij modal klawiszem ESC
            document.addEventListener("keydown", function closeOnEsc(e) {
                if (e.key === "Escape") {
                    modal.remove();
                    document.removeEventListener("keydown", closeOnEsc);
                }
            });
        }
    </script>';
}

// Hookami dla posts
add_filter('manage_posts_columns', 'carni24_add_featured_image_column');
add_action('manage_posts_custom_column', 'carni24_fill_featured_image_column', 10, 2);

// Hookami dla pages
add_filter('manage_pages_columns', 'carni24_add_featured_image_column');
add_action('manage_pages_custom_column', 'carni24_fill_featured_image_column', 10, 2);

// CSS i JavaScript tylko na stronach list wpisów
add_action('admin_head-edit.php', function() {
    global $typenow;
    if (in_array($typenow, ['post', 'page'])) {
        carni24_admin_featured_image_column_css();
        carni24_admin_featured_image_column_js();
    }
});

/**
 * Dodaje sortowanie po obrazku wyróżniającym
 */
function carni24_featured_image_column_sortable($columns) {
    $columns['featured_image'] = 'featured_image';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'carni24_featured_image_column_sortable');
add_filter('manage_edit-page_sortable_columns', 'carni24_featured_image_column_sortable');

/**
 * Obsługuje sortowanie po obrazku wyróżniającym
 */
function carni24_featured_image_column_orderby($query) {
    if (!is_admin()) {
        return;
    }
    
    $orderby = $query->get('orderby');
    
    if ('featured_image' === $orderby) {
        $query->set('meta_key', '_thumbnail_id');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'carni24_featured_image_column_orderby');