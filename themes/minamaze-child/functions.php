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


/*COPIED FROM minamaze/admin/main/options/01.general-settings.php*/
/* Add custom intro section [Extend for more options in future update] */
function drs_custom_intro() {

	if ( ! is_front_page() ) {
		echo	'<div id="intro" class="option1"><div id="intro-core">',
				'<h1 class="page-title"><span>',
				drs_title_select(),
				'</span></h1>',
				thinkup_input_breadcrumbswitch(),
				'</div></div>';
	} else {
		echo '';
	}
}

/*COPIED FROM minamaze/admin/main/options/01.general-settings.php*/
function drs_title_select() {
	global $post;
  global $wp_query;
  $template_type = $wp_query->query_vars['drstk_template_type'];

  if ($template_type == 'search'){
    printf( __('Search', 'lan-thinkupthemes'));
  } elseif ($template_type == 'browse'){
    printf( __('Browse', 'lan-thinkupthemes'));
  } elseif ($template_type == 'item'){
    return;
  } elseif ( is_page() ) {
		printf( __( '%s', 'lan-thinkupthemes' ), get_the_title() );
	} elseif ( is_attachment() ) {
		printf( __( 'Blog Post Image: %s', 'lan-thinkupthemes' ), esc_attr( get_the_title( $post->post_parent ) ) );
	} else if ( is_single() ) {
		printf( __( '%s', 'lan-thinkupthemes' ), get_the_title() );
	} else if ( is_search() ) {
		printf( __( 'Search Results: %s', 'lan-thinkupthemes' ), get_search_query() );
	} else if ( is_404() ) {
		printf( __( 'Page Not Found', 'lan-thinkupthemes' ) );
	} else if ( is_category() ) {
		printf( __( 'Category Archives: %s', 'lan-thinkupthemes' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		printf( __( 'Tag Archives: %s', 'lan-thinkupthemes' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		the_post();
		printf( __( 'Author Archives: %s', 'lan-thinkupthemes' ), get_the_author() );
		rewind_posts();
	} elseif ( is_day() ) {
		printf( __( 'Daily Archives: %s', 'lan-thinkupthemes' ), get_the_date() );
	} elseif ( is_month() ) {
		printf( __( 'Monthly Archives: %s', 'lan-thinkupthemes' ), get_the_date( 'F Y' ) );
	} elseif ( is_year() ) {
		printf( __( 'Yearly Archives: %s', 'lan-thinkupthemes' ), get_the_date( 'Y' ) );
	} elseif ( thinkup_is_blog() ) {
		printf( __( 'Blog', 'lan-thinkupthemes' ) );
	} else {
		printf( __( '%s', 'lan-thinkupthemes' ), get_the_title() );
	}
}
