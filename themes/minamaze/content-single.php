<?php
/**
 * The Single Post content template file.
 *
 * @package ThinkUpThemes
 */
?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php thinkup_input_postmeta(); ?>

		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<?php edit_post_link( __( 'Edit', 'lan-thinkupthemes' ), '<span class="edit-link">', '</span>' ); ?>
		
		</article>