<?php
/**
 * The Template for displaying all single posts.
 *
 * @package ThinkUpThemes
 */

get_header(); ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>

				<?php thinkup_input_nav( 'nav-below' ); ?>

				<?php edit_post_link( __( 'Edit', 'lan-thinkupthemes' ), '<span class="edit-link">', '</span>' ); ?>

				<?php thinkup_input_allowcomments(); ?>

			<?php endwhile; ?>

<?php get_footer(); ?>