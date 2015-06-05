<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package ThinkUpThemes
 */
?>

				<article id="no-results">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'lan-thinkupthemes' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

							<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'lan-thinkupthemes' ), admin_url( 'post-new.php' ) ); ?></p>

						<?php elseif ( is_search() ) : ?>

							<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'lan-thinkupthemes' ); ?></p>
							<?php get_search_form(); ?>

						<?php else : ?>

							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'lan-thinkupthemes' ); ?></p>
							<?php get_search_form(); ?>

						<?php endif; ?>
					</div><!-- .entry-content -->
				</article><!-- #no-results -->