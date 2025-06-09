<?php
// wp-content/themes/carni24/includes/ajax-search.php
// Handler AJAX dla wyszukiwarki

// Enqueue scripts dla AJAX search
function carni24_enqueue_ajax_search() {
    wp_enqueue_script('carni24-ajax-search', get_template_directory_uri() . '/assets/js/ajax-search.js', array('jquery'), '1.0', true);
    
    wp_localize_script('carni24-ajax-search', 'carni24Ajax', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carni24_search_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'carni24_enqueue_ajax_search');

// AJAX handler dla zalogowanych użytkowników
add_action('wp_ajax_carni24_ajax_search', 'carni24_handle_ajax_search');

// AJAX handler dla niezalogowanych użytkowników
add_action('wp_ajax_nopriv_carni24_ajax_search', 'carni24_handle_ajax_search');

function carni24_handle_ajax_search() {
    // Sprawdź nonce
    if (!wp_verify_nonce($_POST['nonce'], 'carni24_search_nonce')) {
        wp_die('Błąd bezpieczeństwa');
    }
    
    // Pobierz parametry wyszukiwania
    $query = sanitize_text_field($_POST['query']);
    $page = intval($_POST['page']) ?: 1;
    $search_types = isset($_POST['search_types']) ? explode(',', sanitize_text_field($_POST['search_types'])) : array('post');
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    
    $posts_per_page = 8;
    $offset = ($page - 1) * $posts_per_page;
    
    // Przygotuj argumenty dla WP_Query
    $args = array(
        's' => $query,
        'post_type' => $search_types,
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'offset' => $offset,
        'orderby' => 'relevance',
        'order' => 'DESC'
    );
    
    // Dodaj filtr kategorii jeśli wybrano
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $category
            )
        );
    }
    
    // Wykonaj wyszukiwanie
    $search_query = new WP_Query($args);
    
    $results = array();
    $total = 0;
    
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            $post_type_obj = get_post_type_object(get_post_type());
            $type_label = $post_type_obj ? $post_type_obj->labels->singular_name : 'Wpis';
            
            // Przygotuj excerpt z wyróżnionymi słowami
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), 20);
            }
            $excerpt = carni24_highlight_search_terms($excerpt, $query);
            
            // Pobierz kategorie
            $categories = get_the_category();
            $category_names = array();
            if ($categories) {
                foreach ($categories as $cat) {
                    $category_names[] = $cat->name;
                }
            }
            
            $results[] = array(
                'title' => carni24_highlight_search_terms(get_the_title(), $query),
                'permalink' => get_permalink(),
                'excerpt' => $excerpt,
                'date' => get_the_date('d.m.Y'),
                'author' => get_the_author(),
                'categories' => implode(', ', $category_names),
                'type_label' => $type_label,
                'post_type' => get_post_type()
            );
        }
        
        $total = $search_query->found_posts;
    }
    
    wp_reset_postdata();
    
    // Oblicz liczbę stron
    $total_pages = ceil($total / $posts_per_page);
    
    // Zwróć wyniki
    wp_send_json_success(array(
        'posts' => $results,
        'total' => $total,
        'page' => $page,
        'total_pages' => $total_pages,
        'query' => $query
    ));
}

// Funkcja do wyróżniania wyszukanych terminów
function carni24_highlight_search_terms($text, $query) {
    if (empty($query)) {
        return $text;
    }
    
    // Podziel query na słowa
    $terms = explode(' ', $query);
    
    foreach ($terms as $term) {
        $term = trim($term);
        if (strlen($term) >= 3) {
            // Użyj regex do wyróżnienia terminu (case insensitive)
            $text = preg_replace('/(' . preg_quote($term, '/') . ')/iu', '<mark>$1</mark>', $text);
        }
    }
    
    return $text;
}

// Funkcja do suggesti wyszukiwania (opcjonalna)
add_action('wp_ajax_carni24_search_suggestions', 'carni24_search_suggestions');
add_action('wp_ajax_nopriv_carni24_search_suggestions', 'carni24_search_suggestions');

