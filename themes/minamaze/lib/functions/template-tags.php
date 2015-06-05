<?php
/**
 * Custom template tags for this theme.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	Display navigation to next/previous pages when applicable.
---------------------------------------------------------------------------------- */
if ( ! function_exists( 'thinkup_input_nav' ) ) :
function thinkup_input_nav( $nav_id ) {

global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>">
	<?php if ( is_single() ) : ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-icon"><i class="icon-angle-left icon-large"></i></span><span class="meta-nav">%title</span>' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="meta-nav">%title</span><span class="meta-icon"><i class="icon-angle-right icon-large"></i></span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'lan-thinkupthemes') ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'lan-thinkupthemes') ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php

}
endif; 


/* ----------------------------------------------------------------------------------
	Display navigation to next/previous image when applicable.
---------------------------------------------------------------------------------- */
if ( ! function_exists( 'thinkup_input_imagesnav' ) ) :
function thinkup_input_imagesnav() {

global $wp_query, $post;
	?>

	<nav role="navigation" id="nav-below">
		<div class="nav-previous"><?php previous_image_link( 'false', '<div class="nav-previous"><span class="meta-icon"><i class="icon-angle-left icon-large"></i></span><span class="meta-nav">' . __( 'Previous', 'lan-thinkupthemes') . ' </span></div>' ); ?></div>
		<div class="nav-next"><?php next_image_link( 'false', '<span class="meta-nav">' . __( 'Next', 'lan-thinkupthemes') . '</span><span class="meta-icon"><i class="icon-angle-right icon-large"></i></span>' ); ?></div>
	</nav><!-- #image-navigation -->

<?php

}
endif;


/* Display comments at bottom of post, page and project pages. */
function thinkup_input_allowcomments() {
	if ( comments_open() || '0' != get_comments_number() )
		comments_template( '/comments.php', true );
}


/* ----------------------------------------------------------------------------------
	Returns true if a blog has more than 1 category.
---------------------------------------------------------------------------------- */
function thinkup_input_categorizedblog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		return true;
	} else {
		return false;
	}
}

/* Flush out the transients used in thinkup_input_categorizedblog. */
function thinkup_input_transient_flusher() {
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'thinkup_input_transient_flusher' );
add_action( 'save_post', 'thinkup_input_transient_flusher' );