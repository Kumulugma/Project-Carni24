<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('carni24_format_count')) {
    function carni24_format_count($number, $singular, $plural_2_4, $plural_5_plus) {
        $number = abs((int) $number);
        
        $last_digit = $number % 10;
        $last_two_digits = $number % 100;
        
        if ($number == 1) {
            return $number . ' ' . $singular;
        } elseif ($last_two_digits >= 11 && $last_two_digits <= 14) {
            return $number . ' ' . $plural_5_plus;
        } elseif ($last_digit >= 2 && $last_digit <= 4) {
            return $number . ' ' . $plural_2_4;
        } else {
            return $number . ' ' . $plural_5_plus;
        }
    }
}

if (!function_exists('carni24_format_species_count')) {
    function carni24_format_species_count($count) {
        return carni24_format_count($count, 'gatunek', 'gatunki', 'gatunków');
    }
}

if (!function_exists('carni24_format_posts_count')) {
    function carni24_format_posts_count($count) {
        return carni24_format_count($count, 'wpis', 'wpisy', 'wpisów');
    }
}

if (!function_exists('carni24_format_guides_count')) {
    function carni24_format_guides_count($count) {
        return carni24_format_count($count, 'poradnik', 'poradniki', 'poradników');
    }
}

if (!function_exists('carni24_format_photos_count')) {
    function carni24_format_photos_count($count) {
        return carni24_format_count($count, 'zdjęcie', 'zdjęcia', 'zdjęć');
    }
}

if (!function_exists('carni24_format_comments_count')) {
    function carni24_format_comments_count($count) {
        return carni24_format_count($count, 'komentarz', 'komentarze', 'komentarzy');
    }
}

if (!function_exists('carni24_format_views_count')) {
    function carni24_format_views_count($count) {
        return carni24_format_count($count, 'wyświetlenie', 'wyświetlenia', 'wyświetleń');
    }
}

if (!function_exists('carni24_format_minutes_count')) {
    function carni24_format_minutes_count($count) {
        return carni24_format_count($count, 'minuta', 'minuty', 'minut');
    }
}

if (!function_exists('carni24_format_years_count')) {
    function carni24_format_years_count($count) {
        return carni24_format_count($count, 'rok', 'lata', 'lat');
    }
}

if (!function_exists('carni24_format_months_count')) {
    function carni24_format_months_count($count) {
        return carni24_format_count($count, 'miesiąc', 'miesiące', 'miesięcy');
    }
}

if (!function_exists('carni24_format_days_count')) {
    function carni24_format_days_count($count) {
        return carni24_format_count($count, 'dzień', 'dni', 'dni');
    }
}

if (!function_exists('carni24_format_pages_count')) {
    function carni24_format_pages_count($count) {
        return carni24_format_count($count, 'strona', 'strony', 'stron');
    }
}

if (!function_exists('carni24_format_users_count')) {
    function carni24_format_users_count($count) {
        return carni24_format_count($count, 'użytkownik', 'użytkownicy', 'użytkowników');
    }
}

if (!function_exists('carni24_format_categories_count')) {
    function carni24_format_categories_count($count) {
        return carni24_format_count($count, 'kategoria', 'kategorie', 'kategorii');
    }
}

if (!function_exists('carni24_format_tags_count')) {
    function carni24_format_tags_count($count) {
        return carni24_format_count($count, 'tag', 'tagi', 'tagów');
    }
}

if (!function_exists('carni24_format_results_count')) {
    function carni24_format_results_count($count) {
        return carni24_format_count($count, 'wynik', 'wyniki', 'wyników');
    }
}

if (!function_exists('carni24_format_files_count')) {
    function carni24_format_files_count($count) {
        return carni24_format_count($count, 'plik', 'pliki', 'plików');
    }
}

if (!function_exists('carni24_format_subscribers_count')) {
    function carni24_format_subscribers_count($count) {
        return carni24_format_count($count, 'subskrybent', 'subskrybenci', 'subskrybentów');
    }
}

if (!function_exists('carni24_format_reading_time')) {
    function carni24_format_reading_time($minutes) {
        if ($minutes < 1) {
            return 'mniej niż minuta czytania';
        }
        return carni24_format_minutes_count($minutes) . ' czytania';
    }
}

if (!function_exists('carni24_format_time_ago')) {
    function carni24_format_time_ago($timestamp) {
        $diff = current_time('timestamp') - $timestamp;
        
        if ($diff < 60) {
            return 'przed chwilą';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' ' . carni24_format_minutes_count($minutes) . ' temu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' ' . carni24_format_count($hours, 'godzina', 'godziny', 'godzin') . ' temu';
        } elseif ($diff < 2592000) {
            $days = floor($diff / 86400);
            return $days . ' ' . carni24_format_days_count($days) . ' temu';
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return $months . ' ' . carni24_format_months_count($months) . ' temu';
        } else {
            $years = floor($diff / 31536000);
            return $years . ' ' . carni24_format_years_count($years) . ' temu';
        }
    }
}