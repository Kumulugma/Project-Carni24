<?php

class Robots {

    public static function generate() {
        
        $filename = plugin_dir_path(__FILE__) . "../modules/K3eRobots/robots.txt";
        $template = fopen($filename, "r");
        $contents = fread($template, filesize($filename));
        fclose($template);

        $sitemap_url = get_site_url() . '/sitemap.xml';
        $contents = str_replace("{sitemap}", $sitemap_url, $contents);

        $fp = fopen(ABSPATH . 'robots.txt', 'w');
        $result = fwrite($fp, $contents);
        fclose($fp);
        
        return $result;
    }

}
