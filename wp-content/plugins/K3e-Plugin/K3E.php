<?php

class K3E {

    const PLUGIN_NAME = "K3e - Plugin";
    const PLUGIN_SLUG = "k3e_plugin";
    const PLUGIN_BOOTSTRAP = 'k3e-plugin';
    const PLUGIN_DIR = 'K3e-Plugin';
    const PLUGIN_URL = ABSPATH . 'wp-content/plugins/' . self::PLUGIN_DIR . '/';
    const PLUGIN_PAGE = 'konfiguracja';
    const PLUGIN_ICON = 'dashicons-schedule';
    const DEFAULT_MODULES = [
        'autoupdate' => ['status' => false, 'name' => 'Automatyczne aktualizacje', 'class' => 'K3eAutoupdate'],
        'thumbnails' => ['status' => false, 'name' => 'Miniaturki', 'class' => 'K3eThumbnails'],
        'hideAdminBar' => ['status' => false, 'name' => 'Ukryj belkę administracyjną', 'class' => 'K3eHideAdminBar'],
        'hideEmojis' => ['status' => false, 'name' => 'Ukryj emotki', 'class' => 'K3eHideEmojis'],
        'themeSupport' => ['status' => false, 'name' => 'Wsparcie szablonu', 'class' => 'K3eThemeSupport'],
        'themeDomain' => ['status' => false, 'name' => 'Domena z tłumaczeniem', 'class' => 'K3eThemeDomain'],
        'documentSeparator' => ['status' => false, 'name' => 'Separator w tytule', 'class' => 'K3eDocumentSeparator'],
        'jQueryDisable' => ['status' => false, 'name' => 'Wyłącz jQuery', 'class' => 'K3eJQueryDisable'],
        'registerMenu' => ['status' => false, 'name' => 'Zarejestruj menu', 'class' => 'K3eRegisterMenu'],
        'placeholders' => ['status' => false, 'name' => 'Zaślepka', 'class' => 'K3ePlaceholder'],
        'preloader' => ['status' => false, 'name' => 'Preloader', 'class' => 'K3ePreloader'],
        'lazyLoader' => ['status' => false, 'name' => 'LazyLoader', 'class' => 'K3eLazyLoader'],
        'staticContent' => ['status' => false, 'name' => 'Treść statyczna', 'class' => 'K3eStaticContent'],
        'disableGutenberg' => ['status' => false, 'name' => 'Wyłącz Gutenberga', 'class' => 'K3eDisableGutenberg'],
        'googleAnalytics' => ['status' => false, 'name' => 'Uruchom Google Analytics', 'class' => 'K3eGoogleAnalytics'],
        'favicon' => ['status' => false, 'name' => 'Favicon', 'class' => 'K3eFavicon'],
        'cookiesPopup' => ['status' => false, 'name' => 'Informacja o Cookies', 'class' => 'K3eCookiesPopup'],
        'disableComments' => ['status' => false, 'name' => 'Wyłącz komentarze', 'class' => 'K3eDisableComments'],
        'disableSearch' => ['status' => false, 'name' => 'Wyłącz wyszukiwarkę', 'class' => 'K3eDisableSearch']
    ];
    const DEFAULT_THEME_SUPPORT = [
//        'admin-bar' => ['status' => false, 'name' => 'admin-bar'],
//        'align-wide' => ['status' => false, 'name' => 'align-wide'],
//        'automatic-feed-links' => ['status' => false, 'name' => 'automatic-feed-links'],
//        'core-block-patterns' => ['status' => false, 'name' => 'core-block-patterns'],
//        'custom-background' => ['status' => false, 'name' => 'custom-background'],
//        'custom-header' => ['status' => false, 'name' => 'custom-header'],
//        'custom-line-height' => ['status' => false, 'name' => 'custom-line-height'],
//        'custom-logo' => ['status' => false, 'name' => 'Personalizowane logo'],
//        'customize-selective-refresh-widgets' => ['status' => false, 'name' => 'customize-selective-refresh-widgets'],
//        'custom-spacing' => ['status' => false, 'name' => 'custom-spacing'],
//        'custom-units' => ['status' => false, 'name' => 'custom-units'],
//        'dark-editor-style' => ['status' => false, 'name' => 'dark-editor-style'],
//        'disable-custom-colors' => ['status' => false, 'name' => 'disable-custom-colors'],
//        'disable-custom-font-sizes' => ['status' => false, 'name' => 'disable-custom-font-sizes'],
//        'editor-color-palette' => ['status' => false, 'name' => 'editor-color-palette'],
//        'editor-gradient-presets' => ['status' => false, 'name' => 'editor-gradient-presets'],
//        'editor-font-sizes' => ['status' => false, 'name' => 'Edytor rozmiarów czcionek'],
//        'editor-styles' => ['status' => false, 'name' => 'Edytor stylów'],
//        'featured-content' => ['status' => false, 'name' => 'featured-content'],
        'html5' => ['status' => true, 'name' => 'HTML5'],
        'menus' => ['status' => false, 'name' => 'Konfigurator menu'],
//        'post-formats' => ['status' => false, 'name' => 'post-formats'],
        'post-thumbnails' => ['status' => true, 'name' => 'Obrazek wyróżniający'],
//        'responsive-embeds' => ['status' => false, 'name' => 'responsive-embeds'],
//        'starter-content' => ['status' => false, 'name' => 'starter-content'],
//        'title-tag' => ['status' => false, 'name' => 'Tytułt tagów'],
//        'wp-block-styles' => ['status' => false, 'name' => 'Style bloków'],
//        'widgets' => ['status' => false, 'name' => 'Widgety'],
//        'widgets-block-editor' => ['status' => false, 'name' => 'Edytor blokowy widgetów']
    ];
    const DEFAULT_THUMBNAILS = [
        'bar' => ['name' => 'bar', 'width' => '300', 'height' => '1300', 'crop' => true]
    ];
    const DEFAULT_THEME_DOMAIN = 'k3e';
    const DEFAULT_DOCUMENT_SEPARATOR = '|';
    const DEFAULT_MENUS = [];
    const OPTION_PRELOADER_ACTIVATE = 'k3e_preloader_activate';
    const OPTION_LAZYLOADER_ACTIVATE = 'k3e_lazyloader_activate';
    const OPTION_THUMBNAIL_SIZES = 'k3e_thumbnail_sizes';
    const OPTION_THEME_SUPPORT = 'k3e_theme_support';
    const OPTION_THEME_DOMAIN = 'k3e_theme_domain';
    const OPTION_STATIC_FORM_TYPE = 'k3e_static_form_type';
    const OPTION_STATIC_FORM_ID = 'k3e_static_form_ID';
    const OPTION_STATIC_FORM_AMOUNT = 'k3e_static_forms_amount';
    const OPTION_STATIC_FORMS = 'k3e_static_forms';
    const OPTION_STATIC_CONTENT = 'k3e_static_content';
    const OPTION_REGISTER_MENU = 'k3e_register_menu';
    const OPTION_PLACEHOLDERS = 'k3e_placeholders';
    const OPTION_PLACEHOLDER_NAME = 'k3e_placeholder_name';
    const OPTION_PLACEHOLDER_AMOUNT = 'k3e_placeholder_amount';
    const OPTION_PLACEHOLDER_ACTIVATE = 'k3e_placeholder_activate';
    const OPTION_DOCUMENT_SEPARATOR = 'k3e_document_separator';
    const OPTION_HIDE_MENU = 'k3e_hide_menu';
    const OPTION_ONEPAGE = 'k3e_onepage';
    const OPTION_SYSTEM_MODULES = 'k3e_system_modules';
    const OPTION_PLUGIN_INSTALL_DATE = 'k3e_plugin_install_date';
    const OPTION_PLUGIN_ACTIVATION_DATE = 'k3e_plugin_activate_date';
    const OPTION_PLUGIN_UNINSTALL_DATE = 'k3e_plugin_uninstall_date';
    const OPTION_PLUGIN_DEACTIVATION_DATE = 'k3e_plugin_deactivate_date';
    const OPTION_DISABLE_GUTENBERG_POSTS = 'k3e_disable_gutenberg_posts';
    const OPTION_GOOGLE_ANALYTICS_ACTIVATE = 'k3e_google_analytics_activate';
    const OPTION_GOOGLE_ANALYTICS_CODE = 'k3e_google_analytics_code';
    const OPTION_FAVICON_ACTIVATE = 'k3e_favicon_activate';
    const OPTION_COOKIES_POPUP_ACTIVATE = 'k3e_google_analytics_activate';
    const OPTION_COOKIES_POPUP_CONTENT = 'k3e_google_analytics_content';
    const OPTION_LAZYLOADER_PLACEHOLDER = 'k3e_lazyloader_placeholder';
    
