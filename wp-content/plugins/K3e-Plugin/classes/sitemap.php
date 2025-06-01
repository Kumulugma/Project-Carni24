<?php

class Sitemap {

    public static function generate() {

        $active = get_option(K3e::OPTION_SITEMAP_POST_TYPES);

        $args = array(
            'post_type' => is_array($active) ? $active : ['post'],
            'numberposts' => -1,
            'order' => 'DESC'
        );

        $filename = plugin_dir_path(__FILE__) . "../modules/K3eSitemap/sitemap.xml";
        $template = fopen($filename, "r");
        $contents = fread($template, filesize($filename));
        fclose($template);

        $content = '';
        $onepage = unserialize(get_option(K3E::OPTION_ONEPAGE));
        if ($onepage != 1) {

            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $content .= '<url><loc>' . get_permalink(get_the_ID()) . '</loc><lastmod>' .
                        get_post_modified_time('Y-m-d\TH:i:s+00:00', false, get_the_ID()) . '</lastmod></url>';
                }
            }
            wp_reset_postdata();
        }
        $content .= '';
        $content .= '<url><loc>' . get_site_url() . '</loc><lastmod>' . date('Y-m-d\TH:i:s+00:00') . '</lastmod></url>';

        $contents = str_replace("{links}", $content, $contents);

        $fp = fopen(ABSPATH . 'sitemap.xml', 'w');
        $result = fwrite($fp, $contents);
        fclose($fp);

        return $result;
    }

}
