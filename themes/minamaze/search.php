<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package ThinkUpThemes
 */

get_header(); ?>

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'search' ); ?>

				<?php endwhile; ?>

				<?php thinkup_input_pagination(); ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'search' ); ?>

			<?php endif; ?>

<?php get_footer(); ?>