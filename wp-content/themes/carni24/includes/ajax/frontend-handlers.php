<?php

if (!defined('ABSPATH')) {
    exit;
}

function carni24_ajax_load_more_posts() {
    check_ajax_referer('carni24_frontend_nonce', 'nonce');
    
    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
    $posts_per_page = isset($_POST['posts_per_page']) ? absint($_POST['posts_per_page']) : 6;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'meta_query' => array(
            array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS'
            )
        )
    );
    
    if (!empty($category)) {
        if ($post_type === 'species') {
            $args['meta_query'][] = array(
                'key' => '_species_category',
                'value' => $category,
                'compare' => '='
            );
        } else {
            $args['category_name'] = $category;
        }
    }
    
    $query = new WP_Query($args);
    $posts_html = '';
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            ob_start();
            if ($post_type === 'species') {
                get_template_part('template-parts/cards/species-card');
            } else {
                get_template_part('template-parts/cards/post-card');
            }
            $posts_html .= ob_get_clean();
        }
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $posts_html,
            'has_more' => $page < $query->max_num_pages,
            'max_pages' => $query->max_num_pages
        ));
    } else {
        wp_send_json_error('Brak więcej postów');
    }
}
add_action('wp_ajax_carni24_load_more_posts', 'carni24_ajax_load_more_posts');
add_action('wp_ajax_nopriv_carni24_load_more_posts', 'carni24_ajax_load_more_posts');

function carni24_ajax_filter_species() {
    check_ajax_referer('carni24_frontend_nonce', 'nonce');
    
    $difficulty = isset($_POST['difficulty']) ? sanitize_text_field($_POST['difficulty']) : '';
    $origin = isset($_POST['origin']) ? sanitize_text_field($_POST['origin']) : '';
    $light = isset($_POST['light']) ? sanitize_text_field($_POST['light']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
    
    $args = array(
        'post_type' => 'species',
        'post_status' => 'publish',
        'posts_per_page' => 12,
        'orderby' => $orderby,
        'order' => $order,
        'meta_query' => array('relation' => 'AND')
    );
    
    if (!empty($difficulty)) {
        $args['meta_query'][] = array(
            'key' => '_species_difficulty',
            'value' => $difficulty,
            'compare' => '='
        );
    }
    
    if (!empty($origin)) {
        $args['meta_query'][] = array(
            'key' => '_species_origin',
            'value' => $origin,
            'compare' => 'LIKE'
        );
    }
    
    if (!empty($light)) {
        $args['meta_query'][] = array(
            'key' => '_species_light',
            'value' => $light,
            'compare' => '='
        );
    }
    
    if (!empty($search)) {
        $args['s'] = $search;
    }
    
    $query = new WP_Query($args);
    $species_html = '';
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            ob_start();
            get_template_part('template-parts/cards/species-card');
            $species_html .= ob_get_clean();
        }
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $species_html,
            'count' => $query->found_posts,
            'message' => 'Znaleziono ' . $query->found_posts . ' gatunków'
        ));
    } else {
        wp_send_json_error('Brak gatunków spełniających kryteria');
    }
}
add_action('wp_ajax_carni24_filter_species', 'carni24_ajax_filter_species');
add_action('wp_ajax_nopriv_carni24_filter_species', 'carni24_ajax_filter_species');

function carni24_ajax_live_search() {
    check_ajax_referer('carni24_frontend_nonce', 'nonce');
    
    $query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
    
    if (strlen($query) < 3) {
        wp_send_json_error('Zapytanie musi mieć minimum 3 znaki');
    }
    
    $args = array(
        'post_type' => array('post', 'species', 'page'),
        'post_status' => 'publish',
        'posts_per_page' => 10,
        's' => $query
    );
    
    $search_query = new WP_Query($args);
    $results = array();
    
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            $result = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => get_post_type(),
                'excerpt' => wp_trim_words(get_the_excerpt(), 15),
                'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') : ''
            );
            
            if (get_post_type() === 'species') {
                $scientific_name = get_post_meta(get_the_ID(), '_species_scientific_name', true);
                if ($scientific_name) {
                    $result['scientific_name'] = $scientific_name;
                }
            }
            
            $results[] = $result;
        }
        wp_reset_postdata();
        
        wp_send_json_success($results);
    } else {
        wp_send_json_error('Brak wyników');
    }
}
add_action('wp_ajax_carni24_live_search', 'carni24_ajax_live_search');
add_action('wp_ajax_nopriv_carni24_live_search', 'carni24_ajax_live_search');

