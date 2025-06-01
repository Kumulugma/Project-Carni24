<?php

class Post_types {

    public static function save() {
        $form = [];

        if (isset($_POST['postTypes'])) {
            foreach (get_post_types() as $type => $value) {
                if (in_array($type, $_POST['postTypes'])) {
                    $form[] = $type;
                }
            }

            update_option(K3E::OPTION_SITEMAP_POST_TYPES, $form);
        }

        return $form;
    }

}
