<?php
/**
 * The template for displaying Archive pages.
 *
 * @package ThinkUpThemes
 */

get_header(); ?>

			<?php if( have_posts() ): ?>

				<?php while( have_posts() ): the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('blog-article'); ?>>

						<?php if ( has_post_thumbnail() ) {
							$column1 = ' two_fifth';
							$column2 = ' three_fifth last';
						} else {
							$column1 = NULL;
							$column2 = NULL;
						} ?>

						<header class="entry-header<?php echo $column1; ?>">
							<?php thinkup_input_blogimage(); ?>
						</header>		

						<div class="entry-content<?php echo $column2; ?>">
							<?php think_input_blogtitle(); ?>

							<?php thinkup_input_blogmeta(); ?>

							<?php thinkup_input_blogtext(); ?>
						</div>

					<div class="clearboth"></div>
					</article><!-- #post-<?php get_the_ID(); ?> -->	

				<?php endwhile; ?>

				<?php thinkup_input_pagination(); ?>

			<?php else: ?>

				<?php get_template_part( 'no-results', 'archive' ); ?>		

			<?php endif; ?>

<?php get_footer() ?>