<?php

class K3eSitemap {

    const VERSION = '0.1a';
    const NAME = 'Mapa strony';
    
    function __construct() {

        add_action('publish_post', 'Sitemap');
        add_action('publish_page', 'Sitemap');
        add_action('save_post', 'Sitemap');

        function Sitemap() {
            Sitemap::generate();
        }

    }

}
