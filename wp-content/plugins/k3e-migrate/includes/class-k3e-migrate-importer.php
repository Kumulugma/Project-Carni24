<?php

class K3E_Migrate_Importer {
    
    public function init() {
        add_action('wp_ajax_k3e_migrate_import_data', array($this, 'ajax_import_data'));
        add_action('wp_ajax_k3e_migrate_process_import_batch', array($this, 'ajax_process_import_batch'));
    }
    
    public function ajax_import_data() {
        check_ajax_referer('k3e_migrate_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Brak uprawnień.', 'k3e-migrate'));
        }
        
        $json_data = isset($_POST['json_data']) ? $_POST['json_data'] : '';
        $cpt_mode = isset($_POST['cpt_mode']) ? sanitize_text_field($_POST['cpt_mode']) : 'existing';
        $import_mode = isset($_POST['import_mode']) ? sanitize_text_field($_POST['import_mode']) : 'update';
        
        if (empty($json_data)) {
            wp_send_json_error(__('Brak danych do importu.', 'k3e-migrate'));
        }
        
        $import_data = json_decode(stripslashes($json_data), true);
        
        if (!$import_data || !isset($import_data['items']) || !isset($import_data['export_info'])) {
            wp_send_json_error(__('Nieprawidłowy format pliku JSON.', 'k3e-migrate'));
        }
        
        $post_type = $import_data['export_info']['post_type'];
        
        if (!$post_type) {
            wp_send_json_error(__('Nie można określić typu treści z pliku JSON.', 'k3e-migrate'));
        }
        
        if ($cpt_mode === 'existing' && !post_type_exists($post_type)) {
            wp_send_json_error(sprintf(__('Custom Post Type "%s" nie istnieje w systemie.', 'k3e-migrate'), $post_type));
        }
        
        if ($cpt_mode === 'create' && !post_type_exists($post_type)) {
            $this->create_post_type($import_data['export_info']['post_type_object']);
        }
        
        $import_config = array(
            'import_data' => $import_data,
            'post_type' => $post_type,
            'cpt_mode' => $cpt_mode,
            'import_mode' => $import_mode,
            'total_items' => count($import_data['items']),
            'processed_items' => 0,
            'successful_items' => 0,
            'updated_items' => 0,
            'created_items' => 0,
            'failed_items' => 0,
            'errors' => array(),
            'start_time' => microtime(true)
        );
        
        set_transient('k3e_migrate_import_config', $import_config, HOUR_IN_SECONDS);
        
        wp_send_json_success(array(
            'total_items' => $import_config['total_items'],
            'post_type' => $post_type
        ));
    }
    
    public function ajax_process_import_batch() {
        check_ajax_referer('k3e_migrate_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Brak uprawnień.', 'k3e-migrate'));
        }
        
        $import_config = get_transient('k3e_migrate_import_config');
        
        if (!$import_config) {
            wp_send_json_error(__('Konfiguracja importu nie istnieje lub wygasła.', 'k3e-migrate'));
        }
        
        $batch_size = 5;
        $offset = $import_config['processed_items'];
        
        $items_to_process = array_slice($import_config['import_data']['items'], $offset, $batch_size);
        
        foreach ($items_to_process as $item) {
            $result = $this->import_item($item, $import_config);
            
            $import_config['processed_items']++;
            
            if ($result['success']) {
                $import_config['successful_items']++;
                if ($result['action'] === 'updated') {
                    $import_config['updated_items']++;
                } else {
                    $import_config['created_items']++;
                }
            } else {
                $import_config['failed_items']++;
                $import_config['errors'][] = $result['error'];
            }
        }
        
        set_transient('k3e_migrate_import_config', $import_config, HOUR_IN_SECONDS);
        
        $completed = $import_config['processed_items'] >= $import_config['total_items'];
        
        $time_elapsed = 0;
        if ($completed) {
            $time_elapsed = microtime(true) - $import_config['start_time'];
        }
        
        wp_send_json_success(array(
            'processed' => $import_config['processed_items'],
            'successful' => $import_config['successful_items'],
            'updated' => $import_config['updated_items'],
            'created' => $import_config['created_items'],
            'failed' => $import_config['failed_items'],
            'total' => $import_config['total_items'],
            'completed' => $completed,
            'time_elapsed' => $time_elapsed,
            'errors' => array_slice($import_config['errors'], -5)
        ));
    }
    
    private function import_item($item, $config) {
        try {
            $post_id = null;
            $action = 'created';
            
            if ($config['import_mode'] === 'update' && isset($item['id'])) {
                $existing_post = get_post($item['id']);
                if ($existing_post && $existing_post->post_type === $config['post_type']) {
                    $post_id = $item['id'];
                    $action = 'updated';
                }
            }
            
            $post_data = array(
                'post_type' => $config['post_type'],
                'post_status' => isset($item['status']) ? $item['status'] : 'publish'
            );
            
            if ($post_id) {
                $post_data['ID'] = $post_id;
            }
            
            if (isset($item['title'])) {
                $post_data['post_title'] = $item['title'];
            }
            
            if (isset($item['content'])) {
                $post_data['post_content'] = $item['content'];
            }
            
            if (isset($item['excerpt'])) {
                $post_data['post_excerpt'] = $item['excerpt'];
            }
            
            if (isset($item['slug'])) {
                $post_data['post_name'] = $item['slug'];
            }
            
            if (isset($item['date_created'])) {
                $post_data['post_date'] = $item['date_created'];
            }
            
            if ($post_id) {
                $result = wp_update_post($post_data, true);
            } else {
                $result = wp_insert_post($post_data, true);
                if (!is_wp_error($result)) {
                    $post_id = $result;
                }
            }
            
            if (is_wp_error($result)) {
                return array(
                    'success' => false,
                    'error' => $result->get_error_message()
                );
            }
            
            if (isset($item['thumbnail']) && is_array($item['thumbnail'])) {
                // Można dodać logikę importu miniaturek jeśli potrzeba
            }
            
            if (isset($item['meta']) && is_array($item['meta'])) {
                foreach ($item['meta'] as $meta_key => $meta_value) {
                    update_post_meta($post_id, $meta_key, $meta_value);
                }
            }
            
            update_post_meta($post_id, '_k3e_migrate_imported_date', current_time('mysql'));
            
            return array(
                'success' => true,
                'action' => $action,
                'post_id' => $post_id
            );
            
        } catch (Exception $e) {
            return array(
                'success' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    private function create_post_type($post_type_object) {
        if (!$post_type_object || !is_array($post_type_object)) {
            return false;
        }
        
        $post_type = $post_type_object['name'];
        $labels = isset($post_type_object['labels']) ? (array) $post_type_object['labels'] : array();
        
        $default_labels = array(
            'name' => ucfirst($post_type),
            'singular_name' => ucfirst($post_type),
            'add_new' => 'Add New',
            'add_new_item' => 'Add New ' . ucfirst($post_type),
            'edit_item' => 'Edit ' . ucfirst($post_type),
            'new_item' => 'New ' . ucfirst($post_type),
            'view_item' => 'View ' . ucfirst($post_type),
            'search_items' => 'Search ' . ucfirst($post_type),
            'not_found' => 'No ' . $post_type . ' found',
            'not_found_in_trash' => 'No ' . $post_type . ' found in Trash'
        );
        
        $labels = array_merge($default_labels, $labels);
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
            'show_in_rest' => true
        );
        
        if (isset($post_type_object['args']) && is_array($post_type_object['args'])) {
            $args = array_merge($args, $post_type_object['args']);
        }
        
        register_post_type($post_type, $args);
        
        return true;
    }
}