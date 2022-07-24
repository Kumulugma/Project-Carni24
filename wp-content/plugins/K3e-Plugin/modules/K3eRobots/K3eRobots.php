<?php

class K3eRobots {

    const VERSION = '0.1a';
    const NAME = 'Robots.TXT';
    
    function __construct() {

        add_action('publish_post', 'K3eRobotsTXT');
        add_action('publish_page', 'K3eRobotsTXT');
        add_action('save_post', 'K3eRobotsTXT');

        function K3eRobotsTXT() {


            $filename = plugin_dir_path( __FILE__ ) . "robots.txt";
            $template = fopen($filename, "r");
            $contents = fread($template, filesize($filename));
            fclose($template);
            
                
            $sitemap_url = get_site_url().'/sitemap.xml';
            $contents = str_replace("{sitemap}", $sitemap_url, $contents);
            
            $fp = fopen(ABSPATH . 'robots.txt', 'w');
            fwrite($fp, $contents);
            fclose($fp);
        }

    }

}
