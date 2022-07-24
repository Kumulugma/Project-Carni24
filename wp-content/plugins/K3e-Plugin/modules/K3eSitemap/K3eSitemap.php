<?php

class K3eSitemap {

    const VERSION = '0.1a';
    const NAME = 'Mapa strony';
    
    function __construct() {

        add_action('publish_post', 'Sitemap');
        add_action('publish_page', 'Sitemap');
        add_action('save_post', 'Sitemap');

        function Sitemap() {
            $postsForSitemap = get_posts(
                    array('numberposts' => -1,
                        'orderby' => 'modified',
                        // 'custom_post' should be replaced with your own Custom Post Type (one or many) 
                        'post_type' => array('post', 'page', 'download', 'product'),
                        'order' => 'DESC'));

            $filename = plugin_dir_path( __FILE__ ) . "sitemap.xml";
            $template = fopen($filename, "r");
            $contents = fread($template, filesize($filename));
            fclose($template);

            $content = '';
            $onepage = unserialize(get_option(K3E::OPTION_ONEPAGE));
            if($onepage != 1) {
            foreach ($postsForSitemap as $post) {
                setup_postdata($post);
                $postdate = explode(" ", $post->post_modified);
                $content .= '<url><loc>' . get_permalink($post->ID) . '</loc><lastmod>' .
                        $postdate[0] . '</lastmod></url>';
            } }
            $content .= '';
            $content .= '<url><loc>' . get_site_url() . '</loc><lastmod>'.date('Y-m-d').'</lastmod></url>';
            
            $contents = str_replace("{links}", $content, $contents);
            $fp = fopen(ABSPATH . 'sitemap.xml', 'w');
            fwrite($fp, $contents);
            fclose($fp);
        }

    }

}
