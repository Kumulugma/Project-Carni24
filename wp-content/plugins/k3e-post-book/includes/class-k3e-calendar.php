<?php
/**
 * Klasa obsługująca logikę kalendarza
 */

if (!defined('ABSPATH')) {
    exit;
}

class K3e_Calendar {
    
    /**
     * Nazwy miesięcy po polsku
     */
    private $month_names = array(
        1 => 'Styczeń', 2 => 'Luty', 3 => 'Marzec', 4 => 'Kwiecień',
        5 => 'Maj', 6 => 'Czerwiec', 7 => 'Lipiec', 8 => 'Sierpień',
        9 => 'Wrzesień', 10 => 'Październik', 11 => 'Listopad', 12 => 'Grudzień'
    );
    
    /**
     * Nazwy dni tygodnia po polsku
     */
    private $day_names = array('Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sob', 'Nd');
    
    /**
     * Generowanie kalendarza HTML
     */
    public function generate_calendar($month, $year) {
        $posts_data = $this->get_posts_for_month($month, $year);
        $first_day = mktime(0, 0, 0, $month, 1, $year);
        $days_in_month = date('t', $first_day);
        $day_of_week = date('N', $first_day) - 1; // 0 = poniedziałek
        
        $html = '<div class="k3e-calendar-wrapper">';
        // Usunięty stary header - używamy nowego w template
        
        $html .= '<table class="k3e-calendar">';
        
        // Nagłówek z dniami tygodnia
        $html .= '<thead><tr>';
        foreach ($this->day_names as $day) {
            $html .= '<th>' . $day . '</th>';
        }
        $html .= '</tr></thead>';
        
        $html .= '<tbody>';
        
        $date = 1;
        $weeks = ceil(($days_in_month + $day_of_week) / 7);
        
        for ($week = 0; $week < $weeks; $week++) {
            $html .= '<tr>';
            
            for ($day = 0; $day < 7; $day++) {
                if (($week == 0 && $day < $day_of_week) || $date > $days_in_month) {
                    $html .= '<td class="k3e-empty-day"></td>';
                } else {
                    $current_date = sprintf('%04d-%02d-%02d', $year, $month, $date);
                    $has_posts = isset($posts_data[$current_date]);
                    $css_class = 'k3e-calendar-day';
                    
                    if ($has_posts) {
                        $css_class .= ' k3e-has-posts';
                        
                        // Dodaj specjalne klasy dla różnych typów wpisów
                        $post_types = array();
                        foreach ($posts_data[$current_date] as $post) {
                            $post_types[] = $post['raw_type'];
                        }
                        $post_types = array_unique($post_types);
                        
                        // Priorytet: species > guides > post > page
                        if (in_array('species', $post_types)) {
                            $css_class .= ' k3e-has-species';
                        } elseif (in_array('guides', $post_types)) {
                            $css_class .= ' k3e-has-guides';
                        } elseif (in_array('post', $post_types)) {
                            $css_class .= ' k3e-has-posts-std';
                        } else {
                            $css_class .= ' k3e-has-pages';
                        }
                    }
                    
                    if ($current_date == date('Y-m-d')) {
                        $css_class .= ' k3e-today';
                    }
                    
                    $html .= '<td class="' . $css_class . '"';
                    
                    if ($has_posts) {
                        $tooltip_data = $this->prepare_tooltip_data($posts_data[$current_date]);
                        $html .= ' data-tooltip="' . esc_attr($tooltip_data) . '"';
                    }
                    
                    $html .= '>';
                    $html .= '<span class="k3e-day-number">' . $date . '</span>';
                    
                    if ($has_posts) {
                        $html .= '<span class="k3e-post-indicator">' . count($posts_data[$current_date]) . '</span>';
                    }
                    
                    $html .= '</td>';
                    $date++;
                }
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Pobieranie wpisów dla określonego miesiąca
     */
    private function get_posts_for_month($month, $year) {
        $posts = get_posts(array(
            'post_type' => array('post', 'page', 'species', 'guides'),
            'post_status' => array('publish', 'private', 'draft'),
            'numberposts' => -1,
            'date_query' => array(
                array(
                    'year' => $year,
                    'month' => $month,
                ),
            ),
        ));
        
        $posts_by_date = array();
        
        foreach ($posts as $post) {
            $post_date = date('Y-m-d', strtotime($post->post_date));
            
            if (!isset($posts_by_date[$post_date])) {
                $posts_by_date[$post_date] = array();
            }
            
            // Pobieranie właściwej nazwy typu wpisu
            $post_type_obj = get_post_type_object($post->post_type);
            $post_type_name = $post_type_obj ? $post_type_obj->labels->singular_name : ucfirst($post->post_type);
            
            $posts_by_date[$post_date][] = array(
                'id' => $post->ID,
                'title' => $post->post_title ?: __('(Bez tytułu)', 'k3e-post-book'),
                'type' => $post_type_name,
                'raw_type' => $post->post_type,
                'status' => $post->post_status,
                'edit_url' => get_edit_post_link($post->ID),
                'view_url' => get_permalink($post->ID),
                'author' => get_the_author_meta('display_name', $post->post_author),
                'date' => $post->post_date
            );
        }
        
        return $posts_by_date;
    }
    
    /**
     * Przygotowywanie danych dla tooltip
     */
    private function prepare_tooltip_data($posts) {
        $tooltip_items = array();
        
        foreach ($posts as $post) {
            $post_type_obj = get_post_type_object($post['type']);
            $post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : ucfirst($post['type']);
            $status_label = $this->get_status_label($post['status']);
            
            $tooltip_items[] = array(
                'title' => esc_html($post['title']),
                'type' => $post_type_label,
                'status' => $status_label,
                'edit_url' => $post['edit_url']
            );
        }
        
        return json_encode($tooltip_items);
    }
    
    /**
     * Pobieranie etykiety statusu wpisu
     */
    private function get_status_label($status) {
        $statuses = array(
            'publish' => __('Opublikowany', 'k3e-post-book'),
            'draft' => __('Szkic', 'k3e-post-book'),
            'private' => __('Prywatny', 'k3e-post-book'),
            'pending' => __('Oczekujący', 'k3e-post-book'),
            'trash' => __('Kosz', 'k3e-post-book')
        );
        
        return isset($statuses[$status]) ? $statuses[$status] : ucfirst($status);
    }
    
    /**
     * Pobieranie poprzedniego miesiąca
     */
    public function get_previous_month($month, $year) {
        if ($month == 1) {
            return array('month' => 12, 'year' => $year - 1);
        }
        return array('month' => $month - 1, 'year' => $year);
    }
    
    /**
     * Pobieranie następnego miesiąca
     */
    public function get_next_month($month, $year) {
        if ($month == 12) {
            return array('month' => 1, 'year' => $year + 1);
        }
        return array('month' => $month + 1, 'year' => $year);
    }
}