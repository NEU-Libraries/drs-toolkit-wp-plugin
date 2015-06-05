<?php
/**
 * The template for displaying Comments.
 *
 * @package ThinkUpThemes
 */
?>

<?php
	/* Exit if the post is password protected & user is not logged in */
	if ( post_password_required() )
		return;
?>

	<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h3 id="comments-title">
			<?php
				printf( _n( '1 comment %2$s', '%1$s comments %2$s', get_comments_number(), 'lan-thinkupthemes' ),
					number_format_i18n( get_comments_number() ), '<span class="post-title">on &ldquo;' . get_the_title() . '&rdquo;</span>' );
			?>
		</h3>
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav role="navigation" id="comment-nav-above" class="comment-navigation">
			<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'lan-thinkupthemes' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'lan-thinkupthemes' ) ); ?></div>
		</nav><!-- #comment-nav-before .comment-navigation -->
		<?php endif;?>

			<ol class="commentlist">
				<?php /* List Comments */ thinkup_input_comments(); ?>
			</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav role="navigation" id="comment-nav-below" class="comment-navigation">
			<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'lan-thinkupthemes' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'lan-thinkupthemes' ) ); ?></div>
		</nav><!-- #comment-nav-below .comment-navigation -->
		<?php endif; ?>

	<?php endif; ?>

	<?php
		/* Message to display when comments are closed */
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>

		<div id="nocomments" class="notification info">
			<div class="icon"><?php _e( 'Comments are closed.', 'lan-thinkupthemes' ); ?></div>
		</div>

	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments .comments-area -->