function carni24_search_suggestions() {
    if (!wp_verify_nonce($_POST['nonce'], 'carni24_search_nonce')) {
        wp_die('Błąd bezpieczeństwa');
    }
    
    $query = sanitize_text_field($_POST['query']);
    
    if (strlen($query) < 2) {
        wp_send_json_success(array());
    }
    
    // Wyszukaj podobne tytuły wpisów
    $suggestions = array();
    
    $args = array(
        's' => $query,
        'post_type' => array('post', 'species'),
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'orderby' => 'relevance'
    );
    
    $suggestion_query = new WP_Query($args);
    
    if ($suggestion_query->have_posts()) {
        while ($suggestion_query->have_posts()) {
            $suggestion_query->the_post();
            
            $suggestions[] = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => get_post_type()
            );
        }
    }
    
    wp_reset_postdata();
    
    // Dodaj popularne tagi/kategorie pasujące do query
    $tags = get_tags(array(
        'search' => $query,
        'number' => 3,
        'hide_empty' => true
    ));
    
    foreach ($tags as $tag) {
        $suggestions[] = array(
            'title' => $tag->name,
            'url' => get_tag_link($tag->term_id),
            'type' => 'tag'
        );
    }
    
    wp_send_json_success($suggestions);
}

// Dodaj meta box do zapisywania słów kluczowych dla lepszego wyszukiwania
function carni24_add_search_keywords_meta_box() {
    add_meta_box(
        'search_keywords',
        'Słowa kluczowe wyszukiwania',
        'carni24_search_keywords_callback',
        array('post', 'species'),
        'normal',
        'low'
    );
}
add_action('add_meta_boxes', 'carni24_add_search_keywords_meta_box');

function carni24_search_keywords_callback($post) {
    wp_nonce_field('search_keywords_meta_box', 'search_keywords_meta_box_nonce');
    
    $keywords = get_post_meta($post->ID, '_search_keywords', true);
    
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="search_keywords">Dodatkowe słowa kluczowe:</label></th>';
    echo '<td>';
    echo '<input type="text" id="search_keywords" name="search_keywords" value="' . esc_attr($keywords) . '" class="large-text" />';
    echo '<p class="description">Dodaj słowa kluczowe oddzielone przecinkami, które pomogą w wyszukiwaniu tego wpisu.</p>';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
}

function carni24_save_search_keywords($post_id) {
    if (!isset($_POST['search_keywords_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['search_keywords_meta_box_nonce'], 'search_keywords_meta_box')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['search_keywords'])) {
        update_post_meta($post_id, '_search_keywords', sanitize_text_field($_POST['search_keywords']));
    }
}
add_action('save_post', 'carni24_save_search_keywords');

// Rozszerz wyszukiwanie o custom fields
function carni24_extend_search($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key' => '_search_keywords',
                'value' => $query->get('s'),
                'compare' => 'LIKE'
            )
        ));
    }
}
add_action('pre_get_posts', 'carni24_extend_search');

// Dodaj CSS do wyróżniania wyszukanych terminów
function carni24_search_highlight_css() {
    echo '<style>
    mark {
        background-color: #fff3cd;
        color: #856404;
        padding: 2px 4px;
        border-radius: 3px;
        font-weight: 600;
    }
    </style>';
}
add_action('wp_head', 'carni24_search_highlight_css');

// Funkcja do logowania popularnych wyszukiwań
function carni24_log_search_query($query) {
    if (empty($query) || strlen($query) < 3) {
        return;
    }
    
    $searches = get_option('carni24_popular_searches', array());
    
    if (isset($searches[$query])) {
        $searches[$query]++;
    } else {
        $searches[$query] = 1;
    }
    
    // Zachowaj tylko 50 najczęstszych wyszukiwań
    arsort($searches);
    $searches = array_slice($searches, 0, 50, true);
    
    update_option('carni24_popular_searches', $searches);
}

// Hook do logowania wyszukiwań
function carni24_track_search() {
    if (is_search() && !empty(get_search_query())) {
        carni24_log_search_query(get_search_query());
    }
}
add_action('wp', 'carni24_track_search');

