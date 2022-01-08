<?php

add_filter('document_title_separator', 'theme_document_title_separator');

function theme_document_title_separator($sep) {
    $sep = '|';
    return $sep;
}
