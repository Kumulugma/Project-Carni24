<?php

//Register headerAsset
include("components/headerAsset.php");
//Register Species
include("post-types/species.php");
//Register Pagination
include("includes/pagination.php");
//Register Title Separator
include("includes/titleSeparator.php");
//Register Read More
include("includes/readMore.php");
//Gallery count
include("includes/galleryCount.php");
//Menu Link Class
include("includes/menuAClass.php");
//Breadcrumbs
include("includes/breadcrumbs.php");
//Spec ID
include("includes/specID.php");
add_theme_support( 'post-thumbnails' );

add_filter( 'image_size_names_choose', 'my_custom_sizes' );
 
function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'tiles' => __( 'Kafelek' )
    ) );
}

