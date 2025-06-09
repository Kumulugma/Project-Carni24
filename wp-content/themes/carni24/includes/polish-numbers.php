<?php
// wp-content/themes/carni24/includes/polish-numbers.php
// Funkcje do poprawnej odmiany liczebników w języku polskim

/**
 * Funkcja do odmiany rzeczowników w języku polskim
 * 
 * @param int $number Liczba
 * @param string $singular Forma pojedyncza (np. "wpis")
 * @param string $plural_2_4 Forma dla 2-4 (np. "wpisy")
 * @param string $plural_5 Forma dla 5+ (np. "wpisów")
 * @return string Poprawnie odmieniona forma
 */
function carni24_polish_declension($number, $singular, $plural_2_4, $plural_5) {
    // Zabezpieczenie dla liczb ujemnych
    $number = abs($number);
    
    // Dla liczb większych niż 100, sprawdzamy tylko ostatnie dwie cyfry
    $lastTwoDigits = $number % 100;
    $lastDigit = $number % 10;
    
    // Wyjątki dla liczb 11-19 (zawsze forma mnoga)
    if ($lastTwoDigits >= 11 && $lastTwoDigits <= 19) {
        return $plural_5;
    }
    
    // Sprawdzenie ostatniej cyfry
    if ($lastDigit === 1) {
        return $singular;
    } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
        return $plural_2_4;
    } else {
        return $plural_5;
    }
}

/**
 * Formatuje liczbę z poprawnie odmienioną nazwą
 * 
 * @param int $number Liczba
 * @param string $singular Forma pojedyncza
 * @param string $plural_2_4 Forma dla 2-4
 * @param string $plural_5 Forma dla 5+
 * @param bool $show_number Czy pokazać liczbę (domyślnie true)
 * @return string Sformatowany tekst z liczbą i nazwą
 */
function carni24_format_count($number, $singular, $plural_2_4, $plural_5, $show_number = true) {
    $declension = carni24_polish_declension($number, $singular, $plural_2_4, $plural_5);
    
    if ($show_number) {
        return number_format($number, 0, ',', ' ') . ' ' . $declension;
    } else {
        return $declension;
    }
}

/**
 * Funkcje pomocnicze dla konkretnych typów treści
 */

// Wpisy
function carni24_posts_count($number = null) {
    if ($number === null) {
        $number = wp_count_posts('post')->publish;
    }
    return carni24_format_count($number, 'wpis', 'wpisy', 'wpisów');
}

// Gatunki
function carni24_species_count($number = null) {
    if ($number === null) {
        $number = wp_count_posts('species')->publish;
    }
    return carni24_format_count($number, 'spisany gatunek', 'spisane gatunki', 'spisanych gatunków');
}

// Zdjęcia/obrazy
function carni24_images_count($number = null) {
    if ($number === null) {
        $number = gallery_count(); // Używa istniejącej funkcji
    }
    return carni24_format_count($number, 'zdjęcie w galerii', 'zdjęcia w galeriach', 'zdjęć w galeriach');
}

// Strony
function carni24_pages_count($number = null) {
    if ($number === null) {
        $number = wp_count_posts('page')->publish;
    }
    return carni24_format_count($number, 'strona', 'strony', 'stron');
}

// Kategorie
function carni24_categories_count($number = null) {
    if ($number === null) {
        $number = wp_count_terms(array('taxonomy' => 'category', 'hide_empty' => false));
    }
    return carni24_format_count($number, 'kategoria', 'kategorie', 'kategorii');
}

// Tagi
function carni24_tags_count($number = null) {
    if ($number === null) {
        $number = wp_count_terms(array('taxonomy' => 'post_tag', 'hide_empty' => false));
    }
    return carni24_format_count($number, 'tag', 'tagi', 'tagów');
}

/**
 * Funkcja uniwersalna do różnych typów treści
 * 
 * @param string $type Typ treści (posts, species, images, pages, categories, tags)
 * @param int|null $number Liczba (jeśli null, pobiera automatycznie)
 * @return string Sformatowany tekst
 */
function carni24_count_text($type, $number = null) {
    switch ($type) {
        case 'posts':
            return carni24_posts_count($number);
        case 'species':
            return carni24_species_count($number);
        case 'images':
            return carni24_images_count($number);
        case 'pages':
            return carni24_pages_count($number);
        case 'categories':
            return carni24_categories_count($number);
        case 'tags':
            return carni24_tags_count($number);
        default:
            return $number ? number_format($number, 0, ',', ' ') : '0';
    }
}

