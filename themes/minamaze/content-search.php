<?php
/**
 * The template for displaying content on the search results page.
 *
 * @package ThinkUpThemes
 */
?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('blog-article'); ?>>

						<div class="entry-content">
							<?php think_input_blogtitle(); ?>

							<div class="entry-meta">
								<?php thinkup_input_blogauthor(); ?>
								<?php thinkup_input_blogdate(); ?>
								<?php thinkup_input_blogcomment(); ?>
								<?php thinkup_input_blogcategory(); ?>
								<?php thinkup_input_blogtag(); ?>
							</div>

							<?php the_excerpt(); ?>
						</div>

					</article><!-- #post-<?php get_the_ID(); ?> -->	