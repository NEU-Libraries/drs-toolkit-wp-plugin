<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * @package ThinkUpThemes
 */


/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link. */
function thinkup_input_pagemenuargs( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'thinkup_input_pagemenuargs' );


/* Adds custom classes to the array of body classes. */
function thinkup_input_bodyclasses( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'thinkup_input_bodyclasses' );


/* Filter in a link to a content ID attribute for the next/previous image links on image attachment pages. */
function thinkup_input_enhancedimagenav( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'thinkup_input_enhancedimagenav', 10, 2 );

/* Add backward compatibility for add_theme_support( 'title-tag' ) */
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function thinkup_input_wptitle() {
?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php
	}
	add_action( 'wp_head', 'thinkup_input_wptitle' );
}