function carni24_ajax_newsletter_signup() {
    check_ajax_referer('carni24_frontend_nonce', 'nonce');
    
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    
    if (!is_email($email)) {
        wp_send_json_error('Nieprawidłowy adres email');
    }
    
    $existing = get_user_by('email', $email);
    if ($existing) {
        wp_send_json_error('Ten adres email jest już zarejestrowany');
    }
    
    $subscribers = get_option('carni24_newsletter_subscribers', array());
    
    if (in_array($email, array_column($subscribers, 'email'))) {
        wp_send_json_error('Ten adres email już subskrybuje newsletter');
    }
    
    $subscriber = array(
        'email' => $email,
        'name' => $name,
        'date' => current_time('mysql'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'confirmed' => false,
        'token' => wp_generate_password(32, false)
    );
    
    $subscribers[] = $subscriber;
    update_option('carni24_newsletter_subscribers', $subscribers);
    
    $confirmation_url = home_url('/?confirm_newsletter=' . $subscriber['token']);
    
    $subject = 'Potwierdź subskrypcję newsletter - ' . get_bloginfo('name');
    $message = "Cześć " . ($name ? $name : '') . "!\n\n";
    $message .= "Dziękujemy za zainteresowanie naszym newsletterem.\n\n";
    $message .= "Aby potwierdzić subskrypcję, kliknij w link:\n";
    $message .= $confirmation_url . "\n\n";
    $message .= "Pozdrawiamy,\n";
    $message .= get_bloginfo('name');
    
    $sent = wp_mail($email, $subject, $message);
    
    if ($sent) {
        wp_send_json_success('Na Twój adres email wysłaliśmy link potwierdzający');
    } else {
        wp_send_json_error('Błąd wysyłania emaila. Spróbuj ponownie');
    }
}
add_action('wp_ajax_carni24_newsletter_signup', 'carni24_ajax_newsletter_signup');
add_action('wp_ajax_nopriv_carni24_newsletter_signup', 'carni24_ajax_newsletter_signup');

function carni24_ajax_contact_form() {
    check_ajax_referer('carni24_frontend_nonce', 'nonce');
    
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error('Wszystkie pola są wymagane');
    }
    
    if (!is_email($email)) {
        wp_send_json_error('Nieprawidłowy adres email');
    }
    
    if (strlen($message) < 10) {
        wp_send_json_error('Wiadomość musi mieć minimum 10 znaków');
    }
    
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    $email_subject = '[' . $site_name . '] Nowa wiadomość z formularza kontaktowego';
    if (!empty($subject)) {
        $email_subject .= ': ' . $subject;
    }
    
    $email_message = "Nowa wiadomość z formularza kontaktowego:\n\n";
    $email_message .= "Imię: " . $name . "\n";
    $email_message .= "Email: " . $email . "\n";
    if (!empty($subject)) {
        $email_message .= "Temat: " . $subject . "\n";
    }
    $email_message .= "Data: " . current_time('Y-m-d H:i:s') . "\n";
    $email_message .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n\n";
    $email_message .= "Wiadomość:\n" . $message . "\n\n";
    $email_message .= "---\n";
    $email_message .= "Ta wiadomość została wysłana z " . home_url();
    
    $headers = array(
        'Reply-To: ' . $name . ' <' . $email . '>',
        'From: ' . $site_name . ' <' . $admin_email . '>'
    );
    
    $sent = wp_mail($admin_email, $email_subject, $email_message, $headers);
    
    if ($sent) {
        wp_send_json_success('Dziękujemy! Twoja wiadomość została wysłana');
    } else {
        wp_send_json_error('Błąd wysyłania wiadomości. Spróbuj ponownie');
    }
}
add_action('wp_ajax_carni24_contact_form', 'carni24_ajax_contact_form');
add_action('wp_ajax_nopriv_carni24_contact_form', 'carni24_ajax_contact_form');

function carni24_ajax_get_related_posts() {
    check_ajax_referer('carni24_frontend_nonce', 'nonce');
    
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    $count = isset($_POST['count']) ? absint($_POST['count']) : 4;
    
    if (!$post_id) {
        wp_send_json_error('Brak ID posta');
    }
    
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error('Post nie istnieje');
    }
    
    $args = array(
        'post_type' => $post->post_type,
        'post_status' => 'publish',
        'posts_per_page' => $count,
        'post__not_in' => array($post_id),
        'orderby' => 'rand'
    );
    
    if ($post->post_type === 'species') {
        $difficulty = get_post_meta($post_id, '_species_difficulty', true);
        if ($difficulty) {
            $args['meta_query'] = array(
                array(
                    'key' => '_species_difficulty',
                    'value' => $difficulty,
                    'compare' => '='
                )
            );
        }
    } else {
        $categories = get_the_category($post_id);
        if (!empty($categories)) {
            $args['category__in'] = array($categories[0]->term_id);
        }
    }
    
    $related_query = new WP_Query($args);
    $related_html = '';
    
    if ($related_query->have_posts()) {
        while ($related_query->have_posts()) {
            $related_query->the_post();
            
            ob_start();
            if ($post->post_type === 'species') {
                get_template_part('template-parts/cards/species-card-small');
            } else {
                get_template_part('template-parts/cards/post-card-small');
            }
            $related_html .= ob_get_clean();
        }
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html' => $related_html,
            'count' => $related_query->found_posts
        ));
    } else {
        wp_send_json_error('Brak powiązanych postów');
    }
}
add_action('wp_ajax_carni24_get_related_posts', 'carni24_ajax_get_related_posts');
add_action('wp_ajax_nopriv_carni24_get_related_posts', 'carni24_ajax_get_related_posts');

function carni24_ajax_toggle_favorite() {
    check_ajax_referer('carni24_frontend_nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Musisz być zalogowany');
    }
    
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    $user_id = get_current_user_id();
    
    if (!$post_id) {
        wp_send_json_error('Brak ID posta');
    }
    
    $favorites = get_user_meta($user_id, 'carni24_favorites', true);
    if (!is_array($favorites)) {
        $favorites = array();
    }
    
    $is_favorite = in_array($post_id, $favorites);
    
    if ($is_favorite) {
        $favorites = array_diff($favorites, array($post_id));
        $action = 'removed';
        $message = 'Usunięto z ulubionych';
    } else {
        $favorites[] = $post_id;
        $action = 'added';
        $message = 'Dodano do ulubionych';
    }
    
    update_user_meta($user_id, 'carni24_favorites', $favorites);
    
    wp_send_json_success(array(
        'action' => $action,
        'message' => $message,
        'is_favorite' => !$is_favorite,
        'count' => count($favorites)
    ));
}
add_action('wp_ajax_carni24_toggle_favorite', 'carni24_ajax_toggle_favorite');