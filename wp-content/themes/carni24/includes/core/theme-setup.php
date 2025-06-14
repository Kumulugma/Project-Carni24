<?php
/**
 * Carni24 Theme Setup
 * Podstawowa konfiguracja wsparcia motywu WordPress
 * 
 * @package Carni24
 * @subpackage Core
 */

// Zabezpieczenie przed bezpośrednim dostępem
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Główna funkcja konfiguracji motywu
 * Dodaje wsparcie dla funkcji WordPress
 */
function carni24_setup_theme_support() {
    // Title tag - WordPress zarządza tytułami
    add_theme_support('title-tag');
    
    // Post thumbnails - featured images
    add_theme_support('post-thumbnails');
    
    // Automatic feed links - RSS
    add_theme_support('automatic-feed-links');
    
    // HTML5 support - nowoczesny markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form', 
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Custom header support
    add_theme_support('custom-header', array(
        'default-image' => '',
        'width'         => 1200,
        'height'        => 300,
        'flex-height'   => true,
        'flex-width'    => true,
    ));
    
    // Custom background support
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));
    
    // Wide blocks - Gutenberg
    add_theme_support('align-wide');
    
    // Responsive embeds
    add_theme_support('responsive-embeds');
    
    // Editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => 'Główny zielony',
            'slug'  => 'main-green',
            'color' => '#198754',
        ),
        array(
            'name'  => 'Ciemny zielony', 
            'slug'  => 'dark-green',
            'color' => '#146c43',
        ),
        array(
            'name'  => 'Jasny zielony',
            'slug'  => 'light-green',
            'color' => '#d1e7dd',
        ),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1140;
    }
}
add_action('after_setup_theme', 'carni24_setup_theme_support');

/**
 * Czyści head z niepotrzebnych elementów
 */
function carni24_clean_wp_head() {
    // Usuń niepotrzebne meta tagi
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Usuń Emoji (opcjonalnie)
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'carni24_clean_wp_head');