// Funkcja do pobierania popularnych wyszukiwań
function carni24_get_popular_searches($limit = 8) {
    $searches = get_option('carni24_popular_searches', array());
    
    if (empty($searches)) {
        // Domyślne popularne wyszukiwania jeśli brak danych
        return array(
            'pielęgnacja', 'uprawa', 'nawożenie', 'podlewanie', 
            'dionaea', 'nepenthes', 'drosera', 'sarracenia'
        );
    }
    
    return array_slice(array_keys($searches), 0, $limit);
}

// Shortcode do wyświetlenia wyszukiwarki
function carni24_ajax_search_shortcode($atts) {
    $atts = shortcode_atts(array(
        'placeholder' => 'Wpisz czego poszukujesz...',
        'show_filters' => 'true',
        'show_popular' => 'true'
    ), $atts);
    
    ob_start();
    include get_template_directory() . '/template-parts/homepage/ajax-search.php';
    return ob_get_clean();
}
add_shortcode('carni24_search', 'carni24_ajax_search_shortcode');

// Widget dla wyszukiwarki
class Carni24_Ajax_Search_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_ajax_search_widget',
            'Carni24 - Wyszukiwarka AJAX',
            array('description' => 'Zaawansowana wyszukiwarka z AJAX')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        echo do_shortcode('[carni24_search]');
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Wyszukiwarka';
        ?>
        <p>
            <label for="<?= $this->get_field_id('title') ?>">Tytuł:</label>
            <input class="widefat" id="<?= $this->get_field_id('title') ?>" name="<?= $this->get_field_name('title') ?>" type="text" value="<?= esc_attr($title) ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

// Rejestracja widgetu
function carni24_register_ajax_search_widget() {
    register_widget('Carni24_Ajax_Search_Widget');
}
add_action('widgets_init', 'carni24_register_ajax_search_widget');

// Admin page do zarządzania popularnymi wyszukiwaniami
function carni24_popular_searches_admin_page() {
    add_submenu_page(
        'themes.php',
        'Popularne wyszukiwania',
        'Wyszukiwania',
        'manage_options',
        'carni24-popular-searches',
        'carni24_popular_searches_page_callback'
    );
}
add_action('admin_menu', 'carni24_popular_searches_admin_page');

function carni24_popular_searches_page_callback() {
    if (isset($_POST['clear_searches'])) {
        delete_option('carni24_popular_searches');
        echo '<div class="notice notice-success"><p>Statystyki wyszukiwań zostały wyczyszczone.</p></div>';
    }
    
    $searches = get_option('carni24_popular_searches', array());
    ?>
    <div class="wrap">
        <h1>Popularne wyszukiwania</h1>
        
        <?php if (!empty($searches)): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Fraza wyszukiwania</th>
                    <th>Liczba wyszukiwań</th>
                    <th>Procent</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = array_sum($searches);
                foreach ($searches as $query => $count): 
                    $percentage = round(($count / $total) * 100, 1);
                ?>
                <tr>
                    <td><strong><?= esc_html($query) ?></strong></td>
                    <td><?= $count ?></td>
                    <td><?= $percentage ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <form method="post" style="margin-top: 20px;">
            <input type="submit" name="clear_searches" value="Wyczyść statystyki" class="button" onclick="return confirm('Czy na pewno chcesz wyczyścić wszystkie statystyki wyszukiwań?')">
        </form>
        
        <?php else: ?>
        <p>Brak danych o wyszukiwaniach. Statystyki będą gromadzone automatycznie gdy użytkownicy będą korzystać z wyszukiwarki.</p>
        <?php endif; ?>
        
        <h2>Shortcode</h2>
        <p>Użyj shortcode <code>[carni24_search]</code> aby wyświetlić wyszukiwarkę AJAX w dowolnym miejscu.</p>
        
        <h2>Widget</h2>
        <p>Dostępny jest również widget "Carni24 - Wyszukiwarka AJAX" w sekcji <a href="<?= admin_url('widgets.php') ?>">Wygląd → Widgety</a>.</p>
    </div>
    <?php
}
?>