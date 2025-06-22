<?php
/**
 * Carni24 WordPress Theme - Main Functions File
 * Uporządkowany system ładowania modułów
 * 
 * @package Carni24
 * @version 3.0.0
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

// ===== STAŁE MOTYWU ===== //
define('CARNI24_VERSION', '3.0.0');
define('CARNI24_THEME_PATH', get_template_directory());
define('CARNI24_THEME_URL', get_template_directory_uri());
define('CARNI24_ASSETS_URL', CARNI24_THEME_URL . '/assets');

// ===== PODSTAWOWA KONFIGURACJA MOTYWU ===== //
require_once CARNI24_THEME_PATH . '/includes/core/theme-setup.php';     // Podstawowe wsparcie motywu i funkcje WordPress

// ===== ASSETS I SKRYPTY ===== //
require_once CARNI24_THEME_PATH . '/includes/core/assets.php';          // Ładowanie CSS/JS, system conditional loading

// ===== FUNKCJE POMOCNICZE ===== //
require_once CARNI24_THEME_PATH . '/includes/helpers/polish-numbers.php';  // Funkcje do polskiej odmiany liczebników
require_once CARNI24_THEME_PATH . '/includes/helpers/utils.php';           // Uniwersalne funkcje pomocnicze (views, reading time, excerpts)
require_once CARNI24_THEME_PATH . '/includes/helpers/search-functions.php'; // Funkcje wyszukiwania i pomocnicze

// ===== OPTYMALIZACJE I KONFIGURACJA ===== //
require_once CARNI24_THEME_PATH . '/includes/optimization/disable-comments.php';  // Kompletne wyłączenie komentarzy
require_once CARNI24_THEME_PATH . '/includes/optimization/image-sizes.php';       // Custom rozmiary obrazów i lazy loading

// ===== SEO I NAWIGACJA ===== //
require_once CARNI24_THEME_PATH . '/includes/seo/meta-tags.php';        // SEO meta tagi, Open Graph, Twitter Cards
require_once CARNI24_THEME_PATH . '/includes/seo/breadcrumbs.php';      // Breadcrumbs z JSON-LD structured data
require_once CARNI24_THEME_PATH . '/includes/seo/schema.php';           // JSON-LD schema markup

// ===== CUSTOM POST TYPES I TAXONOMIE ===== //
require_once CARNI24_THEME_PATH . '/includes/post-types/species.php';   // Custom Post Type dla gatunków roślin
require_once CARNI24_THEME_PATH . '/includes/post-types/guides.php';    // Custom Post Type dla poradników

// ===== META BOXES I CUSTOM FIELDS ===== //
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/seo-fields.php';      // Meta boxy SEO dla postów i stron
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/species-fields.php';  // Meta boxy dla gatunków (pochodzenie, trudność)
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/feature-fields.php';  // Meta boxy dla wyróżnionych treści
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/guides-fields.php';   // Meta boxy dla poradników
require_once CARNI24_THEME_PATH . '/includes/meta-boxes/custom-excerpt.php';  // Niestandardowe excerpty

// ===== ADMIN I THEME OPTIONS ===== //
if (is_admin()) {
    require_once CARNI24_THEME_PATH . '/includes/admin/theme-options.php';     // Panel ustawień motywu
    require_once CARNI24_THEME_PATH . '/includes/admin/admin-assets.php';      // CSS/JS dla panelu admina
    require_once CARNI24_THEME_PATH . '/includes/admin/dashboard-widgets.php'; // UJEDNOLICONE widgety dashboard
    require_once CARNI24_THEME_PATH . '/includes/admin/featured-image-columns.php'; // Kolumny z obrazami w listach
}

// ===== FRONTEND FEATURES ===== //
require_once CARNI24_THEME_PATH . '/includes/frontend/navigation.php';   // Menu i nawigacja
require_once CARNI24_THEME_PATH . '/includes/frontend/widgets.php';      // Widget areas i sidebar
require_once CARNI24_THEME_PATH . '/includes/frontend/filters.php';      // Filtry treści i body classes

// ===== AJAX I API ===== //
require_once CARNI24_THEME_PATH . '/includes/ajax/admin-handlers.php';   // AJAX handlers dla panelu admina
require_once CARNI24_THEME_PATH . '/includes/ajax/frontend-handlers.php'; // AJAX handlers dla frontend

// ===== COMPATIBILITY I HOOKS ===== //
require_once CARNI24_THEME_PATH . '/includes/compatibility/plugins.php'; // Kompatybilność z popularnymi wtyczkami
require_once CARNI24_THEME_PATH . '/includes/hooks/theme-hooks.php';     // Custom hooks i filtry motywu

/**
 * Hook dla dodatkowych akcji po inicjalizacji motywu
 */
do_action('carni24_theme_loaded');