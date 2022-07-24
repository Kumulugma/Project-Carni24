<?php

add_filter('theme_page_templates', 'k3e_add_page_template_to_dropdown');

function k3e_add_page_template_to_dropdown($templates) {
    $templates[plugin_dir_path(__FILE__) . '../themes/_templates/page-placeholder.php'] = __('Zaślepka', 'k3e');

    return $templates;
}

add_filter('template_include', 'k3e_change_page_template', 99);

function k3e_change_page_template($template) {
    if (is_page()) {
        $meta = get_post_meta(get_the_ID());

        if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {
            $template = $meta['_wp_page_template'][0];
        }
    }

    return $template;
}