    const POST_EXCLUDES = [
        'attachment',
        'revision',
        'nav_menu_item',
        'custom_css',
        'customize_changeset',
        'oembed_cache',
        'user_request',
        'wp_block',
        'wp_template',
        'wp_template_part',
        'wp_global_styles',
        'wp_navigation',
        'acf-field-group',
        'acf-field',
        'wpcf7_contact_form'
    ];
    
    const FULL_CONFIG = [
        self::OPTION_PRELOADER_ACTIVATE,
        self::OPTION_LAZYLOADER_ACTIVATE,
        self::OPTION_THUMBNAIL_SIZES,
        self::OPTION_THEME_SUPPORT,
        self::OPTION_THEME_DOMAIN,
        self::OPTION_STATIC_FORM_TYPE,
        self::OPTION_STATIC_FORM_ID,
        self::OPTION_STATIC_FORM_AMOUNT,
        self::OPTION_STATIC_FORMS,
        self::OPTION_STATIC_CONTENT,
        self::OPTION_REGISTER_MENU,
        self::OPTION_PLACEHOLDERS,
        self::OPTION_PLACEHOLDER_NAME,
        self::OPTION_PLACEHOLDER_AMOUNT,
        self::OPTION_PLACEHOLDER_ACTIVATE,
        self::OPTION_DOCUMENT_SEPARATOR,
        self::OPTION_HIDE_MENU,
        self::OPTION_ONEPAGE,
        self::OPTION_SYSTEM_MODULES,
        self::OPTION_DISABLE_GUTENBERG_POSTS,
        self::OPTION_GOOGLE_ANALYTICS_ACTIVATE,
        self::OPTION_GOOGLE_ANALYTICS_CODE,
        self::OPTION_FAVICON_ACTIVATE,
        self::OPTION_COOKIES_POPUP_ACTIVATE,
        self::OPTION_COOKIES_POPUP_CONTENT
    ];

}