/**
 * Funkcja do czasu - dodatkowa
 */
function carni24_time_declension($number, $unit) {
    $forms = array(
        'minute' => array('minuta', 'minuty', 'minut'),
        'hour' => array('godzina', 'godziny', 'godzin'),
        'day' => array('dzień', 'dni', 'dni'),
        'week' => array('tydzień', 'tygodnie', 'tygodni'),
        'month' => array('miesiąc', 'miesiące', 'miesięcy'),
        'year' => array('rok', 'lata', 'lat')
    );
    
    if (!isset($forms[$unit])) {
        return $number;
    }
    
    return carni24_format_count(
        $number,
        $forms[$unit][0],
        $forms[$unit][1], 
        $forms[$unit][2]
    );
}

/**
 * Shortcode do wyświetlania liczników z poprawną odmianą
 * Użycie: [carni24_count type="posts"] lub [carni24_count type="species" number="25"]
 */
function carni24_count_shortcode($atts) {
    $atts = shortcode_atts(array(
        'type' => 'posts',
        'number' => null,
        'show_number' => 'true'
    ), $atts);
    
    $number = $atts['number'] ? intval($atts['number']) : null;
    $show_number = $atts['show_number'] === 'true';
    
    return carni24_count_text($atts['type'], $number);
}
add_shortcode('carni24_count', 'carni24_count_shortcode');

/**
 * Widget do wyświetlania statystyk
 */
class Carni24_Stats_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'carni24_stats_widget',
            'Carni24 - Statystyki',
            array('description' => 'Wyświetla statystyki strony z poprawną odmianą')
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        echo '<ul class="carni24-stats-list">';
        
        if (!empty($instance['show_posts'])) {
            echo '<li>' . carni24_posts_count() . '</li>';
        }
        
        if (!empty($instance['show_species'])) {
            echo '<li>' . carni24_species_count() . '</li>';
        }
        
        if (!empty($instance['show_images'])) {
            echo '<li>' . carni24_images_count() . '</li>';
        }
        
        if (!empty($instance['show_pages'])) {
            echo '<li>' . carni24_pages_count() . '</li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Statystyki';
        $show_posts = !empty($instance['show_posts']) ? $instance['show_posts'] : false;
        $show_species = !empty($instance['show_species']) ? $instance['show_species'] : false;
        $show_images = !empty($instance['show_images']) ? $instance['show_images'] : false;
        $show_pages = !empty($instance['show_pages']) ? $instance['show_pages'] : false;
        ?>
        <p>
            <label for="<?= $this->get_field_id('title') ?>">Tytuł:</label>
            <input class="widefat" id="<?= $this->get_field_id('title') ?>" name="<?= $this->get_field_name('title') ?>" type="text" value="<?= esc_attr($title) ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_posts); ?> id="<?= $this->get_field_id('show_posts') ?>" name="<?= $this->get_field_name('show_posts') ?>" />
            <label for="<?= $this->get_field_id('show_posts') ?>">Pokaż liczbę wpisów</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_species); ?> id="<?= $this->get_field_id('show_species') ?>" name="<?= $this->get_field_name('show_species') ?>" />
            <label for="<?= $this->get_field_id('show_species') ?>">Pokaż liczbę gatunków</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_images); ?> id="<?= $this->get_field_id('show_images') ?>" name="<?= $this->get_field_name('show_images') ?>" />
            <label for="<?= $this->get_field_id('show_images') ?>">Pokaż liczbę zdjęć</label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_pages); ?> id="<?= $this->get_field_id('show_pages') ?>" name="<?= $this->get_field_name('show_pages') ?>" />
            <label for="<?= $this->get_field_id('show_pages') ?>">Pokaż liczbę stron</label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_posts'] = (!empty($new_instance['show_posts'])) ? 1 : 0;
        $instance['show_species'] = (!empty($new_instance['show_species'])) ? 1 : 0;
        $instance['show_images'] = (!empty($new_instance['show_images'])) ? 1 : 0;
        $instance['show_pages'] = (!empty($new_instance['show_pages'])) ? 1 : 0;
        
        return $instance;
    }
}

// Rejestracja widgetu
function carni24_register_stats_widget() {
    register_widget('Carni24_Stats_Widget');
}
add_action('widgets_init', 'carni24_register_stats_widget');
?>