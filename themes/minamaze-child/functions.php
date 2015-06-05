<?php
add_action( 'wp_enqueue_scripts', 'drs_enqueue_styles' );
function drs_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
//     wp_enqueue_style( 'child-style',
//         get_stylesheet_directory_uri() . '/style.css',
//         array('parent-style')
//     );
}


add_theme_support( 'html5', array( 'search-form' ) );

//this doesn't work yet
function my_search_form( $form ) {
	$form = '<form method="post" class="searchform" action="'. esc_url( home_url( "/search" ) ).'" role="search">
    <input type="text" class="search" name="params[q]" value="'.esc_attr( get_search_query() ).'" placeholder="'._e( "Search", "lan-thinkupthemes" ).'" />
    <input type="submit" class="searchsubmit" name="submit" value="Search" />
  </form>';

	return $form;
}

add_filter( 'get_search_form', 'my_search_form' );
