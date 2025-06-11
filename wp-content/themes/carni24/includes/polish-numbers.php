<?php
/**
 * Polish Numbers Helper Functions
 * Plik: includes/polish-numbers.php
 * Autor: Carni24 Theme
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Format Polish numbers with proper grammar
 * Funkcja do poprawnej odmiany polskich liczebników
 * 
 * @param int $number Liczba
 * @param string $singular Forma pojedyncza (1)
 * @param string $plural_2_4 Forma mnoga dla 2-4 
 * @param string $plural_5_plus Forma mnoga dla 5+
 * @return string Poprawnie odmieniona forma
 */
if (!function_exists('carni24_format_count')) {
    function carni24_format_count($number, $singular, $plural_2_4, $plural_5_plus) {
        $number = abs((int) $number); // Zapewni dodatnią liczbę całkowitą
        
        // Sprawdź ostatnią cyfrę
        $last_digit = $number % 10;
        $last_two_digits = $number % 100;
        
        // Reguły polskiej gramatyki
        if ($number == 1) {
            return $number . ' ' . $singular;
        } elseif ($last_two_digits >= 11 && $last_two_digits <= 14) {
            // Wyjątek dla 11-14 (zawsze forma mnoga 5+)
            return $number . ' ' . $plural_5_plus;
        } elseif ($last_digit >= 2 && $last_digit <= 4) {
            // 2, 3, 4 oraz liczby kończące się na 2, 3, 4 (ale nie 12, 13, 14)
            return $number . ' ' . $plural_2_4;
        } else {
            // Wszystkie inne (0, 1, 5-9 oraz 11-14)
            return $number . ' ' . $plural_5_plus;
        }
    }
}

/**
 * Helper function specifically for common Polish words
 * Pomocnicze funkcje dla często używanych słów
 */

if (!function_exists('carni24_count_posts')) {
    function carni24_count_posts($number) {
        return carni24_format_count($number, 'post', 'posty', 'postów');
    }
}

if (!function_exists('carni24_count_species')) {
    function carni24_count_species($number) {
        return carni24_format_count($number, 'gatunek', 'gatunki', 'gatunków');
    }
}

if (!function_exists('carni24_count_photos')) {
    function carni24_count_photos($number) {
        return carni24_format_count($number, 'zdjęcie', 'zdjęcia', 'zdjęć');
    }
}

if (!function_exists('carni24_count_articles')) {
    function carni24_count_articles($number) {
        return carni24_format_count($number, 'artykuł', 'artykuły', 'artykułów');
    }
}

if (!function_exists('carni24_count_pages')) {
    function carni24_count_pages($number) {
        return carni24_format_count($number, 'strona', 'strony', 'stron');
    }
}

if (!function_exists('carni24_count_categories')) {
    function carni24_count_categories($number) {
        return carni24_format_count($number, 'kategoria', 'kategorie', 'kategorii');
    }
}

if (!function_exists('carni24_count_comments')) {
    function carni24_count_comments($number) {
        return carni24_format_count($number, 'komentarz', 'komentarze', 'komentarzy');
    }
}

if (!function_exists('carni24_count_results')) {
    function carni24_count_results($number) {
        return carni24_format_count($number, 'wynik', 'wyniki', 'wyników');
    }
}

if (!function_exists('carni24_count_years')) {
    function carni24_count_years($number) {
        return carni24_format_count($number, 'rok', 'lata', 'lat');
    }
}

if (!function_exists('carni24_count_months')) {
    function carni24_count_months($number) {
        return carni24_format_count($number, 'miesiąc', 'miesiące', 'miesięcy');
    }
}

if (!function_exists('carni24_count_days')) {
    function carni24_count_days($number) {
        return carni24_format_count($number, 'dzień', 'dni', 'dni');
    }
}

if (!function_exists('carni24_count_minutes')) {
    function carni24_count_minutes($number) {
        return carni24_format_count($number, 'minuta', 'minuty', 'minut');
    }
}

/**
 * Format time ago in Polish
 * Formatowanie czasu "temu" w języku polskim
 * 
 * @param string $date Data w formacie rozpoznawalnym przez strtotime()
 * @return string Sformatowany czas w języku polskim
 */
if (!function_exists('carni24_time_ago')) {
    function carni24_time_ago($date) {
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'przed chwilą';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return carni24_count_minutes($minutes) . ' temu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return carni24_format_count($hours, 'godzina', 'godziny', 'godzin') . ' temu';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return carni24_count_days($days) . ' temu';
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return carni24_count_months($months) . ' temu';
        } else {
            $years = floor($diff / 31536000);
            return carni24_count_years($years) . ' temu';
        }
    }
}

/**
 * Polish month names
 * Polskie nazwy miesięcy
 */
if (!function_exists('carni24_get_polish_month')) {
    function carni24_get_polish_month($month_number, $case = 'nominative') {
        $months = array(
            1 => array(
                'nominative' => 'styczeń',
                'genitive' => 'stycznia'
            ),
            2 => array(
                'nominative' => 'luty', 
                'genitive' => 'lutego'
            ),
            3 => array(
                'nominative' => 'marzec',
                'genitive' => 'marca'
            ),
            4 => array(
                'nominative' => 'kwiecień',
                'genitive' => 'kwietnia'
            ),
            5 => array(
                'nominative' => 'maj',
                'genitive' => 'maja'
            ),
            6 => array(
                'nominative' => 'czerwiec',
                'genitive' => 'czerwca'
            ),
            7 => array(
                'nominative' => 'lipiec',
                'genitive' => 'lipca'
            ),
            8 => array(
                'nominative' => 'sierpień',
                'genitive' => 'sierpnia'
            ),
            9 => array(
                'nominative' => 'wrzesień',
                'genitive' => 'września'
            ),
            10 => array(
                'nominative' => 'październik',
                'genitive' => 'października'
            ),
            11 => array(
                'nominative' => 'listopad',
                'genitive' => 'listopada'
            ),
            12 => array(
                'nominative' => 'grudzień',
                'genitive' => 'grudnia'
            )
        );
        
        if (isset($months[$month_number][$case])) {
            return $months[$month_number][$case];
        }
        
        return $months[$month_number]['nominative'] ?? '';
    }
}

/**
 * Format Polish date
 * Formatowanie polskiej daty
 * 
 * @param string $date Data
 * @param string $format Format ('full', 'short', 'month_year')
 * @return string Sformatowana data
 */
if (!function_exists('carni24_format_polish_date')) {
    function carni24_format_polish_date($date, $format = 'full') {
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        
        $day = date('j', $timestamp);
        $month = date('n', $timestamp);
        $year = date('Y', $timestamp);
        
        switch ($format) {
            case 'full':
                return $day . ' ' . carni24_get_polish_month($month, 'genitive') . ' ' . $year;
            case 'short':
                return $day . ' ' . substr(carni24_get_polish_month($month, 'genitive'), 0, 3) . ' ' . $year;
            case 'month_year':
                return carni24_get_polish_month($month, 'nominative') . ' ' . $year;
            default:
                return date('j.m.Y', $timestamp);
        }
    }
}