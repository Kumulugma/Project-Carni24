<?php

class K3E_Migrate_Exporter {
    
    public function init() {
        add_action('wp_ajax_k3e_migrate_get_cpt_list', array($this, 'ajax_get_cpt_list'));
        add_action('wp_ajax_k3e_migrate_get_meta_fields', array($this, 'ajax_get_meta_fields'));
        add_action('wp_ajax_k3e_migrate_start_export', array($this, 'ajax_start_export'));
        add_action('wp_ajax_k3e_migrate_process_export_batch', array($this, 'ajax_process_export_batch'));
        add_action('admin_init', array($this, 'handle_export_download'));
    }
    
    public function ajax_get_cpt_list() {
        check_ajax_referer('k3e_migrate_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Brak uprawnień.', 'k3e-migrate'));
        }
        
        $post_types = get_post_types(array('public' => true), 'objects');
        $cpt_list = array();
        
        foreach ($post_types as $post_type) {
            $count = wp_count_posts($post_type->name);
            $cpt_list[] = array(
                'name' => $post_type->name,
                'label' => $post_type->label,
                'count' => $count->publish
            );
        }
        
        wp_send_json_success($cpt_list);
    }
    
    public function ajax_get_meta_fields() {
        check_ajax_referer('k3e_migrate_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Brak uprawnień.', 'k3e-migrate'));
        }
        
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        
        if (empty($post_type)) {
            wp_send_json_error(__('Nie wybrano typu treści.', 'k3e-migrate'));
        }
        
        $meta_fields = $this->get_meta_fields_for_cpt($post_type);
        $posts_count = $this->get_posts_count($post_type);
        
        wp_send_json_success(array(
            'meta_fields' => $meta_fields,
            'posts_count' => $posts_count
        ));
    }
    
    public function ajax_start_export() {
        check_ajax_referer('k3e_migrate_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Brak uprawnień.', 'k3e-migrate'));
        }
        
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        $options = isset($_POST['options']) ? $_POST['options'] : array();
        $meta_fields = isset($_POST['meta_fields']) ? $_POST['meta_fields'] : array();
        
        if (empty($post_type)) {
            wp_send_json_error(__('Nie wybrano typu treści.', 'k3e-migrate'));
        }
        
        $sanitized_options = array(
            'export_titles' => isset($options['export_titles']) && $options['export_titles'] ? true : false,
            'export_content' => isset($options['export_content']) && $options['export_content'] ? true : false,
            'export_thumbnails' => isset($options['export_thumbnails']) && $options['export_thumbnails'] ? true : false
        );
        
        $sanitized_meta_fields = array();
        foreach ($meta_fields as $meta_field) {
            $sanitized_meta_fields[] = sanitize_text_field($meta_field);
        }
        
        $export_config = array(
            'post_type' => $post_type,
            'options' => $sanitized_options,
            'meta_fields' => $sanitized_meta_fields,
            'total_posts' => $this->get_posts_count($post_type),
            'processed_posts' => 0,
            'exported_data' => array(
                'export_info' => array(
                    'date' => current_time('mysql'),
                    'site_url' => get_site_url(),
                    'plugin_version' => K3E_MIGRATE_VERSION,
                    'post_type' => $post_type,
                    'post_type_object' => get_post_type_object($post_type),
                    'options' => $sanitized_options,
                    'meta_fields' => $sanitized_meta_fields
                ),
                'items' => array()
            ),
            'start_time' => microtime(true)
        );
        
        set_transient('k3e_migrate_export_config', $export_config, HOUR_IN_SECONDS);
        
        wp_send_json_success(array(
            'total_posts' => $export_config['total_posts']
        ));
    }
    
    public function ajax_process_export_batch() {
        check_ajax_referer('k3e_migrate_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Brak uprawnień.', 'k3e-migrate'));
        }
        
        $export_config = get_transient('k3e_migrate_export_config');
        
        if (!$export_config) {
            wp_send_json_error(__('Konfiguracja eksportu nie istnieje lub wygasła.', 'k3e-migrate'));
        }
        
        $batch_size = 10;
        $offset = $export_config['processed_posts'];
        
        $posts = get_posts(array(
            'post_type' => $export_config['post_type'],
            'posts_per_page' => $batch_size,
            'offset' => $offset,
            'post_status' => 'publish'
        ));
        
        foreach ($posts as $post) {
            $item_data = array(
                'id' => $post->ID,
                'slug' => $post->post_name,
                'date_created' => $post->post_date,
                'date_modified' => $post->post_modified,
                'status' => $post->post_status
            );
            
            if ($export_config['options']['export_titles']) {
                $item_data['title'] = $post->post_title;
            }
            
            if ($export_config['options']['export_content']) {
                $item_data['content'] = $post->post_content;
                $item_data['excerpt'] = $post->post_excerpt;
            }
            
            if ($export_config['options']['export_thumbnails']) {
                $thumbnail_id = get_post_thumbnail_id($post->ID);
                if ($thumbnail_id) {
                    $item_data['thumbnail'] = array(
                        'id' => $thumbnail_id,
                        'url' => get_the_post_thumbnail_url($post->ID, 'full'),
                        'alt' => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true)
                    );
                }
            }
            
            if (!empty($export_config['meta_fields'])) {
                $item_data['meta'] = array();
                foreach ($export_config['meta_fields'] as $meta_key) {
                    $meta_value = get_post_meta($post->ID, $meta_key, true);
                    if (!empty($meta_value)) {
                        $item_data['meta'][$meta_key] = $meta_value;
                    }
                }
            }
            
            $export_config['exported_data']['items'][] = $item_data;
            $export_config['processed_posts']++;
        }
        
        set_transient('k3e_migrate_export_config', $export_config, HOUR_IN_SECONDS);
        
        $completed = $export_config['processed_posts'] >= $export_config['total_posts'];
        
        if ($completed) {
            $export_key = 'k3e_migrate_export_' . wp_generate_password(12, false);
            set_transient($export_key, $export_config['exported_data'], HOUR_IN_SECONDS);
            
            $time_elapsed = microtime(true) - $export_config['start_time'];
            
            wp_send_json_success(array(
                'processed' => $export_config['processed_posts'],
                'total' => $export_config['total_posts'],
                'completed' => true,
                'time_elapsed' => $time_elapsed,
                'export_key' => $export_key,
                'download_url' => admin_url('admin.php?page=k3e-migrate-export&action=download&key=' . $export_key)
            ));
        } else {
            wp_send_json_success(array(
                'processed' => $export_config['processed_posts'],
                'total' => $export_config['total_posts'],
                'completed' => false
            ));
        }
    }
    
    public function handle_export_download() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'k3e-migrate-export') {
            return;
        }
        
        if (!isset($_GET['action']) || $_GET['action'] !== 'download') {
            return;
        }
        
        if (!isset($_GET['key'])) {
            return;
        }
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Brak uprawnień.', 'k3e-migrate'));
        }
        
        $export_key = sanitize_text_field($_GET['key']);
        $export_data = get_transient($export_key);
        
        if (!$export_data) {
            wp_die(__('Plik eksportu nie istnieje lub wygasł.', 'k3e-migrate'));
        }
        
        delete_transient($export_key);
        
        $post_type = $export_data['export_info']['post_type'];
        $filename = 'k3e-migrate-' . $post_type . '-' . date('Y-m-d-H-i-s') . '.json';
        
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen(json_encode($export_data)));
        
        echo json_encode($export_data, JSON_PRETTY_PRINT);
        exit;
    }
    
    private function get_meta_fields_for_cpt($post_type) {
        global $wpdb;
        
        $posts = get_posts(array(
            'post_type' => $post_type,
            'posts_per_page' => 50,
            'post_status' => 'publish'
        ));
        
        if (empty($posts)) {
            return array();
        }
        
        $post_ids = wp_list_pluck($posts, 'ID');
        $post_ids_string = implode(',', array_map('intval', $post_ids));
        
        $meta_keys = $wpdb->get_results($wpdb->prepare(
            "SELECT DISTINCT meta_key, COUNT(*) as count 
             FROM {$wpdb->postmeta} 
             WHERE post_id IN ($post_ids_string) 
             AND meta_key NOT LIKE %s 
             GROUP BY meta_key 
             ORDER BY count DESC, meta_key ASC",
            '\_%'
        ));
        
        $meta_fields = array();
        
        foreach ($meta_keys as $meta_key) {
            $sample_value = $wpdb->get_var($wpdb->prepare(
                "SELECT meta_value FROM {$wpdb->postmeta} 
                 WHERE meta_key = %s AND post_id IN ($post_ids_string) 
                 AND meta_value != '' 
                 LIMIT 1",
                $meta_key->meta_key
            ));
            
            $meta_fields[] = array(
                'key' => $meta_key->meta_key,
                'count' => $meta_key->count,
                'sample_value' => $this->get_truncated_value($sample_value)
            );
        }
        
        return $meta_fields;
    }
    
    private function get_posts_count($post_type) {
        $count_posts = wp_count_posts($post_type);
        return $count_posts->publish;
    }
    
    private function get_truncated_value($value, $length = 50) {
        if (is_array($value) || is_object($value)) {
            return 'Array/Object';
        }
        
        $value = (string) $value;
        
        if (strlen($value) > $length) {
            return substr($value, 0, $length) . '...';
        }
        
        return $value;
    }
}