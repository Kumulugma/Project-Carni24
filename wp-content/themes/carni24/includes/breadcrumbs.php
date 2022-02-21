<?php

//Breadcrumbs
function get_breadcrumb() {
    echo '<a href="' . home_url() . '" rel="nofollow">Strona główna</a>';
    if ((is_category() || is_single()) && !is_singular('species')) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        the_category(' &bull; ');
        if (is_single()) {
            echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
            the_title();
        }
    } elseif ((is_category() || is_single()) && is_singular('species')) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";

        global $post;
        $category = get_the_category($post->ID);
        $name = reset($category)->name;
        $slug = reset($category)->slug;
        echo '<a href="/kategoria-gatunku/?spec=' . $slug . '">' . $name;
        if (is_single()) {
            echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
            the_title();
        }
        echo "</a>";
    } elseif (is_page()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
        echo the_title();
    } elseif (is_search()) {
        echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Wynik wyszukiwania dla... ";
        echo '"<em>';
        echo the_search_query();
        echo '</em>"';
    }
}
